@extends('layouts.app')

@section('title')

@endsection

@section('content')
<div class="dt-content">

    <!-- Grid -->
    <div class="row">
        <div class="col-xl-12 pb-3">
            <ol class="breadcrumb bg-white">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                <li class="active breadcrumb-item"></li>
              </ol>
        </div>
        <!-- Grid Item -->
        <div class="col-xl-12">

            <!-- Entry Header -->
            <div class="dt-entry__header">

                <!-- Entry Heading -->
                <div class="dt-entry__heading">
                    <h2 class="dt-page__title mb-0 text-primary"></h2>
                </div>
            </div>
            <!-- /entry header -->

            <!-- Card -->
            <div class="dt-card">

                <!-- Card Body -->
                <div class="dt-card__body">
                @if(session('success'))
                    <div class="alert alert-success">
                        {!! session('success') !!}
                    </div>
                @endif
                <form action="{{ route('file.import') }}" method="POST" enctype="multipart/form-data" method="post">
                @csrf
                <!-- Modal Body -->
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="update_id" id="update_id" />
                            <div class="form-group col-md-4 required border">
                                <label class="mt-4">Location Import</label>
                                <?php

                                if(!$locations->isEMpty()){?>
                                <p style="color:red;">! Already data imported. First Delete the data then again upload the file.</p>
                                <?php }else{ ?>
                                    <input type="file" name="file" class="form-control"/>
                                    <button class="btn btn-success mt-2 mb-3">Import Location Data</button>
                                <?php } ?>

                            </div>

                            <div class="form-group col-md-4 required border ml-5">
                                <label class="mt-4 mb-5">Download Export File</label><br>
                                <a href="{{route('file.export')}}" class="bg-success rounded p-2 mt-4" style="color:#fff">Download Excel File</a>

                            </div>






                        </div>
                    </div>
                    <!-- /modal body -->


                    <!-- Modal Footer -->



                    <!-- /modal footer -->
                </form>




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
<script>
$(document).on('click', '#save-btn', function () {
        let form = document.getElementById('store_or_update_form');
        let formData = new FormData(form);
        let url = "{{route('file.import')}}";
        let id = $('#update_id').val();
        let method;
        if (id) {
            method = 'update';
        } else {
            method = 'add';
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
            $('#save-btn').addClass('kt-spinner kt-spinner--md kt-spinner--light');
        },
        complete: function(){
            $('#save-btn').removeClass('kt-spinner kt-spinner--md kt-spinner--light');
        },
        success: function (data) {
            $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
            $('#store_or_update_form').find('.error').remove();
            if (data.status == false) {
                $.each(data.errors, function (key, value) {
                    $('#store_or_update_form input#' + key).addClass('is-invalid');
                    $('#store_or_update_form textarea#' + key).addClass('is-invalid');
                    $('#store_or_update_form select#' + key).parent().addClass('is-invalid');
                    if(key == 'password' || key == 'password_confirmation'){
                        $('#store_or_update_form #' + key).parents('.form-group').append(
                        '<small class="error text-danger">' + value + '</small>');
                    }else{
                        $('#store_or_update_form #' + key).parent().append(
                        '<small class="error text-danger">' + value + '</small>');
                    }

                });
            } else {
                notification(data.status, data.message);
                if (data.status == 'success') {
                    if (method == 'update') {
                        table.ajax.reload(null, false);
                    } else {
                        table.ajax.reload();
                    }
                    $('#store_or_update_modal').modal('hide');
                }
            }

        },
        error: function (xhr, ajaxOption, thrownError) {
            console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
        }
    });
});
</script>

@endpush
