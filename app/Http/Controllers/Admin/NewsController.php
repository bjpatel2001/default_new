<?php

namespace App\Http\Controllers\Admin;

use App\Models\NewsLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\News;
use Validator;

class NewsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $news;
    protected $news_log;

    public function __construct(News $news, NewsLog $news_log)
    {
        $this->middleware('auth');
        $this->news = $news;
        $this->news_log = $news_log;

    }

    /**
     * Display a listing of the news.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {

        /*
         *  getCollection from App/Models/News
         *
         *  @return mixed
         * */


        $data['newsManagementTab'] = "active open";
        $data['newsTab'] = "active";
        return view('admin.news.newslist', $data);
    }

    public function datatable(Request $request)
    {
        // default count of news $newsCount
        $newsCount = 0;

        /*
         *    getDatatableCollection from App/Models/News
         *   get all newss
         *
         *  @return mixed
         * */
        $newsData = $this->news->getDatatableCollection();

        /*
         *    scopeGetFilteredData from App/Models/News
         *   get filterred newss
         *
         *  @return mixed
         * */
        $newsData = $newsData->GetFilteredData($request);

        /*
         *    getNewsCount from App/Models/News
         *   get count of newss
         *
         *  @return integer
         * */
        $newsCount = $this->news->getNewsCount($newsData);

        //  Sorting news data base on requested sort order
        if (isset(config('constant.newsDataTableFieldArray')[$request->order['0']['column']])) {
            $newsData = $newsData->SortNewsData($request);
        } else {
            $newsData = $newsData->SortDefaultDataByRaw('tbl_news.id', 'desc');
        }

        /*
         *  get paginated collection of news
         *
         * @param  \Illuminate\Http\Request $request
         * @return mixed
         * */
        $newsData = $newsData->GetNewsData($request);

        $appData = array();
        foreach ($newsData as $newsData) {
            $row = array();
            $row[] = $newsData->title;
            $row[] = view('datatable.switch', ['module' => "news", 'status' => $newsData->status, 'id' => $newsData->id])->render();
            $row[] = view('datatable.action', ['module' => "news","log" => true,'id' => $newsData->id])->render();
            $appData[] = $row;
        }

        return [
            'draw' => $request->draw,
            'recordsTotal' => $newsCount,
            'recordsFiltered' => $newsCount,
            'data' => $appData,
        ];
    }

    /**
     * Show the form for creating a new news.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        $data['newsManagementTab'] = "active open";
        $data['newsTab'] = "active";
        return view('admin.news.add',["data" => $data]);
    }

    /**
     * Display the specified news.
     *
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {

        /*
         *  get details of the specified news. from App/Models/News
         *
         * @param mixed $id
         * @param string (id) fieldname
         *  @return mixed
         * */

        $data['details'] = $this->news->getNewsByField($id, 'id');
        $data['masterManagementTab'] = "active open";
        $data['newsTab'] = "active";
        return view('admin.news.edit', ["data" => $data]);
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
            'title' => 'required|max:255',
           /* 'image' => 'required',*/
            'description' => 'required',
           );

        if($mode == 'edit')
        {
            $rules = array(
                'title' => 'required|max:255',
                'description' => 'required',

            );
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            $errorRedirectUrl = "admin/news/add";
            if ($mode == "edit") {
                $errorRedirectUrl = "admin/news/edit/" . $data['id'];
            }
            return redirect($errorRedirectUrl)->withInput()->withErrors($validator);
        }
        return false;
    }

    /**
     * Store a newly created news in storage.
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

        $addnews = $this->news->addNews($request->all());
        if ($addnews) {
            $request->session()->flash('alert-success', trans('app.news_add_success'));
            return redirect('admin/news/list');
        } else {
            $request->session()->flash('alert-danger', trans('app.news_error'));
            return redirect('admin/news/add')->withInput();
        }
    }

    /**
     * Update the specified news in storage.
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
        $addnews = $this->news->addNews($request->all());
        if ($addnews) {
            $request->session()->flash('alert-success', trans('app.news_edit_success'));
            return redirect('admin/news/list');
        } else {
            $request->session()->flash('alert-danger', trans('app.news_error'));
            return redirect('admin/news/edit/' . $request->get('id'))->withInput();
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
        $updateNews = $this->news->updateStatus($request->all());
        if ($updateNews) {
            $request->session()->flash('alert-success', trans('app.news_status_success'));
        } else {
            $request->session()->flash('alert-danger', trans('app.news_error'));
        }
        echo 1;
    }

    /**
     * Delete the specified news in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function delete(request $request)
    {

        $deleteUser = $this->news->deleteNews($request->id);
        if ($deleteUser) {
            $request->session()->flash('alert-success', trans('app.news_delete_success'));
        } else {
            $request->session()->flash('alert-danger', trans('app.news_error'));
        }
        echo 1;
    }

    /**
     * log the specified news in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function log(request $request)
    {
        $data['details'] = $this->news_log->getCollection($request->id);
        $data['newsTab'] = "active";
        return view('admin.news.newslog', $data);
    }

}
