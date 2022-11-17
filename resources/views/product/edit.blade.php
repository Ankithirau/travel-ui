@extends('layouts.app')

@section('styles')

<!-- Data table css -->
<link href="{{URL::asset('assets/plugins/datatable/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/responsivebootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/multipleselect/multiselect.css')}}" rel="stylesheet" />
<script src="{{URL::asset('assets/plugins/multipleselect/multiselect.min.js')}}"></script>
<link href="{{URL::asset('assets/plugins/multidate/styles.css')}}" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="{{URL::asset('assets/plugins/multidate/multidatespicker.js')}}"></script>

@endsection

@section('content')

<!-- page-header -->
<div class="page-header">
  <ol class="breadcrumb">
    <!-- breadcrumb -->
    <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{route('product.index')}}">Product List</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Product</li>
  </ol><!-- End breadcrumb -->
</div>
<!-- End page-header -->
<!-- row opened -->
<div class="row">
  <div class="col-md-12 col-lg-12">
    <div class="card">
      <div class="card-body pt-4">
        <form name="ajax_form" method="post" action="{{route('product.update',$result->id)}}"
          enctype="multipart/form-data" novalidate>
          @csrf
          @method('put')
          <div class="row">
            <div class="col-md-12 col-lg-12">
              <div class="form-group">
                <label for="name" class="col-form-label">Product Name :<span class="text-danger">*</span>:</label>
                <input type="text" name="name" class="form-control" id="name" autocomplete="off"
                  value="{{$result->name}}" placeholder="Enter Product Name" />
              </div>
              <div class="form-group">
                <label for="shortdesc" class="col-form-label">Short Description :<span
                    class="text-danger">*</span></label>
                <textarea class="form-control" name="shortdesc" id="shortdesc" autocomplete="off" cols="30" rows="5"
                  placeholder="Enter Product Price">{{$result->shortdesc}}</textarea>
              </div>
              <div class="form-group">
                <label for="counties_id" class="col-form-label">County you wish to travel from: <span
                    class="text-danger">*</span>:</label>
                <div class="row">
                  <div class="col-5">
                    <select name="counties_id[]" class="form-control counties_id" aria-label="size 3 select example"
                      multiple id='testSelect1'>
                      @if(!empty($counties))
                      @php
                      $selected_counties=explode(',',$result->counties_id);
                      @endphp
                      @foreach($counties as $key=> $county)
                      <option value="{{$county->id}}" @if (in_array($county->id,$selected_counties)) selected
                        @endif>{{$county->name}}</option>
                      @endforeach
                      @endif
                    </select>
                  </div>
                  <div class="col-2">
                    <input type="button" class="btn btn-sm btn-primary" value="Get Pickup Point" id="get_point"
                      data-url="{{route('pickup.get')}}">
                  </div>
                </div>
                <div class="text-danger error_counties_id error-inline"></div>
              </div>
              <div class="form-group">
                <label for="pick_point_id" class="col-form-label pick_label">Pick up Points & Departure Times:
                  <span class="text-danger">*</span>
                </label>
                <div id="pickup_point">
                  <select name="pickup_point_id[]" class="form-control pickup_point_id hide_select"
                    aria-label="size 3 select example" multiple id="testSelect2">
                    @if(!empty($pickup_points))
                    @php
                    $selected_points=explode(',',$result->pickup_point_id);
                    @endphp
                    @foreach($pickup_points as $key=> $points)
                    <option value="{{$points->id}}" @if (in_array($points->id,$selected_points)) selected

                      @endif>{{$points->name}}</option>
                    @endforeach
                    @endif
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="image" class="col-form-label">Product Image :<span class="text-danger">*</span></label>
                <input type="file" name="image" id="image" class="form-control">
              </div>
              <div class="form-group">
                <img src="{{asset('uploads/event_image/').'/'.$result->image}}"
                  class="img-thumbnail rounded mx-auto float-start" alt="" srcset="" height="100" width="100">
              </div>
              <div class="form-group">
                <label for="date_concert" class="col-form-label">Date of Concert:<span
                    class="text-danger">*</span></label>
                <div style="width: 30%">
                  <input type="text" name="date_concert" id="selectedValues" class="date-values" readonly
                    value="{{$result->date_concert}}" />
                  <div id="parent" class="container" style="display:none;">
                    <div class="row header-row">
                      <div class="col-xs previous">
                        <a href="javascript:void(0)" id="previous" onclick="previous()">
                          <i class="fa fa-arrow-left" aria-hidden="true"></i>
                        </a>
                      </div>
                      <div class="card-header month-selected col-sm" id="monthAndYear">
                      </div>
                      <div class="col-sm">
                        <select class="form-control col-xs-6" name="month" id="month" onchange="change()"></select>
                      </div>
                      <div class="col-sm">
                        <select class="form-control col-xs-6" name="year" id="year" onchange="change()"></select>
                      </div>
                      <div class="col-xs next">
                        <a href="javascript:void(0)" id="next" onclick="next()">
                          <i class="fa fa-arrow-right" aria-hidden="true"></i>
                        </a>
                      </div>
                    </div>
                    <table id="calendar">
                      <thead>
                        <tr>
                          <th>S</th>
                          <th>M</th>
                          <th>T</th>
                          <th>W</th>
                          <th>T</th>
                          <th>F</th>
                          <th>S</th>
                        </tr>
                      </thead>
                      <tbody id="calendarBody"></tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label for="status" class="col-form-label">Status :<span class="text-danger">*</span></label>
                <select name="status" class="form-control">
                  @if ($result->status==1)
                  <option value="1" selected>Active</option>
                  <option value="0">Disabled</option>
                  @else
                  <option value="1">Active</option>
                  <option value="0" selected>Disabled</option>
                  @endif
                </select>
              </div>
              <div class="form-group">
                <label for="sku" class="col-form-label">Sku :<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="sku" autocomplete="off" value="{{$result->sku}}" name="sku">
              </div>
              <div class="form-group">
                <label for="event_id" class="col-form-label">Event :<span class="text-danger">*</span></label>
                <select name="event_id" class="form-control">
                  @if(!empty($events))
                  @foreach($events as $event)
                  @if ($event->id==$result->event_id)
                  <option value="{{$event->id}}" selected>{{$event->name}}</option>
                  @else
                  <option value="{{$event->id}}">{{$event->name}}</option>
                  @endif
                  @endforeach
                  @endif
                </select>
              </div>
              <div class="form-group">
                <label for="check_in_per_ticket" class="col-form-label">Check-ins per ticket :<span
                    class="text-danger">*</span></label>
                <input type="number" name="check_ins_per_ticket" class="form-control" id="check_in_per_ticket"
                  autocomplete="off" value="{{$result->check_ins_per_ticket}}">
              </div>
              <div class="form-group">
                <label for="category_id" class="col-form-label">Category :<span class="text-danger">*</span></label>
                <select name="category_id" class="form-control" aria-label="size 3 select example">
                  {{-- @if(!empty($category))
                  @foreach($category as $key=> $category)
                  <option value="{{$category[0]->id}}" selected>{{$category[0]->name}}</option>
                  @endforeach
                  @endif
                  @if(!empty($categories))
                  @foreach($categories as $key => $categories)
                  <option value="{{$categories[0]->id}}">{{$categories[0]->name}}</option>
                  @endforeach
                  @endif --}}
                  @if(!empty($categories))
                  @php
                  $selected_categories=explode(',',$result->category_id);
                  @endphp
                  @foreach($categories as $key=> $category)
                  <option value="{{$category->id}}" @if (in_array($category->id,$selected_categories)) selected

                    @endif>{{$category->name}}</option>
                  @endforeach
                  @endif
                </select>
              </div>
              <div class="form-group">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="Allow_ticket_check_out"
                    name="allow_ticket_check_out" value="{{$result->allow_ticket_check_out}}"
                    {{($result->allow_ticket_check_out==1)?"checked":""}}>
                  <label class="form-check-label" for="flexCheckChecked">
                    Allow ticket check-out :<span class="text-danger">*</span>
                  </label>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="meta_title" class="col-form-label">Product Meta Title<span
                        class="text-danger">*</span></label>
                    <input type="text" name="meta_title" value="{{$result->meta_title}}" class="form-control"
                      id="meta_title" autocomplete="off">
                  </div>
                  <div class="form-group">
                    <label for="meta_tag" class="col-form-label">Product Meta Tag<span
                        class="text-danger">*</span></label>
                    <input type="text" name="meta_tag" value="{{$result->meta_tag}}" class="form-control" id="meta_tag"
                      autocomplete="off">
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="check_in_per_ticket" class="col-form-label">Product Meta Description :<span
                        class="text-danger">*</span></label>
                    <textarea name="meta_desc" id="" cols="25" rows="5"
                      class="form-control">{{$result->meta_description}}</textarea>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-12 col-lg-12">
              <div class="form-group">
                <label for="description" class="col-form-label">Description :<span class="text-danger">*</span></label>
                <textarea class="form-control" id="description" autocomplete="off" cols="30" rows="40"
                  name="description">{{$result->description}}</textarea>
              </div>
            </div>
          </div>
          <input value="Send Request" type="submit" id="form-button" class="btn btn-primary">
        </form>
      </div>
    </div>
  </div>
</div>
<!-- row closed -->

@endsection('content')

@section('scripts')

<!--Jquery Sparkline js-->
<script src="{{URL::asset('assets/plugins/vendors/jquery.sparkline.min.js')}}"></script>

<!-- Chart Circle js-->
<script src="{{URL::asset('assets/plugins/vendors/circle-progress.min.js')}}"></script>

<!--Time Counter js-->
<script src="{{URL::asset('assets/plugins/counters/jquery.missofis-countdown.js')}}"></script>
<script src="{{URL::asset('assets/plugins/counters/counter.js')}}"></script>

<!-- INTERNAL Data tables -->
<script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/jszip.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/pdfmake.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/vfs_fonts.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.html5.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.print.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/responsive.bootstrap4.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/datatable-2.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
@endsection