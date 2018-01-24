<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\State;
use App\Models\StateLog;
use App\Http\Controllers\Controller;
use Validator;
use App\Models\Country;

class StateController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $state;
    protected $country;
    protected $state_log;


    public function __construct(State $state, StateLog $state_log, Country $country)
    {
        $this->middleware('auth');
        $this->state = $state;
        $this->country = $country;
        $this->state_log = $state_log;
    }

    /**
     * Display a listing of the State.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {

        /*
         *  getCollection from App/Models/State
         *
         *  @return mixed
         * */


        $data['locationManagementTab'] = "active open";
        $data['stateTab'] = "active";
        $data['countryData'] = $this->country->getCollection();
        return view('admin.state.statelist', $data);
    }

    public function datatable(Request $request)
    {
        // default count of state $stateCount
        $stateCount = 0;

        /*
         *    getDatatableCollection from App/Models/State
         *
         *   get all state
         *
         *  @return mixed
         * */
        $stateData = $this->state->getDatatableCollection();

        /*
         *    scopeGetFilteredData from App/Models/state
         *   get filterred states
         *
         *  @return mixed
         * */
        $stateData = $stateData->GetFilteredData($request);

        /*
         *    getStateCount from App/Models/State
         *   get count of states
         *
         *  @return integer
         * */
        $stateCount = $this->state->getStateCount($stateData);

        //  Sorting state data base on requested sort order
        if (isset(config('constant.stateDataTableFieldArray')[$request->order['0']['column']])) {
            $stateData = $stateData->SortStateData($request);
        } else {
            $stateData = $stateData->SortDefaultDataByRaw('tbl_state.id', 'desc');
        }

        /*
         *  get paginated collection of state
         *
         * @param  \Illuminate\Http\Request $request
         * @return mixed
         * */
        $stateData = $stateData->GetStateData($request);
        $appData = array();
        foreach ($stateData as $stateData) {
            $row = array();
            $row[] = $stateData->name;
            $row[] = $stateData->Country->name;
            $row[] = view('datatable.switch', ['module' => "state", 'status' => $stateData->status, 'id' => $stateData->id])->render();
            $row[] = view('datatable.action', ['module' => "state","log" => true,'id' => $stateData->id])->render();
            $appData[] = $row;
        }

        return [
            'draw' => $request->draw,
            'recordsTotal' => $stateCount,
            'recordsFiltered' => $stateCount,
            'data' => $appData,
        ];
    }

    /**
     * Show the form for creating a new state.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        $data['locationManagementTab'] = "active open";
        $data['stateTab'] = "active";
        $data['countryData'] = $this->country->getCollection(['check_status' => '1']);
        return view('admin.state.add', $data);
    }

    /**
     * Display the specified state.
     *
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {

        /*
         *  get details of the specified state. from App/Models/State
         *
         * @param mixed $id
         * @param string (id) fieldname
         *  @return mixed
         * */
        $data['details'] = $this->state->getStateByField($id, 'id');
        $data['countryData'] = $this->country->getCollection();
        $data['locationManagementTab'] = "active open";
        $data['stateTab'] = "active";
        return view('admin.state.edit', $data);
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
            'country_id' => 'required',

        );

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            $errorRedirectUrl = "admin/state/add";
            if ($mode == "edit") {
                $errorRedirectUrl = "admin/state/edit/" . $data['id'];
            }
            return redirect($errorRedirectUrl)->withInput()->withErrors($validator);
        }
        return false;
    }

    /**
     * Store a newly created state in storage.
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

        $addstate = $this->state->addState($request->all());
        if ($addstate) {
            $request->session()->flash('alert-success', trans('app.state_add_success'));
            return redirect('admin/state/list');
        } else {
            $request->session()->flash('alert-danger', trans('app.state_error'));
            return redirect('admin/state/add')->withInput();
        }
    }

    /**
     * Update the specified state in storage.
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

        $addstate = $this->state->addState($request->all());
        if ($addstate) {
            $request->session()->flash('alert-success', trans('app.state_edit_success'));
            return redirect('admin/state/list');
        } else {
            $request->session()->flash('alert-danger', trans('app.state_error'));
            return redirect('admin/state/edit/' . $request->get('id'))->withInput();
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
        $updateState = $this->state->updateStatus($request->all());
        if ($updateState) {
            $request->session()->flash('alert-success', trans('app.state_status_success'));
        } else {
            $request->session()->flash('alert-danger', trans('app.state_error'));
        }
        echo 1;
    }

    /**
     * Delete the specified state in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function delete(request $request)
    {

        $deleteUser = $this->state->deleteState($request->id);
        if ($deleteUser) {
            $request->session()->flash('alert-success', trans('app.state_delete_success'));
        } else {
            $request->session()->flash('alert-danger', trans('app.state_error'));
        }
        echo 1;
    }

    /**
     * log the specified state in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function log(request $request)
    {
        $data['details'] = $this->state_log->getCollection($request->id);
        $data['locationManagementTab'] = "active open";
        $data['stateTab'] = "active";
        return view('admin.state.statelog', $data);
    }

}
