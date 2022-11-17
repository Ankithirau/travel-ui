@extends('layouts/contentLayoutMaster')

@section('title', 'Event Create')

@section('create_active', 'active')

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
                        <li class="breadcrumb-item active" aria-current="page">Create</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- list section end -->
    </section>
    <form class="form" name="ajax_form" method="post" action="{{ route('event.store') }}" enctype="multipart/form-data">
        @csrf
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
                                            placeholder="Event Title" name="name" />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="vertical-email">Event SKU</label>
                                        <input type="text" id="vertical-email" name="sku" class="form-control"
                                            placeholder="JOHN256OP" aria-label="john.doe" />
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="fp-multiple">Event Category</label>
                                        <select name="category_id" class="form-control">
                                            <option value="" selected>Select Category</option>
                                            @if (!empty($category))
                                                @foreach ($category as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="fp-multiple">Date of Concert</label>
                                        <input type="text" id="fp-multiple"
                                            class="form-control flatpickr-multiple flatpickr-input active"
                                            placeholder="YYYY-MM-DD" readonly="readonly" name="date_concert">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-12 mb-2">
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
                                                                    <div class="editors">

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
                                        <label for="counties_id">County you wish to travel from</label>
                                        <select name="counties_id[]" class="form-control counties_id select2" multiple
                                            placeholder="Select County" id='counties_id'>
                                            @if (!empty($county))
                                                @foreach ($county as $county)
                                                    <option value="{{ $county->id }}">{{ $county->name }}</option>
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
                                        data-url="{{ route('pickup.get') }}">
                                        <i data-feather='arrow-right-circle'></i>
                                    </button>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group" id="pickup_point">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <label for="image" data-html="true" data-toggle="tooltip"
                                        data-original-title="<small class='text-white'>Allowed JPG, GIF or PNG. Max size of 1MB</small>">Event
                                        Image <i data-feather='info'></i></label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="image" id="image">
                                        <label class="custom-file-label" for="customFile">Choose Event Image</label>
                                    </div>
                                    <div class="media-left mt-2" id="banner_image"></div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="fp-multiple">Check-ins per ticket</label>
                                        <input type="number" min="1" max="10" name="check_ins_per_ticket"
                                            class="form-control" id="someid" onkeypress="return isNumberKey(event)" />
                                        <span id="checkIn"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-12 mb-1 mt-1">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input value="1" type="checkbox" class="custom-control-input"
                                                checked="" name="allow_ticket_check_out">
                                            <label class="custom-control-label" for="customCheck1">Allow ticket
                                                check-out</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-12">
                                    <div class="form-group">
                                        <label for="">Event Description</label>
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
                                                                        {{-- <textarea name="" id="" cols="30" rows="10"></textarea> --}}
                                                                        <div class="editor">

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
                                            placeholder="Event Name" name="meta_title" />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="vertical-email">Event Meta Tag</label>
                                        <input type="text" id="vertical-email" name="meta_tag" class="form-control"
                                            placeholder="Event Meta Tag" aria-label="john.doe" />
                                    </div>
                                </div>
                                <div class="col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="meta_desc">Event Short Description :*</label>
                                        <textarea name="meta_description" class="form-control" id="meta_desc" autocomplete="off" cols="30" rows="4"
                                            placeholder="Enter Event Short Description"></textarea>
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

{{-- <section class="horizontal-wizard">
        <div class="bs-stepper horizontal-wizard-example">
            <div class="bs-stepper-header">
                <div class="step" data-target="#account-details">
                    <button type="button" class="step-trigger">
                        <span class="bs-stepper-box">1</span>
                        <span class="bs-stepper-label">
                            <span class="bs-stepper-title">Event Details</span>
                            <span class="bs-stepper-subtitle">Setup Event Details</span>
                        </span>
                    </button>
                </div>
                <div class="line">
                    <i data-feather="chevron-right" class="font-medium-2"></i>
                </div>
                <div class="step" data-target="#personal-info">
                    <button type="button" class="step-trigger">
                        <span class="bs-stepper-box">2</span>
                        <span class="bs-stepper-label">
                            <span class="bs-stepper-title">Event Info</span>
                        <span class="bs-stepper-subtitle">Add Personal Info</span>
                        </span>
                    </button>
                </div>
                <div class="line">
                    <i data-feather="chevron-right" class="font-medium-2"></i>
                </div>
                <div class="step" data-target="#address-step">
                    <button type="button" class="step-trigger">
                        <span class="bs-stepper-box">3</span>
                        <span class="bs-stepper-label">
                            <span class="bs-stepper-title">Event Meta Info</span>
                        <span class="bs-stepper-subtitle">Add Event Meta Info</span>
                        </span>
                    </button>
                </div>
                <div class="line">
                    <i data-feather="chevron-right" class="font-medium-2"></i>
                </div>
                <div class="step" data-target="#event-info-vertical">
                    <button type="button" class="step-trigger">
                        <span class="bs-stepper-box">4</span>
                        <span class="bs-stepper-label">
                            <span class="bs-stepper-title">Event Other Info</span>
                        <span class="bs-stepper-subtitle">Add Event Other Info</span>
                        </span>
                    </button>
                </div>
                <div class="line">
                    <i data-feather="chevron-right" class="font-medium-2"></i>
                </div>
                <div class="step" data-target="#social-links">
                    <button type="button" class="step-trigger">
                        <span class="bs-stepper-box">4</span>
                        <span class="bs-stepper-label">
                            <span class="bs-stepper-title">Event Descriptions</span>
                        <span class="bs-stepper-subtitle">Add Event Descriptions</span>
                        </span>
                    </button>
                </div>
            </div>
            <div class="bs-stepper-content">
                <div id="account-details" class="content">
                    <div class="content-header">
                        <h5 class="mb-0">Account Details</h5>
                        <small class="text-muted">Enter Your Account Details.</small>
                    </div>
                    <form>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-label" for="username">Username</label>
                                <input type="text" name="username" id="username" class="form-control"
                                    placeholder="johndoe" />
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label" for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control"
                                    placeholder="john.doe@email.com" aria-label="john.doe" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group form-password-toggle col-md-6">
                                <label class="form-label" for="password">Password</label>
                                <input type="password" name="password" id="password" class="form-control"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                            </div>
                            <div class="form-group form-password-toggle col-md-6">
                                <label class="form-label" for="confirm-password">Confirm Password</label>
                                <input type="password" name="confirm-password" id="confirm-password"
                                    class="form-control"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                            </div>
                        </div>
                    </form>
                    <div class="d-flex justify-content-between">
                        <button class="btn btn-outline-secondary btn-prev" disabled>
                            <i data-feather="arrow-left" class="align-middle mr-sm-25 mr-0"></i>
                            <span class="align-middle d-sm-inline-block d-none">Previous</span>
                        </button>
                        <button class="btn btn-primary btn-next">
                            <span class="align-middle d-sm-inline-block d-none">Next</span>
                            <i data-feather="arrow-right" class="align-middle ml-sm-25 ml-0"></i>
                        </button>
                    </div>
                </div>
                <div id="personal-info" class="content">
                    <div class="content-header">
                        <h5 class="mb-0">Personal Info</h5>
                        <small>Enter Your Personal Info.</small>
                    </div>
                    <form>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-label" for="first-name">First Name</label>
                                <input type="text" name="first-name" id="first-name" class="form-control"
                                    placeholder="John" />
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label" for="last-name">Last Name</label>
                                <input type="text" name="last-name" id="last-name" class="form-control"
                                    placeholder="Doe" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-label" for="country">Country</label>
                                <select class="select2 w-100" name="country" id="country">
                                    <option label=" "></option>
                                    <option>UK</option>
                                    <option>USA</option>
                                    <option>Spain</option>
                                    <option>France</option>
                                    <option>Italy</option>
                                    <option>Australia</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label" for="language">Language</label>
                                <select class="select2 w-100" name="language" id="language" multiple>
                                    <option>English</option>
                                    <option>French</option>
                                    <option>Spanish</option>
                                </select>
                            </div>
                        </div>
                    </form>
                    <div class="d-flex justify-content-between">
                        <button class="btn btn-primary btn-prev">
                            <i data-feather="arrow-left" class="align-middle mr-sm-25 mr-0"></i>
                            <span class="align-middle d-sm-inline-block d-none">Previous</span>
                        </button>
                        <button class="btn btn-primary btn-next">
                            <span class="align-middle d-sm-inline-block d-none">Next</span>
                            <i data-feather="arrow-right" class="align-middle ml-sm-25 ml-0"></i>
                        </button>
                    </div>
                </div>
                <div id="address-step" class="content">
                    <div class="content-header">
                        <h5 class="mb-0">Address</h5>
                        <small>Enter Your Address.</small>
                    </div>
                    <form>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-label" for="address">Address</label>
                                <input type="text" id="address" name="address" class="form-control"
                                    placeholder="98  Borough bridge Road, Birmingham" />
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label" for="landmark">Landmark</label>
                                <input type="text" name="landmark" id="landmark" class="form-control"
                                    placeholder="Borough bridge" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-label" for="pincode1">Pincode</label>
                                <input type="text" id="pincode1" class="form-control" placeholder="658921" />
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label" for="city1">City</label>
                                <input type="text" id="city1" class="form-control" placeholder="Birmingham" />
                            </div>
                        </div>
                    </form>
                    <div class="d-flex justify-content-between">
                        <button class="btn btn-primary btn-prev">
                            <i data-feather="arrow-left" class="align-middle mr-sm-25 mr-0"></i>
                            <span class="align-middle d-sm-inline-block d-none">Previous</span>
                        </button>
                        <button class="btn btn-primary btn-next">
                            <span class="align-middle d-sm-inline-block d-none">Next</span>
                            <i data-feather="arrow-right" class="align-middle ml-sm-25 ml-0"></i>
                        </button>
                    </div>
                </div>
                <div id="sevent-info-vertical" class="content">
                    <div class="content-header">
                        <h5 class="mb-0">Social Links</h5>
                        <small>Enter Your Social Links.</small>
                    </div>
                    <form>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-label" for="twitter">Twitter</label>
                                <input type="text" id="twitter" name="twitter" class="form-control"
                                    placeholder="https://twitter.com/abc" />
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label" for="facebook">Facebook</label>
                                <input type="text" id="facebook" name="facebook" class="form-control"
                                    placeholder="https://facebook.com/abc" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-label" for="google">Google+</label>
                                <input type="text" id="google" name="google" class="form-control"
                                    placeholder="https://plus.google.com/abc" />
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label" for="linkedin">Linkedin</label>
                                <input type="text" id="linkedin" name="linkedin" class="form-control"
                                    placeholder="https://linkedin.com/abc" />
                            </div>
                        </div>
                    </form>
                    <div class="d-flex justify-content-between">
                        <button class="btn btn-primary btn-prev">
                            <i data-feather="arrow-left" class="align-middle mr-sm-25 mr-0"></i>
                            <span class="align-middle d-sm-inline-block d-none">Previous</span>
                        </button>
                        <button class="btn btn-success btn-submit">Submit</button>
                    </div>
                </div>
                <div id="social-links" class="content">
                    <div class="content-header">
                        <h5 class="mb-0">Social Links</h5>
                        <small>Enter Your Social Links.</small>
                    </div>
                    <form>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-label" for="twitter">Twitter</label>
                                <input type="text" id="twitter" name="twitter" class="form-control"
                                    placeholder="https://twitter.com/abc" />
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label" for="facebook">Facebook</label>
                                <input type="text" id="facebook" name="facebook" class="form-control"
                                    placeholder="https://facebook.com/abc" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-label" for="google">Google+</label>
                                <input type="text" id="google" name="google" class="form-control"
                                    placeholder="https://plus.google.com/abc" />
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label" for="linkedin">Linkedin</label>
                                <input type="text" id="linkedin" name="linkedin" class="form-control"
                                    placeholder="https://linkedin.com/abc" />
                            </div>
                        </div>
                    </form>
                    <div class="d-flex justify-content-between">
                        <button class="btn btn-primary btn-prev">
                            <i data-feather="arrow-left" class="align-middle mr-sm-25 mr-0"></i>
                            <span class="align-middle d-sm-inline-block d-none">Previous</span>
                        </button>
                        <button class="btn btn-success btn-submit">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}
{{-- <section class="horizontal-wizard">
        <div class="bs-stepper horizontal-wizard-example">
            <div class="bs-stepper-header">
                <div class="step" data-target="#account-details">
                    <button type="button" class="step-trigger">
                        <span class="bs-stepper-box">1</span>
                        <span class="bs-stepper-label">
                            <span class="bs-stepper-title">Event Details</span>
                            <span class="bs-stepper-subtitle">Setup Event Details</span>
                        </span>
                    </button>
                </div>
                <div class="line">
                    <i data-feather="chevron-right" class="font-medium-2"></i>
                </div>
                <div class="step" data-target="#personal-info">
                    <button type="button" class="step-trigger">
                        <span class="bs-stepper-box">2</span>
                        <span class="bs-stepper-label">
                            <span class="bs-stepper-title">Event Info</span>
                            <span class="bs-stepper-subtitle">Add Personal Info</span>
                        </span>
                    </button>
                </div>
                <div class="line">
                    <i data-feather="chevron-right" class="font-medium-2"></i>
                </div>
                <div class="step" data-target="#address-step">
                    <button type="button" class="step-trigger">
                        <span class="bs-stepper-box">3</span>
                        <span class="bs-stepper-label">
                            <span class="bs-stepper-title">Event Meta Info</span>
                            <span class="bs-stepper-subtitle">Add Event Meta Info</span>
                        </span>
                    </button>
                </div>
                <div class="line">
                    <i data-feather="chevron-right" class="font-medium-2"></i>
                </div>
                <div class="step" data-target="#event-info">
                    <button type="button" class="step-trigger">
                        <span class="bs-stepper-box">4</span>
                        <span class="bs-stepper-label">
                            <span class="bs-stepper-title">Event Other Info</span>
                            <span class="bs-stepper-subtitle">Add Event Other Info</span>
                        </span>
                    </button>
                </div>
                <div class="line">
                    <i data-feather="chevron-right" class="font-medium-2"></i>
                </div>
                <div class="step" data-target="#social-links">
                    <button type="button" class="step-trigger">
                        <span class="bs-stepper-box">4</span>
                        <span class="bs-stepper-label">
                            <span class="bs-stepper-title">Event Descriptions</span>
                            <span class="bs-stepper-subtitle">Add Event Descriptions</span>
                        </span>
                    </button>
                </div>
            </div> --}}
{{-- <form name="ajax_form" method="post" action="{{ route('product.store') }}" enctype="multipart/form-data"> --}}
{{-- <div class="bs-stepper-content">
                @csrf
                <div id="account-details" class="content">
                    <div class="content-header">
                        <h5 class="mb-0">Account Details</h5>
                        <small class="text-muted">Enter Your Account Details.</small>
                    </div>
                    <form>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-label" for="vertical-username">Event Name</label>
                                <input type="text" id="vertical-username" class="form-control"
                                    placeholder="Event Name" name="name"/>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label" for="vertical-email">Event SKU</label>
                                <input type="text" id="vertical-email" name="sku" class="form-control" placeholder="JOHN256OP"
                                    aria-label="john.doe" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-label" for="vertical-country">Event Category</label>
                                <select class="select2 w-100" id="vertical-country" name="category_id">
                                    <option value="" selected>Select Event</option>
                                    @if (!empty($category))
                                    @foreach ($category as $category)
                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group form-password-toggle col-md-6">
                                <label class="form-label" for="vertical-confirm-password">Event Status</label>
                                <select class="form-control" id="basicSelect" name="status">
                                    <option value="" selected>Select Status</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </form>
                    <div class="d-flex justify-content-between">
                        <button class="btn btn-outline-secondary btn-prev" disabled>
                            <i data-feather="arrow-left" class="align-middle mr-sm-25 mr-0"></i>
                            <span class="align-middle d-sm-inline-block d-none">Previous</span>
                        </button>
                        <button class="btn btn-primary btn-next">
                            <span class="align-middle d-sm-inline-block d-none">Next</span>
                            <i data-feather="arrow-right" class="align-middle ml-sm-25 ml-0"></i>
                        </button>
                    </div>
                </div>
                <div id="personal-info" class="content">
                    <div class="content-header">
                        <h5 class="mb-0">Personal Info</h5>
                        <small>Enter Your Personal Info.</small>
                    </div>
                    <form>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-label" for="first-name">First Name</label>
                                <input type="text" name="first-name" id="first-name" class="form-control"
                                    placeholder="John" />
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label" for="last-name">Last Name</label>
                                <input type="text" name="last-name" id="last-name" class="form-control"
                                    placeholder="Doe" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-label" for="country">Country</label>
                                <select class="select2 w-100" name="country" id="country">
                                    <option label=" "></option>
                                    <option>UK</option>
                                    <option>USA</option>
                                    <option>Spain</option>
                                    <option>France</option>
                                    <option>Italy</option>
                                    <option>Australia</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label" for="language">Language</label>
                                <select class="select2 w-100" name="language" id="language" multiple>
                                    <option>English</option>
                                    <option>French</option>
                                    <option>Spanish</option>
                                </select>
                            </div>
                        </div>
                    </form>
                    <div class="d-flex justify-content-between">
                        <button class="btn btn-primary btn-prev">
                            <i data-feather="arrow-left" class="align-middle mr-sm-25 mr-0"></i>
                            <span class="align-middle d-sm-inline-block d-none">Previous</span>
                        </button>
                        <button class="btn btn-primary btn-next">
                            <span class="align-middle d-sm-inline-block d-none">Next</span>
                            <i data-feather="arrow-right" class="align-middle ml-sm-25 ml-0"></i>
                        </button>
                    </div>
                </div>
                <div id="address-step" class="content">
                    <div class="content-header">
                        <h5 class="mb-0">Address</h5>
                        <small>Enter Your Address.</small>
                    </div>
                    <form>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-label" for="address">Address</label>
                                <input type="text" id="address" name="address" class="form-control"
                                    placeholder="98  Borough bridge Road, Birmingham" />
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label" for="landmark">Landmark</label>
                                <input type="text" name="landmark" id="landmark" class="form-control"
                                    placeholder="Borough bridge" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-label" for="pincode1">Pincode</label>
                                <input type="text" id="pincode1" class="form-control" placeholder="658921" />
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label" for="city1">City</label>
                                <input type="text" id="city1" class="form-control" placeholder="Birmingham" />
                            </div>
                        </div>
                    </form>
                    <div class="d-flex justify-content-between">
                        <button class="btn btn-primary btn-prev">
                            <i data-feather="arrow-left" class="align-middle mr-sm-25 mr-0"></i>
                            <span class="align-middle d-sm-inline-block d-none">Previous</span>
                        </button>
                        <button class="btn btn-primary btn-next">
                            <span class="align-middle d-sm-inline-block d-none">Next</span>
                            <i data-feather="arrow-right" class="align-middle ml-sm-25 ml-0"></i>
                        </button>
                    </div>
                </div>
                <div id="event-info" class="content">
                    <div class="content-header">
                        <h5 class="mb-0">Address</h5>
                        <small>Enter Your Address.</small>
                    </div>
                    <form>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-label" for="address">Address</label>
                                <input type="text" id="address" name="address" class="form-control"
                                    placeholder="98  Borough bridge Road, Birmingham" />
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label" for="landmark">Landmark</label>
                                <input type="text" name="landmark" id="landmark" class="form-control"
                                    placeholder="Borough bridge" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-label" for="pincode1">Pincode</label>
                                <input type="text" id="pincode1" class="form-control" placeholder="658921" />
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label" for="city1">City</label>
                                <input type="text" id="city1" class="form-control" placeholder="Birmingham" />
                            </div>
                        </div>
                    </form>
                    <div class="d-flex justify-content-between">
                        <button class="btn btn-primary btn-prev">
                            <i data-feather="arrow-left" class="align-middle mr-sm-25 mr-0"></i>
                            <span class="align-middle d-sm-inline-block d-none">Previous</span>
                        </button>
                        <button class="btn btn-primary btn-next">
                            <span class="align-middle d-sm-inline-block d-none">Next</span>
                            <i data-feather="arrow-right" class="align-middle ml-sm-25 ml-0"></i>
                        </button>
                    </div>
                </div>
                <div id="social-links" class="content">
                    <div class="content-header">
                        <h5 class="mb-0">Social Links</h5>
                        <small>Enter Your Social Links.</small>
                    </div>
                    <form>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-label" for="twitter">Twitter</label>
                                <input type="text" id="twitter" name="twitter" class="form-control"
                                    placeholder="https://twitter.com/abc" />
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label" for="facebook">Facebook</label>
                                <input type="text" id="facebook" name="facebook" class="form-control"
                                    placeholder="https://facebook.com/abc" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-label" for="google">Google+</label>
                                <input type="text" id="google" name="google" class="form-control"
                                    placeholder="https://plus.google.com/abc" />
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label" for="linkedin">Linkedin</label>
                                <input type="text" id="linkedin" name="linkedin" class="form-control"
                                    placeholder="https://linkedin.com/abc" />
                            </div>
                        </div>
                    </form>
                    <div class="d-flex justify-content-between">
                        <button class="btn btn-primary btn-prev">
                            <i data-feather="arrow-left" class="align-middle mr-sm-25 mr-0"></i>
                            <span class="align-middle d-sm-inline-block d-none">Previous</span>
                        </button>
                        <button class="btn btn-success btn-submit" type="submit">Submit</button>
                    </div>
                </div>
           
            </div> --}}
{{-- </form> --}}
{{-- </div>
    </section> --}}
