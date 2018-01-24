<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryLog extends Model
{
    //

    protected $table = 'log_tbl_category';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_name', 'status','action',
    ];

    public function CreatedBy()
    {
        return $this->hasOne('App\Models\User','id','created_by');
    }

    /**
     * Get all Category log category wise getCollection
     *
     * @param int $id
     * @return mixed
     */
    public function getCollection($id)
    {
        return CategoryLog::with('CreatedBy')->where('category_id',$id)->get();
    }
}
