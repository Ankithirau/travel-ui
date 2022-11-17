@extends('layouts.app')

@section('styles')

<!-- Data table css -->
<link href="{{URL::asset('assets/plugins/datatable/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/responsivebootstrap4.min.css')}}" rel="stylesheet" />

<!-- Select2 css -->
<link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />

@endsection

@section('content')

<!-- page-header -->
<div class="page-header">
  <ol class="breadcrumb">
    <!-- breadcrumb -->
    <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Pickup Point Lists</li>
  </ol><!-- End breadcrumb -->
  <div class="ml-auto">
    <div class="input-group">
      <a href="javascript:void(0);" class="btn btn-primary text-white mr-2 btn-sm" data-toggle="modal"
        data-target="#modalPickup" title="Add Pickup Point">
        <span>
          <i class="fa fa-plus"></i>
        </span>
        Add Pickup Point
      </a>
    </div>
  </div>
</div>
<!-- End page-header -->
<!-- row opened -->
<div class="row">
  <div class="col-md-12 col-lg-12">
    <div class="card">
      <div class="card-body pt-4">
        <div class="table-responsive">
          <table id="fileexport-datatable" class="mt-2 table table-bordered key-buttons text-nowrap">
            <thead>
              <tr>
                <th class="border-bottom-0 bg-primary">S.No.</th>
                <th class="border-bottom-0 bg-primary">Name</th>
                <th class="border-bottom-0 bg-primary">County</th>
                <th class="border-bottom-0 bg-primary">Status</th>
                <th class="border-bottom-0 bg-primary">Action</th>
              </tr>
            </thead>
            <tbody>
              @if(!empty($results))
              @php
              $i=1

              @endphp
              @foreach($results as $result)
              @php
              $counties=App\Models\County::find($result->counties_id);
              @endphp
              <tr>
                <td>{{$i++}}</td>
                <td>{{$result->name}}</td>
                <td>{{$counties->name}}</td>
                <td>
                  <input type="button"
                    class="btn @if($result->status==0) btn-danger @else btn-success @endif  updateStatus"
                    data-url="{{route('pickup.status', $result->id)}}"
                    value="@if($result->status==0) Inactive @else Active @endif">
                </td>
                <td>
                  <div class="d-flex">
                    <input type="button" class="btn  btn-warning  editRecord" data-title="Pickup"
                      data-url="{{route('pickup.edit', $result->id)}}"
                      data-action="{{route('pickup.update', $result->id)}}" value="Edit">&nbsp;
                    <input type="button" class="btn btn-danger  deleteRecord"
                      data-url="{{route('operator.destroy', $result->id)}}" value="Delete">
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
  </div>
  <div class="modal fade" id="modalPickup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add Pickup Point</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form name="ajax_form" method="post" action="{{route('pickup.store')}}" enctype="multipart/form-data">
          @csrf
          <div class="modal-body">
            <div class="form-group">
              <label for="name" class="col-form-label">Name *:</label>
              <input type="text" name="name" class="form-control" id="name" autocomplete="off">
            </div>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label>County</label>
              <select name="counties_id" class="form-control" id="counties_id">
                <option value="" selected>Select County </option>
                @foreach ($county as $key => $item)
                @if ($item->name)
                <option value="{{$item->id}}">{{$item->name}}</option>
                @endif
                @endforeach
              </select>
            </div>
            <div class="modal-footer">
              <input value="Close" type="button" class="btn btn-secondary" data-dismiss="modal">
              <input value="Submit" type="submit" id="form-button" class="btn btn-primary">
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- row closed -->

@endsection('content')

@section('scripts')

{{--
<!--Jquery Sparkline js-->
<script src="{{URL::asset('assets/plugins/vendors/jquery.sparkline.min.js')}}"></script>

<!-- Chart Circle js-->
<script src="{{URL::asset('assets/plugins/vendors/circle-progress.min.js')}}"></script>

<!--Time Counter js-->
<script src="{{URL::asset('assets/plugins/counters/jquery.missofis-countdown.js')}}"></script>
<script src="{{URL::asset('assets/plugins/counters/counter.js')}}"></script> --}}

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

@endsection