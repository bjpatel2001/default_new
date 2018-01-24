<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MachineLog;
use Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\LaraHelpers;
use App\Models\Category;
use App\Models\MachineImage;
use App\Models\Brochure;
use Config as config;

class Machine extends Model
{
    protected $table = 'tbl_machine';
    protected $primaryKey = 'id';
    use SoftDeletes;

    public function MachineImage()
    {
        return $this->hasMany('App\Models\MachineImage','machine_id','id');
    }

    public function Category()
    {
        return $this->hasOne('App\Models\Category','id','category_id');
    }

    public function Brochure()
    {
        return $this->hasOne('App\Models\Brochure','machine_id','id');
    }

    /**
     * Get all Machines getCollection
     *
     * @param array $models
     * @return mixed
     */
    public function getCollection(array $models = [])
    {
        $machine = Machine::select('tbl_machine.*');
        if(isset($models['check_status']) && $models['check_status'] == 1){
            $machine->where('status',1);
        }
        return $machine->get();
    }

    /**
     * Get all Machine with machine & User relationship
     *
     * @return mixed
     */
    public function getDatatableCollection()
    {
        return Machine::with('Category','Brochure')->select('tbl_machine.*');
    }

    /**
     * Query to get machine total count
     *
     * @param $dbObject
     * @return integer $machineCount
     */
    public static function getMachineCount($dbObject)
    {
        $machineCount = $dbObject->count();
        return $machineCount;
    }

    /**
     * Scope a query to get all data
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGetMachineData($query, $request)
    {
        return $query->skip($request->start)->take($request->length)->get();
    }

    /*
     *    scopeGetFilteredData from App/Models/Machine
     *   get filterred machines
     *
     *  @param  object $query
     *  @param  \Illuminate\Http\Request $request
     *  @return mixed
     * */
    public function scopeGetFilteredData($query, $request)
    {
        $filter = $request->filter;
        $Datefilter = $request->filterDate;
        $filterSelect = $request->filterSelect;
        /*
         * @param string $filter  text type value
         * @param string $Datefilter  date type value
         * @param string $filterSelect select value
         *
         *  @return mixed
         * */
        return $query->Where(function ($query) use ($filter, $Datefilter, $filterSelect) {
            if (count($filter) > 0) {
                foreach ($filter as $key => $value) {
                    if ($value != "") {
                        $query->where($key, 'LIKE', '%' . $value . '%');
                    }
                }
            }

            if (count($Datefilter) > 0) {
                foreach ($Datefilter as $dtkey => $dtvalue) {
                    if ($dtvalue != "") {
                        $query->where($dtkey, 'LIKE', '%' . date('Y-m-d', strtotime($dtvalue)) . '%');
                    }
                }
            }

            if (count($filterSelect) > 0) {
                foreach ($filterSelect as $Sekey => $Sevalue) {
                    if ($Sevalue != "") {
                        $query->whereRaw('FIND_IN_SET(' . $Sevalue . ',' . $Sekey . ')');
                    }
                }
            }

        });

    }

