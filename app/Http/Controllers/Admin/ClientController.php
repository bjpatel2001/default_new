<?php

namespace App\Http\Controllers\Admin;

use App\Models\ClientLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Client;
use Validator;


class ClientController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $client;
    protected $client_log;

    public function __construct(Client $client, ClientLog $client_log)
    {
        $this->middleware('auth');
        $this->client = $client;
        $this->client_log = $client_log;
    }

    /**
     * Display a listing of the client.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {

        /*
         *  getCollection from App/Models/Client
         *
         *  @return mixed
         * */
        $data['clientManagementTab'] = "active open";
        $data['clientTab'] = "active";
        return view('admin.client.clientlist', $data);
    }

    public function datatable(Request $request)
    {
        // default count of client $clientCount
        $clientCount = 0;

        /*
         *    getDatatableCollection from App/Models/Client
         *   get all clients
         *
         *  @return mixed
         * */
        $clientData = $this->client->getDatatableCollection();

        /*
         *    scopeGetFilteredData from App/Models/Client
         *   get filterred clients
         *
         *  @return mixed
         * */
        $clientData = $clientData->GetFilteredData($request);

        /*
         *    getClientCount from App/Models/Client
         *   get count of clients
         *
         *  @return integer
         * */
        $clientCount = $this->client->getClientCount($clientData);

        //  Sorting client data base on requested sort order
        if (isset(config('constant.clientDataTableFieldArray')[$request->order['0']['column']])) {
            $clientData = $clientData->SortClientData($request);
        } else {
            $clientData = $clientData->SortDefaultDataByRaw('tbl_client.id', 'desc');
        }

        /*
         *  get paginated collection of client
         *
         * @param  \Illuminate\Http\Request $request
         * @return mixed
         * */
        $clientData = $clientData->GetClientData($request);
        $appData = array();
        foreach ($clientData as $client) {
            $row = array();
            $row[] = $client->name;
            $row[] = ($client->type == 0)?"Private":"Co-operative Dairy";
            $row[] = view('datatable.switch', ['module' => "client", 'status' => $client->status, 'id' => $client->id])->render();
            $row[] = view('datatable.action', ['module' => "client","log" => true,'id' => $client->id])->render();
            $appData[] = $row;
        }
        return [
            'draw' => $request->draw,
            'recordsTotal' => $clientCount,
            'recordsFiltered' => $clientCount,
            'data' => $appData,
        ];
    }

    /**
     * Show the form for creating a new client.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        $data['clientManagementTab'] = "active open";
        $data['clientTab'] = "active";
        return view('admin.client.add',["data" => $data]);
    }

    /**
     * Display the specified client.
     *
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {

        /*
         *  get details of the specified client. from App/Models/Client
         *
         * @param mixed $id
         * @param string (id) fieldname
         *  @return mixed
         * */

        $data['details'] = $this->client->getClientByField($id, 'id');
        $data['clientManagementTab'] = "active open";
        $data['clientTab'] = "active";
        return view('admin.client.edit', ["data" => $data]);
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
            'name' => 'required|max:50',
            'image' => 'required',
            'description' => 'required',
            'type' => 'required',
        );
        if($mode == 'edit')
        {
            $rules = array(
                'name' => 'required|max:50',
                'description' => 'required',
                'type' => 'required',
            );
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            $errorRedirectUrl = "admin/client/add";
            if ($mode == "edit") {
                $errorRedirectUrl = "admin/client/edit/" . $data['id'];
            }
            return redirect($errorRedirectUrl)->withInput()->withErrors($validator);
        }
        return false;
    }

    /**
     * Store a newly created client in storage.
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

        $addclient = $this->client->addClient($request->all());
        if ($addclient) {
            $request->session()->flash('alert-success', trans('app.client_add_success'));
            return redirect('admin/client/list');
        } else {
            $request->session()->flash('alert-danger', trans('app.client_error'));
            return redirect('admin/client/add')->withInput();
        }
    }

    /**
     * Update the specified client in storage.
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

        $addclient = $this->client->addClient($request->all());
        if ($addclient) {
            $request->session()->flash('alert-success', trans('app.client_edit_success'));
            return redirect('admin/client/list');
        } else {
            $request->session()->flash('alert-danger', trans('app.client_error'));
            return redirect('admin/client/edit/' . $request->get('id'))->withInput();
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
        $updateClient = $this->client->updateStatus($request->all());
        if ($updateClient) {
            $request->session()->flash('alert-success', trans('app.client_status_success'));
        } else {
            $request->session()->flash('alert-danger', trans('app.client_error'));
        }
        echo 1;
    }

    /**
     * Delete the specified client in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function delete(request $request)
    {

        $deleteUser = $this->client->deleteClient($request->id);
        if ($deleteUser) {
            $request->session()->flash('alert-success', trans('app.client_delete_success'));
        } else {
            $request->session()->flash('alert-danger', trans('app.client_error'));
        }
        echo 1;
    }

    /**
     * log the specified client in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function log(request $request)
    {
        $data['details'] = $this->client_log->getCollection($request->id);
        $data['clientManagementTab'] = "active open";
        $data['clientTab'] = "active";
        return view('admin.client.clientlog', $data);
    }
}
