<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Helpers\LaraHelpers;
//use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionOption extends Model
{
    protected $table = 'tbl_question_option';
    protected $primaryKey = 'id';
    //use SoftDeletes;
    protected $fillable = [
        'option','question_id'
    ];


    /**
     * Add Option
     *
     * @param array $models
     * @return boolean true | false
     */
    public function addQuestionOption(array $models = [])
    {
        if(isset($models['option'])){
            foreach($models['option'] as $options)
            {
                $moduledata = QuestionOption::create([
                    'option' => $options,
                    'question_id' => $models['id'],

                ]);
            }
            if ($moduledata)
                return true;
            else
                return false;
        }

    }

    /**
     * Update Option
     *
     * @param array $models
     * @return boolean true | false
     */
    public function updateQuestion(array $models = [])
    {
        $deleteData = QuestionOption::where('question_id',$models['id'])->delete();
       if($deleteData){
            if(isset($models['option'])){
                foreach($models['option'] as $options)
                {
                    $moduledata = QuestionOption::create([
                        'option' => $options,
                        'question_id' => $models['id'],

                    ]);
                }
                if ($moduledata)
                    return true;
                else
                    return false;
            }
        }
    }

    /**
     * get Question Options
     *
     * @param mixed $id
     *
     * @return mixed
     */
    public function getQuestionOption($id)
    {
        return QuestionOption::where('question_id',$id)->get();
    }

    /**
     * Delete Option
     *
     * @param mixed $id
     *
     * @return mixed
     */
    public function deleteOption($id)
    {
        return QuestionOption::where('question_id',$id)->delete();
    }

}
