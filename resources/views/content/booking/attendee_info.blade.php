@extends('layouts/contentLayoutMaster')

@section('title', 'Attendee Info')

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

        <div class="card p-1">
            {{-- <div class="head-label">
                <h6 class="mb-0">County List</h6>
            </div> --}}
            <div class="card-header border-bottom">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb" style="padding: 0.1rem 0.1rem;">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Booking</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Info</li>
                    </ol>
                </nav>
                <div class="dt-action-buttons text-right">
                    <div class="dt-buttons d-inline-flex">
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title text-primary text-capitalize">Booking Info</h4>
                    <button class="btn btn-success btn-sm" id="edit_info" value="1">EDIT</button>
                </div>
                <div class="card-body">
                    <form name="ajax_form" method="post" action="{{ route('county.store') }}" enctype="multipart/form-data"
                        id="form_edit">
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="first-name-column">Created </label>
                                    <input type="text" id="first-name-column" class="form-control" placeholder="Created at" name="fname-column"
                                        value="{{ date('d-m-Y h:m:s', strtotime($booking->created_at)) }}" disabled>

                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="last-name-column">Total Amount</label>
                                    <input type="text" id="last-name-column" class="form-control"
                                        placeholder="Total Amount" name="lname-column"
                                        value="&euro; {{ $booking->total_amount }}" disabled>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="first-name-column">Booking Number </label>
                                    <input type="text" id="first-name-column" class="form-control"
                                        placeholder="Booking Number" name="fname-column"
                                        value="{{ $booking->order_id ? $booking->order_id : 'FT139783073' . $booking->id }}"
                                        disabled>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="first-name-column">Pickup Point SKU </label>
                                    <input type="text" id="first-name-column" class="form-control"
                                        placeholder="Pickup Point SKU" name="fname-column"
                                        value="{{ !empty($booking->route_sku[0]['sku_name']) ? $booking->route_sku[0]['sku_name'] : '' }}"
                                        disabled>
                                </div>
                            </div>
                            {{-- <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="first-name-column">Status</label>
                                    <select class="form-control" id="basicSelect" disabled>
                                        @if ($booking->status == 1)
                                            <option value="{{ $booking->status }}" selected>Active</option>
                                            <option value="0">Inactive</option>
                                        @else
                                            <option value="{{ $booking->status }}" selected>Inactive</option>
                                            <option value="0">Active</option>
                                        @endif
                                    </select>
                                </div>
                            </div> --}}
                            {{-- <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="last-name-column">Total Amount</label>
                                    <input type="text" id="last-name-column" class="form-control"
                                        placeholder="Total Amount" name="lname-column"
                                        value="&euro; {{ $booking->total_amount }}" disabled>
                                </div>
                            </div> --}}
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="city-column">Voucher </label>
                                    <input type="text" id="city-column" class="form-control" placeholder="Voucher"
                                        name="city-column" value="{{ $booking->coupon_name['promo_code'] }}" disabled>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="country-floating">Event Name</label>
                                    <select class="form-control" id="basicSelect" disabled>
                                        @if ($booking->product_name)
                                            @foreach ($booking->product_name as $product)
                                                @if ($booking->product_id == $product['id'])
                                                    <option value="{{ $product['id'] }}" selected>
                                                        {{ substr($product['name'], 0, 70) . '...' }}</option>
                                                @else
                                                    <option value="{{ $product['id'] }}">
                                                        {{ substr($product['name'], 0, 70) . '...' }}</option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="company-column">Pickup Point</label>
                                    <select class="form-control" id="basicSelect" disabled>
                                        @if ($booking->pickup_point)
                                            @foreach ($booking->pickup_point as $point)
                                                @if ($booking->pickup_id == $point['id'])
                                                    <option value="{{ $point['id'] }}" selected>
                                                        {{ substr($point['name'], 0, 70) . '...' }}</option>
                                                @else
                                                    <option value="{{ $point['id'] }}">
                                                        {{ substr($point['name'], 0, 70) . '...' }}</option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="email-id-column">No of Seats</label>
                                    <input type="email" id="email-id-column" class="form-control"
                                        name="email-id-column" placeholder="Email"
                                        value="{{ $booking->number_of_seats }}" disabled>
                                </div>
                            </div>
                            {{-- <div class="col-12">
                                <div class="form-group">
                                    <label for="email-id-column">Description</label>
                                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" placeholder="Ticket Description" disabled>{{ $booking->booking_info }}</textarea>
                                </div>
                            </div> --}}
                            <div class="col-12">
                                <button type="reset" class="btn btn-primary mr-1 waves-effect waves-float waves-light"
                                    disabled>Submit</button>
                                <button type="reset" class="btn btn-outline-secondary waves-effect"
                                    disabled>Reset</button>
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
                                <th> Attendee Name</th>
                                <th> Attendee number</th>
                                <th> Created</th>
                                <th> Actions</th>
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
                                        <td>{{ Str::upper($result['attendee_name']) }}</td>
                                        <td><i data-feather='phone-call'></i> &nbsp{{ $result['attendee_number'] }}</td>
                                        <td>{{ date('d-M-y', strtotime($result['created_at'])) }}</td>
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
                                                        data-title="Attendee"
                                                        data-url="{{ route('booking.edit', $result->id) }}"
                                                        data-action="{{ route('booking.update', $result->id) }}">
                                                        <i data-feather="edit-2" class="mr-50"></i>
                                                        <span>Edit</span>
                                                    </a>
                                                    {{-- <a class="dropdown-item deleteRecord" href="javascript:void(0);"
                                                        data-url="{{ route('bus.destroy', $result->id) }}">
                                                        <i data-feather="trash" class="mr-50"></i>
                                                        <span>Delete</span>
                                                    </a> --}}
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Modal to add new user starts-->
        <div class="modal modal-slide-in new-user-modal fade" id="modalAttendee">
            <div class="modal-dialog">
                <form class="add-new-user modal-content pt-0" name="ajax_form" method="post"
                    action="{{ route('county.store') }}" enctype="multipart/form-data">
                    @csrf
                    @method('post')
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                    <div class="modal-header mb-1">
                        <h5 class="modal-title" id="exampleModalLabel">Add Attendee</h5>
                    </div>
                    <div class="modal-body flex-grow-1">
                        <div class="form-group">
                            <label class="form-label" for="basic-icon-default-fullname">Attendee Name</label>
                            <input type="text" class="form-control dt-full-name" id="basic-icon-default-fullname"
                                placeholder="Enter Attendee Name" name="attendee_name" aria-label="John Doe"
                                aria-describedby="basic-icon-default-fullname2" />
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="basic-icon-default-fullname">Attendee Number</label>
                            <input type="text" class="form-control dt-full-name" id="basic-icon-default-fullname"
                                placeholder="Enter Attendee Number" name="attendee_number" aria-label="John Doe"
                                aria-describedby="basic-icon-default-fullname2" />
                        </div>
                        <button type="submit" class="btn btn-primary mr-1 data-submit">Submit</button>
                        <button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- Modal to add new user Ends-->
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
