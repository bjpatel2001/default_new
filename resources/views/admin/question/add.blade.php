@extends('admin.layouts.common')
@section('pageTitle')
    {{trans('app.question_add_title')}}
@endsection
@push('externalCssLoad')
@endpush
@push('internalCssLoad')

@endpush
@section('content')
    <div class="be-content">
        <div class="page-head">
            <h2>{{trans('app.question')}} {{trans('app.management')}}</h2>
            <ol class="breadcrumb">
                <li><a href="{{url('admin/dashboard')}}">{{trans('app.admin_home')}}</a></li>
                <li><a href="{{url('admin/question/list')}}">{{trans('app.question')}} {{trans('app.management')}}</a></li>
                <li class="active">{{trans('app.add')}} {{trans('app.question')}}</li>
            </ol>
        </div>
        <div class="main-content container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default panel-border-color panel-border-color-primary">
                        <div class="panel-heading panel-heading-divider">{{trans('app.add')}} {{trans('app.question')}}</div>
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
                            <form action="{{url('admin/question/store')}}" enctype="multipart/form-data" name="app_add_form" id="app_form" style="border-radius: 0px;" method="post" class="form-horizontal group-border-dashed">

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Question Type </label>
                                    <div class="col-sm-6 col-md-4">
                                        <select name="type" id="type" class="form-control" required>
                                            <option value="">Select Question Type</option>
                                            <option value="0" selected>With Option</option>
                                            <option value="1">With out Option</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Question <span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <textarea type="text" rows="5" name="question" id="question" placeholder="Question" class="form-control input-sm required" >{{old('question')}}</textarea>
                                    </div>
                                </div>

                                <div class="form-group" id="optional">
                                    <label class="col-sm-4 control-label">Optoin<span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                    <div class="input_fields_wrap">
                                            <div style="padding-bottom: 10px;">
                                                <button class="add_field_button"><i class="icon mdi mdi-plus "></i></button>
                                            </div>
                                            <div style="padding-bottom: 10px">
                                                <input type="text" name="option[0]" placeholder="Option" class="form-control option_input input-sm required" />
                                            </div>
                                    </div>
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
                                        <button type="submit" class="btn btn-space btn-info btn-lg">{{trans('app.add')}} {{trans('app.question')}}</button>
                                        <a href="{{url('admin/question/list')}}" class="btn btn-space btn-danger btn-lg">Cancel</a>
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
<script src="{{url('public/js/jquery.validate.min.js')}}"></script>
<script type="text/javascript">
    app.validate.init();

    // For creating dynamic input value of option

    $(document).ready(function() {

        var max_fields      = 10; //maximum input boxes allowed
        var wrapper         = $(".input_fields_wrap"); //Fields wrapper
        var add_button      = $(".add_field_button"); //Add button ID

        var x = 1; //initlal text box count
        $(add_button).click(function(e){ //on add input button click
            e.preventDefault();
            if(x < max_fields){ //max input box allowed
                $(wrapper).append('<div style="padding-bottom: 10px"><input type="text" placeholder="Option" class="form-control input-sm option_input required" name="option['+x+']" /><a href="#" class="remove_field">Remove</a></div>'); //add input box
                x++; //text box increment
            }
        });

        $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
            e.preventDefault();
            $(this).parent('div').remove();
            var i = 0;
            $(".option_input").each(function () {
                $(this).attr('name','option['+i+']');
                i++;
            });

            x--;
        })
    });

    // For showing div of optinal and non optional section

    $(function() {
        $('#type').change(function(){
            if($('#type').val() == '0') {
                $('#optional').show();
            } else {
                $('#optional').hide();
            }
        });
    });
</script>
@endpush