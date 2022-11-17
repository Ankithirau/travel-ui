@extends('layouts/contentLayoutMaster')

@section('title', 'Event Inventory')

@section('inventory_active', 'active')

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

<style>
    .select2-selection__rendered {
        height: 105px;
        overflow-y: scroll !important;
    }
</style>

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
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Event Shared Variation Inventory</a></li>
                        {{-- <li class="breadcrumb-item active" aria-current="page">List</li> --}}
                    </ol>
                </nav>
            </div>
            <div>
                <h6 class="card-title mt-2 mb-2 ml-0 text-primary text-capitalize">Select Event to create Shared Variation
                    Inventory</h6>
                <form action="{{ url('admin/event-inventory') }}" method="get">
                    @csrf
                    @method('get')
                    <div class="row mt-1">
                        <div class="col-sm-6">
                            <div class="input-group input-group-merge">
                                <select class="form-control variation" id="basicSelect" name="id">
                                    <option value="" selected>Select Event</option>
                                    @if (!empty($products))
                                        @foreach ($products as $item)
                                            <option value="{{ $item->id }}"
                                                {{ $product_id == $item->id ? 'selected' : '' }}>
                                                {{ substr($item->name, 0, 70) . '...' }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-0">
                            <div class="input-group input-group-merge">
                                <input type="hidden" name="product_id" class="product_id" value="{{ $product_id }}">
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
    @if (!empty($inventory_list) && count($inventory_list)>0)
    <section id="accordion-with-margin">
        <div class="row">
            <div class="col-sm-12">
                <div class="card collapse-icon">
                    <div class="card-header">
                        <h4 class="card-title">Quantity Manager</h4>
                    </div>
                    <div class="card-body">
                        <p class="card-text"></p>
                        <div class="collapse-margin" id="accordionExample">
                            @foreach ($inventory_list as $key => $items)
                            <div class="card">
                                <div class="card-header" id="headingOne" data-toggle="collapse" role="button"
                                    data-target="#collapseOne{{$key}}" aria-expanded="false" aria-controls="collapseOne">
                                    <span class="lead collapse-title text-primary text-capitalize"> {{$items->group_name}} </span>
                                </div>
                                <div id="collapseOne{{$key}}" class="collapse" aria-labelledby="headingOne"
                                    data-parent="#accordionExample">
                                    <div class="card-body">
                                        <form action="{{ url('admin/update-inventory') }}" method="post" name="ajax_form">
                                            @csrf
                                            @method('put')
                                            <div data-repeater-list="invoice">
                                                <div data-repeater-item>
                                                    <div class="row d-flex align-items-end getInput">
                                                        <div class="col-md-4 col-12">
                                                            <div class="form-group">
                                                                <label for="group_name">Group Name:</label>
                                                                <input type="text" class="form-control group_name"
                                                                    aria-describedby="group_name" placeholder="Group Name"
                                                                    name="group_name" value="{{$items->group_name}}" required/>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="total_seat">Quantity:</label>
                                                                <input type="number" class="form-control total_seat"
                                                                    aria-describedby="itemcost" placeholder="32" name="total_seat" value="{{$items->total_seat}}" required/>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" name="group_id" value="{{$items->id}}">
                                                        <div class="col-md-6 col-12">
                                                            <div class="form-group">
                                                                <label for="pickup_point">Variation(s):</label>
                                                                <select
                                                                    class="form-control select2 select2-hidden-accessible addGroup pickup_point"
                                                                    name="pickup_point[]" tabindex="-1" aria-hidden="true"
                                                                    aria-describedby="pickup_point" multiple="" required>
                                                                    @foreach ($variations as $item)
                                                                        <option value="{{ $item['id'] . '#' . $item['date'] }}" {{(in_array($item['id'],explode(' ,',$items->pickup_point)))?"selected":""}} 
                                                                            data-date="{{ $item['date'] }}">{{ $item['name'] }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1 col-12">
                                                            <div class="form-group">
                                                                <button class="btn btn-outline-success text-nowrap px-1"
                                                                    type="submit">
                                                                    <i data-feather='check' class="mr-25"></i>
                                                                    <span>Update</span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    @if (!empty($points))
        <section class="form-control-repeater">
            <div class="row">
                <!-- Invoice repeater -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Shared Variation Inventory</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ url('admin/store-inventory') }}" class="invoice-repeater" method="post">
                                @csrf
                                @method('post')
                                <div data-repeater-list="invoice">
                                    <div data-repeater-item>
                                        <div class="row d-flex align-items-end getInput">
                                            <div class="col-md-4 col-12">
                                                <div class="form-group">
                                                    <label for="itemname">Group Name:</label>

                                                    <input type="text" class="form-control group_name"
                                                        aria-describedby="itemname" placeholder="Group Name"
                                                        name="group_name" />
                                                </div>
                                                <div class="form-group">
                                                    <label for="itemcost">Quantity:</label>
                                                    <input type="number" class="form-control total_seat"
                                                        aria-describedby="itemcost" placeholder="32" name="total_seat" />
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="itemquantity">Variation(s):</label>
                                                    <select
                                                        class="form-control select2 select2-hidden-accessible addGroup pickup_point"
                                                        name="pickup_point[]" tabindex="-1" aria-hidden="true"
                                                        aria-describedby="itemquantity" multiple="">
                                                        @foreach ($variations as $item)
                                                            <option value="{{ $item['id'] . '#' . $item['date'] }}"
                                                                data-date="{{ $item['date'] }}">{{ $item['name'] }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-1 col-12">
                                                <div class="form-group">
                                                    <button class="btn btn-outline-primary text-nowrap px-1 groupBtn"
                                                        type="submit">
                                                        <i data-feather='check' class="mr-25"></i>
                                                        <span>Submit</span>
                                                    </button>
                                                </div>
                                                <div class="form-group">
                                                    <button class="btn btn-outline-danger text-nowrap px-1"
                                                        data-repeater-delete type="button">
                                                        <i data-feather="x" class="mr-25"></i>
                                                        <span>Delete</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <hr />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <button class="btn btn-icon btn-primary" type="button" data-repeater-create>
                                            <i data-feather="plus" class="mr-25"></i>
                                            <span>Add New</span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- /Invoice repeater -->
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
    <script src="{{ asset(mix('vendors/js/forms/repeater/jquery.repeater.min.js')) }}"></script>

@endsection

@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/extensions/ext-component-sweet-alerts.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/extensions/ext-component-toastr.js')) }}"></script>
    <script src="{{ asset('js/scripts/common/common-action.js') }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/form-select2.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/form-repeater.js')) }}"></script>
    <script src="{{asset(mix('js/scripts/components/components-collapse.js'))}}"></script>
       
@endsection