    /**
     * Scope a query to sort data
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSortMachineData($query, $request)
    {

        return $query->orderBy(config('constant.machineDataTableFieldArray')[$request->order['0']['column']], $request->order['0']['dir']);

    }

    /**
     * Scope a query to sort data
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string $column
     * @param  string $dir
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSortDefaultDataByRaw($query, $column, $dir)
    {
        return $query->orderBy($column, $dir);
    }

    /**
     * Add & update Machine addMachine
     *
     * @param array $models
     * @return boolean true | false
     */
    public function addMachine(array $models = [])
    {
        $machineLog = new MachineLog();

        if (isset($models['id'])) {
            $machine = Machine::find($models['id']);
            $machineLog->action = "Update";
        } else {
            $machine = new Machine;
            $machineLog->created_by = $machine->created_by = Auth::id();
            $machineLog->action = "Add";
        }
        $machineLog->machine_name = $machine->machine_name = $models['machine_name'];
        $machineLog->category_id = $machine->category_id = $models['category_id'];
        $machineLog->description = $machine->description = $models['description'];
        $machineLog->created_by = $machine->created_by = $machine->updated_by = Auth::id();
        if (isset($models['status'])) {
            $machineLog->status = $machine->status = $models['status'];
        } else {
            $machineLog->status = $machine->status = 0;
        }
        if (isset($models['app_dashboard'])) {
            $machine->app_dashboard = $models['app_dashboard'];
        } else {
            $machine->app_dashboard = 0;
        }
        $machineId = $machine->save();

        // For adding the Machine Product

        /*
         *@param array $brochureMachineData
         *
         * return boolean
         */
        if(!empty($models['file_name'])){

            $brochureMachineData['category_id'] = $machine->category_id;
            $brochureMachineData['machine_id'] = $machine->id;
            $brochureMachineData['file_name'] = $models['file_name'];
            $brochureMachineData['old_file_name'] = $models['old_file_name'];
            $brochureMachineData['name'] = $machine->machine_name;
            $brochureMachineData['type'] = 1;
            $brochureMachineData['status'] = 1;
            if(isset($models['brochure_id'])){
                $brochureMachineData['id'] = $models['brochure_id'];
            }
            $brochure = new Brochure();
            $brochureResult = $brochure->addBrochure($brochureMachineData);

        }


        if (isset($models['machine_image']) && $models['machine_image'] != "") {
            foreach ($models['machine_image'] as $image){
                $machineImage = new MachineImage();
                $filepath = public_path() . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'machine' . DIRECTORY_SEPARATOR . $machine->id . DIRECTORY_SEPARATOR;
                $machineImage->image = LaraHelpers::upload_image($filepath, $image, "", true);
                $machineImage->machine_id = $machine->id;
                $machineImage->created_by = $machineImage->updated_by = Auth::id();
                $machineImage->save();
            }
        }
        $machineLog->machine_id = $machine->id;
        $machineLog->save();

        if($machineLog->save()):
            $machineimage = new MachineImage();
            $machineImageData = $machineimage->getImage($machine->id);
            $filepath = url('/') . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'machine' . DIRECTORY_SEPARATOR . $machine->id . DIRECTORY_SEPARATOR;
            $addmesage = new NotificationMessage();
            $dat=array();
            $dat['title'] = $models['machine_name'];
            $dat['description'] = $models['description'];
            $dat['image'] = $filepath.$machineImageData->image;
            $dat['sender_id'] = Auth::id();
            $addmesage->addMessage($dat);
        endif;


        // For sending the Notification to user who are logged in
        /*
         * @param array $data
         *
         * @param string $type
         * */

        /* implemented in cron*/

        /*if(!isset($models['id'])){
            $data = $machine->getMachineByField($machine->id,'id');
            $machineimage = new MachineImage();
            $machineImageData = $machineimage->getImage($machine->id);
            $filepath = url('/') . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'machine' . DIRECTORY_SEPARATOR . $machine->id . DIRECTORY_SEPARATOR;
            $data['title'] = $data->machine_name;
            $data['message'] = $data->description;
            $data['imageUrl'] = $filepath.$machineImageData->image;
            $notification = new Notification();
            $notification->sendNotification($data ,"Machine");
        }*/

        if ($machineId)
            return true;
        else
            return false;
    }

    /**
     * get Machine By fieldname getMachineByField
     *
     * @param mixed $id
     * @param string $field_name
     * @return mixed
     */
    public function getMachineByField($id, $field_name)
    {
        return Machine::with('MachineImage','Brochure')->where($field_name, $id)->first();
    }

    /**
     * update Machine Status
     *
     * @param array $models
     * @return boolean true | false
     */
    public function updateStatus(array $models = [])
    {
        $machineLog = new MachineLog();

        $machine = Machine::find($models['id']);
        $machineLog->status = $machine->status = $models['status'];
        $machineLog->created_by = $machineLog->updated_by = $machine->updated_by = Auth::id();
        $machineLog->action = "Change Password";
        $machineId = $machine->save();
        $machineLog->machine_id = $machine->id;
        $machineLog->save();


        if ($machineId)
            return true;
        else
            return false;

    }

    /**
     * update Machine Status
     *
     * @param array $models
     * @return boolean true | false
     */
    public function updateDashboardStatus(array $models = [])
    {
        $machine = Machine::find($models['id']);
        $machine->app_dashboard = $models['status'];
        $machine->updated_by = Auth::id();
        $machineId = $machine->save();

        if ($machineId)
            return true;
        else
            return false;

    }

    /**
     * Delete Machine
     *
     * @param int $id
     * @return boolean true | false
     */
    public function deleteMachine($id)
    {
        $brochure = new Brochure();
        $brohcureDelete = $brochure->deleteBrochure($id,'machine_id');

        $machineImage = new MachineImage;
        $machineImageData = $machineImage->getAllImage($id);
        foreach ($machineImageData as $imageData){
            $machineImage->deleteImage($imageData->id);
        }
        $machineLog = new MachineLog();
        $machineLog->created_by = $machineLog->updated_by = Auth::id();
        $machineLog->action = "Delete";
        $delete = Machine::where('id', $id)->delete();
        $machineLog->machine_id = $id;
        $machineLog->save();

        if ($delete)
            return true;
        else
            return false;

    }

    /**
     * Get all recently added Machine for Dashboard (API Call)
     *
     * @return mixed
     */
    public function getRecentMachine()
    {
        return Machine::with('MachineImage')
                        ->where('app_dashboard',1)
                        ->where('status',1)
                        ->orderBy('created_at', 'desc')
                        ->take(config::get('constant.dashboard_machine'))
                        ->get();
    }

    /**
     * get Machine By fieldname getMachineByField
     *
     * @param array $id
     * @param string $field_name
     * @return mixed
     */
    public function getMachines($field_name,$ids)
    {
        return Machine::with('MachineImage')->whereIn($field_name, $ids)->get();
    }


}
