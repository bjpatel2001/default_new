@extends('admin.layouts.common')
@section('pageTitle')
    {{trans('app.brochure_add_title')}}
@endsection
@push('externalCssLoad')
@endpush
@push('internalCssLoad')

@endpush
@section('content')
    <div class="be-content">
        <div class="page-head">
            <h2>{{trans('app.brochure')}} {{trans('app.management')}}</h2>
            <ol class="breadcrumb">
                <li><a href="{{url('admin/dashboard')}}">{{trans('app.admin_home')}}</a></li>
                <li><a href="{{url('admin/brochure/list')}}">{{trans('app.brochure')}} {{trans('app.management')}}</a></li>
                <li class="active">{{trans('app.add')}} {{trans('app.brochure')}}</li>
            </ol>
        </div>
        <div class="main-content container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default panel-border-color panel-border-color-primary">
                        <div class="panel-heading panel-heading-divider">{{trans('app.add')}} {{trans('app.brochure')}}</div>
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
                            <form action="{{url('admin/brochure/store')}}" enctype="multipart/form-data" name="app_add_form" id="app_form" style="border-radius: 0px;" method="post" class="form-horizontal group-border-dashed">
                                <input type="hidden" name="type" id="type"   value="1" />
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Brochure Name <span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="text" name="name" id="name" placeholder="Brochure Name" class="form-control input-sm required" value="{{old('name')}}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Category </label>
                                    <div class="col-sm-6 col-md-4">
                                        <select name="category_id" id="category_id" class="form-control">
                                            <option value="0">Select Category</option>
                                            @foreach($category as $category)
                                                <option value="{{$category->id}}">{{ $category->category_name }}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Product </label>
                                    <div class="col-sm-6 col-md-4">
                                        <select name="machine_id" id="machine_id" class="form-control">
                                            
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Brochure File <span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="file" name="file_name" id="file_name" class="form-control input-sm required" />
                                        <input type="hidden" name="old_file_name" id="old_file_name" class="form-control input-sm required" value="" />
                                    </div>
                                </div>

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
                                        <button type="submit" class="btn btn-space btn-info btn-lg">{{trans('app.add')}} {{trans('app.brochure')}}</button>
                                        <a href="{{url('admin/brochure/list')}}" class="btn btn-space btn-danger btn-lg">Cancel</a>
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