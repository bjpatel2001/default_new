<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\CountryLog;
use App\Http\Controllers\Controller;
use Validator;

class CountryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $country;
    protected $country_log;


    public function __construct(Country $country, CountryLog $country_log)
    {
        $this->middleware('auth');
        $this->country = $country;
        $this->country_log = $country_log;
    }

    /**
     * Display a listing of the country.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {

        /*
         *  getCollection from App/Models/Country
         *
         *  @return mixed
         * */


        $data['locationManagementTab'] = "active open";
        $data['countryTab'] = "active";
        $data['countryData'] = $this->country->getCollection();
        return view('admin.country.countrylist', $data);
    }

    public function datatable(Request $request)
    {
        // default count of country $countryCount
        $countryCount = 0;

        /*
         *    getDatatableCollection from App/Models/Country
         *
         *   get all country
         *
         *  @return mixed
         * */
        $countryData = $this->country->getDatatableCollection();

        /*
         *    scopeGetFilteredData from App/Models/country
         *   get filterred countrys
         *
         *  @return mixed
         * */
        $countryData = $countryData->GetFilteredData($request);

        /*
         *    getCountryCount from App/Models/Country
         *   get count of countrys
         *
         *  @return integer
         * */
        $countryCount = $this->country->getCountryCount($countryData);

        //  Sorting country data base on requested sort order
        if (isset(config('constant.countryDataTableFieldArray')[$request->order['0']['column']])) {
            $countryData = $countryData->SortCountryData($request);
        } else {
            $countryData = $countryData->SortDefaultDataByRaw('tbl_country.id', 'desc');
        }

        /*
         *  get paginated collection of country
         *
         * @param  \Illuminate\Http\Request $request
         * @return mixed
         * */
        $countryData = $countryData->GetCountryData($request);
        $appData = array();
        foreach ($countryData as $countryData) {
            $row = array();
            $row[] = $countryData->name;
            $row[] = view('datatable.switch', ['module' => "country", 'status' => $countryData->status, 'id' => $countryData->id])->render();
            $row[] = view('datatable.action', ['module' => "country","log" => true,'id' => $countryData->id])->render();
            $appData[] = $row;
        }

        return [
            'draw' => $request->draw,
            'recordsTotal' => $countryCount,
            'recordsFiltered' => $countryCount,
            'data' => $appData,
        ];
    }

    /**
     * Show the form for creating a new country.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        $data['locationManagementTab'] = "active open";
        $data['countryTab'] = "active";
        $data['countryData'] = $this->country->getCollection(['check_status' => '1']);
        return view('admin.country.add', $data);
    }

    /**
     * Display the specified country.
     *
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {

        /*
         *  get details of the specified country. from App/Models/Country
         *
         * @param mixed $id
         * @param string (id) fieldname
         *  @return mixed
         * */
        $data['details'] = $this->country->getCountryByField($id, 'id');
        $data['countryData'] = $this->country->getCollection();
        $data['locationManagementTab'] = "active open";
        $data['countryTab'] = "active";
        return view('admin.country.edit', $data);
    }


    /**
     * Validation of add and edit action customeValidate
     *
     * @param array $data
     * @param string $mode
     * @return mixed
     */

    public function customeValidate($data, $mode)
    {
        $rules = array(
            'name' => 'required|max:50',

        );

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            $errorRedirectUrl = "admin/country/add";
            if ($mode == "edit") {
                $errorRedirectUrl = "admin/country/edit/" . $data['id'];
            }
            return redirect($errorRedirectUrl)->withInput()->withErrors($validator);
        }
        return false;
    }

    /**
     * Store a newly created country in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(request $request)
    {
        $validations = $this->customeValidate($request->all(), 'add');
        if ($validations) {
            return $validations;
        }

        $addcountry = $this->country->addCountry($request->all());
        if ($addcountry) {
            $request->session()->flash('alert-success', trans('app.country_add_success'));
            return redirect('admin/country/list');
        } else {
            $request->session()->flash('alert-danger', trans('app.country_error'));
            return redirect('admin/country/add')->withInput();
        }
    }

    /**
     * Update the specified country in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(request $request)
    {
        $validations = $this->customeValidate($request->all(), 'edit');
        if ($validations) {
            return $validations;
        }

        $addcountry = $this->country->addCountry($request->all());
        if ($addcountry) {
            $request->session()->flash('alert-success', trans('app.country_edit_success'));
            return redirect('admin/country/list');
        } else {
            $request->session()->flash('alert-danger', trans('app.country_error'));
            return redirect('admin/country/edit/' . $request->get('id'))->withInput();
        }
    }

    /**
     * Update status to the specified user in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(request $request)
    {
        $updateCountry = $this->country->updateStatus($request->all());
        if ($updateCountry) {
            $request->session()->flash('alert-success', trans('app.country_status_success'));
        } else {
            $request->session()->flash('alert-danger', trans('app.country_error'));
        }
        echo 1;
    }

    /**
     * Delete the specified country in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function delete(request $request)
    {

        $deleteUser = $this->country->deleteCountry($request->id);
        if ($deleteUser) {
            $request->session()->flash('alert-success', trans('app.country_delete_success'));
        } else {
            $request->session()->flash('alert-danger', trans('app.country_error'));
        }
        echo 1;
    }

    /**
     * log the specified country in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function log(request $request)
    {
        $data['details'] = $this->country_log->getCollection($request->id);
        $data['locationManagementTab'] = "active open";
        $data['countryTab'] = "active";
        return view('admin.country.countrylog', $data);
    }


}
