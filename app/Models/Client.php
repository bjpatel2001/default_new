<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\LaraHelpers;
use App\Models\ClientLog;

class Client extends Model
{
    protected $table = 'tbl_client';
    protected $primaryKey = 'id';
    use SoftDeletes;

    /**
     * Get all Client getCollection
     *
     * @param array $models
     * @return mixed
     */
    public function getCollection(array $models = [])
    {
        $client = Client::select('tbl_client.*');
        if(isset($models['check_status']) && $models['check_status'] == 1){
            $client->where('status',1);
        }
        return $client->get();
    }

    /**
     * Get all Client with Client & User relationship
     *
     * @return mixed
     */
    public function getDatatableCollection()
    {
        return Client::select('tbl_client.*');

    }

    /**
     * Query to get Client total count
     *
     * @param $dbObject
     * @return integer $clientCount
     */
    public static function getClientCount($dbObject)
    {
        $clientCount = $dbObject->count();
        return $clientCount;
    }

    /**
     * Scope a query to get all data
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGetClientData($query, $request)
    {
        return $query->skip($request->start)->take($request->length)->get();
    }

    /*
     *    scopeGetFilteredData from App/Models/Client
     *   get filterred Clients
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
    public function scopeSortClientData($query, $request)
    {

        return $query->orderBy(config('constant.clientDataTableFieldArray')[$request->order['0']['column']], $request->order['0']['dir']);

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
     * Add & update Client addClient
     *
     * @param array $models
     * @return boolean true | false
     */
    public function addClient(array $models = [])
    {
        $clientLog = new ClientLog();
        if (isset($models['id'])) {
            $client = Client::find($models['id']);
            $clientLog->action = "Update";
        } else {
            $client = new Client;
            $clientLog->created_by = $client->created_by = Auth::id();
            $clientLog->action = "Add";
        }

        $clientLog->name = $client->name = $models['name'];
        $clientLog->description = $client->description = $models['description'];
        $clientLog->type = $client->type = $models['type'];


        if (isset($models['image']) && $models['image'] != "") {
            $filepath = public_path() . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'client' . DIRECTORY_SEPARATOR;
            $client->image = LaraHelpers::upload_image($filepath, $models['image'], $models['old_image']);
        }
        $clientLog->created_by = $clientLog->updated_by = $client->updated_by = Auth::id();
        if (isset($models['status'])) {
            $clientLog->status = $client->status = $models['status'];
        } else {
            $clientLog->status = $client->status = 0;
        }

        $clientId = $client->save();
        $clientLog->client_id = $client->id;
        $clientLog->save();

        if ($clientId)
            return true;
        else
            return false;
    }

    /**
     * get Client By fieldname getClientByField
     *
     * @param mixed $id
     * @param string $field_name
     * @return mixed
     */
    public function getClientByField($id, $field_name)
    {
        return Client::where($field_name, $id)->first();
    }

    /**
     * update Client Status
     *
     * @param array $models
     * @return boolean true | false
     */
    public function updateStatus(array $models = [])
    {
        $clientLog = new ClientLog();

        $client = Client::find($models['id']);
        $clientLog->status = $client->status = $models['status'];
        $clientLog->created_by = $clientLog->updated_by = $client->updated_by = Auth::id();
        $clientLog->action = "Status Changed";
        $clientId = $client->save();
        $clientLog->client_id = $client->id;
        $clientLog->save();


        if ($clientId)
            return true;
        else
            return false;
    }

    /**
     * Delete Client
     *
     * @param int $id
     * @return boolean true | false
     */
    public function deleteClient($id)
    {

        $clientLog = new ClientLog();
        $clientLog->created_by = $clientLog->updated_by = Auth::id();
        $clientLog->action = "Delete";
        $delete = Client::where('id', $id)->delete();
        $clientLog->client_id = $id;
        $clientLog->save();

        if ($delete)
            return true;
        else
            return false;
    }

    /**
     * Get all Client getClientCollection For API call
     *
     *
     * @return mixed
     */
    public function getClientCollections()
    {
        return Client::where('status',1)
            ->get();
    }

    /**
     * Get Recent Client {3} getRecentClientCollection For API call
     *
     * @return mixed
     */
    public function getRecentClientCollection()
    {
        return Client::where('status',1)->orderBy('id','desc')->take(3)->get();
    }
}
