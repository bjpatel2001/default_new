@extends('admin.layouts.common')
@section('pageTitle')
    {{trans('app.request_list_title')}}
@endsection
@push('externalCssLoad')
@endpush
@push('internalCssLoad')

@endpush
@section('content')
    <div class="be-content">
        <div class="page-head">
            <h2>{{trans('app.request')}} {{trans('app.management')}}</h2>
            <ol class="breadcrumb">
                <li><a href="{{url('admin/dashboard')}}">{{trans('app.admin_home')}}</a></li>
                <li class="active">{{trans('app.request')}} {{trans('app.list')}}</li>
            </ol>
        </div>
        <div class="main-content container-fluid">

            <!-- Caontain -->
            <div class="panel panel-default panel-border-color panel-border-color-primary pull-left">
                <div class="row">
                    <div class="col-sm-12 col-xs-12">

                    </div>
                </div>
                <div class="row" style="display: none;">
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
                                <a href="{{url('admin/request/add')}}">
                                    <button class="btn btn-space btn-primary"><i
                                                class="icon mdi mdi-plus "></i> {{trans('app.add')}} {{trans('app.request')}}
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="deta-table request-table pull-left">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="panel panel-default panel-table">
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table id="dataTableLog"
                                               class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">

                                            <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Name</th>
                                                <th>Business Name</th>
                                                <th>Contact Number</th>
                                                <th>Country</th>
                                                <th>State</th>
                                               <th class="no-sort">Action</th>
                                            </tr>
                                            </thead>

                                            <tbody>
                                            @if(count($requestData) > 0)
                                                @foreach($requestData as $row)
                                                    <tr>
                                                        <td>{{(date('d-M-Y', strtotime($row->created_at)))}}</td>
                                                        <td>{{$row->User->name}}</td>
                                                        <td>{{$row->User->business_name}}</td>
                                                        <td>{{$row->User->mobile_number}}</td>
                                                        <td>{{$row->Country->name}}</td>
                                                        <td>{{$row->State->name}}</td>
                                                        <td>
                                                            <a href="{{url('/admin/request/view/'.$row->id)}}" title="View">
                                                                View
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
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
@endsection

@push('externalJsLoad')
<script src="{{url('js/appDatatable.js')}}"></script>
<script src="{{url('js/modules/default.js')}}"></script>
@endpush
@push('internalJsLoad')
<script>
    //app.default.init({"route":{"url":'request/datatable'}});
    $(document).ready(function () {
        $("#dataTableLog").dataTable();
        $(".deta-table").animate({"opacity":1},1200);
    });
</script>
@endpush