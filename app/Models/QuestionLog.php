<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionLog extends Model
{
    protected $table = 'log_tbl_question';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'question_id', 'status','action',
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
        return QuestionLog::with('CreatedBy')->where('question_id',$id)->get();
    }
}
