<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestQuestion extends Model
{
    //
    protected $table = 'tbl_requested_question';
    protected $primaryKey = 'id';

    /**
     * Inserting the Request Question data
     *
     * @param  $data
     * @return object
     */

    public function insertQuestion($data)
    {
        $request_question = new RequestQuestion();
        $request_question->request_id = $data['request_id'];
        $request_question->question = $data['question'];
        $request_question->answer = $data['option'];
        $request_question->created_by = $data['user_id'];
        $request_question->updated_by = $data['user_id'];
        $request_question_id = $request_question->save();

        if($request_question_id){
            return $request_question;
        }
        return false;

    }

    /**
     * Delete Request Question
     *
     * @param string $field_name
     * @param int $id
     * @return boolean true | false
     */
    public function deleteRequestQuestion($field_name,$id)
    {
        $delete = RequestQuestion::where($field_name,$id)->delete();
        if ($delete)
            return true;
        else
            return false;

    }
}
