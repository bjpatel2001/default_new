<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrochureLog extends Model
{
    //

    protected $table = 'log_tbl_brochure';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','filename','category_id','machine_id','type', 'status','action',
    ];

    public function CreatedBy()
    {
        return $this->hasOne('App\Models\User','id','created_by');
    }

    /**
     * Get all Brochure log Brochure wise getCollection
     *
     * @param int $id
     * @return mixed
     */
    public function getCollection($id)
    {
        return BrochureLog::with('CreatedBy')->where('brochure_id',$id)->get();
    }
}
