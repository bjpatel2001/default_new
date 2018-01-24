<div class="be-left-sidebar">
    <div class="left-sidebar-wrapper"><a href="#" class="left-sidebar-toggle">{{trans('app.admin_home')}}</a>
        <div class="left-sidebar-spacer">
            <div class="left-sidebar-scroll">
                <div class="left-sidebar-content">
                    <ul class="sidebar-elements">
                        <li class="divider">{{trans('app.menu')}}</li>
                        <li class="{{$dashboardTab or ''}}" title="Dashboard"><a href="{{url('web/user-dashboard')}}"><i
                                        class="icon mdi mdi-home"></i><span>{{trans('app.admin_home')}}</span></a>
                        </li>
                        {{--<li class="parent {{$productManagementTab or ''}}" title="Product Management"><a href="#"><i
                                        class="icon mdi mdi-account mdi-18px"></i><span>{{trans('app.product_management')}}</span></a>
                            <ul class="sub-menu">
                              <li class="{{$categoryTab or ''}}">
                                <a href="{{url('admin/category/list')}}">{{trans('app.category')}} {{trans('app.management')}}</a>
                              </li>
                              <li class="{{$machineTab or ''}}">
                                <a href="{{url('admin/machine/list')}}">{{trans('app.machine')}} {{trans('app.management')}}</a>
                              </li>
                            </ul>
                        </li>--}}


                        <li title="profile" class="{{$profileTab or ''}}"><a href="{{url('web/user/profile')}}"><i
                                        class="icon mdi mdi-face"></i></i><span>{{trans('app.my_profile')}}</span></a>
                        </li>
                        <li title="Change Password" class="{{$changePasswordTab or ''}}"><a
                                    href="{{url('web/change-password')}}"><i class="icon mdi mdi-lock"></i></i>
                                <span>{{trans('app.change_password')}}</span></a>
                        </li>

                    </ul>

                </div>
            </div>
        </div>

    </div>
</div>