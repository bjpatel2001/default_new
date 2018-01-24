@extends('admin.layouts.common')
@section('pageTitle')
    {{trans('app.user_edit_title')}}
@endsection
@push('externalCssLoad')
<link rel="stylesheet" href="{{url('css/plugins/jquery.datetimepicker.css')}}" type="text/css" />
@endpush
@push('internalCssLoad')

@endpush
@section('content')
    <div class="be-content">
            <div class="page-head">
            <h2>{{trans('app.user')}} {{trans('app.management')}}</h2>
            <ol class="breadcrumb">
                <li><a href="{{url('admin/dashboard')}}">{{trans('app.admin_home')}}</a></li>
                <li><a href="{{url('admin/user/list')}}">{{trans('app.user')}} {{trans('app.management')}}</a></li>
                <li class="active">{{trans('app.edit')}} {{trans('app.user')}}</li>
            </ol>
        </div>
        <div class="main-content container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default panel-border-color panel-border-color-primary">
                        <div class="panel-heading panel-heading-divider">{{trans('app.edit')}} {{trans('app.user')}}</div>
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
                            <form action="{{url('admin/app_user/update')}}" name="app_edit_form" enctype="multipart/form-data" id="app_form" style="border-radius: 0px;" method="post" class="form-horizontal group-border-dashed">

                                 <div class="form-group">
                                    <label class="col-sm-4 control-label">First Name <span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="text" name="first_name" id="first_name" placeholder="First Name" class="form-control input-sm required" value="{{$details->first_name}}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Last Name <span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="text" name="last_name" id="last_name" placeholder="Last Name" class="form-control input-sm required" value="{{$details->last_name}}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Mobile No <span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="text" name="mobile_number" id="mobile_number" placeholder="Mobile No" class="form-control input-sm required number" maxlength="10" value="{{$details->mobile_number}}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Business Name<span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="text" name="business_name" id="business_name" placeholder="Business Name" class="form-control input-sm required "value="{{$details->business_name}}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Select Country <span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <select class="form-control input-sm required" name="country_id" id="country_id">
                                            <option value="">{{trans('app.select')}} Country</option>
                                            @if(count($countryData) > 0)
                                                @foreach($countryData as $row)
                                                    <option value="{{$row->id}}" @if($details->country_id == $row->id){{"selected"}}@endif>{{$row->name}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Select State <span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <select class="form-control input-sm required" name="state_id" id="state_id">
                                            <option value="">{{trans('app.select')}} State</option>
                                            @if(count($stateData) > 0)
                                                @foreach($stateData as $row)
                                                    <option value="{{$row->id}}" @if($details->state_id == $row->id){{"selected"}}@endif>{{$row->name}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Email Editress <span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="text" name="email" id="email" placeholder="Email Editress" class="form-control input-sm required email" readonly value="{{$details->email}}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Profile Image</label>
                                    <div class="col-sm-6 col-md-4">
                                        <?php
                                        $class = '';
                                        if ($details->profile_image == ""):
                                            $class = 'required';
                                        endif;
                                        ?>
                                        <input type="file" class="{{$class}}" name="profile_image" id="profile_image" title="Profile Image" value="" />
                                        <input type="hidden" name="old_profile_image" value="{{$details->profile_image}}" id="old_profile_image" />
                                        @if($details->profile_image != "")
                                            <img src="{{url('img/user/'.$details->profile_image)}}" style="width:50px;height:50px;cursor:pointer;" class="bm_image" />

                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">User type </label>
                                    <div class="col-sm-6 col-md-4">
                                        <select name="user_type" id="user_type" class="form-control">
                                            <option value="0">Select User Type</option>
                                            @foreach(Config::get('constant.user_type') as $user_type_key => $user_type_val)
                                                <option value="{{$user_type_key}}" @if($user_type_key == $details->user_type) {{"selected"}} @endif>{{$user_type_val}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <?php
                                    $status_check = "";
                                    if ($details->status == '1') {
                                        $status_check = "checked";
                                    }
                                ?>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Status<span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <div class="switch-button switch-button-lg">
                                            <input name="status" id="swt1" {{$status_check}} type="checkbox" value="1" />
                                            <span>
                                                 <label for="swt1"></label>
                                             </span>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="login_type" value="4">

                                {{ csrf_field() }}
                                <input type="hidden" name="id" id="id" value="{{$details->id}}" />
                                <div class="col-sm-6 col-md-8 savebtn">
                                    <p class="text-right">
                                        <button type="submit" class="btn btn-space btn-info btn-lg">Update {{trans('app.user')}}</button>
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
    <div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel">Image preview</h4>
                </div>
                <div class="modal-body">
                    <img src="" id="imagepreview" style="width: 100%; height: auto;" >
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
            $(".bm_image").on("click", function() {
                $('#imagepreview').attr('src', $(this).attr('src'));
                $('#imagemodal').modal('show');
            });

            $("#country_id").change(function(){
                var selectedCountry = $("#country_id option:selected").val();
                app.showLoader();
                $.ajax({
                    type: "POST",
                    url: '{{url('admin/app_user/get_states')}}',
                    data: { country_id : selectedCountry, _token: csrf_token}
                }).done(function(data){
                    app.hideLoader();
                    $("#state_id").html(data);
                });
            });
        });

</script>
@endpush