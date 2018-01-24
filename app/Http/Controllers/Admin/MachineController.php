<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brochure;
use App\Models\Category;
use App\Models\MachineImage;
use App\Models\MachineLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Machine;
use Validator;
use Config as config;

class MachineController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $machine;
    protected $category;
    protected $machine_log;
    protected $machine_image;
    protected $brochure;

    public function __construct(Machine $machine, MachineLog $machine_log, Category $category, MachineImage $machine_image,Brochure $brochure)
    {
        $this->middleware('auth');
        $this->machine = $machine;
        $this->category = $category;
        $this->machine_log = $machine_log;
        $this->machine_image = $machine_image;
        $this->brochure = $brochure;
    }

    /**
     * Display a listing of the machine.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {

        /*
         *  getCollection from App/Models/Machine
         *
         *  @return mixed
         * */


        $data['productManagementTab'] = "active open";
        $data['machineTab'] = "active";
        $data['categoryData'] = $this->category->getCollection();
        return view('admin.machine.machinelist', $data);
    }

    public function datatable(Request $request)
    {
        // default count of machine $machineCount
        $machineCount = 0;

        /*
         *    getDatatableCollection from App/Models/Machine
         *   get all machines
         *
         *  @return mixed
         * */
        $machineData = $this->machine->getDatatableCollection();

        /*
         *    scopeGetFilteredData from App/Models/Machine
         *   get filterred machines
         *
         *  @return mixed
         * */
        $machineData = $machineData->GetFilteredData($request);

        /*
         *    getMachineCount from App/Models/Machine
         *   get count of machines
         *
         *  @return integer
         * */
        $machineCount = $this->machine->getMachineCount($machineData);

        //  Sorting machine data base on requested sort order
        if (isset(config('constant.machineDataTableFieldArray')[$request->order['0']['column']])) {
            $machineData = $machineData->SortMachineData($request);
        } else {
            $machineData = $machineData->SortDefaultDataByRaw('tbl_machine.id', 'desc');
        }

        /*
         *  get paginated collection of machine
         *
         * @param  \Illuminate\Http\Request $request
         * @return mixed
         * */
        $machineData = $machineData->GetMachineData($request);
        $appData = array();
        $filepath = url('/') . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'brochure' . DIRECTORY_SEPARATOR;
        foreach ($machineData as $machineData) {
            $row = array();
            $row[] = $machineData->machine_name;
            $row[] = $machineData->Category->category_name;
            $row[] = '<i class="fa fa-image fa-2x machine_image_class" rel="'.$machineData->id.'"></i>';
            //$row[] = (!empty($machineData->Brochure))? '<a href="'.$filepath.'/'.$machineData->Brochure->file_name.'" class="html5lightbox" data-width="750" data-height="320"><i class="fa fa-file-pdf-o fa-2x"></i></a>' : "--";
            $row[] = (!empty($machineData->Brochure))? '<i class="fa fa-file-pdf-o fa-2x html5lightbox" rel="'.$machineData->Brochure->file_name.'"></i>' : "--";
            $row[] = view('datatable.switch', ['module' => "machine",'dashboard_switch'=> true ,'status' => $machineData->app_dashboard, 'id' => $machineData->id])->render();
            $row[] = view('datatable.switch', ['module' => "machine", 'status' => $machineData->status, 'id' => $machineData->id])->render();
            $row[] = view('datatable.action', ['module' => "machine","log" => true,'id' => $machineData->id])->render();
            $appData[] = $row;
        }

        return [
            'draw' => $request->draw,
            'recordsTotal' => $machineCount,
            'recordsFiltered' => $machineCount,
            'data' => $appData,
        ];
    }

    /**
     * Show the form for creating a new machine.
     *
     * @return \Illuminate\Http\Response
     */

    public function create($flag = null, $category_id=null)
    {
        $data['productManagementTab'] = "active open";
        if($flag != ""){
            $data['flag'] = $flag;
        }
        if($flag != ""){
            $data['category_id'] = $category_id;
        }
        $featureMachinesCount = Machine::where('app_dashboard',1)->where('status',1)->count();
        if(config::get('constant.dashboard_machine') > $featureMachinesCount){
            $data['dashboard_machine_count'] = "1";
        }else{
            $data['dashboard_machine_count'] = "0";
        }
        $data['machineTab'] = "active";
        $data['categoryData'] = $this->category->getCollection(['check_status' => '1']);
        return view('admin.machine.add', $data);
    }

    /**
     * Display the specified machine.
     *
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {

        /*
         *  get details of the specified machine. from App/Models/Machine
         *
         * @param mixed $id
         * @param string (id) fieldname
         *  @return mixed
         * */
        $data['details'] = $this->machine->getMachineByField($id, 'id');
        $data['categoryData'] = $this->category->getCollection();
        $featureMachinesCount = Machine::where('app_dashboard',1)->where('status',1)->count();
        if(config::get('constant.dashboard_machine') > $featureMachinesCount || $data['details']->app_dashboard == "1"){
            $data['dashboard_machine_count'] = "1";
        }else{
            $data['dashboard_machine_count'] = "0";
        }
        $data['productManagementTab'] = "active open";
        $data['machineTab'] = "active";
        return view('admin.machine.edit', $data);
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
            'machine_name' => 'required|max:50',
            'category_id' => 'required',
            'file_name' => 'mimes:pdf',
        );

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            $errorRedirectUrl = "admin/machine/add";
            if ($mode == "edit") {
                $errorRedirectUrl = "admin/machine/edit/" . $data['id'];
            }
            return redirect($errorRedirectUrl)->withInput()->withErrors($validator);
        }
        return false;
    }

    /**
     * Store a newly created machine in storage.
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

        $addmachine = $this->machine->addMachine($request->all());
        if ($addmachine) {
            if (isset($request->quotation_flag) && $request->quotation_flag != null){
                return redirect('admin/quotation/list');
            }else{
                return redirect('admin/machine/list');
            }
            $request->session()->flash('alert-success', trans('app.machine_add_success'));
            return redirect('admin/machine/list');
        } else {
            $request->session()->flash('alert-danger', trans('app.machine_error'));
            return redirect('admin/machine/add')->withInput();
        }
    }

    /**
     * Update the specified machine in storage.
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

        $addmachine = $this->machine->addMachine($request->all());
        if ($addmachine) {
            $request->session()->flash('alert-success', trans('app.machine_edit_success'));
            return redirect('admin/machine/list');
        } else {
            $request->session()->flash('alert-danger', trans('app.machine_error'));
            return redirect('admin/machine/edit/' . $request->get('id'))->withInput();
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
        $updateMachine = $this->machine->updateStatus($request->all());
        if ($updateMachine) {
            $request->session()->flash('alert-success', trans('app.machine_status_success'));
        } else {
            $request->session()->flash('alert-danger', trans('app.machine_error'));
        }
        echo 1;
    }

    /**
     * Update status to the specified user in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function changeDashboardStatus(request $request)
    {

        $featureMachinesCount = Machine::where('app_dashboard',1)->where('status',1)->count();
        if(config::get('constant.dashboard_machine') > $featureMachinesCount || $request->status == "0"){
            $updateMachine = $this->machine->updateDashboardStatus($request->all());
            if ($updateMachine) {
                $request->session()->flash('alert-success', trans('app.machine_status_success'));
            } else {
                $request->session()->flash('alert-danger', trans('app.machine_error'));
            }

        }else{
            $request->session()->flash('alert-danger','No more than '.config::get('constant.dashboard_machine').' machines can be featured on dashboard of application.');
        }
        echo 1;
    }

    /**
     * Delete the specified machine in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function delete(request $request)
    {

        $deleteUser = $this->machine->deleteMachine($request->id);
        if ($deleteUser) {
            $request->session()->flash('alert-success', trans('app.machine_delete_success'));
        } else {
            $request->session()->flash('alert-danger', trans('app.machine_error'));
        }
        echo 1;
    }

    /**
     * log the specified machine in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function log(request $request)
    {
        $data['details'] = $this->machine_log->getCollection($request->id);
        $data['productManagementTab'] = "active open";
        $data['machineTab'] = "active";
        return view('admin.machine.machinelog', $data);
    }
    /**
     * delete the image specified machine in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function deleteImage(request $request)
    {
        if(isset($request->type) && $request->type == 'single'){
            $this->machine_image->deleteImage($request->id);
            $view=1;
        }else{
            foreach ($request->id as $id){
                $this->machine_image->deleteImage($id);
            }
            $machine_id = $request->machine_id;
            $getImages = $this->machine_image->getCollection($machine_id);
            $view = NULL;
            foreach ($getImages as $image){
                $view.= '<div class="col-sm-3 col-md-3 image_class">
                                                <input name="image" type="checkbox" class="fa fa-window-close pull-left" value="'.$image->id.'"><img src="'.url('img/machine/'.$image->machine_id.'/'.$image->image).'" style="width:50px;height:50px;cursor:pointer;" class="machine_image" />
                                            </div>';
            }
        }
        echo $view;
    }
    /**
     * Get a listing of images of the machine.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function machineImage(request $request)
    {
        $getImages = $this->machine_image->getCollection($request->id);
        if(!empty($getImages)){
            $machine = array();
            foreach($getImages as $machineIds){
               $machine[] =  $machineIds->machine_id;
            }
            $machine_id = current($machine);

        }

        $view = NULL;
        foreach ($getImages as $image){
            $view.= '<div class="col-sm-3 col-md-3 image_class">
                                                <input name="image" type="checkbox" class="pull-left" value="'.$image->id.'"><img src="'.url('img/machine/'.$image->machine_id.'/thumb/'.$image->image).'" style="width:50px;height:50px;cursor:pointer;" class="machine_image" />
                                            </div>';
        }
        $view.='<input type="hidden" value="'.$machine_id.'" name = "machine_id" id="machine_id" value = "machine_id" />';
        return $view;
    }
}
