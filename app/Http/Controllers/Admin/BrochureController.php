<?php

namespace App\Http\Controllers\Admin;

use App\Models\BrochureLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Brochure;
use Validator;
use App\Models\Category;
use App\Models\Machine;

class BrochureController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $brochure;
    protected $category;
    protected $machine;
    protected $brochure_log;

    public function __construct(Brochure $brochure, BrochureLog $brochure_log, Machine $machine,Category $category)
    {
        $this->middleware('auth');
        $this->brochure = $brochure;
        $this->brochure_log = $brochure_log;
        $this->machine = $machine;
        $this->category = $category;
    }

    /**
     * Display a listing of the brochure.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {

        /*
         *  getCollection from App/Models/Brochure
         *
         *  @return mixed
         * */


        $data['brochureManagementTab'] = "active open";
        $data['brochureTab'] = "active";
        return view('admin.brochure.brochurelist', $data);
    }

    public function datatable(Request $request)
    {
        // default count of brochure $brochureCount
        $brochureCount = 0;

        /*
         *    getDatatableCollection from App/Models/Brochure
         *   get all brochures
         *
         *  @return mixed
         * */
        $brochureData = $this->brochure->getDatatableCollection();

        /*
         *    scopeGetFilteredData from App/Models/Brochure
         *   get filterred brochures
         *
         *  @return mixed
         * */
        $brochureData = $brochureData->GetFilteredData($request);

        /*
         *    getBrochureCount from App/Models/Brochure
         *   get count of brochures
         *
         *  @return integer
         * */
        $brochureCount = $this->brochure->getBrochureCount($brochureData);

        //  Sorting brochure data base on requested sort order
        if (isset(config('constant.brochureDataTableFieldArray')[$request->order['0']['column']])) {
            $brochureData = $brochureData->SortBrochureData($request);
        } else {
            $brochureData = $brochureData->SortDefaultDataByRaw('tbl_brochure.id', 'desc');
        }

        /*
         *  get paginated collection of brochure
         *
         * @param  \Illuminate\Http\Request $request
         * @return mixed
         * */
        $brochureData = $brochureData->GetBrochureData($request);
       // dd($brochureData);
        $appData = array();
        foreach ($brochureData as $brochure) {
            $row = array();
            $row[] = $brochure->name;
            $row[] = (!empty($brochure->Category))?$brochure->Category->category_name:"---";
            $row[] = (!empty($brochure->Machine))?$brochure->Machine->machine_name:"---";
            $row[] = view('datatable.switch', ['module' => "brochure", 'status' => $brochure->status, 'id' => $brochure->id])->render();
            $row[] = view('datatable.action', ['module' => "brochure","log" => true,'id' => $brochure->id])->render();
            $appData[] = $row;
        }

        return [
            'draw' => $request->draw,
            'recordsTotal' => $brochureCount,
            'recordsFiltered' => $brochureCount,
            'data' => $appData,
        ];
    }

    /**
     * Show the form for creating a new brochure.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        $data['brochureManagementTab'] = "active open";
        $data['brochureTab'] = "active";
        $category = $this->category->getCollection();
        $machine = $this->machine->getCollection();
        return view('admin.brochure.add',["data" => $data,"category" => $category,"machine" => $machine]);
    }

    /**
     * Display the specified brochure.
     *
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {

        /*
         *  get details of the specified brochure. from App/Models/Brochure
         *
         * @param mixed $id
         * @param string (id) fieldname
         *  @return mixed
         * */

        $data['details'] = $this->brochure->getBrochureByField($id, 'id');
        $data['brochureManagementTab'] = "active open";
        $data['brochureTab'] = "active";
        $category = $this->category->getCollection();
        $machine = $this->machine->getCollection();
        return view('admin.brochure.edit', ["data" => $data,"category" => $category,"machine" => $machine]);
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
            'category_id' => 'required',
            'machine_id' => 'required',
            'file_name' => 'required|mimes:pdf',
        );
        if($mode == 'edit')
        {
            $rules = array(
                'name' => 'required|max:50',
                'category_id' => 'required',
                'machine_id' => 'required',

            );
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            $errorRedirectUrl = "admin/brochure/add";
            if ($mode == "edit") {
                $errorRedirectUrl = "admin/brochure/edit/" . $data['id'];
            }
            return redirect($errorRedirectUrl)->withInput()->withErrors($validator);
        }
        return false;
    }

    /**
     * Store a newly created brochure in storage.
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

        $addbrochure = $this->brochure->addBrochure($request->all());
        if ($addbrochure) {
            $request->session()->flash('alert-success', trans('app.brochure_add_success'));
            return redirect('admin/brochure/get_private_brochure');
        } else {
            $request->session()->flash('alert-danger', trans('app.brochure_error'));
            return redirect('admin/brochure/add')->withInput();
        }
    }

    /**
     * Update the specified brochure in storage.
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

        $addbrochure = $this->brochure->addBrochure($request->all());
        if ($addbrochure) {
            $request->session()->flash('alert-success', trans('app.brochure_edit_success'));
            return redirect('admin/brochure/get_private_brochure');
        } else {
            $request->session()->flash('alert-danger', trans('app.brochure_error'));
            return redirect('admin/brochure/edit/' . $request->get('id'))->withInput();
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
        $updateBrochure = $this->brochure->updateStatus($request->all());
        if ($updateBrochure) {
            $request->session()->flash('alert-success', trans('app.brochure_status_success'));
        } else {
            $request->session()->flash('alert-danger', trans('app.brochure_error'));
        }
        echo 1;
    }

    /**
     * Delete the specified brochure in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function delete(request $request)
    {

        $deleteUser = $this->brochure->deleteBrochure($request->id);
        if ($deleteUser) {
            $request->session()->flash('alert-success', trans('app.brochure_delete_success'));
        } else {
            $request->session()->flash('alert-danger', trans('app.brochure_error'));
        }
        echo 1;
    }

    /**
     * log the specified brochure in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function log(request $request)
    {
        $data['details'] = $this->brochure_log->getCollection($request->id);
        $data['brochureManagementTab'] = "active open";
        $data['brochureTab'] = "active";
        return view('admin.brochure.brochurelog', $data);
    }

    /**
     * Display the Private brochure.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function getPrivateBrochure()
    {

        /*
         *  get details of the specified brochure. from App/Models/Brochure
         *
         *
         *  @return mixed
         * */
        $data['details'] = $this->brochure->getMasterBrochure();
        $data['brochureManagementTab'] = "active open";
        $data['masterbrochureTab'] = "active";
        return view('admin.brochure.editprivate',$data);
    }

    /**
     * delete the Brochure specified machine in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function deleteFile(request $request)
    {
           return $this->brochure->deleteBrochure($request->id);
    }
}
