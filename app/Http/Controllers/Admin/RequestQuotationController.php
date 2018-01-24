<?php

namespace App\Http\Controllers\Admin;
use App\Models\RequestCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use App\Models\RequestQuotation;
use DB;

class RequestQuotationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $requestquotation;
    protected $requestCategory;

    public function __construct(RequestQuotation $requestquotation,RequestCategory $requestCategory)
    {
        $this->requestquotation = $requestquotation;
        $this->requestCategory = $requestCategory;
    }

    /**
     * Display a listing of the Request.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {

        /*
         *  getCollection from App/Models/Request
         *
         *  @return mixed
         * */
        $data['requestManagementTab'] = "active open";
        $data['requestTab'] = "active";
        $data['requestData'] = RequestQuotation::with('User','State','Country')->orderBy('id','desc')->get();
        return view('admin.request.requestlist',$data);
    }

    public function datatable(Request $request)
    {
        // default count of request $requestCount
        $requestCount = 0;

        /*
         *    getDatatableCollection from App/Models/Request
         *
         *   get all request
         *
         *  @return mixed
         * */
        $requestData = $this->requestquotation->getDatatableCollection();

        /*
         *    scopeGetFilteredData from App/Models/request
         *   get filterred requests
         *
         *  @return mixed
         * */
        $requestData = $requestData->GetFilteredData($request);

        /*
         *    getRequestCount from App/Models/Request
         *   get count of requests
         *
         *  @return integer
         * */
        $requestCount = $this->requestquotation->getRequestCount($requestData);

        //  Sorting request data base on requested sort order
        if (isset(config('constant.requestDataTableFieldArray')[$request->order['0']['column']])) {
            $requestData = $requestData->SortRequestData($request);
        } else {
            $requestData = $requestData->SortDefaultDataByRaw('tbl_request.id', 'desc');
        }

        /*
         *  get paginated collection of request
         *
         * @param  \Illuminate\Http\Request $request
         * @return mixed
         * */
        $requestData = $requestData->GetRequestData($request);
        $appData = array();
        foreach ($requestData as $requestData) {
            $row = array();
            $row[] = $requestData->request;
            $row[] = ($requestData->type == 0)?"Optional":"Without Option";
            $row[] = '<i class="fa fa-eye request_image_class" rel="'.$requestData->id.'"></i>';
            $row[] = view('datatable.switch', ['module' => "request", 'status' => $requestData->status, 'id' => $requestData->id])->render();
            $row[] = view('datatable.action', ['module' => "request","log" => true,'id' => $requestData->id])->render();
            $appData[] = $row;
        }

        return [
            'draw' => $request->draw,
            'recordsTotal' => $requestCount,
            'recordsFiltered' => $requestCount,
            'data' => $appData,
        ];
    }

    public function view(Request $request)
    {

        $requestData = $this->requestquotation->getRequestByField($request->id,'id');

        $catData = RequestCategory::select(\DB::raw("GROUP_CONCAT(tbl_machine.machine_name) as machine_names"),'tbl_category.category_name')
                    ->leftjoin("tbl_category",'tbl_category.id','=','tbl_request_category.category_id')
                    ->leftjoin("tbl_machine",'tbl_machine.id','=','tbl_request_category.machine_id')
                    ->where('tbl_request_category.request_id',$request->id)
                    ->groupBy("tbl_request_category.category_id")
                    ->get();

        $data['requestManagementTab'] = "active open";
        $data['requestTab'] = "active";
        $data['requestData'] = $requestData;
        $data['categoryData'] = $catData;
        return view('admin.request.view',$data);
    }

}
