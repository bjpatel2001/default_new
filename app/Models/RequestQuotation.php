<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class RequestQuotation extends Model
{
    //
    protected $table = 'tbl_request_quotation';
    protected $primaryKey = 'id';

    public function User()
    {
        return $this->hasOne('App\Models\AppUser','id','user_id');
    }

    public function State()
    {
        return $this->hasOne('App\Models\State','id','state_id');
    }

    public function Country()
    {
        return $this->hasOne('App\Models\Country','id','country_id');
    }
    public function RequestQuestion()
    {
        return $this->hasMany('App\Models\RequestQuestion','request_id','id');
    }

    /**
     * Inserting the Request Qoutation data
     *
     * @param  $data
     * @return object
     */
    public function insertData($data)
    {

        $request_quotation = new RequestQuotation();
        $request_quotation->user_id = $data['user_id'];
        $request_quotation->state_id = $data['state_id'];
        $request_quotation->country_id = $data['country_id'];
        $request_quotation->created_by = $data['user_id'];
        $request_quotation->updated_by = $data['user_id'];
        $request_quotation_id = $request_quotation->save();

        if($request_quotation_id){
            return $request_quotation;
        }
        return false;
    }

    public function getRequestByField($id, $field_name)
    {
        return RequestQuotation::with('User','State','Country','RequestQuestion')->where($field_name, $id)->first();
    }

    /**
     * Delete Request Quotation
     *
     * @param string $field_name
     * @param int $id
     * @return boolean true | false
     */
    public function deleteRequestQuotation($field_name,$id)
    {
        $delete = RequestQuotation::where($field_name,$id)->delete();
        if ($delete)
            return true;
        else
            return false;

    }
}
