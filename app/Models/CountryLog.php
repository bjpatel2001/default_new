<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CountryLog extends Model
{
    protected $table = 'log_tbl_country';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'country_id', 'status','action',
    ];

    public function CreatedBy()
    {
        return $this->hasOne('App\Models\User','id','created_by');
    }

    /**
     * Get all Machine log machine wise getCollection
     *
     * @param int $id
     * @return mixed
     */
    public function getCollection($id)
    {
        return CountryLog::with('CreatedBy')->where('country_id',$id)->get();
    }
}
