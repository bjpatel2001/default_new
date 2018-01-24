@extends('admin.layouts.common')
@section('pageTitle')
    {{trans('app.machine_list_title')}}
@endsection
@push('externalCssLoad')
@endpush
@push('internalCssLoad')

@endpush
<style>
    .image_class{margin-top: 15px;}
    .machine_image_class{cursor: pointer;}
    .machine_list_images{margin-bottom: 5px;}
</style>
@section('content')
    <div class="be-content">
        <div class="page-head">
            <h2>{{trans('app.machine')}} {{trans('app.management')}}</h2>
            <ol class="breadcrumb">
                <li><a href="{{url('admin/dashboard')}}">{{trans('app.admin_home')}}</a></li>
                <li class="active">{{trans('app.machine')}} {{trans('app.list')}}</li>
            </ol>
        </div>
        <div class="main-content container-fluid">

            <!-- Caontain -->
            <div class="panel panel-default panel-border-color panel-border-color-primary pull-left">
                <div class="row">
                    <div class="col-sm-12 col-xs-12">

                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-xs-12">
                        <div class="activity-but activity-space pull-left">
                            <div class="pull-left">
                                <a href="javascript:void(0);" class="btn btn-warning func_SearchGridData"><i
                                            class="icon mdi mdi-search"></i> Search</a>
                            </div>
                            <div class="pull-left">
                                <a href="javascript:void(0);" class="btn btn-danger func_ResetGridData"
                                   style="margin-left: 10px;">Reset</a>
                            </div>
                            <div class="addreport pull-right">
                                <a href="{{url('admin/machine/add')}}">
                                    <button class="btn btn-space btn-primary"><i
                                                class="icon mdi mdi-plus "></i> {{trans('app.add')}} {{trans('app.machine')}}
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="deta-table machine-table pull-left">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="panel panel-default panel-table">
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table id="dataTable"
                                               class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Category</th>
                                                    <th class="no-sort">Image</th>
                                                    <th class="no-sort">Brochure Pdf</th>
                                                    <th class="no-sort">On App Dashboard</th>
                                                    <th>Status</th>
                                                    <th class="no-sort">Action</th>
                                                </tr>
                                            </thead>
                                            <thead>
                                                 <tr>
                                                    <td>
                                                        <input type="text" name="filter[machine_name]" style="width: 150px;" id="name" value="" />
                                                    </td>
                                                     <td>
                                                         <select name="filterSelect[category_id]" id="category_id" style="width: 150px;">
                                                             <option value="" selected="selected">Select</option>
                                                             @foreach($categoryData as $category)
                                                                 <option value="{{$category->id}}">{{$category->category_name}}</option>
                                                             @endforeach
                                                         </select>
                                                     </td>
                                                     <td></td>
                                                     <td></td>
                                                     <td></td>
                                                     <td>
                                                         <select name="filterSelect[status]" id="status">
                                                             <option value="">{{trans('app.select')}}</option>
                                                             <option value="1">{{trans('app.active')}}</option>
                                                             <option value="0">{{trans('app.inactive')}}</option>
                                                         </select>
                                                     </td>
                                                    <td></td>
                                              </tr>
                                            </thead>


                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body col-md-12 machine_list_images">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default remove_images">Remove</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
    /* for pdf Popup*/
    <?php $filepath = url('/') . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'brochure' . DIRECTORY_SEPARATOR; ?>
    <input type="hidden" value="{{$filepath}}" id="url"/>

@endsection

@push('externalJsLoad')
<script src="{{url('js/appDatatable.js')}}"></script>
<script src="{{url('js/modules/default.js')}}"></script>
@endpush
@push('internalJsLoad')
<script>
    app.default.init({"route":{"url":'machine/datatable'}});
    $(document).ready(function () {
        $(document).on('click','.machine_image_class',function () {
            var id = $(this).attr('rel');
            $.ajax({
                type: "POST",
                url: app.config.SITE_PATH +'machine/machine_image',
                data: { id : id,_token: csrf_token}
            }).done(function(data){
                $('.machine_list_images').html(data);
                $('#myModal').modal('show');
            });
        });

        $(document).on('click', ".change_dashboard_status", function () {
            var status = '1';
            if ($(this).closest("div.switch-button").children("input:checked").length > '0') {
                status = '0';
            }

            var id = $(this).closest("div.switch-button").children("input").attr('rel');
            var spath = $(this).closest("div.switch-button").children("input").attr('formaction');
            var url = app.config.SITE_PATH + spath +'/change_dashboard_status';
            app.changeStatus(id, url, status);
        });

        $(document).on('click','.remove_images',function () {
            var checkValues = $('input[name=image]:checked').map(function()
            {
                return $(this).val();
            }).get();
            var machine_id = $('#machine_id').val();
            $.ajax({
                type: "POST",
                url: app.config.SITE_PATH +'machine/delete_image',
                data: { id : checkValues,_token: csrf_token, machine_id : machine_id}
            }).done(function(data){
                $('.machine_list_images').html(data);
            });
        });

        // For showing the popup of pdf Viewr
        $(document).on('click','.html5lightbox',function () {
            var name = $(this).attr('rel');
            var url_name = $('#url').val();
            var url = url_name+name;
            var data = '<iframe src="'+url+'" width="100%" height="800px"></iframe>';
            $('.machine_list_images').html(data);
            $('#myModal').modal('show');
        });
    });
</script>
@endpush