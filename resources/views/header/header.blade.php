<div class="left-sidenav">
    <!-- LOGO -->
    <div class="topbar-left">

    </div>
    <!--end logo-->
    <div class="leftbar-profile p-3 w-100">
        <div class="media position-relative">
            <div class="leftbar-user online">
                <img src="{{asset('/images/user_icons.png')}}" alt="" class="thumb-md rounded-circle">
            </div>
            <div class="media-body align-self-center text-truncate ml-3">

                <h5 class="mt-0 mb-1 font-weight-semibold">{{Auth::user()->fname}}</h5>

            </div><!--end media-body-->
        </div>
    </div>
    <div class="slimScrollDiv " style="position: relative; overflow: hidden; width: auto; height: 4159px;">
        <ul class="metismenu left-sidenav-menu slimscroll mm-show"
            style="overflow: hidden; width: auto; height: 4159px;">


            <li class="{{ Request::is('/') ? 'mm-active' : '' }} leftbar-menu-item">
                <a class="menu-link" href="{{url('/')}}"><i class="fa fa-home" aria-hidden="true"></i>
                    <span>Dashboard</span></a>
            </li>

            @can('MANAGE_CUSTOMERS')
                <li class="{{ Request::is('customer*') ? 'mm-active' : '' }} leftbar-menu-item">
                    <a class="menu-link" href="{{url('customer')}}"><i class="fa fa-user-plus" aria-hidden="true"></i>
                        <span>Customers</span></a>
                </li>
            @endcan

            @can('MANAGE_AREAS')
            <li class="{{ Request::is('areas*') ? 'mm-active' : '' }} leftbar-menu-item">
                <a class="menu-link" href="{{url('/areas')}}"><i class="fa fa-street-view" aria-hidden="true"></i>
                    <span>Areas</span></a>
            </li>
            @endcan
            <li class="{{ Request::is('shop*') ? 'mm-active' : '' }} leftbar-menu-item">
                <a class="menu-link" href="{{url('/shop')}}"><i class="fa fa-shopping-cart" aria-hidden="true"></i>
                    <span>Shop</span></a>
            </li>
            @can('MANAGE_PRODUCTS')
                <li class="leftbar-menu-item dropdown {{ Request::is('product') ? 'mm-active' : '' }} {{ Request::is('category*') ? 'mm-active' : '' }}">
                    <a class="menu-link" href="javascript: void(0);" class="menu-link" aria-expanded="false">
                        <i class="fa fa-lightbulb-o" aria-hidden="true"></i>
                        <span>Product</span>
                        <span class="menu-arrow">
                            <i class="mdi mdi-chevron-right"></i>
                        </span>
                    </a>
                    <ul class="nav-second-level mm-collapse" aria-expanded="false">
                        @can('MANAGE_PRODUCTS')
                            <li>
                                <a href="{{url('product')}}"><i class="pe-7s-home"></i>Products</a>
                            </li>
                        @endcan
                        @can('MANAGE_PRODUCT_CATEGORIES')
                            <li>
                                <a href="{{url('category')}}"><i class="pe-7s-home"></i>Categories</a>
                            </li>
                        @endcan
                        @can('INVENTORY_MANUAL_UPDATE')
                            <li>
                                <a href="{{url('inventory')}}"><i class="pe-7s-home"></i>Inventory</a>
                            </li>
                                <li>
                                    <a href="{{route('bulk')}}"><i class="pe-7s-home"></i>Bulk Price Update</a>
                                </li>
                        @endcan
                    </ul>
                </li>
            @endcan

            @can('MANAGE_GRN')
                <li class="leftbar-menu-item {{ Request::is('grn*') ? 'mm-active' : '' }}">
                    <a class="menu-link" href="{{url('/grn')}}"><i class="fa fa-level-up" aria-hidden="true"></i>
                        <span>GRN</span></a>
                </li>
            @endcan

            @can('MANAGE_INVOICE')
                <li class="leftbar-menu-item dropdown {{ Request::is('invoice*') ? 'active' : '' }}">
                    <a class="menu-link" href="javascript: void(0);" class="menu-link" aria-expanded="false">
                        <i class="fa fa-list-alt" aria-hidden="true"></i>
                        <span>Invoice</span>
                        <span class="menu-arrow">
                            <i class="mdi mdi-chevron-right"></i>
                        </span>
                    </a>

                    <ul class="nav-second-level mm-collapse" aria-expanded="false">
                        @can('MANAGE_INVOICE')
                            <li>
                                <a href="{{url('invoice?invoice_type=quick')}}"><i class="pe-7s-home"></i>Quick Sell</a>
                            </li>
                            <li>
                                <a href="{{url('invoice?invoice_type=orders')}}"><i class="pe-7s-home"></i>Orders</a>
                            </li>
                            <li>
                                <a href="{{url('invoice?invoice_type=dispatch')}}"><i class="pe-7s-home"></i>Ready to
                                    dispatch</a>
                            </li>
                            <li>
                                <a href="{{url('invoice?invoice_type=invoice')}}"><i class="pe-7s-home"></i>Invoice</a>
                            </li>
                        @endcan
                        @can('MANAGE_CREDIT_NOTE')
                            <li>
                                <a href="{{url('invoice_return')}}"><i class="pe-7s-home"></i>Credit Notes</a>
                            </li>
                        @endcan


                    </ul>
                </li>
            @endcan

            @can('MANAGE_PAYMENT')
                <li class="leftbar-menu-item {{ Request::is('payment/create*') ? 'mm-active' : '' }}">
                    <a class="menu-link" href="{{url('payment')}}"><i class="fa fa-money" aria-hidden="true"></i><span>Payment</span></a>
                </li>
            @endcan

            @can('MANAGE_CHEQUE_PAYMENT')
                <li class="leftbar-menu-item {{ Request::is('cheque_payment*') ? 'mm-active' : '' }}">
                    <a class="menu-link" href="{{url('cheque_payment')}}"><i class="fa fa-money-check-alt"
                                                                             aria-hidden="true"></i><span>Cheques</span></a>
                </li>
            @endcan
            @can('MANAGE_REPORTS')
                <li class="leftbar-menu-item {{ Request::is('reports*') ? 'mm-active' : '' }}">
                    <a class="menu-link" href="{{url('reports/index')}}"><i class="fa fa-industry"
                                                                            aria-hidden="true"></i><span>Reports</span></a>
                </li>
            @endcan
            @can('MANAGE_SETTING')
                <li class="leftbar-menu-item dropdown {{ Request::is('configurations*') ? 'active' : '' }}
                {{ Request::is('config_categories*') ? 'active' : '' }}
                {{ Request::is('settings*') ? 'active' : '' }}
                {{ Request::is('users*') ? 'active' : '' }}
                {{ Request::is('roles*') ? 'active' : '' }}
                {{ Request::is('permissions*') ? 'active' : '' }}">

                    <a class="menu-link" href="javascript: void(0);" class="menu-link" aria-expanded="false">
                        <i class="fas fa-cog fa-spin"></i>


                        <span>Setting</span>
                        <span class="menu-arrow">
                            <i class="mdi mdi-chevron-right"></i>
                        </span>
                    </a>


                    <ul class="nav-second-level mm-collapse" aria-expanded="false">

                        @can('MANAGE_CONFIGURATION')
                            <li>
                                <a href="{{url('configurations')}}"><i class="pe-7s-home"></i>Configurations</a>
                            </li>
                        @endcan
                        @can('MANAGE_CONFIGURATION')
                            <li>
                                <a href="{{url('config_categories')}}"><i class="pe-7s-home"></i>Config Categories</a>
                            </li>
                        @endcan
                        @can('MANAGE_COMPANY_INFORMATION')
                            <li>
                                <a href="{{url('settings')}}"><i class="pe-7s-home"></i>Settings</a>
                            </li>
                        @endcan
                        @can('MANAGE_USERS')
                            <li>
                                <a href="{{url('users')}}"><i class="pe-7s-home"></i>Users</a>
                            </li>
                        @endcan
                        @can('MANAGE_ROLES')
                            <li>
                                <a href="{{url('roles')}}"><i class="pe-7s-home"></i>Roles</a>
                            </li>
                        @endcan
                        @can('MANAGE_PERMISSIONS')
                            <li>
                                <a href="{{url('permissions')}}"><i class="pe-7s-home"></i>Permissions</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan


        </ul>
        <div class="slimScrollBar"
             style="background: rgba(162, 177, 208, 0.13); width: 7px; position: absolute; top: 0px; opacity: 1; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 800px;"></div>
        <div class="slimScrollRail"
             style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;"></div>
    </div>

