@extends('admin.layouts.common')
@section('pageTitle')
    {{trans('app.news_edit_title')}}
@endsection
@push('externalCssLoad')

@endpush
@push('internalCssLoad')

@endpush
@section('content')
    <div class="be-content">
            <div class="page-head">
            <h2>{{trans('app.news')}} {{trans('app.management')}}</h2>
            <ol class="breadcrumb">
                <li><a href="{{url('admin/dashboard')}}">{{trans('app.admin_home')}}</a></li>
                <li><a href="{{url('admin/news/list')}}">{{trans('app.news')}} {{trans('app.management')}}</a></li>
                <li class="active">{{trans('app.edit')}} {{trans('app.news')}}</li>
            </ol>
        </div>
        <div class="main-content container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default panel-border-color panel-border-color-primary">
                        <div class="panel-heading panel-heading-divider">{{trans('app.edit')}} {{trans('app.news')}}</div>
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
                            <form action="{{url('admin/news/update')}}" name="app_edit_form" id="app_form" enctype="multipart/form-data" style="border-radius: 0px;" method="post" class="form-horizontal group-border-dashed">
                                <input type="hidden" name="type" id="type"  value="1" />
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Title<span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="text" name="title" id="title" placeholder="Title" class="form-control input-sm required" value="{{$data['details']->title}}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Description <span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <textarea type="text" name="description" rows="10" id="description" placeholder="Description" class="form-control input-sm required" >{{$data['details']->description}}</textarea>
                                    </div>
                                </div>
                                @if(isset($data['details']->image) && !empty($data['details']->image))
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Image</label>
                                    <div class="col-sm-6 col-md-4">
                                        <?php
                                        $class = '';
                                        if ($data['details']->image == ""):
                                            $class = '';
                                        endif;
                                        ?>
                                        <input type="file" class="{{$class}}" name="image" id="image" title="Image" value="{{$data['details']->image}}" />
                                        <input type="hidden" name="old_image" value="{{$data['details']->image}}" id="old_image" />
                                        @if($data['details']->image != "")
                                           {{$data['details']->image}}
                                        @endif
                                            <div class="col-sm-3 col-md-3 image_class">
                                                <img src="{{url('img/news/'.$data['details']->image)}}" style="width:50px;height:50px;cursor:pointer;" />
                                            </div>
                                    </div>

                                </div>
                                @endif
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
                                        <button type="submit" class="btn btn-space btn-info btn-lg">Update {{trans('app.news')}}</button>
                                        <a href="{{url('admin/news/list')}}" class="btn btn-space btn-danger btn-lg">Cancel</a>
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

            tinymce.init({ selector: '#description', relative_urls : false,    remove_script_host : false,    convert_urls : true,
                plugins: "link",
                toolbar:    'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | sizeselect | fontselect |  fontsizeselect | link',
            });
        });
</script>
@endpush