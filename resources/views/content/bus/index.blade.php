@extends('layouts/contentLayoutMaster')

@section('title', 'Manage Bus')

@section('bus_active', 'active')

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

        {{-- {{ dd($results) }} --}}
        <div class="card p-1">
            {{-- <div class="head-label">
                <h6 class="mb-0">County List</h6>
            </div> --}}
            <div class="card-header border-bottom">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb" style="padding: 0.1rem 0.1rem;">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Bus</a></li>
                        <li class="breadcrumb-item active" aria-current="page">List</li>
                    </ol>
                </nav>
                @if (Auth::user()->type == 'Operator')
                    <div class="dt-action-buttons text-right">
                        <div class="dt-buttons d-inline-flex">
                            {{-- <button class="dt-button buttons-collection btn btn-outline-secondary dropdown-toggle mr-2"
                                tabindex="0" aria-controls="DataTables_Table_0" type="button" aria-haspopup="true">
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-share font-small-4 mr-50">
                                        <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path>
                                        <polyline points="16 6 12 2 8 6"></polyline>
                                        <line x1="12" y1="2" x2="12" y2="15"></line>
                                    </svg>
                                    Export
                                </span>
                            </button> --}}
                            <button class="dt-button create-new btn btn-primary" tabindex="0"
                                aria-controls="DataTables_Table_0" type="button" data-toggle="modal"
                                data-target="#modalBus">
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="feather feather-plus mr-50 font-small-4">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                    Add Bus
                                </span>
                            </button>
                        </div>
                    </div>
                @endif
            </div>
            
            <div class="table-responsive">
                <table class="table text-center" id="datatable">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Operator Name</th>
                            <th>Bus No.</th>
                            <th>Bus Capacity</th>
                            @if (Auth::user()->type !== 'Operator')
                                <th>Route Name</th>
                            @endif
                            <th>Status</th>
                            <th> Actions</th>
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
                                    <td>{{ $result->name }}</td>
                                    <td>{{ $result->bus_number }}</td>
                                    <td>{{ $result->capacity }}</td>
                                    @if (Auth::user()->type !== 'Operator')
                                        @if (isset($result->route_name))
                                            <td class="text-primary">{{ Str::ucfirst($result->route_name) }}</td>
                                        @else
                                            <td class="text-warning">No Route Assign</td>
                                        @endif
                                    @endif
                                    <td>
                                        <div class="custom-control custom-control-success custom-switch">
                                            <input type="checkbox" @if ($result->status == 1) checked @endif
                                                class="custom-control-input updateStatus"
                                                id="customSwitch{{ $result->id }}" data-status_id="{{ $result->id }}"
                                                data-url="{{ route('bus.status', $result->id) }}" />
                                            <label class="custom-control-label"
                                                for="customSwitch{{ $result->id }}"></label>
                                        </div>
                                    </td>
                                    <td>
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
                                                <a class="dropdown-item editRecord" href="javascript:void(0);"
                                                    data-title="Bus" data-url="{{ route('bus.edit', $result->id) }}"
                                                    data-action="{{ route('bus.update', $result->id) }}">
                                                    <i data-feather="edit-2" class="mr-50"></i>
                                                    <span>Edit</span>
                                                </a>
                                                <a class="dropdown-item deleteRecord" href="javascript:void(0);"
                                                    data-url="{{ route('bus.destroy', $result->id) }}">
                                                    <i data-feather="trash" class="mr-50"></i>
                                                    <span>Delete</span>
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <!-- Modal to add new user starts-->
            <div class="modal modal-slide-in new-user-modal fade" id="modalBus">
                <div class="modal-dialog">
                    <form class="add-new-user modal-content pt-0" name="ajax_form" method="post"
                        action="{{ route('bus.store') }}" enctype="multipart/form-data">
                        @csrf
                        @method('post')
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                        <div class="modal-header mb-1">
                            <h5 class="modal-title" id="exampleModalLabel">Add Buses</h5>
                        </div>
                        <div class="modal-body flex-grow-1">
                            {{-- <div class="form-group">
                                <label for="basicSelect">Operator Name</label>
                                <select class="form-control" id="basicSelect" name="operator_name">
                                    <option value="" selected>Select Operator</option>
                                    @foreach ($operators as $operator)
                                        <option value="{{ $operator->id }}">{{ $operator->name }}</option>
                                    @endforeach
                                </select>
                            </div> --}}
                            <div class="form-group">
                                <label class="form-label" for="basic-icon-default-fullname">Bus Number</label>
                                <input type="text" name="bus_number" class="form-control dt-full-name"
                                    id="basic-icon-default-fullnamewertyuio" placeholder="Enter Bus Number"
                                    aria-label="John Doe" aria-describedby="basic-icon-default-fullname2" />
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="basic-icon-default-fullname">Bus Registration
                                    Number</label>
                                <input type="text" class="form-control dt-full-name" id="basic-icon-default-fullname"
                                    placeholder="Enter Registration Number" name="bus_registration_number"
                                    aria-label="John Doe" aria-describedby="basic-icon-default-fullname2" />
                            </div>
                            <div class="form-group">
                                <label for="basicSelect">Bus Type</label>
                                <select class="form-control" id="basicSelect" name="bus_type">
                                    <option value="" selected>Select Bus Type</option>
                                    <option value="AC">AC</option>
                                    <option value="Non-AC">No AC</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="basic-icon-default-fullname">Bus Capacity</label>
                                <input type="number" class="form-control dt-full-name"
                                    id="basic-icon-default-fullnamewerty" placeholder="Enter Bus Capacity"
                                    name="capacity" aria-label="John Doe"
                                    aria-describedby="basic-icon-default-fullname2" />
                            </div>
                            @if (Auth::user()->type !== 'Operator')
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="busRoute">Bus Route</label>
                                        <select class="form-control" id="busRoute" name="route_id" required>
                                            <option value="" selected>Select Bus Route</option>
                                            @forelse ($routes as $route)
                                                <option value="{{ $route->id }}">
                                                    {{ Str::ucfirst($route->route_name) }}</option>
                                            @empty
                                                <option>No Route Found</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                            @endif
                            <div class="form-group">
                                <label class="form-label" for="basic-icon-default-fullname">Driver Username</label>
                                <input type="text" class="form-control dt-full-name"
                                    id="basic-icon-default-fullnamewerty" placeholder="Enter Driver Username"
                                    name="dusername" aria-label="John Doe"
                                    aria-describedby="basic-icon-default-fullname2" />
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="basic-icon-default-fullname">Driver Password</label>
                                <input type="password" class="form-control dt-full-name"
                                    id="basic-icon-default-fullnamewerty" placeholder="Enter Driver Password"
                                    name="dpassword" aria-label="John Doe"
                                    aria-describedby="basic-icon-default-fullname2" />
                            </div>
                            {{-- <div class="form-group">
                                <label for="basicSelect">Bus Status</label>
                                <select class="form-control" id="basicSelect" name="status">
                                    <option value="" selected>Select Bus Status</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inacitve</option>
                                </select>
                            </div> --}}
                            <button type="submit" class="btn btn-primary mr-1 data-submit">Submit</button>
                            <button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Modal to add new user Ends-->
        </div>
        <!-- list section end -->
    </section>
    <!-- users list ends -->
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
