@php
$configData = Helper::applClasses();
@endphp
<div class="main-menu menu-fixed {{ $configData['theme'] === 'dark' || $configData['theme'] === 'semi-dark' ? 'menu-dark' : 'menu-light' }} menu-accordion menu-shadow"
    data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <span class="brand-logo">
                        <svg viewbox="0 0 139 95" version="1.1" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink" height="24">
                            <defs>
                                <lineargradient id="linearGradient-1" x1="100%" y1="10.5120544%" x2="50%"
                                    y2="89.4879456%">
                                    <stop stop-color="#000000" offset="0%"></stop>
                                    <stop stop-color="#FFFFFF" offset="100%"></stop>
                                </lineargradient>
                                <lineargradient id="linearGradient-2" x1="64.0437835%" y1="46.3276743%" x2="37.373316%"
                                    y2="100%">
                                    <stop stop-color="#EEEEEE" stop-opacity="0" offset="0%"></stop>
                                    <stop stop-color="#FFFFFF" offset="100%"></stop>
                                </lineargradient>
                            </defs>
                            <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g id="Artboard" transform="translate(-400.000000, -178.000000)">
                                    <g id="Group" transform="translate(400.000000, 178.000000)">
                                        <path class="text-primary" id="Path"
                                            d="M-5.68434189e-14,2.84217094e-14 L39.1816085,2.84217094e-14 L69.3453773,32.2519224 L101.428699,2.84217094e-14 L138.784583,2.84217094e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L6.71554594,44.4188507 C2.46876683,39.9813776 0.345377275,35.1089553 0.345377275,29.8015838 C0.345377275,24.4942122 0.230251516,14.560351 -5.68434189e-14,2.84217094e-14 Z"
                                            style="fill:currentColor"></path>
                                        <path id="Path1"
                                            d="M69.3453773,32.2519224 L101.428699,1.42108547e-14 L138.784583,1.42108547e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L32.8435758,70.5039241 L69.3453773,32.2519224 Z"
                                            fill="url(#linearGradient-1)" opacity="0.2"></path>
                                        <polygon id="Path-2" fill="#000000" opacity="0.049999997"
                                            points="69.3922914 32.4202615 32.8435758 70.5039241 54.0490008 16.1851325">
                                        </polygon>
                                        <polygon id="Path-21" fill="#000000" opacity="0.099999994"
                                            points="69.3922914 32.4202615 32.8435758 70.5039241 58.3683556 20.7402338">
                                        </polygon>
                                        <polygon id="Path-3" fill="url(#linearGradient-2)" opacity="0.099999994"
                                            points="101.428699 0 83.0667527 94.1480575 130.378721 47.0740288"></polygon>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </span>
                    <h2 class="brand-text">Travelmaster</h2>
                </a>
            </li>
            <li class="nav-item nav-toggle">
                <a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse">
                    <i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i>
                    <i class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc"
                        data-ticon="disc"></i>
                </a>
            </li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
   
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            {{-- Foreach menu item starts --}}
            <li class="nav-item @yield('dashboard_active')">
                <a href="{{ route('dashboard-ecommerce')}}" class="d-flex align-items-center" target="_self">
                    <i data-feather='home'></i>
                    <span class="menu-title text-truncate">Dashboard</span>
                </a>
            </li>
            @if(Auth::user()->type==='Admin')                
            <li class="nav-item @yield('booking_active')">
                <a href="{{ url('admin/booking') }}" class="d-flex align-items-center" target="_self">
                    <i data-feather='percent'></i>
                    <span class="menu-title text-truncate">Manage Bookings</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:void(0)" class="d-flex align-items-center" target="_self">
                    <i data-feather='calendar'></i>
                    <span class="menu-title text-truncate">Manage Events</span>
                </a>
                <ul class="menu-content">
                    <li class="@yield('create_active')">
                        <a href="{{ url('admin/event/create') }}" class="d-flex align-items-center" target="_self">
                            <i data-feather='circle'></i>
                            <span class="menu-item text-truncate">Create Event</span>
                        </a>
                    </li>
                    <li class="@yield('list_active')">
                        <a href="{{ url('admin/event/') }}" class="d-flex align-items-center" target="_self">
                            <i data-feather='circle'></i>
                            <span class="menu-item text-truncate">Event list</span>
                        </a>
                    </li>
                    <li class="@yield('category_active')">
                        <a href="{{ url('admin/category') }}" class="d-flex align-items-center" target="_self">
                            <i data-feather='circle'></i>
                            <span class="menu-title text-truncate">Event Category</span>
                        </a>
                    </li>
                    <li class="@yield('variation_active')">
                        <a href="{{ route('event-variation.variation') }}" class="d-flex align-items-center" target="_self">
                            <i data-feather='circle'></i>
                            <span class="menu-item text-truncate">Event Variations</span>
                        </a>
                    </li>
                    <li class="@yield('assign_active')">
                        <a href="{{route('assign-bus.index')}}" class="d-flex align-items-center" target="_self">
                            <i data-feather='circle'></i>
                            <span class="menu-item text-truncate">Event Assign Bus</span>
                        </a>
                    </li>
                    <li class="@yield('inventory_active')">
                        <a href="{{url('admin/event-inventory')}}" class="d-flex align-items-center" target="_self">
                            <i data-feather='circle'></i>
                            <span class="menu-item text-truncate">Event Shared Variation Inventory</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="javascript:void(0)" class="d-flex align-items-center" target="_self">
                    <i data-feather='users'></i>
                    <span class="menu-title text-truncate">Manage Users</span>
                </a>
                <ul class="menu-content">
                    <li class="@yield('users_active')">
                        <a href="{{ url('admin/users') }}" class="d-flex align-items-center" target="_self">
                            <i data-feather='circle'></i>
                            <span class="menu-item text-truncate">Users Lists</span>
                        </a>
                    </li>
                    <li class="@yield('attendee_active')">
                        <a href="{{ url('admin/attendee-list') }}" class="d-flex align-items-center" target="_self">
                            <i data-feather='circle'></i>
                            <span class="menu-item text-truncate">Passenger Lists</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item @yield('operator_active')">
                <a href="{{ url('admin/operator') }}" class="d-flex align-items-center" target="_self">
                    <i data-feather='user'></i>
                    <span class="menu-title text-truncate">Manage Operator</span>
                </a>
            </li>
            <li class="nav-item @yield('admin_request_active')">
                <a href="{{ url('admin/admin-request') }}" class="d-flex align-items-center" target="_self">
                    <i data-feather='send'></i>
                    <span class="menu-title text-truncate">Manage Request</span>
                </a>
            </li>
            <li class="nav-item @yield('route_active')">
                <a href="{{ url('admin/route') }}" class="d-flex align-items-center" target="_self">
                    <i data-feather='truck'></i>
                    <span class="menu-title text-truncate">Manage Route</span>
                </a>
            </li>

            <li class="nav-item @yield('county_active')">
                <a href="{{ url('admin/county') }}" class="d-flex align-items-center" target="_self">
                    <i data-feather='flag'></i>
                    <span class="menu-title text-truncate">County</span>
                </a>
            </li>
            <li class="nav-item @yield('pickup_active')">
                <a href="{{ url('admin/pickup-point') }}" class="d-flex align-items-center" target="_self">
                    <i data-feather='map-pin'></i>
                    <span class="menu-title text-truncate">Pickup Point</span>
                </a>
            </li>
            <li class="nav-item @yield('banner_active')">
                <a href="{{ url('admin/banner') }}" class="d-flex align-items-center" target="_self">
                    <i data-feather='image'></i>
                    <span class="menu-title text-truncate">Banner</span>
                </a>
            </li>
            <li class="nav-item @yield('coupon_active')">
                <a href="{{ url('admin/coupon') }}" class="d-flex align-items-center" target="_self">
                    <i data-feather='gift'></i>
                    <span class="menu-title text-truncate">Coupon</span>
                </a>
            </li>
            <li class="nav-item @yield('settings_active')">
              <a href="{{ url('admin/seo') }}" class="d-flex align-items-center" target="_self">
                  <i data-feather='settings'></i>
                  <span class="menu-title text-truncate">Settings</span>
              </a>
            </li>
            @elseif (Auth::user()->type==='Operator')
            <li class="nav-item">
                <a href="javascript:void(0)" class="d-flex align-items-center" target="_self">
                    <i data-feather='send'></i>
                    <span class="menu-title text-truncate">Manage Request</span>
                </a>
                <ul class="menu-content">
                    <li class="@yield('operator_request_active')">
                        <a href="{{ url('admin/operator-request') }}" class="d-flex align-items-center" target="_self">
                            <i data-feather='circle'></i>
                            <span class="menu-item text-truncate">Request List</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endif
            <li class="nav-item @yield('bus_active')">
                <a href="{{ url('admin/bus') }}" class="d-flex align-items-center" target="_self">
                    <i data-feather='truck'></i>
                    <span class="menu-title text-truncate">Manage Buses</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:void(0)" class="d-flex align-items-center" target="_self" onclick="logout()">
                    <i data-feather='log-out'></i>
                    <span class="menu-title text-truncate">Logout</span>
                </a>
                <form class="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                  @csrf
                </form>
            </li>
        </ul>
    </div>
</div>
<!-- END: Main Menu-->
