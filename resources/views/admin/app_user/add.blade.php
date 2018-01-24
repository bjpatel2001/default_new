@extends('admin.layouts.common')
@section('pageTitle')
    {{trans('app.user_add_title')}}
@endsection
@push('externalCssLoad')
<link rel="stylesheet" href="{{url('css/plugins/jquery.datetimepicker.css')}}" type="text/css" />
@endpush
@push('internalCssLoad')

@endpush
@section('content')
    <div class="be-content">
        <div class="page-head">
            <h2>{{trans('app.app_user')}} {{trans('app.management')}}</h2>
            <ol class="breadcrumb">
                <li><a href="{{url('admin/dashboard')}}">{{trans('app.admin_home')}}</a></li>
                <li><a href="{{url('admin/app_user/list')}}">{{trans('app.app_user')}} {{trans('app.management')}}</a></li>
                <li class="active">{{trans('app.add')}} {{trans('app.app_user')}}</li>
            </ol>
        </div>
        <div class="main-content container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default panel-border-color panel-border-color-primary">
                        <div class="panel-heading panel-heading-divider">{{trans('app.add')}} {{trans('app.app_user')}}</div>
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
                            <form action="{{url('admin/app_user/store')}}" name="app_add_form" enctype="multipart/form-data" id="app_form" style="border-radius: 0px;" method="post" class="form-horizontal group-border-dashed">

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">First Name <span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="text" name="first_name" id="first_name" placeholder="First Name" class="form-control input-sm required" value="{{old('first_name')}}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Last Name <span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="text" name="last_name" id="last_name" placeholder="Last Name" class="form-control input-sm required" value="{{old('last_name')}}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Mobile No<span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="text" name="mobile_number" id="mobile_number" placeholder="Mobile No" class="form-control input-sm required number" maxlength="10" value="{{old('mobile_number')}}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Business Name<span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="text" name="business_name" id="business_name" placeholder="Business Name" class="form-control input-sm required "value="{{old('business_name')}}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Country<span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <select name="country_id" id="country_id" placeholder="Country Name" class="form-control input-sm required">
                                            <option value="" selected="selected">Select Country</option>
                                            @foreach($countryData as $country)
                                                <option value="{{$country->id}}">{{$country->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Select State<span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <select class="form-control input-sm required" name="state_id" id="state_id">
                                            <option value="0">{{trans('app.select')}} State</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Email Address <span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="text" name="email" id="email" placeholder="Email Address" class="form-control input-sm required email" value="{{old('email')}}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Profile Image <span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="file" name="profile_image" id="profile_image" class="form-control input-sm required" />
                                        <input type="hidden" name="old_profile_image" id="old_profile_image" class="form-control input-sm required" value="" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">User type </label>
                                    <div class="col-sm-6 col-md-4">
                                        <select name="user_type" id="user_type" class="form-control">
                                            <option value="0">Select User Type</option>
                                            @foreach(Config::get('constant.user_type') as $user_type_key => $user_type_val)
                                                <option value="{{$user_type_key}}">{{$user_type_val}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Password <span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="password" name="password" id="password" placeholder="Password" class="form-control input-sm required" value="{{old('password')}}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Confirm Password <span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" class="form-control input-sm required" value="{{old('confirm_password')}}" />
                                    </div>
                                </div>
                                <input type="hidden" name="login_type" value="4">

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Status<span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">

                                        <div class="switch-button switch-button-lg">
                                             <input name="status" id="swt1" checked type="checkbox" value="1" />
                                             <span>
                                                 <label for="swt1"></label>
                                             </span>
                                         </div>
                                    </div>
                                </div>

                                {{ csrf_field() }}

                                <div class="col-sm-6 col-md-8 savebtn">
                                    <p class="text-right">
                                        <button type="submit" class="btn btn-space btn-info btn-lg">{{trans('app.add')}} {{trans('app.user')}}</button>
                                        <a href="{{url('admin/app_user/list')}}" class="btn btn-space btn-danger btn-lg">Cancel</a>
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
<script src="{{url('js/plugins/jquery.datetimepicker.js')}}" type="text/javascript"></script>
<script src="{{url('js/modules/user.js')}}"></script>
@endpush
@push('internalJsLoad')
<script type="text/javascript">

    app.validate.init(app.user.action.event.custome_validations());
    app.user.action.event.common();
    $(document).ready(function () {
        $("#country_id").change(function(){
            var selectedCountry = $("#country_id option:selected").val();
            app.showLoader();
            $.ajax({
                type: "POST",
                url: '{{url('admin/app_user/get_states')}}',
                data: { country_id : selectedCountry,check_status:'1', _token: csrf_token}
            }).done(function(data){
                app.hideLoader();
                $("#state_id").html(data);
            });
        });
    });
</script>
@endpush