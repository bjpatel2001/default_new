@extends('admin.layouts.common')
@section('pageTitle')
    {{trans('app.user_profile_title')}}
@endsection
@push('externalCssLoad')
@endpush
@push('internalCssLoad')
<style>
    .col-sm-6 .control-label{text-align: left !important; font-weight: bold;}
</style>
@endpush
@section('content')
    <div class="be-content">

        <div class="main-content container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default panel-border-color panel-border-color-primary">
                        <div class="panel-heading panel-heading-divider">Request Quotation</div>
                        <div class="panel-body">
                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form action="{{url('admin/user/update')}}" name="app_profile_form" id="app_form" style="border-radius: 0px;" method="post" class="form-horizontal group-border-dashed">

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Name</label>
                                    <div class="col-sm-6 col-md-4">
                                        <label class="col-md-12 control-label">{{$requestData->User->name}}<label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Date</label>
                                    <div class="col-sm-6 col-md-4">
                                        <label class="col-md-12 control-label">{{(date('d-M-Y', strtotime($requestData->created_at)))}}<label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Business Name</label>
                                    <div class="col-sm-6 col-md-4">
                                        <label class="col-md-12 control-label">{{$requestData->User->business_name}}<label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Mobile Number</label>
                                    <div class="col-sm-6 col-md-4">
                                        <label class="col-md-12 control-label">{{$requestData->User->mobile_number}}<label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Country</label>
                                    <div class="col-sm-6 col-md-4">
                                        <label class="col-md-12 control-label">{{$requestData->Country->name}}<label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">State</label>
                                    <div class="col-sm-6 col-md-4">
                                        <label class="col-md-12 control-label">{{$requestData->State->name}}<label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" style="font-weight: bolder; color:black;">
                                    <h2>Specifications</h2>
                                    </label>
                                </div>
                                @foreach($requestData->RequestQuestion as $data)
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label"></label>
                                        <div>{{$data->question}}</div><br>
                                        <label class="col-sm-4 control-label"></label>
                                        <div>&nbsp;&nbsp; - &nbsp;&nbsp; {{$data->answer}}</div>
                                    </div>
                                @endforeach
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" style="font-weight: bolder; color:black;">
                                        <h2>Request Quote Products</h2>
                                    </label>
                                </div>
                                @foreach($categoryData as $data)
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label"></label>
                                        <div>{{$data->category_name}}</div><br>
                                        <?php $machines = explode(",",$data->machine_names);?>
                                        @foreach($machines as $machine)
                                            <label class="col-sm-4 control-label"></label>
                                            <div>&nbsp;&nbsp; - &nbsp;&nbsp; {{$machine}}</div>
                                        @endforeach
                                    </div>
                                @endforeach

                                <div class="col-sm-6 col-md-8 savebtn">
                                    <p class="text-right">
                                        <a href="{{url('admin/request/list')}}" class="btn btn-space btn-danger btn-lg">Back</a>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('externalJsLoad')
@endpush
@push('internalJsLoad')

@endpush