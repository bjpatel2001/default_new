@extends('admin.layouts.common')
@section('pageTitle')
    {{trans('app.client_brochure')}}
@endsection
@push('externalCssLoad')

@endpush
@push('internalCssLoad')

@endpush
@section('content')
    <div class="be-content">

        <div class="main-content container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default panel-border-color panel-border-color-primary">
                        <div class="panel-heading panel-heading-divider">{{trans('app.client')}} {{trans('app.pdf')}}</div>
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
                            <form action="{{url('admin/client/store-pdf')}}" name="app_edit_form" id="app_form" enctype="multipart/form-data" style="border-radius: 0px;" method="post" class="form-horizontal group-border-dashed">


                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Client Pdf</label>
                                    <div class="col-sm-6 col-md-4">
                                        <?php
                                        $class = '';
                                        if(!empty($details)):
                                            if ($details->file_name == ""):
                                                $class = 'required';
                                            endif;
                                        endif;
                                        ?>

                                        <input type="file" class="{{$class}}" name="file_name" id="file_name" required title="File Name" value="{{$details->file_name or ""}}" />
                                        <input type="hidden" name="old_file_name" value="{{$details->file_name or ""}}" id="old_file_name" />

                                        @if(!empty($details))
                                                <input type="hidden" name="id" value="{{$details->id}}" id="id" />
                                        @endif

                                    </div>
                                    @if(!empty($details))
                                    <div class="col-md-3"><a href="{{url('files/client_pdf/'.$details->file_name)}}"><i class="fa fa-file-pdf-o" style="font-size: 24px;"></i></a></div>
                                    @endif
                                </div>


                                {{ csrf_field() }}
                                <div class="col-sm-6 col-md-8 savebtn">
                                    <p class="text-right">
                                        <button type="submit" class="btn btn-space btn-info btn-lg">Update {{trans('app.client')}} {{trans('app.pdf')}}</button>

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
</script>
@endpush