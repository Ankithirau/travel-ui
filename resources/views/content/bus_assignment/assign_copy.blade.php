@extends('layouts/contentLayoutMaster')

@section('title', 'Bus Assignment')

@section('assign_active', 'active')

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
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/extensions/ext-component-toastr.css')) }}">
@endsection

@section('content')
    <!-- users list start -->
    <section class="app-user-list">
        <!-- users filter start -->
        <!-- users filter end -->
        <!-- list section start -->
        <div class="card p-1">
            <div class="card-header border-bottom">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb" style="padding: 0.1rem 0.1rem;">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Bus Assignment</a></li>
                        <li class="breadcrumb-item active" aria-current="page">List</li>
                    </ol>
                </nav>
            </div>
            <div>
                <form action="{{ route('assign-bus.index') }}" method="get">
                    @csrf
                    @method('get')
                    <div class="row mt-1">
                        <div class="col-sm-6">
                            <div class="input-group input-group-merge">
                                <select class="form-control variation" id="basicSelect" name="id">
                                    <option value="" selected>Select Event</option>
                                    @if ($items)
                                        @foreach ($items as $collection)
                                            <option value="{{ $collection->id }}" {{($collection->id==((isset($id))?$id:0) )?"selected":""}}>
                                                {{ substr($collection->name, 0, 70) . '...' }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-0">
                            <div class="input-group input-group-merge">
                                <input type="submit" class="btn btn-primary waves-effect waves-float waves-light"
                                    value="Submit">
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            @if (!empty($points))
                <div class="dt-action-buttons">
                    <div class="dt-buttons d-inline-flex">
                        <div class="card-body">
                            <div class="text-left text-wrap">
                                <span>
                                    <label for="" class="font-weight-bold text-dark">Event Name :</label>
                                    <h4 class="text-primary">{{ $product_name }}</h4>
                                </span>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                  <div class="form-row">
                                    <div class="col-lg-5">
                                      <label></label>
                                      <a href="{{ route('assign.show', $id) }}"
                                            class="btn btn-flat-primary waves-effect form-control">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-check mr-25">
                                                <polyline points="20 6 9 17 4 12"></polyline>
                                            </svg>
                                            <span><small> Bus Assign List </small></span>
                                        </a>
                                    </div>
                                    <div class="col-lg-3">
                                      <label>Filter by Route:</label>
                                        <select class="form-control" aria-label="Default select example" name="route_id"
                                            id="route">
                                            <option value="all">All</option>
                                            @if ($routes)
                                                @foreach ($routes as $route)
                                                    <option value="{{ $route->id }}" >{{ $route->route_name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        {{-- {{print_r($routes)}} --}}
                                    </div>
                                    <div class="col-lg-4 pl-3">
                                      <label class="mb-1">Total Booking:</label>
                                      <p class="pl-2 ml-1 text-success h4" id="set_total">{{$total_booking}}</p>
                                    </div>
                                    
                                  </div>
                                </div>
                            </div>
                            {{-- <div class="row">
                                <div class="form-row mb-1 mt-1">
                                    <div class="col-lg-3">
                                        <label></label>
                                        <a href="{{ route('assign.show', $id) }}"
                                            class="btn btn-flat-success waves-effect">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-check mr-25">
                                                <polyline points="20 6 9 17 4 12"></polyline>
                                            </svg>
                                            <span><small> Bus Assign List </small></span>
                                        </a>
                                    </div>
                                    <div class="col-lg-3 mt-1">
                                        <label>Filter by Route:</label>
                                        <select class="form-control" aria-label="Default select example" name="route_id"
                                            id="route">
                                            <option value="all">All</option>
                                            @if ($routes)
                                                @foreach ($routes as $route)
                                                    <option value="{{ $route->id }}" >{{ $route->route_name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-lg-3 mt-1">
                                        <label>Filter by Route:</label>
                                        <select class="form-control" aria-label="Default select example" name="route_id"
                                            id="route">
                                            <option value="all">All</option>
                                            @if ($routes)
                                                @foreach ($routes as $route)
                                                    <option value="{{ $route->id }}" >{{ $route->route_name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
                
                <form name="ajax_form" method="post" action="{{ route('product.add_variation_by_form') }}"
                    enctype="multipart/form-data" novalidate>
                    @csrf
                    @method('POST')
                    <div class="table-responsive" id="insert_btn">

                        <div class="table-wrapper-scroll-y my-custom-scrollbar">
                            <table class="table w-auto" id="get_val">
                                <thead>
                                    <tr>
                                        <th>Sno</th>
                                        <th>County</th>
                                        <th>Pickup Point</th>
                                        <th>Seat Booked</th>
                                        <th id="add_th">Concert Date</th>
                                        <th id="route_head">Route</th>
                                        <th>Allot Bus</th>
                                        <th>Update</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                        $num = 0;
                                    @endphp
                                    {{-- {{$total_booking}} --}}
                                    @foreach ($points as $collections)
                                        @foreach ($collections as $item)
                                            {{-- @if ($item->county_name) --}}
                                                <tr>
                                                    <td>{{ $i++ }}</td>
                                                    <td>
                                                        <input type="hidden" value="{{$total_booking}}" id="total_booking">
                                                        {{ $item->county_name }}
                                                        <input type="hidden" name="counties_id[]" class="counties_id"
                                                            value="{{ $item->counties_id }}" id="counties_id">
                                                        <input type="hidden" name="product_id" class="product_id"
                                                            value="{{ $item->product_id }}" id="product_id"
                                                            data-url="{{ route('assign-bus.index') }}">
                                                    </td>
                                                    <td>
                                                        {{ $item->name }}
                                                        <input type="hidden" name="pickup_point_id[]"
                                                            class="pickup_point_id" value="{{ $item->id }}"
                                                            id="pickup_point_id">
                                                    </td>
                                                    {{ $item->ss }}
                                                    <td>
                                                        {{ $item->seat_count }}
                                                        <input type="hidden" name="seat_count[]" class="seat_count"
                                                            value="{{ $item->seat_count }}" id="seat_count">
                                                    </td>
                                                    <td>{{ $item->date_concert }}
                                                        <input type="hidden" name="date_concert[]" class="date_concert"
                                                            id="date_concert_{{ $item->id }}"
                                                            value="{{ $item->date_concert }}">
                                                        <div
                                                            class="date_concert {{ $num }} text-danger error-inline">
                                                        </div>
                                                    </td>
                                                    @if ($item->routes)
                                                        <td>
                                                            <select name="routes[]" class="form-control routes"
                                                                id="route_name_{{ $item->id }}">
                                                                <option value="" selected>Select Route</option>
                                                                @foreach ($item->routes as $route)
                                                                    <option value="{{ $route['route_id'] }}">
                                                                        {{ trim($route['route_name']) }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select name="buses[]" class="form-control buses"
                                                                id="buses_{{ $item->id }}">
                                                                <option value="" selected>Select Bus</option>
                                                                @foreach ($buses as $bus)
                                                                    <option value="{{ $bus->id }}">
                                                                        {{ $bus->bus_number }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <div
                                                                class="buses {{ $num }} text-danger error-inline">
                                                            </div>
                                                        </td>
                                                    @else
                                                        <td colspan="2">
                                                            <p class="text-capitalize text-warning text-center">no route
                                                                assign
                                                                for
                                                                this pickup point</p>
                                                        </td>
                                                    @endif
                                                    <td>
                                                        @if ($item->routes)
                                                            <button type="button"
                                                                class="btn btn-icon btn-icon rounded-circle btn-flat-success waves-effect schedule_update"
                                                                data-action="{{ route('schedule-bus.add_schedule', isset($item->id) ? $item->id : 0) }}">
                                                                <i data-feather='arrow-up-circle'></i>
                                                            </button>
                                                        @else
                                                            <button type="button"
                                                                class="btn btn-icon btn-icon rounded-circle btn-flat-danger waves-effect">
                                                                <i data-feather='arrow-down-circle'></i>
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            {{-- @endif --}}
                                        @endforeach
                                        @php
                                            $num++;
                                        @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            @endif

            <!-- Modal to add new user starts-->
                {{-- <div class="modal modal-slide-in new-user-modal fade" id="modals-slide-in">
                    <div class="modal-dialog">
                        <form class="add-new-user modal-content pt-0" name="ajax_form" method="post"
                            action="{{ route('county.store') }}" enctype="multipart/form-data">
                            @csrf
                            @method('post')
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                            <div class="modal-header mb-1">
                                <h5 class="modal-title" id="exampleModalLabel">Add County</h5>
                            </div>
                            <div class="modal-body flex-grow-1">
                                <div class="form-group">
                                    <label class="form-label" for="basic-icon-default-fullname">County Name</label>
                                    <input type="text" class="form-control dt-full-name" id="basic-icon-default-fullname"
                                        placeholder="Enter County Name" name="name" aria-label="John Doe"
                                        aria-describedby="basic-icon-default-fullname2" />
                                </div>
                                <button type="submit" class="btn btn-primary mr-1 data-submit">Submit</button>
                                <button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div> --}}
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
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.buttons.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.bootstrap4.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>

@endsection

@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/extensions/ext-component-sweet-alerts.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/extensions/ext-component-toastr.js')) }}"></script>
    <script src="{{ asset('js/scripts/common/common-action.js') }}"></script>

@endsection
