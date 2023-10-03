@extends('layouts.app')

@section('title')
    {{ $page_title }}
@endsection

@push('stylesheet')

@endpush

@section('content')
<div class="dt-content">

    <!-- Grid -->
    <div class="row">
        <div class="col-xl-12 pb-3">
            <ol class="breadcrumb bg-white">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                <li class="active breadcrumb-item">{{ $sub_title }}</li>
              </ol>
        </div>
        <!-- Grid Item -->
        <div class="col-xl-12">

            <!-- Entry Header -->
            <div class="dt-entry__header">

                <!-- Entry Heading -->
                <div class="dt-entry__heading">
                    <h2 class="dt-page__title mb-0 text-primary"><i class="{{ $page_icon }}"></i> {{ $sub_title }}</h2>
                </div>
                <!-- /entry heading -->

            </div>
            <!-- /entry header -->

            <!-- Card -->
            <div class="dt-card">

                <!-- Card Body -->
                <div class="dt-card__body">
                    <div class="dt-card__body tabs-container tabs-vertical">

                        <!-- Tab Navigation -->
                        <ul class="nav nav-tabs flex-column" role="tablist">
                          <li class="nav-item">
                            <div class="nav-link active" data-toggle="tab" href="#general-setting"
                            role="tab" aria-controls="general-setting" aria-selected="true">General Setting
                            </div>
                          </li>
                        </ul>
                        <!-- /tab navigation -->

                        <!-- Tab Content -->
                        <div class="tab-content">

                          <!-- Tab Pane -->
                          <div id="general-setting" class="tab-pane active">
                            <div class="card-body">
                                <form id="general-form" class="col-md-12" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <x-form.textbox labelName="Company Name" name="title" required="required" value="{{ config('settings.title') }}"
                                        col="col-md-8" placeholder="Enter title"/>
                                        <x-form.textarea labelName="Company Address" name="address" required="required" value="{{ config('settings.address') }}"
                                        col="col-md-8" placeholder="Enter address"/>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="form-group col-md-6 required">
                                                    <label for="logo">Logo</label>
                                                    <div class="col-md-12 px-0 text-center">
                                                        <div id="logo">

                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="old_logo" id="old_logo" value="{{ config('settings.logo') }}">
                                                </div>
                                                <div class="form-group col-md-6 required">
                                                    <label for="logo">Favicon</label>
                                                    <div class="col-md-12 px-0 text-center">
                                                        <div id="favicon">

                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="old_favicon" id="old_favicon" value="{{ config('settings.favicon') }}">
                                                </div>
                                                <div class="form-group col-md-6 required">
                                                    <label for="footer">Footer Logo</label>
                                                    <div class="col-md-12 px-0 text-center">
                                                        <div id="footerlogo">

                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="old_footer" id="old_footer" value="{{ config('settings.footerlogo') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <x-form.textbox labelName="Copyright" name="copyright" required="required" value="{{ config('settings.copyright') }}"
                                        col="col-md-8" placeholder="Enter copyright text"/>

                                        <x-form.textbox labelName="Email" name="email" required="required" value="{{ config('settings.email') }}"
                                        col="col-md-8" placeholder="Enter Company Email"/>
                                        <x-form.textbox labelName="Phone" name="phone" required="required" value="{{ config('settings.phone') }}"
                                        col="col-md-8" placeholder="Enter Company Phone"/>
                                        <x-form.textbox labelName="Hot Number" name="hotnumber" required="required" value="{{ config('settings.hotnumber') }}"
                                        col="col-md-8" placeholder="Enter Company Hot Number"/>

                                        <!-- <div class="form-group col-md-8">
                                            <label for="">Currency Position</label><br>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="prefix" name="currency_position" value="prefix" class="custom-control-input"
                                                    {{ config('settings.currency_position') == 'prefix' ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="prefix">prefix</label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="suffix" name="currency_position" value="suffix" class="custom-control-input"
                                                {{ config('settings.currency_position') == 'suffix' ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="suffix">Suffix</label>
                                            </div>
                                        </div> -->

                                    </div>

                                    <div class="form-group col-md-12">
                                        <button type="reset" class="btn btn-danger btn-sm">Reset</button>
                                        <button type="button" class="btn btn-primary btn-sm" id="general-save-btn" onclick="save_data('general')">Save</button>
                                    </div>
                                </form>
                            </div>
                          </div>
                          <!-- /tab pane-->



                        </div>
                        <!-- /tab content -->

                      </div>
                </div>
                <!-- /card body -->

            </div>
            <!-- /card -->

        </div>
        <!-- /grid item -->

    </div>
    <!-- /grid -->

</div>
@endsection

@push('script')
<script src="js/spartan-multi-image-picker-min.js"></script>
<script>
$(document).ready(function(){
    $('#logo').spartanMultiImagePicker({
        fieldName: 'logo',
        maxCount: 1,
        rowHeight: '200px',
        groupClassName: 'col-md-12 com-sm-12 com-xs-12',
        maxFileSize: '',
        dropFileLabel: 'Drop Here',
        allowExt: 'png|jpg|jpeg',
        onExtensionErr: function(index, file){
            Swal.fire({icon:'error',title:'Oops...',text: 'Only png, jpg and jpeg file format allowed!'});
        }
    });
    $('#favicon').spartanMultiImagePicker({
        fieldName: 'favicon',
        maxCount: 1,
        rowHeight: '200px',
        groupClassName: 'col-md-12 com-sm-12 com-xs-12',
        maxFileSize: '',
        dropFileLabel: 'Drop Here',
        allowExt: 'png',
        onExtensionErr: function(index, file){
            Swal.fire({icon:'error',title:'Oops...',text: 'Only png file format allowed!'});
        }
    });
    $('#footerlogo').spartanMultiImagePicker({
        fieldName: 'footerlogo',
        maxCount: 1,
        rowHeight: '200px',
        groupClassName: 'col-md-12 com-sm-12 com-xs-12',
        maxFileSize: '',
        dropFileLabel: 'Drop Here',
        allowExt: 'png',
        onExtensionErr: function(index, file){
            Swal.fire({icon:'error',title:'Oops...',text: 'Only png file format allowed!'});
        }
    });

    $('input[name="logo"],input[name="favicon"],input[name="footerlogo"]').prop('required',true);

    $('.remove-files').on('click', function(){
        $(this).parents('.col-md-12').remove();
    });

    @if(config('settings.logo'))
    $('#logo img.spartan_image_placeholder').css('display','none');
    $('#logo .spartan_remove_row').css('display','none');
    $('#logo .img_').css('display','block');
    $('#logo .img_').attr('src','{{ asset("storage/".LOGO_PATH.config("settings.logo")) }}');
    @endif

    @if(config('settings.favicon'))
    $('#favicon img.spartan_image_placeholder').css('display','none');
    $('#favicon .spartan_remove_row').css('display','none');
    $('#favicon .img_').css('display','block');
    $('#favicon .img_').attr('src','{{ asset("storage/".LOGO_PATH.config("settings.favicon")) }}');
    @endif

    @if(config('settings.footerlogo'))
    $('#footerlogo img.spartan_image_placeholder').css('display','none');
    $('#footerlogo .spartan_remove_row').css('display','none');
    $('#footerlogo .img_').css('display','block');
    $('#footerlogo .img_').attr('src','{{ asset("storage/".LOGO_PATH.config("settings.footerlogo")) }}');
    @endif

});

function save_data(form_id) {
    let form = document.getElementById(form_id+'-form');
    let formData = new FormData(form);
    let url;
    if(form_id == 'general'){
        url = "{{ route('general.setting') }}";
    }else{
        url = "{{ route('mail.setting') }}";
    }
    $.ajax({
        url: url,
        type: "POST",
        data: formData,
        dataType: "JSON",
        contentType: false,
        processData: false,
        cache: false,
        beforeSend: function(){
            $('#'+form_id+'-save-btn').addClass('kt-spinner kt-spinner--md kt-spinner--light');
        },
        complete: function(){
            $('#'+form_id+'-save-btn').removeClass('kt-spinner kt-spinner--md kt-spinner--light');
        },
        success: function (data) {
            $('#'+form_id+'-form').find('.is-invalid').removeClass('is-invalid');
            $('#'+form_id+'-form').find('.error').remove();
            if (data.status == false) {
                $.each(data.errors, function (key, value) {
                    $('#'+form_id+'-form input#' + key).addClass('is-invalid');
                    $('#'+form_id+'-form textarea#' + key).addClass('is-invalid');
                    $('#'+form_id+'-form select#' + key).parent().addClass('is-invalid');
                $('#'+form_id+'-form #' + key).parent().append(
                    '<small class="error text-danger">' + value + '</small>');
                });
            } else {
                notification(data.status, data.message);
            }
        },
        error: function (xhr, ajaxOption, thrownError) {
            console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
        }
    });
}
</script>
@endpush
