@extends('admin.layouts.common')
@section('pageTitle')
    {{trans('app.client_edit_title')}}
@endsection
@push('externalCssLoad')

@endpush
@push('internalCssLoad')

@endpush
@section('content')
    <div class="be-content">
            <div class="page-head">
            <h2>{{trans('app.client')}} {{trans('app.management')}}</h2>
            <ol class="breadcrumb">
                <li><a href="{{url('admin/dashboard')}}">{{trans('app.admin_home')}}</a></li>
                <li><a href="{{url('admin/client/list')}}">{{trans('app.client')}} {{trans('app.management')}}</a></li>
                <li class="active">{{trans('app.edit')}} {{trans('app.client')}}</li>
            </ol>
        </div>
        <div class="main-content container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default panel-border-color panel-border-color-primary">
                        <div class="panel-heading panel-heading-divider">{{trans('app.edit')}} {{trans('app.client')}}</div>
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
                            <form action="{{url('admin/client/update')}}" name="app_edit_form" id="app_form" enctype="multipart/form-data" style="border-radius: 0px;" method="post" class="form-horizontal group-border-dashed">
                                <input type="hidden" name="type" id="type"  value="1" />

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Client Name <span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="text" name="name" id="name" placeholder="Client Name" class="form-control input-sm required" value="{{$data['details']->name}}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Description <span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <textarea type="text" name="description" id="description" placeholder="Description" class="form-control input-sm required" >{{$data['details']->description}}</textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Client Type <span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <select name="type" id="type" placeholder="Country Name" class="form-control input-sm required">
                                            <option value="" selected="selected">Please Type</option>
                                            <option value="0" @if($data['details']->type == "0") {{"selected"}} @endif>Private</option>
                                            <option value="1"@if($data['details']->type == "1") {{"selected"}} @endif>Co-Operative Dairy</option>

                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Image</label>
                                    <div class="col-sm-6 col-md-4">
                                        <?php
                                        $class = '';
                                        if ($data['details']->image == ""):
                                            $class = 'required';
                                        endif;
                                        ?>
                                        <input type="file" class="{{$class}}" name="image" id="image" title="Image" value="{{$data['details']->image}}" />
                                        <input type="hidden" name="old_image" value="{{$data['details']->image}}" id="old_image" />
                                        @if($data['details']->image != "")
                                           {{$data['details']->image}}
                                        @endif
                                            <div class="col-sm-3 col-md-3 image_class">
                                                <img src="{{url('img/client/'.$data['details']->image)}}" style="width:50px;height:50px;cursor:pointer;" />
                                            </div>
                                    </div>
                                </div>

                                <?php
                                $status_check = "";
                                if ($data['details']->status == '1') {
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

                                {{ csrf_field() }}
                                <input type="hidden" name="id" id="id" value="{{$data['details']->id}}" />
                                <div class="col-sm-6 col-md-8 savebtn">
                                    <p class="text-right">
                                        <button type="submit" class="btn btn-space btn-info btn-lg">Update {{trans('app.client')}}</button>
                                        <a href="{{url('admin/client/list')}}" class="btn btn-space btn-danger btn-lg">Cancel</a>
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
<script type="text/javascript">
        app.validate.init();
        $('#category_id').change(function(){
            var category_id = $(this).val();
            if(category_id){
                $.ajax({
                    type:"POST",
                    url:"{{url('admin/category/get_product')}}",
                    data:{category_id:category_id,_token: csrf_token,},

                    success:function(res){
                        console.log(res);
                        if(res){
                            $("#machine_id").empty();
                            $("#machine_id").append('<option>Select</option>');
                            $.each(res,function(key,value){
                                $("#machine_id").append('<option value="'+value.id+'">'+value.machine_name+'</option>');
                            });

                        }else{
                            $("#machine_id").empty();
                        }
                    }
                });
            }else{
                $("#machine_id").empty();

            }
        });
</script>
@endpush