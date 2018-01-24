<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Machine;
use App\Models\MachineImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Quotation;
use Validator;
use App\Models\QuotationMapping;
class QuotationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $quotation;
    protected $category;
    protected $machine;
    protected $machineimage;

    public function __construct(Quotation $quotation, Category $category, Machine $machine,MachineImage $machineimage)
    {
        $this->middleware('auth');
        $this->quotation = $quotation;
        $this->category = $category;
        $this->machine = $machine;
        $this->machineimage = $machineimage;
    }

    /**
     * Display a listing of the quotation.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {

        /*
         *  getCollection from App/Models/Quotation
         *
         *  @return mixed
         * */


        $data['quotationManagementTab'] = "active open";
        $data['quotationTemplateTab'] = "active";
        $data['details'] = $this->quotation->getCollection();
        $data['categoryData'] = $this->category->getCollection();
        return view('admin.quotation.quotation_list', $data);
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
            'section_name' => 'required|max:50',
            'product_name' => 'required'
        );

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            $errorRedirectUrl = "admin/quotation/list";
            return redirect($errorRedirectUrl)->withInput()->withErrors($validator);
        }
        return false;
    }

    /**
     * Store a newly created quotation in storage.
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

        $addquotation = $this->quotation->addQuotation($request->all());
        if ($addquotation) {
            $request->session()->flash('alert-success', trans('app.quotation_add_success'));
            return redirect('admin/quotation/list');
        } else {
            $request->session()->flash('alert-danger', trans('app.quotation_error'));
            return redirect('admin/quotation/list')->withInput();
        }
    }

    /**
     * Update the specified quotation in storage.
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

        $addquotation = $this->quotation->addQuotation($request->all());
        if ($addquotation) {
            $request->session()->flash('alert-success', trans('app.quotation_edit_success'));
            return redirect('admin/quotation/list');
        } else {
            $request->session()->flash('alert-danger', trans('app.quotation_error'));
            return redirect('admin/quotation/edit/' . $request->get('id'))->withInput();
        }
    }
    /**
     * Get a listing of images of the quotation.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function categoryProducts(request $request)
    {

        $row = '';

        $machineArray = [];
        $cat_machine_datas = QuotationMapping::whereIn('category_id', $request->id)->get();
        foreach ($cat_machine_datas as $cat_machine_data){
            $machineArray[] = $cat_machine_data->machine_id;
        }

        $details = $this->quotation->getCollection();
        foreach ($request->id as $ids){
            $machine = $this->category->getMachineByCategory($ids);
            foreach($machine as $machineId){
                foreach ($machineId->machine as $key=>$machineData){
                    $machineId->machine[$key]['machineImages'] = '';
                    $machineImages = $this->machineimage->getImage($machineData->id);
                    if($machineImages){
                        $machineId->machine[$key]['machineImages'] = $machineImages->image;
                    }
                }
            }

            $row.= view('admin.quotation.productsection', ['machineData'=>$machine,'machineArray'=>$machineArray,'details'=>$details ])->render();
        }

        return [
            'data' => $row,
            'statusCode' => '1'
        ];
    }
}
