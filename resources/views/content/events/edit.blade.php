@extends('layouts/contentLayoutMaster')

@section('title', 'Event')

@section('list_active','active')

@section('vendor-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/wizard/bs-stepper.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/editors/quill/katex.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/editors/quill/monokai-sublime.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/editors/quill/quill.snow.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/editors/quill/quill.bubble.css')) }}">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Inconsolata&family=Roboto+Slab&family=Slabo+27px&family=Sofia&family=Ubuntu+Mono&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/spinner/jquery.bootstrap-touchspin.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/toastr.min.css')) }}">


@endsection

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/sweetalert2.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/extensions/ext-component-sweet-alerts.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-user.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-wizard.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-quill-editor.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/extensions/ext-component-toastr.css')) }}">

@endsection

@section('content')
    <!-- users list start -->
    <section class="app-user-list">
        <!-- users filter start -->

        <!-- users filter end -->
        <!-- list section start -->
        <div class="card">
            <div class="card-header border-bottom">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb" style="padding: 0.1rem 0.1rem;">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Event</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- list section end -->
    </section>
    <form class="form" name="ajax_form" method="post" action="{{route('event.update',$result->id)}}"
        enctype="multipart/form-data">
        @csrf
        @method('put')
    <section id="multiple-column-form">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Create Event</h4>
                    </div>
                    <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label" for="vertical-username">Event Title</label>
                                        <input type="text" id="vertical-username" class="form-control"
                                            placeholder="Event Name" name="name" value="{{ $result->name }}" />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="vertical-email">Event SKU</label>
                                        <input type="text" id="vertical-email" name="sku" class="form-control"
                                            placeholder="JOHN256OP" aria-label="john.doe" value="{{ $result->sku }}" />
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="category_id">Event Category</label>
                                        <select name="category_id" class="form-control">
                                            <option value="">Select Category</option>
                                            @if (!empty($categories))
                                                @foreach ($categories as $key => $category)
                                                    <option value="{{ $category->id }}" @if ($category->id==$result->category_id) selected @endif>
                                                        {{ $category->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="date_concert">Date of Concert</label>
                                        <input type="text" id="date_concert"
                                            class="form-control flatpickr-multiples flatpickr-input active"
                                            placeholder="YYYY-MM-DD" name="date_concert"
                                            value="{{ $result->date_concert }}">
                                    </div>
                                    {{-- <label for="last-name-column">Event Short Description :*</label>
                                    <textarea name="shortdesc" class="form-control" id="shortdesc" autocomplete="off" cols="30" rows="4"
                                        placeholder="Enter Event Short Description">{{ $result->shortdesc }}</textarea> --}}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-12 mb-1">
                                    <label for="last-name-column">Event Short Description :*</label>
                                    <div class="row snow-editors">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div id="snow-wrapper">
                                                                <div id="snow-containers">
                                                                    <div class="quill-toolbars">
                                                                        <span class="ql-formats">
                                                                            <select class="ql-header">
                                                                                <option value="1">Heading</option>
                                                                                <option value="2">Subheading
                                                                                </option>
                                                                                <option selected>Normal</option>
                                                                            </select>
                                                                            <select class="ql-font">
                                                                                <option selected>Sailec Light</option>
                                                                                <option value="sofia">Sofia Pro
                                                                                </option>
                                                                                <option value="slabo">Slabo 27px
                                                                                </option>
                                                                                <option value="roboto">Roboto Slab
                                                                                </option>
                                                                                <option value="inconsolata">Inconsolata
                                                                                </option>
                                                                                <option value="ubuntu">Ubuntu Mono
                                                                                </option>
                                                                            </select>
                                                                        </span>
                                                                        <span class="ql-formats">
                                                                            <button class="ql-bold"></button>
                                                                            <button class="ql-italic"></button>
                                                                            <button class="ql-underline"></button>
                                                                        </span>
                                                                        <span class="ql-formats">
                                                                            <button class="ql-list"
                                                                                value="ordered"></button>
                                                                            <button class="ql-list"
                                                                                value="bullet"></button>
                                                                        </span>
                                                                        <span class="ql-formats">
                                                                            <button class="ql-link"></button>
                                                                            <button class="ql-image"></button>
                                                                            <button class="ql-video"></button>
                                                                        </span>
                                                                        <span class="ql-formats">
                                                                            <button class="ql-formula"></button>
                                                                            <button class="ql-code-block"></button>
                                                                        </span>
                                                                        <span class="ql-formats">
                                                                            <button class="ql-clean"></button>
                                                                        </span>
                                                                    </div>
                                                                    <div class="editors" style="height: 120px">
                                                                        {!! $result->shortdesc !!}
                                                                    </div>
                                                                    <input type="hidden" name="shortdesc" id="shortdesc" value="">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <textarea name="shortdesc" class="form-control" id="shortdesc" autocomplete="off" cols="30" rows="4"
                                        placeholder="Enter Event Short Description"></textarea> --}}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 col-12">
                                    <div class="form-group" id="event_county">
                                        <label for="counties_id">Event County </label>
                                        <select name="counties_id[]" class="form-control counties_id select2 countyAll" multiple
                                            placeholder="Select County" id='counties_id'>
                                            {{-- <option value="all" id="checkall">Select All</option> --}}
                                            @if (!empty($counties))
                                                @php
                                                    $selected_counties = explode(',', $result->counties_id);
                                                @endphp
                                                @foreach ($counties as $key => $county)
                                                    <option value="{{ $county->id }}"
                                                        @if (in_array($county->id, $selected_counties)) selected @endif class="checkcounty">
                                                        {{ $county->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <span id="countySelect"></span>
                                    </div>
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" class="custom-control-input" id="chkall">
                                        <label class="custom-control-label" for="chkall">Select All</label>
                                    </div>
                                </div>
                                <div class="col-md-1 col-12">
                                    <button type="button" class="btn btn-flat-success btn-sm waves-effect mt-2"
                                        style="margin-top: 1.9rem !important;" id="get_point"
                                        data-url="{{ route('pickup.get') }}"><i
                                            data-feather='arrow-right-circle'></i></button>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group" id="pickup_point">
                                        <label for="counties_id">Event Pickup Point </label>
                                        <select name="pickup_point_id[]" multiple="multiple"
                                            class="select2 select2-size-sm form-control pickup_point_id hide_select pointAll"
                                            placeholder="Select Pickup Point" id="small-select-multi">
                                            {{-- <option value="all" id="checkall">Select All</option> --}}
                                            @if (!empty($pickup_points))
                                                @php
                                                    // $selected_points = explode(',', $result->pickup_point_id);
                                                @endphp
                                                @foreach ($pickup_points as $key => $points)
                                                <option value="{{ $points->id }}" selected class="checkpoint">{{$points->name}}</option>
                                                    {{-- <option value="{{ $points->id }}"
                                                        @if (in_array($points->id, $selected_points)) selected @endif>
                                                        {{ $points->name }}
                                                    </option> --}}
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                            <label for="fp-multiple">Check-ins per ticket</label>
                                            <input type="number" min="1" max="10" name="check_ins_per_ticket"
                                                class="form-control" id="someid" onkeypress="return isNumberKey(event)" value="{{$result->check_ins_per_ticket}}"/>
                                            <span id="checkIn"></span>
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <label for="customFile"  data-html="true" data-toggle="tooltip"
                                    data-original-title="<small class='text-white'>Allowed JPG, GIF or PNG. Max size of 1MB</small>">Event Image <i data-feather='info'></i></label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="customFile" name="image"
                                            id="image">
                                        <label class="custom-file-label" for="customFile">Choose Event Image</label>
                                    </div>
                                    <div class="media-left mt-2" id="banner_image"></div>
                                </div>
                                <div class="col-md-2 col-12">
                                    <img src="{{ asset('uploads/event_image/') . '/' . $result->image }}" alt="avatar"
                                        height="80" width="120" class="cursor-pointer mt-1 ml-4" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-12">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input"
                                                name="allow_ticket_check_out" value="{{ $result->allow_ticket_check_out }}" {{ $result->allow_ticket_check_out === 1 ? 'checked' : '' }} id="customCheck1">
                                            <label class="custom-control-label" for="customCheck1">Allow ticket
                                                check-out</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-12">
                                    <div class="form-group">
                                        <div class="row snow-editor">
                                            <div class="col-12">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div id="snow-wrapper">
                                                                    <div id="snow-container">
                                                                        <div class="quill-toolbar">
                                                                            <span class="ql-formats">
                                                                                <select class="ql-header">
                                                                                    <option value="1">Heading
                                                                                    </option>
                                                                                    <option value="2">Subheading
                                                                                    </option>
                                                                                    <option selected>Normal</option>
                                                                                </select>
                                                                                <select class="ql-font">
                                                                                    <option selected>Sailec Light
                                                                                    </option>
                                                                                    <option value="sofia">Sofia Pro
                                                                                    </option>
                                                                                    <option value="slabo">Slabo 27px
                                                                                    </option>
                                                                                    <option value="roboto">Roboto Slab
                                                                                    </option>
                                                                                    <option value="inconsolata">
                                                                                        Inconsolata
                                                                                    </option>
                                                                                    <option value="ubuntu">Ubuntu Mono
                                                                                    </option>
                                                                                </select>
                                                                            </span>
                                                                            <span class="ql-formats">
                                                                                <button class="ql-bold"></button>
                                                                                <button class="ql-italic"></button>
                                                                                <button class="ql-underline"></button>
                                                                            </span>
                                                                            <span class="ql-formats">
                                                                                <button class="ql-list"
                                                                                    value="ordered"></button>
                                                                                <button class="ql-list"
                                                                                    value="bullet"></button>
                                                                            </span>
                                                                            <span class="ql-formats">
                                                                                <button class="ql-link"></button>
                                                                                <button class="ql-image"></button>
                                                                                <button class="ql-video"></button>
                                                                            </span>
                                                                            <span class="ql-formats">
                                                                                <button class="ql-formula"></button>
                                                                                <button class="ql-code-block"></button>
                                                                            </span>
                                                                            <span class="ql-formats">
                                                                                <button class="ql-clean"></button>
                                                                            </span>
                                                                        </div>
                                                                        {{-- <textarea name="" id="" cols="30" rows="10"></textarea> --}}
                                                                        <div class="editor">
                                                                            {!! $result->description !!}
                                                                        </div>
                                                                        <input type="hidden" name="description"
                                                                            id="product_desc" value="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="floating-label-input">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title text-primary">Event Seo</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="vertical-username">Event Meta Title</label>
                                    <input type="text" id="vertical-username" class="form-control"
                                        placeholder="Event Name" name="meta_title"
                                        value="{{ $result->meta_title }}" />
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="vertical-email">Event Meta Tag</label>
                                    <input type="text" id="vertical-email" name="meta_tag" class="form-control"
                                        placeholder="Event Meta Tag" aria-label="john.doe"
                                        value="{{ $result->meta_tag }}" />
                                </div>
                            </div>
                            <div class="col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="last-name-column">Event Short Description :*</label>
                                    <textarea name="meta_description" class="form-control" id="meta_desc" autocomplete="off" cols="30" rows="4"
                                        placeholder="Enter Event Short Description">{{ $result->meta_description }}</textarea>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mr-1" id="form-button">Submit</button>
                        <button type="reset" class="btn btn-outline-secondary">Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</form>

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
    <script src="{{ asset(mix('vendors/js/forms/wizard/bs-stepper.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.date.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.time.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/pickadate/legacy.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/editors/quill/katex.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/editors/quill/highlight.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/editors/quill/quill.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/spinner/jquery.bootstrap-touchspin.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>

@endsection

@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/extensions/ext-component-sweet-alerts.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/form-wizard.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/form-select2.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/pickers/form-pickers.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/form-quill-editor.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/form-number-input.js')) }}"></script>
    <script src="{{ asset('js/scripts/common/common-action.js') }}"></script>
    <script src="{{ asset(mix('js/scripts/extensions/ext-component-toastr.js')) }}"></script>

@endsection
