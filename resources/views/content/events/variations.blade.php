@extends('layouts/contentLayoutMaster')

@section('title', 'Event Variation')

@section('variation_active','active')

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
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Event Variation</a></li>
                        <li class="breadcrumb-item active" aria-current="page">List</li>
                    </ol>
                </nav>
            </div>
            <div>
                <form action="{{ url('admin/event-variation') }}" method="get">
                    @csrf
                    @method('get')
                    <div class="row mt-1">
                        <div class="col-sm-6">
                            <div class="input-group input-group-merge">
                                <select class="form-control variation" id="basicSelect" name="id">
                                    <option value="" selected>Select Event</option>
                                    @if ($products)
                                        @foreach ($products as $item)
                                            <option value="{{ $item->id }}" {{($product_id==$item->id)?"selected":""}}>{{ substr($item->name, 0, 70) . '...' }}
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
            @if (!empty($results))
            <form name="ajax_form" method="post" action="{{ route('product.add_variation_by_form') }}"
                enctype="multipart/form-data" novalidate id="set_variation">
                <div class="table-responsive pt-2" id="insert_btn">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-10">
                                <span>
                                    <label for="" class="h6 text-dark">Event Name :</label>
                                    <h4 class="card-title text-primary">{{$product_name}}</h4>
                                </span>
                            </div>
                            <div class="col-sm-2">
                                <label for="set_val" class="h6 text-dark">Master Input :</label>
                                <input type="number" class="form-control border border-success" placeholder="Set Price" id="set_val">
                            </div>
                        </div>
                      </div>
                    <div class="table-wrapper-scroll-y my-custom-scrollbar" id="set_variation">
                        <table class="table table-fixed">
                            @csrf
                            @method('POST')
                            <thead>
                                <tr>
                                    <th>Date of Concert</th>
                                    <th>County</th>
                                    <th>Pickup Point</th>
                                    {{-- <th>Stock Quantity</th> --}}
                                    <th>Price</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="show_table">
                                @php
                                    $num = 0;
                                @endphp
                                @foreach ($results as $item => $val)
                                    @foreach ($val['pick_point'] as $pick => $point)
                                        <tr>
                                            <td>
                                                <p>{{ date('d-m-Y', strtotime($val['date_concert'])) }}</p>
                                                <input type="hidden" name="date[]" class="form-control date_concert"
                                                    value="{{ $val['date_concert'] }}">
                                                <input type="hidden" name="product_id[]" class="form-control product"
                                                    value="{{ $val['product']['id'] }}">
                                            </td>
                                            <td>
                                                <p>{{ $val['county']['name'] }}</p>
                                                <input type="hidden" name="county_id[]" class="form-control county_id"
                                                    value="{{ $val['county']['id'] }}">
                                            </td>
                                            <td>
                                                <p>{{ isset($point->name) ? $point->name : '' }}</p>
                                                <input type="hidden" name="pickup_id[]" class="form-control pickup_id"
                                                    value="{{ $point->id }}">
                                            </td>
                                            @php
                                                $prices = \App\Models\Product_variation::select('id', 'price', 'stock_quantity')
                                                    ->where(['date_concert' => trim($val['date_concert']), 'counties_id' => $val['county']['id'], 'pickup_point_id' => $point->id, 'product_id' => $val['product']['id']])
                                                    ->first();
                                            @endphp
                                            @if ($prices)
                                                {{-- <td>
                                                    <div class="input-group input-group-merge">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                    data-feather='shopping-bag'></i></span>
                                                        </div>
                                                        <input type="text" name="stock_quantity[]"
                                                            class="form-control stock_quantity"
                                                            id="stock_{{ $point->id }}"
                                                            value="{{ $prices->stock_quantity != 0 ? $prices->stock_quantity : '' }}"
                                                            placeholder="Stock Quantity">
                                                    </div>
                                                    <div
                                                        class="stock_quantity {{ $num }} text-danger error-inline">
                                                    </div>
                                                </td> --}}
                                                <td>
                                                    <div class="input-group input-group-merge">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">$</span>
                                                        </div>
                                                        <input type="text" name="price[]" class="form-control price"
                                                            id="price_{{ $point->id }}" value="{{ $prices->price }}"
                                                            placeholder="Ticket Price">
                                                    </div>

                                                    <div class="price {{ $num }} text-danger error-inline">
                                                    </div>
                                                </td>
                                            @else
                                                {{-- <td>
                                                    <div class="input-group input-group-merge">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                    data-feather='shopping-bag'></i></span>
                                                        </div>
                                                        <input type="text" name="stock_quantity[]"
                                                            class="form-control stock_quantity"
                                                            id="stock_{{ $point->id }}" placeholder="Stock Quantity">
                                                    </div>
                                                    <div
                                                        class="stock_quantity {{ $num }} text-danger error-inline">
                                                    </div>
                                                </td> --}}
                                                <td>
                                                    <div class="input-group input-group-merge">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">$</span>
                                                        </div>
                                                        <input type="text" name="price[]" class="form-control price"
                                                            id="price_{{ $point->id }}" placeholder="Ticket Price">
                                                    </div>
                                                    <div class="price {{ $num }} text-danger error-inline">
                                                    </div>
                                                </td>
                                            @endif
                                            <td>
                                                <a href="javascript:void(0)"
                                                    class="btn btn-flat-success waves-effect update_variation"
                                                    data-action="{{ route('product.add_variation', isset($prices->id) ? $prices->id : 0) }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-arrow-up-circle">
                                                        <circle cx="12" cy="12" r="10"></circle>
                                                        <polyline points="16 12 12 8 8 12"></polyline>
                                                        <line x1="12" y1="16" x2="12"
                                                            y2="8">
                                                        </line>
                                                    </svg>
                                                </a>
                                            </td>
                                        </tr>
                                        @php
                                            $num++;
                                        @endphp
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
            @endif
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
{{-- <script>
    $('.btn-variation').show();
</script> --}}
