@extends('layouts/contentLayoutMaster')

@section('title', 'Manage Booking')

@section('booking_active', 'active')

@section('vendor-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/toastr.min.css')) }}">
@endsection

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/sweetalert2.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/extensions/ext-component-sweet-alerts.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-user.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/extensions/ext-component-toastr.css')) }}">
@endsection

@section('content')
    <!-- users list start -->
    <section class="app-user-list">
        <!-- users filter start -->
        <!-- users filter end -->

        <!-- list section start -->
        <div class="card">
            <div class="card-header border-bottom">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb" style="padding: 0.1rem 0.1rem;">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Booking</a></li>
                        <li class="breadcrumb-item active" aria-current="page">List</li>
                    </ol>
                </nav>
            </div>
            <div class="table-responsive">
                <div class="card">
                    <h5 class="card-header"></h5>
                    <form action="{{ route('booking.index') }}" method="GET">
                        <div class="d-flex justify-content-between align-items-center mx-50 row pt-0 pb-2">
                            <div class="col-md-4 user_role">
                                @csrf
                                @method('GET')
                                <label for="event_id">Filter By Event:</label>
                                <select id="event_id" class="form-control" name="event_id" onchange="this.form.submit()">
                                    <option value="" disabled selected>Select Event</option>
                                    {{-- <option value="all" {{ $event_id == 'all' ? 'selected' : '' }}> Select All </option> --}}
                                    @foreach ($products as $item)
                                        <option value="{{ $item->id }}" {{ $item->id == $event_id ? 'selected' : '' }}>
                                            {{ substr($item->name, 0, 50) . '...' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 user_plan">
                                <label for="event_id">Filter By Pickup Point:</label>
                                <select id="pickup_point" class="form-control text-capitalize mb-md-0 mb-2"
                                    name="pickup_point" onchange="this.form.submit()">
                                    <option value="" selected> Select Pickup Point </option>
                                    @foreach ($pick_point as $point)
                                        <option value="{{ $point->id }}" {{ $point->id == $pickup_point_id ? 'selected' : '' }}> {{ substr($point->name, 0, 50) . '...' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 user_status">
                                <label for="filter_status">Filter By Status:</label>
                                <select id="filter_status" name="filter_status"
                                    class="form-control text-capitalize mb-md-0 mb-2xx" onchange="this.form.submit()">
                                    <option value="" selected> Select Status </option>
                                    <option value="3" class="text-capitalize text-warning"
                                        {{ !empty($status) && $status == 3 ? 'selected' : '' }}>Pending</option>
                                    <option value="1" class="text-capitalize text-success"
                                        {{ !empty($status) && $status == 1 ? 'selected' : '' }}>Accpeted</option>
                                    <option value="4" class="text-capitalize text-danger"
                                        {{ !empty($status) && $status == 4 ? 'selected' : '' }}>Rejected</option>
                                    <option value="2" class="text-capitalize text-primary"
                                        {{ !empty($status) && $status == 2 ? 'selected' : '' }}>Completed</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- list section end -->
    </section>
    <!-- users list ends -->
    <section id="basic-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <table class="table" id="datatable">
                        <thead>
                            <tr>
                                <th> S.No.</th>
                                <th> Booking NO.</th>
                                <th> Customer</th>
                                <th> Pickup Point</th>
                                <th> Order Placed</th>
                                <th> Tickets</th>
                                <th> Total Amount</th>
                                <th> Payment Status</th>
                                {{-- <th> Actions</th> --}}
                            </tr>
                        </thead>
                        <tbody id="show_table" style="display: none">
                            @if (!empty($results))
                                @php
                                    $i = 1;
                                @endphp
                                @foreach ($results as $key => $result)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td><a
                                                href="{{ route('attendee.info', $result->id) }}">{{ $result->order_id ? $result->order_id : 'FT139783073' . $i++ }}</a>
                                        </td>
                                        <td class="text-capitalize">
                                            <div class="d-flex justify-content-left align-items-center">
                                                <div class="avatar-wrapper">
                                                    <div class="avatar @if($result->payment['status'] == 'completed') bg-light-success @elseif ($result->payment['status'] == 'in-progress') bg-light-warning @else bg-light-danger @endif mr-1">
                                                        <span
                                                            class="avatar-content">{{ substr($result->booking_fname, 0, 1) }}{{ strtoupper(substr($result->booking_lname, 0, 1)) }}</span>
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <a href="javascript:void(0)"
                                                        class="user_name text-truncate">
                                                        <span
                                                            class="font-weight-bold">{{ $result->booking_fname }}&nbsp{{ $result->booking_lname }}</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if (isset($result->sku['sku_name']))
                                                {{ $result->sku['sku_name'] }}
                                            @else
                                                <p> MGK22-3A-D1-CK1</p>
                                            @endif
                                        </td>
                                        <td>{{ date('d-M-y', strtotime($result->created_at)) }}</td>
                                        <td>{{ $result->number_of_seats }}</td>
                                        <td>&euro; {{ $result->total_amount }}</td>
                                        <td>
                                            @if ($result->payment['status'] == 'completed')
                                                <div class="badge badge-pill badge-light-success">Confirmed</div>
                                                <span class="badge rounded-pill bg-success"></span>
                                            @elseif ($result->payment['status'] == 'in-progress')
                                                <div class="badge badge-pill badge-light-warning">In-progress</div>
                                            @else
                                                <div class="badge badge-pill badge-light-danger">Failed</div>
                                            @endif
                                        </td>
                                        {{-- <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-sm dropdown-toggle hide-arrow"
                                                    data-toggle="dropdown">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-more-vertical">
                                                        <circle cx="12" cy="12" r="1"></circle>
                                                        <circle cx="12" cy="5" r="1"></circle>
                                                        <circle cx="12" cy="19" r="1"></circle>
                                                    </svg>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item editRecord"
                                                        href="{{ route('attendee.info', $result->id) }}">
                                                        <i data-feather="edit-2" class="mr-50"></i>
                                                        <span>Edit</span>
                                                    </a>
                                                    <a class="dropdown-item deleteRecord" href="javascript:void(0);"
                                                        data-url="{{ route('ticket.destroy', $result->id) }}">
                                                        <i data-feather="trash" class="mr-50"></i>
                                                        <span>Delete</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </td> --}}
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('vendor-script')
    {{-- Vendor js files --}}
    <script src="{{ asset(mix('vendors/js/tables/datatable/jquery.dataTables.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.bootstrap4.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.responsive.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/responsive.bootstrap4.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.checkboxes.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.buttons.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/jszip.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/pdfmake.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/vfs_fonts.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.html5.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.print.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.rowGroup.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>
@endsection

@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/extensions/ext-component-sweet-alerts.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/extensions/ext-component-toastr.js')) }}"></script>
    <script src="{{ asset('js/scripts/common/common-action.js') }}"></script>

@endsection
