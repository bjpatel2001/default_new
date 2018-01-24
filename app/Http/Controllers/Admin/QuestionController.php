<?php

namespace App\Http\Controllers\Admin;

use App\Models\QuestionOption;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\QuestionLog;
use App\Http\Controllers\Controller;
use Validator;
use App\Models\Country;

class QuestionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $question;
    protected $question_log;
    protected $question_option;


    public function __construct(Question $question, QuestionLog $question_log,QuestionOption $question_option)
    {
        $this->middleware('auth');
        $this->question = $question;
        $this->question_log = $question_log;
        $this->question_option = $question_option;
    }

    /**
     * Display a listing of the Question.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {

        /*
         *  getCollection from App/Models/Question
         *
         *  @return mixed
         * */
        $data['quotationManagementTab'] = "active open";
        $data['questionTab'] = "active";
        return view('admin.question.questionlist',$data);
    }

    public function datatable(Request $request)
    {
        // default count of question $questionCount
        $questionCount = 0;

        /*
         *    getDatatableCollection from App/Models/Question
         *
         *   get all question
         *
         *  @return mixed
         * */
        $questionData = $this->question->getDatatableCollection();

        /*
         *    scopeGetFilteredData from App/Models/question
         *   get filterred questions
         *
         *  @return mixed
         * */
        $questionData = $questionData->GetFilteredData($request);

        /*
         *    getQuestionCount from App/Models/Question
         *   get count of questions
         *
         *  @return integer
         * */
        $questionCount = $this->question->getQuestionCount($questionData);

        //  Sorting question data base on requested sort order
        if (isset(config('constant.questionDataTableFieldArray')[$request->order['0']['column']])) {
            $questionData = $questionData->SortQuestionData($request);
        } else {
            $questionData = $questionData->SortDefaultDataByRaw('tbl_question.id', 'desc');
        }

        /*
         *  get paginated collection of question
         *
         * @param  \Illuminate\Http\Request $request
         * @return mixed
         * */
        $questionData = $questionData->GetQuestionData($request);
        $appData = array();
        foreach ($questionData as $questionData) {
            $row = array();
            $row[] = $questionData->question;
            $row[] = ($questionData->type == 0)?"Optional":"Without Option";
            $row[] = '<i class="fa fa-eye question_image_class" rel="'.$questionData->id.'"></i>';
            $row[] = view('datatable.switch', ['module' => "question", 'status' => $questionData->status, 'id' => $questionData->id])->render();
            $row[] = view('datatable.action', ['module' => "question","log" => true,'id' => $questionData->id])->render();
            $appData[] = $row;
        }

        return [
            'draw' => $request->draw,
            'recordsTotal' => $questionCount,
            'recordsFiltered' => $questionCount,
            'data' => $appData,
        ];
    }

    /**
     * Show the form for creating a new question.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        $data['quotationManagementTab'] = "active open";
        $data['questionTab'] = "active";
        return view('admin.question.add',$data);
    }

    /**
     * Display the specified question.
     *
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {

        /*
         *  get details of the specified question. from App/Models/Question
         *
         * @param mixed $id
         * @param string (id) fieldname
         *  @return mixed
         * */
        $data['details'] = $this->question->getQuestionByField($id, 'id');
        $data['option'] = $this->question_option->getQuestionOption($id);

        $data['quotationManagementTab'] = "active open";
        $data['questionTab'] = "active";
        return view('admin.question.edit', $data);
    }


    /**
     * Validation of add and edit action customeValidate
     *
     * @param array $data
     * @param string $mode
     * @return mixed
     */

    public function customeValidate($data, $mode)
    {
        $rules = array(
            'question' => 'required',

        );

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            $errorRedirectUrl = "admin/question/add";
            if ($mode == "edit") {
                $errorRedirectUrl = "admin/question/edit/" . $data['id'];
            }
            return redirect($errorRedirectUrl)->withInput()->withErrors($validator);
        }
        return false;
    }

    /**
     * Store a newly created question in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(request $request)
    {
        $validations = $this->customeValidate($request->all(), 'add');
        if ($validations) {
            return $validations;
        }
        $addquestion = $this->question->addQuestion($request->all());
        if ($addquestion) {
            $request->session()->flash('alert-success', trans('app.question_add_success'));
            return redirect('admin/question/list');
        } else {
            $request->session()->flash('alert-danger', trans('app.question_error'));
            return redirect('admin/question/add')->withInput();
        }
    }

    /**
     * Update the specified question in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(request $request)
    {
        $validations = $this->customeValidate($request->all(), 'edit');
        if ($validations) {
            return $validations;
        }

        $addquestion = $this->question->addQuestion($request->all());
        if ($addquestion) {
            $request->session()->flash('alert-success', trans('app.question_edit_success'));
            return redirect('admin/question/list');
        } else {
            $request->session()->flash('alert-danger', trans('app.question_error'));
            return redirect('admin/question/edit/' . $request->get('id'))->withInput();
        }
    }

    /**
     * Update status to the specified user in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(request $request)
    {
        $updateQuestion = $this->question->updateStatus($request->all());
        if ($updateQuestion) {
            $request->session()->flash('alert-success', trans('app.question_status_success'));
        } else {
            $request->session()->flash('alert-danger', trans('app.question_error'));
        }
        echo 1;
    }

    /**
     * Delete the specified question in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function delete(request $request)
    {

        $deleteUser = $this->question->deleteQuestion($request->id);
        if ($deleteUser) {
            $request->session()->flash('alert-success', trans('app.question_delete_success'));
        } else {
            $request->session()->flash('alert-danger', trans('app.question_error'));
        }
        echo 1;
    }

    /**
     * log the specified question in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function log(request $request)
    {
        $data['details'] = $this->question_log->getCollection($request->id);
        $data['quotationManagementTab'] = "active open";
        $data['questionTab'] = "active";
        return view('admin.question.questionlog', $data);
    }

    /**
     * Get a listing of Question and Option.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function getQuestionOption(request $request)
    {
        //$getQuestion = $this->question->getCollection($request->id);
        $getQuestion = $this->question->getQuestionByField($request->id, 'id');
        $getOptions = $this->question_option->getQuestionOption($request->id);
        $view = NULL;

        $view.= '<div class="question_title"><lable style="font-size: 25px;font-weight: bold"> '.$getQuestion->question.' </lable></div>';
        foreach($getOptions as $options)
        {
            $view.='<li style="font-size:20px;"> '.$options->option.' </li>' ;
        }
        return $view;
    }

}
