
@extends('layouts/contentLayoutMaster')

@section('title', 'Account Settings')

@section('settings_active', 'active')

@section('vendor-style')
  <!-- vendor css files -->
  <link rel='stylesheet' href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
  <link rel='stylesheet' href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/toastr.min.css')) }}">
@endsection
@section('page-style')
  <!-- Page css files -->
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-pickadate.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/sweetalert2.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/extensions/ext-component-sweet-alerts.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/extensions/ext-component-toastr.css')) }}">

@endsection

@section('content')
<!-- account setting page -->
<section class="app-user-list">
    <!-- list section start -->
    <div class="card">

        <div class="card-header border-bottom">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb" style="padding: 0.1rem 0.1rem;">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Seo</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Settings</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- list section end -->
</section>

<section id="page-account-settings">
  <div class="row">
    <!-- left menu section -->
    <div class="col-md-3 mb-2 mb-md-0">
      <ul class="nav nav-pills flex-column nav-left">
        <!-- general -->
        <li class="nav-item">
          <a
            class="nav-link active"
            id="account-pill-general"
            data-toggle="pill"
            href="#account-vertical-general"
            aria-expanded="true"
          >
            <i data-feather='tool'  class="font-medium-3 mr-1"></i>
            <span class="font-weight-bold">Seo Setting</span>
          </a>
        </li>
        <!-- change password -->
        <li class="nav-item">
          <a
            class="nav-link"
            id="account-pill-password"
            data-toggle="pill"
            href="#account-vertical-password"
            aria-expanded="false"
          >
          <i data-feather='trending-up' class="font-medium-3 mr-1"></i>
            <span class="font-weight-bold">Seo Analytics</span>
          </a>
        </li>
      </ul>
    </div>
    <!--/ left menu section -->

    <!-- right content section -->
    <div class="col-md-9">
      <div class="card">
        <div class="card-body">
          <div class="tab-content">
            <!-- general tab -->
            <div
              role="tabpanel"
              class="tab-pane active"
              id="account-vertical-general"
              aria-labelledby="account-pill-general"
              aria-expanded="true"
            >
              <!-- header media -->
              {{-- <div class="media">
                <a href="javascript:void(0);" class="mr-25">
                  <img
                    src="{{asset('images/portrait/small/avatar-s-11.jpg')}}"
                    id="account-upload-img"
                    class="rounded mr-50"
                    alt="profile image"
                    height="80"
                    width="80"
                  />
                </a>
                <!-- upload and reset button -->
                <div class="media-body mt-75 ml-1">
                  <label for="account-upload" class="btn btn-sm btn-primary mb-75 mr-75">Upload</label>
                  <input type="file" id="account-upload" hidden accept="image/*" />
                  <button class="btn btn-sm btn-outline-secondary mb-75">Reset</button>
                  <p>Allowed JPG, GIF or PNG. Max size of 800kB</p>
                </div>
                <!--/ upload and reset button -->
              </div> --}}
              <!--/ header media -->

              <!-- form -->
              <form class="validate-form" name="ajax_form" method="post" action="{{ route('seo.update',$results[0]['id']) }}" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-2 text-primary">
                            <i data-feather='tool'></i>
                            <h4 class="mb-0 ml-75 text-primary">Seo Setting</h4>
                        </div>
                      </div>
                  <div class="col-12 col-sm-6">
                    <div class="form-group">
                        <label class="form-label" for="basic-icon-default-fullname">Seo Title</label>
                            <input type="text" class="form-control dt-full-name" id="basic-icon-default-fullname" placeholder="Enter Seo Title" name="meta_title" aria-label="John Doe" aria-describedby="basic-icon-default-fullname2" value="{{$results[0]['meta_title']}}" />
                    </div>
                  </div>
                  <div class="col-12 col-sm-6">
                    <div class="form-group">
                        <label class="form-label" for="basic-icon-default-fullname">Seo Tag</label>
                        <input type="text" class="form-control dt-full-name" id="basic-icon-default-fullname"
                            placeholder="Enter Seo Tag" name="meta_tag" aria-label="John Doe"
                            aria-describedby="basic-icon-default-fullname2" value="{{$results[0]['meta_tag']}}"/>
                    </div>
                  </div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-12">
                        <div class="form-group">
                            <label class="form-label" for="basic-icon-default-fullname">Seo Description</label>
                            <textarea class="form-control" id="exampleFormControlTextarea1" rows="5" placeholder="Textarea" name="meta_description">{{$results[0]['meta_description']}}</textarea>
                        </div>
                      </div>
                      <div class="col-12 col-sm-12">
                        <div class="media">
                            <a href="javascript:void(0);" class="mr-25">
                              <img
                                src="{{asset('uploads/seo_image')}}/{{$results[0]['meta_image']}}"
                                id="account-upload-img"
                                class="rounded mr-50"
                                alt="profile image"
                                height="100"
                                width="100"
                              />
                            </a>
                            <!-- upload and reset button -->
                            <div class="media-body mt-75 ml-1">
                              <label for="account-upload" class="btn btn-sm btn-primary mb-75 mr-75">Upload</label>
                              <input type="file" name="meta_image" class="custom-file-input" id="account-upload" hidden accept="image/*">
                              <button class="btn btn-sm btn-outline-secondary mb-75">Reset</button>
                              <p>Allowed JPG, GIF or PNG. Max size of 800kB</p>
                            </div>
                            <!--/ upload and reset button -->
                          </div>
                      </div>
                      <div class="col-12">
                        <button type="submit" class="btn btn-primary mt-2 mr-1">Save changes</button>
                        <button type="reset" class="btn btn-outline-secondary mt-2">Cancel</button>
                      </div>
                </div>
              </form>
              <!--/ form -->
            </div>
            <!--/ general tab -->

            <!-- change password -->
            <div
              class="tab-pane fade"
              id="account-vertical-password"
              role="tabpanel"
              aria-labelledby="account-pill-password"
              aria-expanded="false"
            >

              <!-- form -->
              <form class="validate-form" name="ajax_form" method="post" action="{{ route('seo.metaformula',$results[0]['id']) }}" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-2 text-primary">
                          <i data-feather='trending-up'></i>
                          <h4 class="mb-0 ml-75 text-primary">Seo Analytics</h4>
                        </div>
                      </div>
                  <div class="col-12 col-sm-12">
                    <div class="form-label-group">
                        <textarea class="form-control" id="label-textarea" rows="3" placeholder="Enter Seo Analytics" name="meta_formula"></textarea>
                        <label for="label-textarea"></label>
                    </div>
                  </div>
                  <div class="col-12">
                    <button type="submit" class="btn btn-primary mr-1 mt-1">Save changes</button>
                    <button type="reset" class="btn btn-outline-secondary mt-1">Cancel</button>
                  </div>
                </div>
              </form>
              <!--/ form -->
            </div>
            <!--/ change password -->
          </div>
        </div>
      </div>
    </div>
    <!--/ right content section -->
  </div>
</section>
<!-- / account setting page -->
@endsection

@section('vendor-script')
  <!-- vendor files -->
  <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>
@endsection
@section('page-script')
  <!-- Page js files -->
  <script src="{{ asset(mix('js/scripts/extensions/ext-component-sweet-alerts.js')) }}"></script>
  <script src="{{ asset(mix('js/scripts/extensions/ext-component-toastr.js')) }}"></script>
  <script src="{{ asset(mix('js/scripts/pages/page-account-settings.js')) }}"></script>

  <script src="{{ asset('js/scripts/common/common-action.js') }}"></script>

@endsection