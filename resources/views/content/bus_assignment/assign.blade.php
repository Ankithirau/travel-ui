@extends('layouts/contentLayoutMaster')

@section('title', 'Bus Assignment')

@section('assign_active', 'active')

@section('vendor-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/toastr.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">

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
                                            <option value="{{ $collection->id }}"
                                                {{ $collection->id == (isset($id) ? $id : 0) ? 'selected' : '' }}>
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
        </div>
        <!-- list section end -->
    </section>

    @if ($status !== 0)

        <section id="input-group-dropdown">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title"></h4>
                        </div>
                        <div class="card-body">
                            {{-- <p></p> --}}
                            <form action="{{ route('assign-bus.index') }}" method="get">
                                <input type="hidden" name="id" value="{{ !empty($id) ? $id : null }}">
                                <div class="row">
                                    <div class="col-md-5 col-12 mb-1">
                                        <div class="form-group">
                                            <label for="selectRoute">Filter By Route :</label>
                                            <select class="form-control" id="selectRoute" name="route_id" required
                                                name="route_id">
                                                <option value="" selected>Select Route</option>
                                                @if (isset($routes))
                                                    @foreach ($routes as $route)
                                                        <option value="{{ $route->id }}"
                                                            {{ $route->id == $route_id ? 'selected' : '' }}>
                                                            {{ $route->route_name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-5 col-12 mb-1">
                                        <div class="form-group">
                                            <label for="selectDate">Filter By Concert Date :</label>
                                            <select class="form-control" id="selectDate" name="concert_date" required>
                                                <option value="" selected>Select Concert Date</option>
                                                @foreach ($items as $date)
                                                    @if (!empty($id))
                                                        @if ($date->id == $id)
                                                            @foreach (explode(', ', $date->date_concert) as $item)
                                                                <option value="{{ $item }}"
                                                                    {{ $item == $date_concert ? 'selected' : '' }}>
                                                                    {{ date('d-M-y', strtotime($item)) }}</option>
                                                            @endforeach
                                                        @endif
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-12 mb-1">
                                        <div class="form-group mt-2">
                                            <button type="submit"
                                                class="btn btn-icon btn-primary waves-effect waves-float waves-light"
                                                style="margin-top: 2px">
                                                <i data-feather='arrow-right-circle'></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            @if (!empty($bus_lists) && count($booking) > 0)
                                <div class="row">
                                    <div class="col-md-5 col-12 mb-1">
                                        <div class="form-group">
                                            <label for="selectRoute">Assign Bus to Passenger :</label>
                                            <select class="form-control" id="selectbus" name="bus_id" required
                                                name="route_id">
                                                <option value="" selected>Select Bus</option>
                                                @foreach ($bus_lists as $list)
                                                    @if (session()->has('bus_id') && session()->get('bus_id') == $list->id)
                                                        <option value="{{ $list->id }}" class="text-primary" selected>
                                                            {!! $list->bus_number !!}&nbsp;&nbsp;Capacity-{!! $list->remaining_capacity !!}
                                                        </option>
                                                    @else
                                                        <option value="{{ $list->id }}" class="text-primary">
                                                            {!! $list->bus_number !!}&nbsp;&nbsp;Capacity-{!! $list->remaining_capacity !!}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>
                                    <div class="col-md-2 col-12 mb-1">
                                        <div class="form-group mt-2">
                                            <button type="submit"
                                                class="btn btn-icon btn-primary waves-effect waves-float waves-light btn-val"
                                                style="margin-top: 2px" data-product_id="{{ request()->id }}">
                                                Assign Bus &nbsp;<i data-feather='arrow-right-circle'></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section id="multiple-column-form">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        @if (!empty($booking) && count($booking) > 0)
                            <div class="card-header">
                                <h4 class="card-title text-primary">Bus Assignment</h4>
                                <p class="mr-3"> <span class="text-primary h5"> Total Seat: &nbsp;&nbsp;</span><span class="badge badge-primary">{{ array_sum($booking_count) }}</span></p>
                            </div>
                            <div class="card-body">
                                <form name="ajax_form" method="post" action="" enctype="multipart/form-data"
                                    novalidate class="pt-2" id="myForm">
                                    @csrf
                                    @method('POST')
                                    <div class="table-responsive">
                                        <div class="table-wrapper-scroll-y my-custom-scrollbar">
                                            <table class="table w-100 table-fixed font-small-4 text-center" id="get_val">
                                                <thead class="text-center">
                                                    <tr>
                                                        @if (!empty($bus_lists))
                                                            <th style="position: sticky;top: 0">
                                                                <div class="custom-control custom-checkbox selectAll">
                                                                    <input type="checkbox" class="custom-control-input"
                                                                        id="selectAllCheck" />
                                                                    <label class="custom-control-label pl-25"
                                                                        for="selectAllCheck"></label>
                                                                </div>
                                                            </th>
                                                        @endif
                                                        <th style="position: sticky;top: 0">Sno</th>
                                                        <th style="position: sticky;top: 0">Customer Name</th>
                                                        {{-- <th style="position: sticky;top: 0">Pickup Point</th> --}}
                                                        <th style="position: sticky;top: 0">Seat Booked</th>
                                                        <th style="position: sticky;top: 0">Assign Status</th>
                                                        @if (!empty($bus_lists))
                                                            <th style="position: sticky;top: 0">Bus Lists</th>
                                                        @endif
                                                        <th style="position: sticky;top: 0">Concert Date</th>
                                                        @if (!empty($bus_lists))
                                                            <th style="position: sticky;top: 0">Update</th>
                                                        @endif
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $i = 1;
                                                        $num = 0;
                                                    @endphp
                                                    @foreach ($booking as $item)
                                                        <tr>
                                                            @if (!empty($bus_lists))
                                                                <td class="cell-1" data-toggle="collapse"
                                                                    data-target="#demo{{ $i }}">
                                                                    <div
                                                                        class="custom-control custom-checkbox checkBoxClass">
                                                                        <input type="checkbox"
                                                                            class="custom-control-input checkboxall ml-5"
                                                                            id="customCheck{{ $i }}"
                                                                            value="{{ $item->id }}" name="type" />
                                                                        <label class="custom-control-label"
                                                                            for="customCheck{{ $i }}"></label>
                                                                    </div>
                                                                </td>
                                                            @endif
                                                            <td style="padding: 1.01rem 1rem;">
                                                                {{ $i++ }}
                                                            </td>
                                                            <td style="padding: 1.01rem 1rem;">
                                                                {{-- <input type="hidden" name="passenger_name[]"
                                                                    value="{{ $item->booking_fname . ' ' . $item->booking_lname }}"> --}}
                                                                {{ $item->booking_fname . ' ' . $item->booking_lname }}
                                                                {{-- <input type="hidden" name="pickup_point_id[]"
                                                                    value="{{ $item->pickup_id }}">
                                                                <input type="hidden" name="product_id[]"
                                                                    value="{{ $item->product_id }}"> --}}
                                                            </td>
                                                            {{-- <td style="padding: 1.01rem 1rem;">
                                                                <input type="hidden" name="pickup_point_id[]"
                                                                    value="{{ $item->pickup_id }}">
                                                                <input type="hidden" name="product_id[]"
                                                                    value="{{ $item->product_id }}">
                                                                {{ $item->pickup_point_id }}
                                                            </td> --}}
                                                            <td style="padding: 1.01rem 1rem;">
                                                                <span
                                                                    class="badge badge-light-success badge-pill font-weight-normal">{{ $item->number_of_seats }}</span>
                                                            </td>
                                                            <td style="padding: 1.01rem 1rem;">
                                                                @if ($item->assign_status == 1)
                                                                    <div class="badge badge-pill badge-light-success">
                                                                        Assigned</div>
                                                                @else
                                                                    <div class="badge badge-pill badge-light-warning">Not
                                                                        Assigned</div>
                                                                @endif
                                                            </td>
                                                            @if (!empty($bus_lists))
                                                                <td style="padding: 1.01rem 1rem;" class="w-25">
                                                                    <select class="form-control"
                                                                        id="bus-{{ $i - 1 }}"
                                                                        style="width: 223px !important">
                                                                        <option value="" selected>Select Bus</option>
                                                                        @foreach ($bus_lists as $list)
                                                                            <option value="{{ $list->id }}"
                                                                                {{ $item->bus_flag_id == $list->id ? 'selected' : '' }}
                                                                                class="text-primary">
                                                                                {!! $list->bus_number !!}&nbsp;&nbsp;Capacity-{!! $list->remaining_capacity !!}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                            @endif
                                                            <td style="padding: 1.01rem 1rem;">
                                                                {{-- <input type="hidden" name="schedule_date[]"
                                                                    value="{{ $item->date_concert }}"> --}}
                                                                {{ date('d-M-Y', strtotime($item->date_concert)) }}
                                                            </td>
                                                            @if (!empty($bus_lists))
                                                                <td style="padding: 1.01rem 1rem;">
                                                                    <button type="button"
                                                                        class="btn btn-icon btn-icon rounded-circle btn-flat-success waves-effect btn-update"
                                                                        data-id={{ $i - 1 }}
                                                                        data-url="{{ route('assign.user') }}"
                                                                        data-booking_id="{{ $item->id }}"
                                                                        data-product_id="{{ $item->product_id }}">
                                                                        <i data-feather='arrow-up-circle'></i>
                                                                    </button>
                                                                </td>
                                                            @endif
                                                        </tr>
                                                        @php
                                                            $j = 1;
                                                        @endphp
                                                        @foreach ($item->info as $val)
                                                            <tr id="demo{{ $i - 1 }}"
                                                                class="collapse cell-1 row-child text-primary text-center">
                                                                {{-- <td class=""></td> --}}
                                                                <td class="text-center mr-5">&#x2022;</td>
                                                                <td colspan="2" class="ml-2 text-center">
                                                                    <input type="hidden" name="attendee_name[]"
                                                                        value="{{ $val->attendee_name }}">
                                                                    <input type="hidden" name="parent_id[]"
                                                                        value="{{ $item->id }}">
                                                                    {{ $val->attendee_name }}
                                                                </td>
                                                                <td colspan="3" class="text-center">
                                                                    {{ $item->pickup_point_id }}</td>
                                                                <td class="text-center" colspan="2">
                                                                    {{ $val->attendee_number }}</td>
                                                                {{-- <td colspan="2">
                                                                    {{ date('d-M-Y', strtotime($item->date_concert)) }}
                                                                </td> --}}
                                                            </tr>
                                                        @endforeach
                                                    @endforeach
                                                    @php
                                                        $num++;
                                                    @endphp
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @else
                            <p class="text-danger p-1 text-center mt-1">No Record Found</p>
                        @endif
                    </div>
                </div>
            </div>
        </section>

    @endif
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
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>

@endsection

@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/extensions/ext-component-sweet-alerts.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/extensions/ext-component-toastr.js')) }}"></script>
    <script src="{{ asset('js/scripts/common/common-action.js') }}"></script>
    <script src="{{ asset(mix('js/scripts/components/components-collapse.js')) }}"></script>
@endsection
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    $(document).ready(function() {

        $('.btn-val').click(function(e) {

            var $id = $('#selectbus').val();

            var product_id = $(this).data('product_id');

            var route_id = $("#selectRoute option:selected").val();

            $(".has_error").remove();

            let booking_id = [];

            $("input:checkbox[name=type]:checked").each(function() {
                booking_id.push($(this).val());
            });

            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                },
                url: `{{ route('assign.store') }}`,
                data: {
                    'bus_id': $id,
                    'booking_id': booking_id,
                    'route_id': route_id,
                    'product_id': product_id,
                },
                beforeSend: function() {
                    // $('.btn-val').attr('disabled', true).text('Please Wait...');
                },
                success: function(response) {
                    var data = JSON.parse(response)
                    if (data.status == 422) {
                        $("#selectbus").after(
                            '<div class="text-danger has_error">' +
                            data.msg +
                            "</div>"
                        );
                    } else {
                        showSuccess(data.msg);
                        location.reload();
                    }

                }
            }).fail(function(response, status, error) {
                if (response.responseJSON.errors.bus_id !== undefined && response.responseJSON
                    .errors.bus_id !== "") {
                    $("#selectbus").after(
                        '<div class="text-danger has_error">' +
                        response.responseJSON.errors.bus_id +
                        "</div>"
                    );
                } else if (response.responseJSON.errors.booking_id !== undefined) {
                    $("#selectbus").after(
                        '<div class="text-danger has_error">' +
                        response.responseJSON.errors.booking_id +
                        "</div>"
                    );
                }
            });
        });

        $('.btn-update').click(function(e) {

            $(".has_error").remove();

            let id = $(this).data('id');

            let url = $(this).data('url');

            let booking_id = $(this).data('booking_id');

            let product_id = $(this).data('product_id');

            var route_id = $("#selectRoute option:selected").val();

            $('#customCheck' + id).prop('checked', true);

            let bus_id = $('#bus-' + id).find(":selected").val();

            $.ajax({
                type: "GET",
                url: url,
                data: {
                    'bus_id': bus_id,
                    'booking_id': booking_id,
                    'route_id': route_id,
                    'product_id': product_id
                },
                success: function(response) {
                    var data = JSON.parse(response)
                    if (data.status == 422) {
                        $("#bus-" + id).after(
                            '<div class="text-danger has_error">' +
                            data.msg +
                            "</div>"
                        );
                    } else {
                        showSuccess(data.msg);
                        location.reload();
                    }
                }
            });
        });

        $("#selectAllCheck").click(function() {
            // localStorage.setItem("mytime", Date.now());
            // console.log(localStorage.getItem("mytime"));
            if (this.checked) {
                $('.checkboxall').each(function() {
                    $(".checkboxall").prop('checked', true);
                })
            } else {
                $('.checkboxall').each(function() {
                    $(".checkboxall").prop('checked', false);
                })
            }
        });
    });
</script>
