@extends('admin.layouts.common')
@section('pageTitle')
    {{trans('app.permission_list_title')}}
@endsection
@push('externalCssLoad')

@endpush
@push('internalCssLoad')

@endpush
@section('content')
    <div class="be-content">
        <div class="page-head">
            <?php $baseUrl = App::make('url')->to('/'); ?>
            <h2>{{trans('app.permission')}} {{trans('app.management')}}</h2>
            <ol class="breadcrumb">
                <li><a href="{{url('admin/dashboard')}}">{{trans('app.admin_home')}}</a></li>
                <li class="active">{{trans('app.permission')}} {{trans('app.list')}}</li>
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
                            {{--<div class="addreport pull-right">
                                <a href="{{url('admin/permission/add')}}"><button class="btn btn-space btn-primary btn-lg hover"><i class="icon mdi mdi-plus "></i>Add Permission</button></a>
                            </div>--}}
                            <!--  <div class="btn-group ">
                                  <button type="button " class="btn btn-primary btn-lg active">All</button>
                                  <button type="button" class="btn btn-primary btn-lg">Pending</button>
                                  <button type="button" class="btn btn-primary btn-lg">Approved</button>
                             </div>
                             <div class="search-but pull-right">
                                  <button class="btn btn-space btn-danger pull-right btn-lg">Reset</button>
                                  <button class="btn btn-space btn-primary pull-right btn-lg">Search</button>
                             </div> -->
                        </div>
                    </div>
                </div>
                <div class="deta-table user-table pull-left">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="panel panel-default panel-table">
                                <!--
                                                            <div class="panel-heading">Export Functions
                                                              <div class="tools"><span class="icon mdi mdi-download"></span><span class="icon mdi mdi-more-vert"></span></div>
                                                            </div>
                                -->
                                <div class="panel-body">
                                    <table id="table3" class="table table-striped table-hover table-fw-widget">
                                        <thead>
                                        <tr>
                                            <th>{{trans('app.permission')}} Name</th>
                                            <th>{{trans('app.permission')}} Code</th>
                                            <th>Action</th>

                                        </tr>

                                        </thead>
                                        <tbody>
                                        @if(count($permissionData) > 0)
                                            @foreach($permissionData as $row)
                                                <tr>
                                                    <td>{{$row->name}}</td>
                                                    <td>{{$row->code}}</td>
                                                    <td>
                                                        <div class="icon pull-left"><a href="{{$baseUrl.'/admin/permission/edit/'.$row->id}}"><span title="Edit" class="mdi mdi-edit"></span></a></div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="3">No Record Found</td>
                                            </tr>
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
@endsection

@push('externalJsLoad')
@endpush
@push('internalJsLoad')

@endpush