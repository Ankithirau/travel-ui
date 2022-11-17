@extends('layouts/contentLayoutMaster')

@section('title', 'Operator')

@section('admin_request_active', 'active')

@section('vendor-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/toastr.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/animate/animate.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">

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
    <section id="multiple-column-form">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Send Request</h4>
                    </div>
                    <div class="card-body">
                        <form class="form" name="ajax_form" method="post" action="{{ route('admin.request_submit') }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('post')
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="eventSelect">Event</label>
                                        <select class="form-control getDate" id="eventSelect" name="product_id">
                                            <option value="" selected>Select Event</option>
                                            @foreach ($events as $item)
                                                <option value="{{ $item->id }}" data-date="{{ $item->date_concert }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="fp-default">Request Date</label>
                                        <select class="form-control" id="eventDate" name="request_date">
                                            <option value="" selected>Select Date</option>
                                        </select>
                                        {{-- <input type="text" id="fp-default"
                                            class="form-control flatpickr-basic flatpickr-input" placeholder="YYYY-MM-DD"
                                            style="background: white" name="request_date"> --}}
                                    </div>
                                </div>
                                {{-- <div class="col-md-3 col-12">
                                    <div class="form-group">
                                        <label for="operatorSelect">Request Time</label>
                                        <input type="text" id="pt-default" class="form-control pickatime" placeholder="1:00 AM" name="request_time"/>
                                    </div>
                                </div> --}}
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="operatorSelect">Operator</label>
                                        <select class="form-control" id="operatorSelect" data-url="{{ route('bus.list') }}"
                                            name="operator_id">
                                            <option value="" selected>Select Operator</option>
                                            @foreach ($operators as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5 col-12">
                                    <div class="form-group">
                                        <label for="busSelect">Bus</label>
                                        <select class="form-control" id="busSelect" name="bus_id">
                                            <option value="" selected>Select Bus</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-1 col-12 mt-2">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-icon btn-outline-success waves-effect"
                                            style="margin-top: 2px">
                                            <i data-feather='send'></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="basic-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <table class="table" id="datatable">
                        <thead>
                            <tr>
                                <th>Sno.</th>
                                <th>Event Name</th>
                                <th>Operator Name</th>
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
                                        <td> {{ $result->event_name }} </td>
                                        <td>
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
                                        </td>
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
                                        <td>
                                            <a href="javascript:;" class="item-edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-edit font-small-4">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7">
                                                    </path>
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z">
                                                    </path>
                                                </svg>
                                            </a>
                                            &nbsp
                                            <a href="javascript:;" class="item-edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-trash-2 font-small-4 mr-50">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path
                                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                    </path>
                                                    <line x1="10" y1="11" x2="10" y2="17">
                                                    </line>
                                                    <line x1="14" y1="11" x2="14" y2="17">
                                                    </line>
                                                </svg>
                                            </a>
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
    <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.date.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.time.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/pickadate/legacy.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
@endsection

@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/extensions/ext-component-sweet-alerts.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/extensions/ext-component-toastr.js')) }}"></script>
    <script src="{{ asset('js/scripts/common/common-action.js') }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/pickers/form-pickers.js')) }}"></script>
@endsection
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<script>
$(document).ready(function () {
    $('.getDate').change(function (e) { 
        e.preventDefault();
        var id=$(this).val();
        var row=$('option:selected', this).data('date');
        var result=row.split(',');
        var embabed='<option value="" selected>Select Date</option>';
        result.forEach(element => {
            var Cdate=new Date(element);
            var Curdate=+Cdate.getDate()+'-'+Cdate.toLocaleString('default', { month: 'long' })+'-'+Cdate.getFullYear();
            embabed+='<option value="">'+ Curdate +'</option>';
        });
        $('#eventDate').html(embabed);
    });
});
</script>