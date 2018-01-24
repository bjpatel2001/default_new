<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\CountryLog;
use App\Models\State;
use Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    protected $table = 'tbl_country';
    protected $primaryKey = 'id';
    use SoftDeletes;

    public function States()
    {
        return $this->hasMany('App\Models\State','country_id','id')->where('status',1);
    }

    /**
     * Get all Countrys getCollection
     *
     * @param array $models
     * @return mixed
     */
    public function getCollection(array $models = [])
    {
        $country = Country::with('States')->select('tbl_country.*');
        if(isset($models['check_status']) && $models['check_status'] == 1){
            $country->where('status',1);
        }
        return $country->get();
    }

    /**
     * Get all Country with Country & User relationship
     *
     * @return mixed
     */
    public function getDatatableCollection()
    {
        return Country::select('tbl_country.*');
    }

    /**
     * Query to get country total count
     *
     * @param $dbObject
     * @return integer $countryCount
     */
    public static function getCountryCount($dbObject)
    {
        $countryCount = $dbObject->count();
        return $countryCount;
    }

    /**
     * Scope a query to get all data
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGetCountryData($query, $request)
    {
        return $query->skip($request->start)->take($request->length)->get();
    }

    /*
     *    scopeGetFilteredData from App/Models/Country
     *   get filterred countrys
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
    public function scopeSortCountryData($query, $request)
    {

        return $query->orderBy(config('constant.countryDataTableFieldArray')[$request->order['0']['column']], $request->order['0']['dir']);

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
     * Add & update Country addCountry
     *
     * @param array $models
     * @return boolean true | false
     */
    public function addCountry(array $models = [])
    {
        $countryLog = new CountryLog();

        if (isset($models['id'])) {
            $country = Country::find($models['id']);
            $countryLog->action = "Update";
        } else {
            $country = new Country;
            $countryLog->created_by = $country->created_by = Auth::id();
            $countryLog->action = "Add";
        }
        $countryLog->name = $country->name = $models['name'];
        $countryLog->created_by = $country->created_by = $country->updated_by = Auth::id();
        if (isset($models['status'])) {
            $countryLog->status = $country->status = $models['status'];
        } else {
            $countryLog->status = $country->status = 0;
        }
        $countryId = $country->save();


        $countryLog->country_id = $country->id;
        $countryLog->save();

        if ($countryId)
            return true;
        else
            return false;
    }

    /**
     * get country By fieldname getCountryByField
     *
     * @param mixed $id
     * @param string $field_name
     * @return mixed
     */
    public function getCountryByField($id, $field_name)
    {
        return Country::where($field_name, $id)->first();
    }

    /**
     * update Country Status
     *
     * @param array $models
     * @return boolean true | false
     */
    public function updateStatus(array $models = [])
    {
        $countryLog = new CountryLog();

        $country = Country::find($models['id']);
        $countryLog->status = $country->status = $models['status'];
        $countryLog->created_by = $countryLog->updated_by = $country->updated_by = Auth::id();
        $countryLog->action = "Update";
        $countryId = $country->save();
        $countryLog->country_id = $country->id;
        $countryLog->save();


        if ($countryId)
            return true;
        else
            return false;

    }

    /**
     * Delete Country
     *
     * @param int $id
     * @return boolean true | false
     */
    public function deleteCountry($id)
    {
        $countryData = State::where('country_id',$id)->first();
        if($countryData){
            return false;
        }

        $countryLog = new CountryLog();
        $countryLog->created_by = $countryLog->updated_by = Auth::id();
        $countryLog->action = "Delete";
        $delete = Country::where('id', $id)->delete();
        $countryLog->country_id = $id;
        $countryLog->save();

        if ($delete)
            return true;
        else
            return false;

    }


    public function State()
    {
        return $this->hasMany('App\Models\State','country_id','id');
    }
    /**
     * Get all State with State & User relationship
     *
     * @return mixed
     */
    public function getLocation()
    {
        return Country::with('State')->get();
    }
}
