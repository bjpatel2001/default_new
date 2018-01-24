<?php

namespace App\Http\Controllers\Admin;

use App\Models\CategoryLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Support\Facades\Input;
use Validator;

class CategoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $category;
    protected $category_log;

    public function __construct(Category $category, CategoryLog $category_log)
    {
        $this->middleware('auth');
        $this->category = $category;
        $this->category_log = $category_log;
    }

    /**
     * Display a listing of the category.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {

        /*
         *  getCollection from App/Models/Category
         *
         *  @return mixed
         * */


        $data['productManagementTab'] = "active open";
        $data['categoryTab'] = "active";
        return view('admin.category.categorylist', $data);
    }

    public function datatable(Request $request)
    {
        // default count of category $categoryCount
        $categoryCount = 0;

        /*
         *    getDatatableCollection from App/Models/Category
         *   get all categorys
         *
         *  @return mixed
         * */
        $categoryData = $this->category->getDatatableCollection();

        /*
         *    scopeGetFilteredData from App/Models/Category
         *   get filterred categorys
         *
         *  @return mixed
         * */
        $categoryData = $categoryData->GetFilteredData($request);

        /*
         *    getCategoryCount from App/Models/Category
         *   get count of categorys
         *
         *  @return integer
         * */
        $categoryCount = $this->category->getCategoryCount($categoryData);

        //  Sorting category data base on requested sort order
        if (isset(config('constant.categoryDataTableFieldArray')[$request->order['0']['column']])) {
            $categoryData = $categoryData->SortCategoryData($request);
        } else {
            $categoryData = $categoryData->SortDefaultDataByRaw('tbl_category.id', 'desc');
        }

        /*
         *  get paginated collection of category
         *
         * @param  \Illuminate\Http\Request $request
         * @return mixed
         * */
        $categoryData = $categoryData->GetCategoryData($request);

        $appData = array();
        foreach ($categoryData as $categoryData) {
            $row = array();
            $row[] = $categoryData->category_name;
            $row[] = view('datatable.switch', ['module' => "category", 'status' => $categoryData->status, 'id' => $categoryData->id])->render();
            $row[] = view('datatable.action', ['module' => "category","log" => true,'id' => $categoryData->id])->render();
            $appData[] = $row;
        }

        return [
            'draw' => $request->draw,
            'recordsTotal' => $categoryCount,
            'recordsFiltered' => $categoryCount,
            'data' => $appData,
        ];
    }

    /**
     * Show the form for creating a new category.
     *
     * @return \Illuminate\Http\Response
     */

    public function create($flag = null)
    {
        $data['productManagementTab'] = "active open";
        $data['categoryTab'] = "active";
        if($flag != ""){
            $data['flag'] = $flag;
        }
        return view('admin.category.add', $data);
    }

    /**
     * Display the specified category.
     *
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id, $flag = null)
    {

        /*
         *  get details of the specified category. from App/Models/Category
         *
         * @param mixed $id
         * @param string (id) fieldname
         *  @return mixed
         * */
        $data['details'] = $this->category->getCategoryByField($id, 'id');
        if($flag != ""){
            $data['flag'] = $flag;
        }
        $data['masterManagementTab'] = "active open";
        $data['categoryTab'] = "active";
        return view('admin.category.edit', $data);
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
            'category_name' => 'required|max:50',
        );

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            $errorRedirectUrl = "admin/category/add";
            if ($mode == "edit") {
                $errorRedirectUrl = "admin/category/edit/" . $data['id'];
            }
            return redirect($errorRedirectUrl)->withInput()->withErrors($validator);
        }
        return false;
    }

    /**
     * Store a newly created category in storage.
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

        $addcategory = $this->category->addCategory($request->all());
        if ($addcategory) {
            $request->session()->flash('alert-success', trans('app.category_add_success'));
            if (isset($request->quotation_flag) && $request->quotation_flag != null){
                return redirect('admin/quotation/list');
            }else{
                return redirect('admin/category/list');
            }
        } else {
            $request->session()->flash('alert-danger', trans('app.category_error'));
            return redirect('admin/category/add')->withInput();
        }
    }

    /**
     * Update the specified category in storage.
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

        $addcategory = $this->category->addCategory($request->all());
        if ($addcategory) {
            $request->session()->flash('alert-success', trans('app.category_edit_success'));
            if (isset($request->quotation_flag) && $request->quotation_flag != null){
                return redirect('admin/quotation/list');
            }else{
                return redirect('admin/category/list');
            }
        } else {
            $request->session()->flash('alert-danger', trans('app.category_error'));
            return redirect('admin/category/edit/' . $request->get('id'))->withInput();
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
        $updateCategory = $this->category->updateStatus($request->all());
        if ($updateCategory) {
            $request->session()->flash('alert-success', trans('app.category_status_success'));
        } else {
            $request->session()->flash('alert-danger', trans('app.category_error'));
        }
        echo 1;
    }

    /**
     * Delete the specified category in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function delete(request $request)
    {

        $deleteUser = $this->category->deleteCategory($request->id);
        if ($deleteUser) {
            $request->session()->flash('alert-success', trans('app.category_delete_success'));
        } else {
            $request->session()->flash('alert-danger', trans('app.category_error'));
        }
        echo 1;
    }

    /**
     * log the specified category in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function log(request $request)
    {
        $data['details'] = $this->category_log->getCollection($request->id);
        $data['productManagementTab'] = "active open";
        $data['categoryTab'] = "active";
        return view('admin.category.categorylog', $data);
    }


    /**
     * Show the form for creating a new category.
     *
     * @return \Illuminate\Http\Response
     */

    public function getMachineByCategoryId(request $request)
    {
         $state =  $this->category->getProductByCategoryId($request->all());
        return response()->json($state);

    }
}
