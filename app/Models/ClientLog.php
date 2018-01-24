<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientLog extends Model
{
    //

    protected $table = 'log_tbl_client';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','client_id','description','type', 'status','action',
    ];

    public function CreatedBy()
    {
        return $this->hasOne('App\Models\User','id','created_by');
    }

    /**
     * Get all Client log Client wise getCollection
     *
     * @param int $id
     * @return mixed
     */
    public function getCollection($id)
    {
        return ClientLog::with('CreatedBy')->where('client_id',$id)->get();
    }
}
