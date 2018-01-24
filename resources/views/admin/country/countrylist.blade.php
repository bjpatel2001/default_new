@extends('admin.layouts.common')
@section('pageTitle')
    {{trans('app.machine_list_title')}}
@endsection
@push('externalCssLoad')
@endpush
@push('internalCssLoad')

@endpush

@section('content')
    <div class="be-content">
        <div class="page-head">
            <h2>{{trans('app.country')}} {{trans('app.management')}}</h2>
            <ol class="breadcrumb">
                <li><a href="{{url('admin/dashboard')}}">{{trans('app.admin_home')}}</a></li>
                <li class="active">{{trans('app.country')}} {{trans('app.list')}}</li>
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
                                <a href="{{url('admin/country/add')}}">
                                    <button class="btn btn-space btn-primary"><i
                                                class="icon mdi mdi-plus "></i> {{trans('app.add')}} {{trans('app.country')}}
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="deta-table country-table pull-left">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="panel panel-default panel-table">
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table id="dataTable"
                                               class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">

                                            <thead>
                                                <tr>
                                                    <th>Country</th>
                                                    <th>Status</th>
                                                    <th class="no-sort">Action</th>
                                                </tr>
                                            </thead>
                                            <thead>
                                                 <tr>
                                                    <th>
                                                        <input type="text" name="filter[name]" id="name" value=""/>
                                                    </th>

                                                     <th>
                                                         <select name="filterSelect[status]" id="status">
                                                             <option value="">{{trans('app.select')}}</option>
                                                             <option value="1">{{trans('app.active')}}</option>
                                                             <option value="0">{{trans('app.inactive')}}</option>
                                                         </select>
                                                     </th>
                                                    <th></th>
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
                <div id="myModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body col-md-12 country_list_images">

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default remove_images">Remove</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('externalJsLoad')
<script src="{{url('js/appDatatable.js')}}"></script>
<script src="{{url('js/modules/default.js')}}"></script>
@endpush
@push('internalJsLoad')
<script>
    app.default.init({"route":{"url":'country/datatable'}});

</script>
@endpush