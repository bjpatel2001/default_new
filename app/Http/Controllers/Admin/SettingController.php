<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class SettingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $setting;

    public function __construct(Setting $setting)
    {
        $this->middleware('auth');
        $this->setting = $setting;
    }

    /**
     * Show the form for General Setting.
     *
     * @return \Illuminate\Http\Response
     */

    public function clientPdf()
    {
        $data['clientManagementTab'] = "active open";
        $data['settingTab'] = "active";
        $data['details'] = $this->setting->getSettingByField(1,'id');
        return view('admin.client.editprivate', $data);
    }

    /**
     * Store a update setting in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(request $request)
    {
        $rules = ['file_name'=>'required|mimes:pdf'];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errorRedirectUrl = "admin/client/client-pdf";
            return redirect($errorRedirectUrl)->withInput()->withErrors($validator);
        }
        $addsetting = $this->setting->addSetting($request->all());
        if ($addsetting) {
            $request->session()->flash('alert-success', "Client Pdf Updated Successfully!");
            return redirect('admin/client/client-pdf');
        } else {
            $request->session()->flash('alert-danger', "Update Client Pdf failed");
            return redirect('admin/client/client-pdf')->withInput();
        }
    }
}
