    @extends('admin.layouts.common')
@section('pageTitle')
    {{trans('app.machine_add_title')}}
@endsection
@push('externalCssLoad')
@endpush
@push('internalCssLoad')

@endpush
<style>
    .category_class {
        border: 1px solid #bdc0c7;
        padding: 10px;
        margin-left: 69px;
        width: 90%;
    }
    .legend_class {
        color: #666;
        border-bottom: none;
        margin-bottom: 0px;
        width: 58px;
        font-size: 13px !important;
    }
    #category_class{
        display: none;
    }
    .product_class{
        box-shadow: none !important;
    }
    .panel-body{
        border-top: none !important;
    }
</style>
@section('content')
    <div class="be-content">
        <div class="page-head">
            <h2>{{trans('app.machine')}} {{trans('app.management')}}</h2>
            <ol class="breadcrumb">
                <li><a href="{{url('admin/dashboard')}}">{{trans('app.admin_home')}}</a></li>
                <li><a href="{{url('admin/machine/list')}}">{{trans('app.machine')}} {{trans('app.management')}}</a></li>
                <li class="active">{{trans('app.add')}} {{trans('app.machine')}}</li>
            </ol>
        </div>
        <div class="main-content container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default panel-border-color panel-border-color-primary">
                        <div class="panel-heading panel-heading-divider">{{trans('app.add')}} {{trans('app.machine')}}</div>
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
                            <form action="{{url('admin/machine/store')}}" enctype="multipart/form-data" name="app_add_form" id="app_form" style="border-radius: 0px;" method="post" class="form-horizontal group-border-dashed">

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Category Name <span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <select name="category_id" id="category_id" placeholder="Category Name" class="form-control input-sm required">
                                            <?php $selected = '';?>
                                            @foreach($categoryData as $category)
                                                @if(((isset($category_id) && $category_id != null)) && $category_id == $category->id)
                                                   <option value="{{$category->id}}" selected="selected">{{$category->category_name}}</option>
                                                @else
                                                        <option value="{{$category->id}}">{{$category->category_name}}</option>
                                                    @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Machine Name <span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="text" name="machine_name" id="machine_name" placeholder="Machine Name" class="form-control input-sm required" value="{{old('machine_name')}}" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Description <span class="error">*</span></label>
                                    <div class="col-sm-8 col-md-6">
                                        <textarea type="text" name="description" rows="15" id="description" placeholder="Description" class="form-control input-sm required" >{{old('description')}}</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Image<span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="file" name="machine_image[]" id="machine_image" placeholder="Image" class="form-control input-sm required" multiple >
                                    </div>
                                </div>

                                @if($dashboard_machine_count == "1")
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Feature Machine On Dashboard</label>
                                        <div class="col-sm-6 col-md-4">

                                            <div class="switch-button switch-button-lg">
                                                <input name="app_dashboard" id="swt2" type="checkbox" value="1" />
                                                <span>
                                                 <label for="swt2"></label>
                                             </span>
                                            </div>
                                        </div>
                                    </div>
                                @endif

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

                                <div class="panel-heading panel-heading-divider">Machine Specification</div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Machine Brochure</label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="file" name="file_name" id="file_name" placeholder="Machine Brochure" class="form-control input-sm" >
                                        <input type="hidden" name="old_file_name" id="old_file_name" class="form-control input-sm required" value="" />
                                    </div>
                                </div>

                                {{ csrf_field() }}

                                <div class="col-sm-6 col-md-8 savebtn">
                                    <p class="text-right">
                                        <button type="submit" class="btn btn-space btn-info btn-lg">{{trans('app.add')}} {{trans('app.machine')}}</button>
                                        <a href="{{url('admin/machine/list')}}" class="btn btn-space btn-danger btn-lg">Cancel</a>
                                    </p>
                                </div>

                                @if((isset($flag) && $flag != ""))
                                    <input type="hidden" value="{{$flag}}" name="quotation_flag"/>
                                @else
                                    <input type="hidden" value="{{old('quotation_flag')}}" name="quotation_flag"/>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('externalJsLoad')
<script src="{{url('js/plugins/tinymce/tinymce.min.js')}}" type="text/javascript"></script>
@endpush
@push('internalJsLoad')
<script type="text/javascript">
    app.validate.init();
    $(document).ready(function () {

        tinymce.init({ selector: '#description', relative_urls : false,    remove_script_host : false,    convert_urls : true,
                     plugins: "link",
                     toolbar:    'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | sizeselect | fontselect |  fontsizeselect | link',
        });
    });
</script>
@endpush