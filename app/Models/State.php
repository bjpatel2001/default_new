<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\StateLog;
use Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class State extends Model
{
    protected $table = 'tbl_state';
    protected $primaryKey = 'id';
    use SoftDeletes;

    public function Country()
    {
        return $this->hasOne('App\Models\Country','id','country_id');
    }

    /**
     * Get all States getCollection
     *
     * @param array $models
     * @return mixed
     */
    public function getCollection(array $models = [])
    {
        $state = State::select('tbl_state.*');
        if(isset($models['check_status']) && $models['check_status'] == 1){
            $state->where('status',1);
        }
        return $state->get();
    }

    /**
     * Get all State with State & User relationship
     *
     * @return mixed
     */
    public function getDatatableCollection()
    {
        return State::with('Country')->select('tbl_state.*','tbl_country.name as c_name');
    }

    /**
     * Query to get state total count
     *
     * @param $dbObject
     * @return integer $stateCount
     */
    public static function getStateCount($dbObject)
    {
        $stateCount = $dbObject->count();
        return $stateCount;
    }

    /**
     * Scope a query to get all data
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGetStateData($query, $request)
    {
        return $query->skip($request->start)->take($request->length)->get();
    }

    /*
     *    scopeGetFilteredData from App/Models/State
     *   get filterred states
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
        return $query->leftJoin('tbl_country','tbl_country.id','=','tbl_state.country_id')
                ->where(function ($query) use ($filter, $Datefilter, $filterSelect) {
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
    public function scopeSortStateData($query, $request)
    {

        return $query->orderBy(config('constant.stateDataTableFieldArray')[$request->order['0']['column']], $request->order['0']['dir']);

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
     * Add & update State addState
     *
     * @param array $models
     * @return boolean true | false
     */
    public function addState(array $models = [])
    {
        $stateLog = new StateLog();

        if (isset($models['id'])) {
            $state = State::find($models['id']);
            $stateLog->action = "Update";
        } else {
            $state = new State;
            $stateLog->created_by = $state->created_by = Auth::id();
            $stateLog->action = "Add";
        }
        $stateLog->name = $state->name = $models['name'];
        $stateLog->country_id = $state->country_id = $models['country_id'];
        $stateLog->created_by = $state->created_by = $state->updated_by = Auth::id();
        if (isset($models['status'])) {
            $stateLog->status = $state->status = $models['status'];
        } else {
            $stateLog->status = $state->status = 0;
        }
        $stateId = $state->save();


        $stateLog->state_id = $state->id;
        $stateLog->save();

        if ($stateId)
            return true;
        else
            return false;
    }

    /**
     * get state By fieldname getStateByField
     *
     * @param mixed $id
     * @param string $field_name
     * @return mixed
     */
    public function getStateByField($id, $field_name)
    {
        return State::where($field_name, $id)->first();
    }

    /**
     * update State Status
     *
     * @param array $models
     * @return boolean true | false
     */
    public function updateStatus(array $models = [])
    {
        $stateLog = new StateLog();

        $state = State::find($models['id']);
        $stateLog->status = $state->status = $models['status'];
        $stateLog->created_by = $stateLog->updated_by = $state->updated_by = Auth::id();
        $stateLog->action = "Update";
        $stateId = $state->save();
        $stateLog->state_id = $state->id;
        $stateLog->save();


        if ($stateId)
            return true;
        else
            return false;

    }

    /**
     * Delete state
     *
     * @param int $id
     * @return boolean true | false
     */
    public function deleteState($id)
    {
        $stateLog = new StateLog();
        $stateLog->created_by = $stateLog->updated_by = Auth::id();
        $stateLog->action = "Delete";
        $delete = State::where('id', $id)->delete();
        $stateLog->state_id = $id;
        $stateLog->save();

        if ($delete)
            return true;
        else
            return false;

    }

    /**
     * get state list country wise
     *
     * @param array $models
     * @return mixed
     */
    public function get_state_list(array $models = [])
    {
        $states = State::where('country_id',$models['country_id']);
        if(isset($models['check_status']) && $models['check_status'] == 1){
            $states->where('status',1);
        }
        return $states->get();
    }

}