</div>


<!-- Top Bar Start -->
<div class="topbar">
    <!-- Navbar -->
    <nav class="navbar-custom">


        <ul class="list-unstyled topbar-nav float-right mb-0">

            <li>
                <a class="nav-link">
                    <span id="system_time_display"></span>
                </a>
            </li>

            <li class="dropdown">
                <a class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown" href="#"
                   role="button"
                   aria-haspopup="false" aria-expanded="false">
                    <img src="{{asset('/images/user_icons.png')}}" alt="profile-user" class="rounded-circle"/>
                    <span class="ml-1 nav-user-name hidden-sm">{{Auth::user()->username}} <i
                            class="mdi mdi-chevron-down"></i> </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">

                    <a class="dropdown-item" href="{{url('change_password')}}"><i
                            class="dripicons-lock text-muted mr-2"></i> Change Password</a>
                    <div class="dropdown-divider"></div>


                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();" class="dropdown-item">
                        <i class="dripicons-exit text-muted mr-2"></i> {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                          style="display: none;">
                        @csrf
                    </form>


                </div>
            </li>

        </ul><!--end topbar-nav-->

        <ul class="list-unstyled topbar-nav mb-0">
            <li>
                <a href="../crm/crm-index.html">
                            <span class="responsive-logo">
                                <img src="../assets/images/logo-sm.png" alt="logo-small"
                                     class="logo-sm align-self-center" height="34">
                            </span>
                </a>
            </li>
            <li>
                <button class="button-menu-mobile nav-link">
                    <i data-feather="menu" class="align-self-center"></i>
                </button>
            </li>
            <li class="nav-item">
                <a href="{{url('/')}}" class="logo">
                    <span>
                       {!!  getLogo(array('class'=> 'img-responsive')) !!}
                    </span>

                </a>
            </li>
        </ul>
    </nav>
    <!-- end navbar-->
</div>
<!-- Top Bar End -->

