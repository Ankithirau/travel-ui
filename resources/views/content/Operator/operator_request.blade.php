@extends('layouts/contentLayoutMaster')

@section('title', 'Operator')

@section('operator_request_active', 'active')

@section('vendor-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/toastr.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/animate/animate.min.css')) }}">

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

        <div class="card p-1">
            {{-- <div class="head-label">
                <h6 class="mb-0">County List</h6>
            </div> --}}
            <div class="card-header border-bottom">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb" style="padding: 0.1rem 0.1rem;">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Operator</a></li>
                        <li class="breadcrumb-item active" aria-current="page">List</li>
                    </ol>
                </nav>
            </div>

            <div class="table-responsive">
                <div class="card">
                    <h5 class="card-header">Search Filter</h5>
                    <form action="{{ route('operator.request') }}" method="GET">
                        <div class="d-flex justify-content-between align-items-center mx-50 row pt-0 pb-2">
                            <div class="col-md-4 user_role">
                                @csrf
                                @method('GET')
                                <select id="filter_event" class="form-control text-capitalize mb-md-0 mb-2"
                                    data-url="{{ route('operator.request') }}" onchange="this.form.submit()"
                                    name="event_id">
                                    <option value="" selected>Select Event</option>
                                    @foreach ($events as $item)
                                        <option value="{{$item->id}}" class="text-capitalize" {{!empty($status_event) && $status_event==$item->id ? "selected":"" }}>{{ substr($item->name, 0, 50) . '...' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 user_plan">
                                <select id="filter_date" class="form-control text-capitalize mb-md-0 mb-2"
                                    name="filter_date" onchange="this.form.submit()">
                                    <option value="" selected> Select Date </option>
                                    @for ($m = 1; $m <= 12; $m++)
                                        <option value="{{ date('Y') . '-' . str_pad($m, 2, '0', STR_PAD_LEFT) }}"
                                            class="text-capitalize" {{!empty($status_date) && $status_date==date('Y') . '-' . str_pad($m, 2, '0', STR_PAD_LEFT)  ? "selected":"" }}>
                                            {{ date('F', mktime(0, 0, 0, $m, 1, date('Y'))) . '  ' . date('Y') }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-4 user_status">
                                <select id="filter_status" name="status" class="form-control text-capitalize mb-md-0 mb-2xx" onchange="this.form.submit()">
                                    
                                    <option value="" selected> Select Status </option>
                                    <option value="3" class="text-capitalize text-warning" {{!empty($status) && $status==3 ? "selected":"" }}>Pending</option>
                                    <option value="1" class="text-capitalize text-success" {{!empty($status) && $status==1 ? "selected":"" }}>Accpeted</option>
                                    <option value="4" class="text-capitalize text-danger" {{!empty($status) && $status==4 ? "selected":"" }}>Rejected</option>
                                    <option value="2" class="text-capitalize text-primary" {{!empty($status) && $status==2 ? "selected":"" }}>Completed</option>
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
                                <th>Sno.</th>
                                <th>Event Name</th>
                                {{-- <th>Operator Name</th> --}}
                                <th>Bus Number</th>
                                <th>Requested Date</th>
                                <th>Request Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="show_table" style="display: none">
                            @if (!empty($results))
                                @php
                                    $i = 1;
                                @endphp
                                @foreach ($results as $result)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td class="w-50">
                                            <img src="{{ asset('uploads/event_image') . '/' . $result->image }}"
                                                class="mr-75" height="20" width="20" alt="">
                                            <span class="font-weight-normal"
                                                style="width: 150px">{{ $result->event_name }}</span>
                                        </td>
                                        {{-- <td>
                                            <div class="d-flex justify-content-left align-items-center">
                                                <div class="avatar  bg-light-warning  mr-1"><span
                                                        class="avatar-content">IT</span>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span
                                                        class="emp_name text-truncate font-weight-bold">{{ $result->operator_name }}
                                                    </span>
                                                    <small class="emp_post text-truncate text-muted">Operator</small>
                                                </div>
                                            </div>
                                        </td> --}}
                                        <td>{{ $result->bus_number }}</td>
                                        <td>{{ date('d-M-Y', strtotime($result->request_date)) }}</td>
                                        <td>
                                            @if ($result->request_status == 'accepted')
                                                <span class="badge badge-pill  badge-light-success">
                                                    {{ Str::ucfirst($result->request_status) }}
                                                </span>
                                            @elseif ($result->request_status == 'completed')
                                                <span class="badge badge-pill  badge-light-primary">
                                                    {{ Str::ucfirst($result->request_status) }}
                                                </span>
                                            @elseif ($result->request_status == 'pending')
                                                <span class="badge badge-pill  badge-light-warning">
                                                    {{ Str::ucfirst($result->request_status) }}
                                                </span>
                                            @else
                                                <span class="badge badge-pill  badge-light-danger">
                                                    {{ Str::ucfirst($result->request_status) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="">
                                            @if ($result->request_status !== 'accepted' &&
                                                $result->request_status !== 'rejected' &&
                                                $result->request_status !== 'completed')
                                                <button type="button"
                                                    class="btn btn-icon btn-sm btn-outline-success waves-effect"
                                                    id="send_request" value="1"
                                                    data-url="{{ route('operator.request_submit', $result->id) }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-check">
                                                        <polyline points="20 6 9 17 4 12"></polyline>
                                                    </svg>
                                                </button>
                                                <button type="button"
                                                    class="btn btn-icon btn-sm btn-outline-danger waves-effect"
                                                    id="send_request" value="4"
                                                    data-url="{{ route('operator.request_submit', $result->id) }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-x">
                                                        <line x1="18" y1="6" x2="6"
                                                            y2="18"></line>
                                                        <line x1="6" y1="6" x2="18"
                                                            y2="18"></line>
                                                    </svg>
                                                </button>
                                            @endif
                                        </td>
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
    <script src="{{ asset(mix('vendors/js/extensions/polyfill.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>
@endsection

@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/extensions/ext-component-sweet-alerts.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/extensions/ext-component-toastr.js')) }}"></script>
    <script src="{{ asset('js/scripts/common/common-action.js') }}"></script>
@endsection
