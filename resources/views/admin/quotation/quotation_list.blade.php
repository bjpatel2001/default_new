@extends('admin.layouts.common')
@section('pageTitle')
    {{trans('app.quotation_add_title')}}
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
    .add_error_class{border: 1px solid red !important;}
    .add_product{margin-top: -10px;margin-right: 10px;}
</style>
@section('content')
    <div class="be-content">
        <div class="page-head">
            <h2>{{trans('app.quotation')}} {{trans('app.management')}}</h2>
            <ol class="breadcrumb">
                <li><a href="{{url('admin/dashboard')}}">{{trans('app.admin_home')}}</a></li>
                <li><a href="{{url('admin/quotation/list')}}">{{trans('app.quotation')}} {{trans('app.management')}}</a>
                </li>
            </ol>
        </div>
        <div class="main-content container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default panel-border-color panel-border-color-primary">
                        <div class="panel-heading panel-heading-divider">{{trans('app.add')}} {{trans('app.quotation')}}</div>
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
                            <form action="{{url('admin/quotation/store')}}" name="app_add_form" id="app_form"
                                  style="border-radius: 0px;" method="post" class="form-horizontal group-border-dashed">

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Section Name<span
                                                class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <?php $value = '' ?>
                                        @foreach($details as $quotation)
                                                <?php $value = $quotation->section_name; ?>
                                                    <input type="hidden" name="id" value="{{$quotation->id}}">
                                        @endforeach
                                        <input type="text" name="section_name" id="section_name"
                                               placeholder="Section Name" class="form-control input-sm required"
                                               value="{{$value}}"/>
                                    </div>

                                    <?php
                                        $default_checked = 'checked';
                                        if(count($details) > 0){
                                            $default_checked = '';
                                        }
                                    ?>

                                    <br>
                                    <fieldset class="category_class">
                                        <legend class="legend_class">Category:</legend>
                                        <a href="{{url('admin/category/add/1')}}" class="btn btn-default pull-right">Add Category</a>
                                        <table class="dt-responsive nowrap" cellspacing="0" width="100%">
                                            <tbody>
                                            @foreach($categoryData as $category)
                                                <tr>
                                                    <td>
                                                        <?php $checked = '' ?>
                                                            @foreach($details as $quotation)
                                                                @foreach($quotation->QuotationMapping as $categoryMapping)
                                                                    @if($category->id == $categoryMapping->category_id)
                                                                        <?php $checked = 'checked="checked"' ?>
                                                                    @endif

                                                                @endforeach
                                                            @endforeach
                                                            <input type='checkbox' class="category_name"
                                                                   name="category_name[]" {{$checked}} {{$default_checked}} value="{{$category->id}}"/>
                                                    </td>
                                                    <td>
                                                        {{$category->category_name}}
                                                    </td>
                                                    <td><a href="{{url('/admin/category/edit/'.$category->id.'/1')}}">
                                                            <i class="fa fa-edit fa-lg" style="font-size: 20px;"></i>
                                                        </a></td>
                                                    <td><a href="javascript:void(0);" class="deleteRecord"
                                                           formaction="category" rel="{{$category->id}}">
                                                            <i class="fa fa-trash fa-lg"
                                                               style="font-size: 20px; margin-left: 5px;"></i>
                                                        </a></td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </fieldset>

                                </div>
                                <div class="form-group">
                                    <fieldset id="category_class" class="category_class">
                                        <legend class="legend_class">Product:</legend>
                                        <div class="panel-group" id="accordion">
                                            <div class="panel panel-default product_class">

                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                                {{ csrf_field() }}
                                <input type="hidden" name="remove_category_ids" id="remove_category_ids" value="" />
                                <div class="col-sm-6 col-md-8 savebtn">
                                    <p class="text-right">
                                        <button type="submit"
                                                class="btn btn-space btn-info btn-lg">{{trans('app.save')}} {{trans('app.template')}}</button>
                                        <a href="{{url('admin/quotation/list')}}"
                                           class="btn btn-space btn-danger btn-lg">Cancel</a>
                                    </p>
                                </div>
                                @foreach($details as $quotation)
                                    @foreach($quotation->QuotationMapping as $categoryMapping)
                                        <input type="hidden" name="checked_machine[]" value="{{$categoryMapping->machine_id}}">
                                        <input type="hidden" name="quotation_mapping[]" value="{{$categoryMapping->id}}">
                                    @endforeach
                                @endforeach
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('externalJsLoad')
<script src="{{url('js/modules/default.js')}}"></script>
@endpush
@push('internalJsLoad')
<script type="text/javascript">
    var validations = {
        ignore: "",
        errorPlacement: function (error, element) {
            if (element.is(":checkbox")){
                var class_name = element.closest(".panel-collapse").attr('id');
                $(".panel_"+class_name).addClass("add_error_class");
            }
        }
    };
    app.validate.init(validations);
    $(document).ready(function () {

        $("#app_form").submit(function () {
            $(".panel-heading").removeClass("add_error_class");
            if(!$("#app_form").valid()){
                return false;
            }
        });

        $(document).on('click', '.category_name', function (event) {
            var remove_category_ids = [];
            $(".category_name").each(function () {
                if($(this).closest("td").children(".category_name:checked").length == 0){
                    remove_category_ids.push($(this).val());
                }
            });

            $("#remove_category_ids").val(remove_category_ids.join(','));
            $('input[name="checked_machine[]"]').val("");
            $(document).trigger('get_category_name');
        });
        $(document).on('click', '.product_name', function () {
            if(!$(this).is(':checked')){
                $(this).next('input').val($(this).val());
            }else{
                $(this).next('input').val("");
            }
        });
        $(document).trigger('get_category_name');
    });

    $(document).on('get_category_name',function(){
        if ($(".category_name:checked").length > '0') {
            var category_ids = $('.category_name:checked').map(function()
            {
                return $(this).val();
            }).get();
            var machine_ids = $('input[name="checked_machine[]"]').map(function()
            {
                return $(this).val();
            }).get();
            app.showLoader(".main-content");
            $.ajax({
                type: "POST",
                url: app.config.SITE_PATH + 'quotation/category_products',
                data: {id: category_ids, _token: csrf_token,machine_ids:machine_ids},
                success: function (response) {
                    if (response.statusCode == "1") {
                        $('#category_class').show();
                        $(".product_class").html(response.data);
                    }
                    app.hideLoader(".main-content");
                }
            });
        } else {
            $('#category_class').hide();
            $(".product_class").html('');
        }
    });
/*
    $(function(){
        $('#brij').on('hide.bs.collapse', function () {
            $('#brijesh').html('<span class="glyphicon glyphicon-collapse-down"></span> Show');
        })
        $('#brij').on('show.bs.collapse', function () {
            $('#brijesh').html('<span class="glyphicon glyphicon-collapse-up"></span> Hide');
        })
    })*/
</script>
@endpush