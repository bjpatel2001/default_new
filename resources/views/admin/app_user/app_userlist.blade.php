@extends('admin.layouts.common')
@section('pageTitle')
    {{trans('app.user_list_title')}}
@endsection
@push('externalCssLoad')
<link rel="stylesheet" href="{{url('css/plugins/jquery.datetimepicker.css')}}" type="text/css"/>
@endpush
@push('internalCssLoad')

@endpush
@section('content')
    <div class="be-content">
        <div class="page-head">
            <h2>{{trans('app.app_user') ." ". trans('app.management')}}</h2>
            <ol class="breadcrumb">
                <li><a href="{{url('admin/dashboard')}}">{{trans('app.admin_home')}}</a></li>
                <li class="active">{{trans('app.user') ." ". trans('app.management') ." ".trans('app.list') }}</li>
            </ol>
        </div>
        <div class="main-content container-fluid">

            <!-- Caontain -->
            <div class="panel panel-default panel-border-color panel-border-color-primary pull-left">
                <div class="row">
                    <div class="col-sm-12 col-xs-12">

                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-xs-12">
                        <div class="activity-but activity-space pull-left">
                            <div class="pull-left">
                                <a href="javascript:void(0);" class="btn btn-warning func_SearchGridData"><i
                                            class="icon mdi mdi-search"></i> Search</a>
                            </div>
                            <div class="pull-left">
                                <a href="javascript:void(0);" class="btn btn-danger func_ResetGridData"
                                   style="margin-left: 10px;">Reset</a>
                            </div>
                            <div class="addreport pull-right">
                                <a href="{{url('admin/app_user/add')}}">
                                    <button class="btn btn-space btn-primary"><i
                                                class="icon mdi mdi-plus "></i> {{trans('app.add')}} {{trans('app.user')}}
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="deta-table user-table pull-left">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="panel panel-default panel-table">
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table id="dataTable"
                                               class="table display dt-responsive responsive nowrap table-striped table-hover table-fw-widget"
                                               style="width: 100%;">

                                            <thead>

                                            <tr>
                                                <th>Name</th>
                                                <th>Email Address</th>
                                                <th>Mobile No</th>
                                                <th>Country</th>
                                                <th>State</th>
                                                <th>User type</th>
                                                <th>Quotation Request</th>
                                                <th>Status</th>
                                                <th class="no-sort">Action</th>
                                            </tr>

                                            </thead>
                                            <thead>
                                            <tr>

                                                <th>
                                                    <input type="text" name="filter[name]" value="" />
                                                </th>

                                                <th>
                                                    <input type="text" name="filter[email]" value="" />
                                                </th>
                                                <th>
                                                    <input type="text" name="filter[mobile_number]" value="" />
                                                </th>
                                                <th>
                                                    <select name="filterSelect[country_id]" id="country_id" >
                                                        <option value="" selected="selected">Select Country</option>
                                                        @foreach($countryData as $country)
                                                            <option value="{{$country->id}}">{{$country->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </th>
                                                <th>
                                                    <select name="filterSelect[state_id]" id="state_id" >
                                                        <option value="" selected="selected">Select State</option>
                                                    </select>
                                                </th>
                                                <th>
                                                    <select name="filterSelect[user_type]" id="user_type" >
                                                        <option value="">Select User Type</option>
                                                        @foreach(Config::get('constant.user_type') as $user_type_key => $user_type_val)
                                                            <option value="{{$user_type_key}}">{{$user_type_val}}</option>
                                                        @endforeach
                                                    </select>
                                                </th>
                                                <th></th>
                                                <th>
                                                    <select name="filterSelect[status]" id="status">
                                                        <option value="">{{trans('app.select')}}</option>
                                                        <option value="1">{{trans('app.active')}}</option>
                                                        <option value="0">{{trans('app.inactive')}}</option>
                                                    </select>
                                                </th>
                                                <th></th>
                                            </tr>
                                            </thead>

                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('externalJsLoad')
<script src="{{url('js/plugins/jquery.datetimepicker.js')}}" type="text/javascript"></script>
<script src="{{url('js/appDatatable.js')}}"></script>
<script src="{{url('js/modules/default.js')}}"></script>
@endpush
@push('internalJsLoad')
<script>
    app.default.init({"route":{"url":'app_user/datatable'}});
    $(document).ready(function () {
        $("#country_id").change(function(){
            var selectedCountry = $("#country_id option:selected").val();
            $.ajax({
                type: "POST",
                url: '{{url('admin/app_user/get_states')}}',
                data: { country_id : selectedCountry, _token: csrf_token}
            }).done(function(data){
                $("#state_id").html(data);
            });
        });
    });

</script>
@endpush