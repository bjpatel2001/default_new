<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\LaraHelpers;
use App\Models\BrochureLog;

class Brochure extends Model
{
    protected $table = 'tbl_brochure';
    protected $primaryKey = 'id';
    use SoftDeletes;


    public function Category()
    {
        return $this->hasOne('App\Models\Category','id','category_id');
    }

    public function Machine()
    {
        return $this->hasOne('App\Models\Machine','id','machine_id');
    }

    /**
     * Get all Brochure getCollection
     *
     * @param array $models
     * @return mixed
     */
    public function getCollection(array $models = [])
    {
        $brochure = Brochure::select('tbl_brochure.*');
        if(isset($models['check_status']) && $models['check_status'] == 1){
            $brochure->where('status',1);
        }
        return $brochure->get();
    }

    /**
     * Get all Brochure with Brochure & User relationship
     *
     * @return mixed
     */
    public function getDatatableCollection()
    {
        return Brochure::with('Category','Machine')->select('tbl_brochure.*');

    }

    /**
     * Query to get Brochure total count
     *
     * @param $dbObject
     * @return integer $brochureCount
     */
    public static function getBrochureCount($dbObject)
    {
        $brochureCount = $dbObject->count();
        return $brochureCount;
    }

    /**
     * Scope a query to get all data
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGetBrochureData($query, $request)
    {
        return $query->skip($request->start)->take($request->length)->get();
    }

    /*
     *    scopeGetFilteredData from App/Models/Brochure
     *   get filterred Brochures
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
            $query->where('type',1);

        });

    }

    /**
     * Scope a query to sort data
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSortBrochureData($query, $request)
    {

        return $query->orderBy(config('constant.brochureDataTableFieldArray')[$request->order['0']['column']], $request->order['0']['dir']);

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
     * Add & update Brochure addBrochure
     *
     * @param array $models
     * @return boolean true | false
     */
    public function addBrochure(array $models = [])
    {
        $brochureLog = new BrochureLog();
        if (isset($models['id']) && !empty($models['id'])) {
            $brochure = Brochure::find($models['id']);
            $brochureLog->action = "Update";
        } else {
            $brochure = new Brochure;
            $brochureLog->created_by = $brochure->created_by = Auth::id();
            $brochureLog->action = "Add";
        }

        $brochureLog->name = $brochure->name = $models['name'];
        $brochureLog->category_id = $brochure->category_id = $models['category_id'];
        $brochureLog->machine_id = $brochure->machine_id = $models['machine_id'];
        $brochureLog->type = $brochure->type = $models['type'];


        if (isset($models['file_name']) && $models['file_name'] != "") {
            $filepath = public_path() . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'brochure' . DIRECTORY_SEPARATOR;
            $brochure->file_name = LaraHelpers::upload_image($filepath, $models['file_name'], $models['old_file_name']);
        }
        $brochureLog->created_by = $brochureLog->updated_by = $brochure->updated_by = Auth::id();
        if (isset($models['status'])) {
            $brochureLog->status = $brochure->status = $models['status'];
        } else {
            $brochureLog->status = $brochure->status = 0;
        }

        $brochureId = $brochure->save();
        $brochureLog->brochure_id = $brochure->id;
        $brochureLog->save();

        // For sending the Notification to user who are logged in
        /*
         * @param array $data
         *
         * @param string $type
         * */
        if(!isset($models['id'])){
            $data = $brochure->getBrochureByField($brochure->id,'id');
            $filepath = url('/') . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'brochure' . DIRECTORY_SEPARATOR;
            $data['title'] = $data->name;
            $data['message'] = "New product brochure added to the list";
            $data['imageUrl'] = "";
            $notification = new Notification();
            $notification->sendNotification($data ,"Brochure");
        }

        if ($brochureId)
            return true;
        else
            return false;
    }

    /**
     * get Brochure By fieldname getBrochureByField
     *
     * @param mixed $id
     * @param string $field_name
     * @return mixed
     */
    public function getBrochureByField($id, $field_name)
    {
        return Brochure::where($field_name, $id)->first();
    }

    /**
     * update Brochure Status
     *
     * @param array $models
     * @return boolean true | false
     */
    public function updateStatus(array $models = [])
    {
        $brochureLog = new BrochureLog();

        $brochure = Brochure::find($models['id']);
        $brochureLog->status = $brochure->status = $models['status'];
        $brochureLog->created_by = $brochureLog->updated_by = $brochure->updated_by = Auth::id();
        $brochureLog->action = "Status Changed";
        $brochureId = $brochure->save();
        $brochureLog->brochure_id = $brochure->id;
        $brochureLog->save();


        if ($brochureId)
            return true;
        else
            return false;

    }

    /**
     * Delete Brochure
     *
     * @param int $id
     * @return boolean true | false
     */
    public function deleteBrochure($id,$field)
    {

        /*$brochureLog = new BrochureLog();
        $brochureLog->created_by = $brochureLog->updated_by = Auth::id();
        $brochureLog->action = "Delete";
        $delete = Brochure::where('id', $id)->delete();
        $brochureLog->brochure_id = $id;
        $brochureLog->save();

        if ($delete)
            return true;
        else
            return false;*/

        $delete = Brochure::where($field, $id)->first();
        if(empty($delete)){
           return true;
        }
        $deleteFile = $delete;
        $filepath = url('/') . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'brochure' . DIRECTORY_SEPARATOR;
        $unlink_image = $deleteFile->file_name;
        if (isset($unlink_image) && $unlink_image != "") {
            if(file_exists($filepath . $unlink_image)){
                unlink($filepath . $unlink_image);
            }
        }
        $delete = $deleteFile->delete();
        if ($delete)
            return true;
        else
            return false;

    }

    /**
     * get Master Brochure
     *
     *
     * @return mixed
     */
    public function getMasterBrochure()
    {
        $brochureData = Brochure::where('type', 0)->first();
        if($brochureData){
            return $brochureData;
        }
        else{
            $brochure = new Brochure;
            $brochure->name = "Master Brochure";
            $brochure->category_id = 0;
            $brochure->machine_id= 0;
            $brochure->type= 0;
            $brochure->status= 1;
            $brochure->created_by = Auth::id();
            $brochure->save();
            return Brochure::where('type', 0)->first();
        }
    }

    /**
     * Get all Brochure getBrochureCollection For API call
     *
     * @param array $models
     * @return mixed
     */
    public function getBrochureCollection()
    {
        return Brochure::with('Category','Machine')
                        ->select('tbl_brochure.*')
                        ->where('status',1)
                        ->get();
    }
}
