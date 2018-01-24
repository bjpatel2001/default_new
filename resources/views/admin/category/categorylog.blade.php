@extends('admin.layouts.common')
@section('pageTitle')
    {{trans('app.category_list_title')}}
@endsection
@push('externalCssLoad')
@endpush
@push('internalCssLoad')
@endpush
@section('content')
    <div class="be-content">
        <div class="page-head">
            <h2>{{trans('app.category')}} {{trans('app.management')}}</h2>
            <ol class="breadcrumb">
                <li><a href="{{url('admin/dashboard')}}">{{trans('app.admin_home')}}</a></li>
                <li><a href="{{url('admin/category/list')}}">{{trans('app.category')}} {{trans('app.management')}}</a></li>
                <li class="active">{{trans('app.category')}} {{__('app.log')}} {{trans('app.list')}}</li>
            </ol>
        </div>
        <div class="main-content container-fluid">

            <!-- Caontain -->
            <div class="panel panel-default panel-border-color panel-border-color-primary pull-left">
                <div class="row">
                    <div class="col-sm-12 col-xs-12">

                    </div>
                </div>

                <div class="deta-table category-table pull-left">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="panel panel-default panel-table">
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table id="dataTableLog"
                                               class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">

                                            <thead>
                                                <tr>
                                                    <th>Created By</th>
                                                    <th>Actions</th>
                                                    <th>Created At</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach($details as $row)
                                                    <tr>
                                                        <td>{{$row->CreatedBy->name}}</td>
                                                        <td>{{$row->action}}</td>
                                                        <td>{{$row->created_at}}</td>
                                                    </tr>
                                                @endforeach
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
@endpush
@push('internalJsLoad')
<script>
$(document).ready(function () {
   $("#dataTableLog").dataTable();
    $(".deta-table").animate({"opacity":1},1200);
});
</script>
@endpush