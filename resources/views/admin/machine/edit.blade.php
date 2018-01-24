@extends('admin.layouts.common')
@section('pageTitle')
    {{trans('app.machine_edit_title')}}
@endsection
@push('externalCssLoad')

@endpush
@push('internalCssLoad')

@endpush
<style>
    .image_class{
        margin-top: 3px;
    }
</style>
@section('content')
    <div class="be-content">
            <div class="page-head">
            <h2>{{trans('app.machine')}} {{trans('app.management')}}</h2>
            <ol class="breadcrumb">
                <li><a href="{{url('admin/dashboard')}}">{{trans('app.admin_home')}}</a></li>
                <li><a href="{{url('admin/machine/list')}}">{{trans('app.machine')}} {{trans('app.management')}}</a></li>
                <li class="active">{{trans('app.edit')}} {{trans('app.machine')}}</li>
            </ol>
        </div>
        <div class="main-content container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default panel-border-color panel-border-color-primary">
                        <div class="panel-heading panel-heading-divider">{{trans('app.edit')}} {{trans('app.machine')}}</div>
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
                            <form action="{{url('admin/machine/update')}}" enctype="multipart/form-data" name="app_edit_form" id="app_form" style="border-radius: 0px;" method="post" class="form-horizontal group-border-dashed">

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Category Name <span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <select name="category_id" id="category_id" placeholder="Category Name" class="form-control input-sm required">
                                            @foreach($categoryData as $category)
                                                @if($category->id == $details->category_id)
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
                                        <input type="text" name="machine_name" id="machine_name" placeholder="Machine Name" class="form-control input-sm required" value="{{$details->machine_name}}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Description <span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-6">
                                        <textarea type="text" name="description" rows="15" id="description" placeholder="Description" class="form-control input-sm required" >{{$details->description}}</textarea>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Image<span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="file" name="machine_image[]" id="machine_image" placeholder="Image" class="form-control input-sm image_class" multiple >
                                        @foreach($details->MachineImage as $image)
                                            <div class="col-sm-3 col-md-3 image_class">
                                                <i class="fa fa-window-close pull-right deleteImage" rel="{{$image->id}}"></i><img src="{{url('img/machine/'.$details->id.'/thumb/'.$image->image)}}" style="width:50px;height:50px;cursor:pointer;" class="machine_image" />
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <?php
                                $app_status_check = "";
                                if ($details->app_dashboard == '1') {
                                    $app_status_check = "checked";
                                }
                                ?>
                                @if($dashboard_machine_count == "1")
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Feature Machine On Dashboard</label>
                                        <div class="col-sm-6 col-md-4">
                                            <div class="switch-button switch-button-lg">
                                                <input name="app_dashboard" id="swt2" {{$app_status_check}} type="checkbox" value="1" />
                                                <span>
                                                     <label for="swt2"></label>
                                                 </span>
                                            </div>
                                        </div>
                                    </div>
                                @endif

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

                                <div class="panel-heading panel-heading-divider">Machine Specification</div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Machine Brochure</label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="file" name="file_name" id="file_name" title="File Name" value="" class="form-control input-sm image_class" />
                                        <input type="hidden" name="old_file_name" value="" id="old_file_name"  />
                                        <input type="hidden" name="brochure_id" value="" id="brochure_id" />
                                        <br/>
                                        <div class="col-sm-12 pdf_class">
                                        @if(isset($details->Brochure->file_name))
                                            <div class="col-sm-2">
                                            <i class="fa fa-btn fa-file-pdf-o fa-2x"></i>
                                            </div>
                                            <div class="col-sm-8">
                                                <i class=" fa fa-window-close deletePdf" rel="{{$details->Brochure->id}}" style="position: absolute; cursor: pointer;left: -4px;">&nbsp;</i>
                                                {{$details->Brochure->file_name}}
                                            </div>
                                        @endif
                                        </div>
                                    </div>
                                </div>


                                {{ csrf_field() }}
                                <input type="hidden" name="id" id="id" value="{{$details->id}}" />
                                <div class="col-sm-6 col-md-8 savebtn">
                                    <p class="text-right">
                                        <button type="submit" class="btn btn-space btn-info btn-lg">Update {{trans('app.machine')}}</button>
                                        <a href="{{url('admin/machine/list')}}" class="btn btn-space btn-danger btn-lg">Cancel</a>
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
<script src="{{url('js/plugins/tinymce/tinymce.min.js')}}" type="text/javascript"></script>
@endpush
@push('internalJsLoad')
<script type="text/javascript">
        app.validate.init();
        $(document).ready(function () {
           $(document).on('click','.deleteImage',function () {
               var result = confirm("Are you sure you want to delete this image?");
               var id = $(this).attr('rel');
               if (result) {
                   $(this).closest('div.image_class').remove();
                   $.ajax({
                       type: "POST",
                       url: app.config.SITE_PATH +'machine/delete_image',
                       data: { id : id,_token: csrf_token, type: 'single'}
                   }).done(function(data){

                   });
               }
           });

            $(document).on('click','.deletePdf',function () {
                var result = confirm("Are you sure you want to delete this Pdf?");
                var id = $(this).attr('rel');
                if (result) {
                    $(this).closest('div.pdf_class').remove();
                    $.ajax({
                        type: "POST",
                        url: app.config.SITE_PATH +'brochure/delete_file',
                        data: { id : id,_token: csrf_token}
                    }).done(function(data){

                    });
                }
            });

            // For Editor

            tinymce.init({ selector: '#description', relative_urls : false,    remove_script_host : false,    convert_urls : true,
                plugins: "link",
                toolbar:    'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | sizeselect | fontselect |  fontsizeselect | link',
            });

        });

</script>
@endpush