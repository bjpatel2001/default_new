<?php

namespace App\Http\Controllers\Api;
use App\Models\RequestCategory;
use App\Models\RequestQuestion;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use App\Models\RequestQuotation;

class RequestQuotationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $requestquotation;
    protected $requestCategory;
    protected $requestQuestion;

    public function __construct(RequestQuotation $requestquotation,RequestCategory $requestCategory,RequestQuestion $requestQuestion)
    {
        $this->requestquotation = $requestquotation;
        $this->requestCategory = $requestCategory;
        $this->requestQuestion = $requestQuestion;

    }

    /**
     * Show the form for creating a new requestquotation.
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(request $request)
    {
        $validator = Validator::make($request->all(), [
            'country_id' => 'required',
            'state_id' => 'required',
            'user_id' => 'required',
            'questions' => 'required',
            'selected_category' => 'required'
        ]);

        if ($validator->fails()) {
            return response(['statusCode' =>0,'errors' => [],'message' =>$validator->errors()->all()]);
        }

        if($request->all()){

            $data['user_id'] = $request->user_id;
            $data['state_id'] = $request->state_id;
            $data['country_id'] = $request->country_id;

            $requestData = $this->requestquotation->insertData($data);
            if($requestData){
                $requestId = $requestData->id;

                // For inserting Category and Machine
                $selected_category = json_decode($request->selected_category);
                foreach($selected_category as $category){

                    foreach($category->machine_id as $machine){
                        $categoryData['request_id'] = $requestId;
                        $categoryData['machine_id'] = $machine;
                        $categoryData['category_id'] = $category->category_id;
                        $this->requestCategory->insertCategory($categoryData);
                    }
                }

                // For inserting Question and option
                $questions = json_decode($request->questions);
                foreach($questions as $question){

                    $questionData['question'] = $question->question;
                    $questionData['option'] = $question->answer;
                    $questionData['request_id'] = $requestId;
                    $questionData['user_id'] = $request->user_id;
                    $this->requestQuestion->insertQuestion($questionData);

                }
            }


            return response(['statusCode' =>1,'message' => ['Quotation send successfully']]);
        }else{
            return response(['statusCode' =>0,'message' => ['Failed to send Quotation, Please tyr again! ']]);
        }
    }

}
