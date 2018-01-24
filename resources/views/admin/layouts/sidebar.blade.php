<div class="be-left-sidebar">
    <div class="left-sidebar-wrapper"><a href="#" class="left-sidebar-toggle">{{trans('app.admin_home')}}</a>
        <div class="left-sidebar-spacer">
            <div class="left-sidebar-scroll">
                <div class="left-sidebar-content">
                    <ul class="sidebar-elements">
                        <li class="divider">{{trans('app.menu')}}</li>
                        <li class="{{$dashboardTab or ''}}" title="Dashboard"><a href="{{url('admin/dashboard')}}"><i
                                        class="icon mdi mdi-home"></i><span>{{trans('app.admin_home')}}</span></a>
                        </li>
                        @if(Auth::user()->role_id == 1)
                        <li title="profile" class="{{$userTab or ''}}"><a href="{{url('admin/user/list')}}"><i
                                        class="icon mdi mdi-account-add"></i></i><span>{{trans('app.admin')}} {{trans('app.user')}} {{trans('app.management')}}</span></a>
                        </li>
                        @endif

                        <li class="parent {{$productManagementTab or ''}}" title="Product Management"><a href="#"><i
                                        class="icon mdi mdi-account mdi-18px"></i><span>{{trans('app.product_management')}}</span></a>
                            <ul class="sub-menu">
                              <li class="{{$categoryTab or ''}}">
                                <a href="{{url('admin/category/list')}}">{{trans('app.category')}} {{trans('app.management')}}</a>
                              </li>
                              <li class="{{$machineTab or ''}}">
                                <a href="{{url('admin/machine/list')}}">{{trans('app.machine')}} {{trans('app.management')}}</a>
                              </li>
                            </ul>
                        </li>

                        <li class="parent {{$brochureManagementTab or ''}}" title="Brochure Management"><a href="#"><i
                                        class="icon mdi mdi-account mdi-18px"></i><span>{{trans('app.brochure_management')}}</span></a>
                            <ul class="sub-menu">
                                <li class="{{$masterbrochureTab or ''}}">
                                    <a href="{{url('admin/brochure/get_private_brochure')}}">{{trans('app.master_brochure')}} {{trans('app.management')}}</a>
                                </li>
                                {{--<li class="{{$brochureTab or ''}}">
                                    <a href="{{url('admin/brochure/list')}}">{{trans('app.brochure')}} {{trans('app.management')}}</a>
                                </li>--}}
                            </ul>
                        </li>
                        <li class="parent {{$locationManagementTab or ''}}" title="Location Management"><a href="#"><i
                                        class="icon mdi mdi-account mdi-18px"></i><span>{{trans('app.location_management')}}</span></a>
                            <ul class="sub-menu">
                                <li class="{{$countryTab or ''}}">
                                    <a href="{{url('admin/country/list')}}">{{trans('app.country')}} {{trans('app.management')}}</a>
                                </li>
                                <li class="{{$stateTab or ''}}">
                                    <a href="{{url('admin/state/list')}}">{{trans('app.state')}} {{trans('app.management')}}</a>
                                </li>
                            </ul>
                        </li>
                        <li class="parent {{$masterManagementTab or ''}}" title="Master Managemet"><a href="#"><i
                                        class="icon mdi mdi-account mdi-18px"></i><span>{{trans('app.master_managemet')}}</span></a>
                            <ul class="sub-menu">

                              <li class="{{$app_userTab or ''}}">
                                    <a href="{{url('admin/app_user/list')}}">{{trans('app.app_user')}} {{trans('app.management')}}</a>
                              </li>

                               {{-- <li class="{{$permissionTab or ''}}">
                                    <a href="{{url('admin/permission/list')}}">{{trans('app.permission')}} {{trans('app.management')}}</a>
                                </li>--}}

                            </ul>
                        </li>

                        <li class="parent {{$quotationManagementTab or ''}}" title="{{trans('app.quotation_management')}}"><a href="#"><i
                                        class="icon mdi mdi-quote mdi-18px"></i><span>{{trans('app.quotation_management')}}</span></a>
                            <ul class="sub-menu">
                                <li class="{{$questionTab or ''}}">
                                    <a href="{{url('admin/question/list')}}">{{trans('app.question_managment')}}</a>
                                </li>
                                <li class="{{$quotationTemplateTab or ''}}">
                                    <a href="{{url('admin/quotation/list')}}">{{trans('app.quotation_template')}}</a>
                                </li>

                            </ul>
                        </li>


                        <li title="profile" class="{{$newsTab or ''}}"><a href="{{url('admin/news/list')}}"><i
                                        class="icon mdi mdi-quote"></i></i><span>{{trans('app.news_managment')}}</span></a>
                        </li>

                        <li class="parent {{$clientManagementTab or ''}}" title="{{trans('app.client_managment')}}"><a href="#"><i
                                        class="icon mdi mdi-quote mdi-18px"></i><span>{{trans('app.client_managment')}}</span></a>
                            <ul class="sub-menu">
                                <li class="{{$clientTab or ''}}">
                                    <a href="{{url('admin/client/list')}}">{{trans('app.client')}}</a>
                                </li>
                                <li class="{{$settingTab or ''}}">
                                    <a href="{{url('admin/client/client-pdf')}}">{{trans('app.client')}} {{trans('app.pdf')}}</a>
                                </li>

                            </ul>
                        </li>

                        <li title="question" class="{{$requestTab or ''}}"><a href="{{url('admin/request/list')}}"><i
                                        class="icon mdi mdi-quote"></i></i><span>{{trans('app.request_managment')}}</span></a>
                        </li>
                        {{--<li title="Reports"><a href="{{url('maintanance')}}"><i class="icon fa fa-file-text-o"
                                                                                 aria-hidden="true"></i><span>{{trans('app.reports')}}</span></a>
                        </li>--}}

                        <li title="profile" class="{{$profileTab or ''}}"><a href="{{url('admin/user/profile')}}"><i
                                        class="icon mdi mdi-face"></i></i><span>{{trans('app.my_profile')}}</span></a>
                        </li>
                        <li title="Change Password" class="{{$changePasswordTab or ''}}"><a
                                    href="{{url('admin/change-password')}}"><i class="icon mdi mdi-lock"></i></i>
                                <span>{{trans('app.change_password')}}</span></a>
                        </li>

                    </ul>

                </div>
            </div>
        </div>

    </div>
</div>