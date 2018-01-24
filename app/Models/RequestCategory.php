<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestCategory extends Model
{
    //
    protected $table = 'tbl_request_category';
    protected $primaryKey = 'id';

    public function Category()
    {
        return $this->hasOne('App\Models\Category','id','category_id');
    }

    public function Machine()
    {
        return $this->hasOne('App\Models\Machine','id','machine_id');
    }
    /**
     * Inserting the Request Qoutation data
     *
     * @param  $data
     * @return object
     */

    public function insertCategory($data)
    {
        $request_category = new RequestCategory();
        $request_category->request_id = $data['request_id'];
        $request_category->category_id = $data['category_id'];
        $request_category->machine_id = $data['machine_id'];
        $request_category_id = $request_category->save();

        if($request_category_id){
            return $request_category;
        }
        return false;

    }

    /**
     * Delete Request Category
     *
     * @param string $field_name
     * @param int $id
     * @return boolean true | false
     */
    public function deleteRequestCategory($field_name,$id)
    {
        $delete = RequestCategory::where($field_name,$id)->delete();
        if ($delete)
            return true;
        else
            return false;

    }
}
