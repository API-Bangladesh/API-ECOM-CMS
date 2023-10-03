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
                @if (permission('pimage-add'))
                <button class="btn btn-primary btn-sm" onclick="showFormModal('Add New Product','Save');">
                    <i class="fas fa-plus-square"></i> Add New
                 </button>
                @endif


            </div>
            <!-- /entry header -->

            <!-- Card -->
            <div class="dt-card">

                <!-- Card Body -->
                <div class="dt-card__body">

                    <form id="form-filter">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="name">Product Name</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Enter Product Name">
                            </div>
                            <div class="form-group col-md-8 pt-24">
                               <button type="button" class="btn btn-danger btn-sm float-right" id="btn-reset"
                               data-toggle="tooltip" data-placement="top" data-original-title="Reset Data">
                                   <i class="fas fa-redo-alt"></i>
                                </button>
                               <button type="button" class="btn btn-primary btn-sm float-right mr-2" id="btn-filter"
                               data-toggle="tooltip" data-placement="top" data-original-title="Filter Data">
                                   <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    <table id="dataTable" class="table table-striped table-bordered table-hover">
                        <thead class="bg-primary">
                            <tr>
                                @if (permission('pimage-bulk-delete'))
                                <th>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="select_all" onchange="select_all()">
                                        <label class="custom-control-label" for="select_all"></label>
                                    </div>
                                </th>
                                @endif
                                <th>Sl</th>
                                <th>Product Name</th>
                                <th>Image</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                </div>
                <!-- /card body -->

            </div>
            <!-- /card -->

        </div>
        <!-- /grid item -->

    </div>
    <!-- /grid -->

</div>
@include('productimage::modal')
@endsection

@push('script')
<script src="js/spartan-multi-image-picker-min.js"></script>
<script>
var table;
$(document).ready(function(){

    table = $('#dataTable').DataTable({
        "processing": true, //Feature control the processing indicator
        "serverSide": true, //Feature control DataTable server side processing mode
        "order": [], //Initial no order
        "responsive": true, //Make table responsive in mobile device
        "bInfo": true, //TO show the total number of data
        "bFilter": false, //For datatable default search box show/hide
        "lengthMenu": [
            [5, 10, 15, 25, 50, 100, 1000, 10000, -1],
            [5, 10, 15, 25, 50, 100, 1000, 10000, "All"]
        ],
        "pageLength": 10, //number of data show per page
        "language": {
            processing: `<i class="fas fa-spinner fa-spin fa-3x fa-fw text-primary"></i> `,
            emptyTable: '<strong class="text-danger">No Data Found</strong>',
            infoEmpty: '',
            zeroRecords: '<strong class="text-danger">No Data Found</strong>'
        },
        "ajax": {
            "url": "{{route('pimage.datatable.data')}}",
            "type": "POST",
            "data": function (data) {
                data._token      = _token;
            }
        },
        "columnDefs": [{
                @if (permission('pimage-bulk-delete'))
                "targets": [0,4],
                @else
                "targets": [3],
                @endif
                "orderable": false,
                "className": "text-center"
            },
            {
                @if (permission('pimage-bulk-delete'))
                "targets": [1,2,3,4],
                @else
                "targets": [0,1,2,3],
                @endif
                "className": "text-center"
            },
            {
                @if (permission('pimage-bulk-delete'))
                "targets": [3,4],
                @else
                "targets": [2,3],
                @endif
                "className": "text-right"
            }
        ],
        "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6' <'float-right'B>>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'<'float-right'p>>>",

        "buttons": [
            @if (permission('pimage-report'))
            {
                'extend':'colvis','className':'btn btn-secondary btn-sm text-white','text':'Column'
            },
            {
                "extend": 'print',
                'text':'Print',
                'className':'btn btn-secondary btn-sm text-white',
                "title": " List",
                "orientation": "landscape", //portrait
                "pageSize": "A4", //A3,A5,A6,legal,letter
                "exportOptions": {
                    columns: function (index, data, node) {
                        return table.column(index).visible();
                    }
                },
                customize: function (win) {
                    $(win.document.body).addClass('bg-white');
                },
            },
            {
                "extend": 'csv',
                'text':'CSV',
                'className':'btn btn-secondary btn-sm text-white',
                "title": "Prodcut Image List",
                "filename": "prodcut-image-list",
                "exportOptions": {
                    columns: function (index, data, node) {
                        return table.column(index).visible();
                    }
                }
            },
            {
                "extend": 'excel',
                'text':'Excel',
                'className':'btn btn-secondary btn-sm text-white',
                "title": "Prodcut Image List",
                "filename": "prodcut-image-list",
                "exportOptions": {
                    columns: function (index, data, node) {
                        return table.column(index).visible();
                    }
                }
            },
            {
                "extend": 'pdf',
                'text':'PDF',
                'className':'btn btn-secondary btn-sm text-white',
                "title": "Prodcut Image List",
                "filename": "prodcut-image-list",
                "orientation": "landscape", //portrait
                "pageSize": "A4", //A3,A5,A6,legal,letter
                "exportOptions": {
                    columns: [1, 2, 3, 4]
                },
            },
            @endif
            @if (permission('pimage-bulk-delete'))
            {
                'className':'btn btn-danger btn-sm delete_btn d-none text-white',
                'text':'Delete',
                action:function(e,dt,node,config){
                    multi_delete();
                }
            }
            @endif
        ],
    });

    $('#btn-filter').click(function () {
        table.ajax.reload();
    });

    $('#btn-reset').click(function () {
        $('#form-filter')[0].reset();
        $('#form-filter .selectpicker').selectpicker('refresh');
        table.ajax.reload();
    });

    $(document).on('click', '#save-btn', function (event) {
        let form = document.getElementById('store_or_update_form');
        let formData = new FormData(form);
        let url = "{{route('pimage.store.or.update')}}";
        let id = $('#update_id').val();
        let method;
        if (id) {
            method = 'update';
        } else {
            method = 'add';
        }
        event.preventDefault();
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
                    if(key == 'code'){
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
                   $('#image').empty();
                    $('#image').spartanMultiImagePicker({
                        fieldName: 'fileUpload[]',
                        rowHeight: '100px',
                        groupClassName: 'col-md-4 com-sm-4 com-xs-4',
                        maxFileSize: '',
                        dropFileLabel: 'Drop Here',
                        allowExt: 'png|jpg|jpeg',
                        onExtensionErr: function(index, file) {
                            Swal.fire({ icon: 'error', title: 'Oops...', text: 'Only png, jpg, jpeg file format allowed!' });
                        }
                        });

                }
            }

        },
        error: function (xhr, ajaxOption, thrownError) {
            console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
        }
    });
    });

    $(document).on('click', '.edit_data', function () {
        let id = $(this).data('id');
        $('#store_or_update_form')[0].reset();
        $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
        $('#store_or_update_form').find('.error').remove();
        if (id) {
            $.ajax({
                url: "{{route('pimage.edit')}}",
                type: "POST",
                data: { id: id,_token: _token},
                dataType: "JSON",
                success: function (data) {

                    $('#store_or_update_form #update_id').val(data.id);
                    $('#store_or_update_form #product_id').val(data.product_id);
                    $('#store_or_update_form #old_image').val(data.image);
                    $('#store_or_update_form .selectpicker').selectpicker('refresh');
                    if(data.image){
                        var image = "{{ asset('storage/'.PRODUCT_MULTI_IMAGE_PATH)}}/"+data.image;
                        $('#store_or_update_form #image img.spartan_image_placeholder').css('display','none');
                        $('#store_or_update_form #image .spartan_remove_row').css('display','none');
                        $('#store_or_update_form #image .img_').css('display','block');
                        $('#store_or_update_form #image .img_').attr('src',image);
                    }else{
                        $('#store_or_update_form #image img.spartan_image_placeholder').css('display','block');
                        $('#store_or_update_form #image .spartan_remove_row').css('display','none');
                        $('#store_or_update_form #image .img_').css('display','none');
                        $('#store_or_update_form #image .img_').attr('src','');
                    }

                    $('#store_or_update_modal').modal({
                        keyboard: false,
                        backdrop: 'static',
                    });
                    $('#store_or_update_modal .modal-title').html(
                        '<i class="fas fa-edit"></i> <span>Edit ' + data.product_id + '</span>');
                    $('#store_or_update_modal #save-btn').text('Update');

                },
                error: function (xhr, ajaxOption, thrownError) {
                    console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
                }
            });
        }
    });




    $(document).on('click', '.delete_data', function () {
        let id    = $(this).data('id');
        let name  = $(this).data('name');
        let row   = table.row($(this).parent('tr'));
        let url   = "{{ route('pimage.delete') }}";
        delete_data(id, url, table, row, name);
    });

    function multi_delete(){
        let ids = [];
        let rows;
        $('.select_data:checked').each(function(){
            ids.push($(this).val());
            rows = table.rows($('.select_data:checked').parents('tr'));
        });
        if(ids.length == 0){
            Swal.fire({
                type:'error',
                title:'Error',
                text:'Please checked at least one row of table!',
                icon: 'warning',
            });
        }else{
            let url = "{{route('pimage.bulk.delete')}}";
            bulk_delete(ids,url,table,rows);
        }
    }

    $(document).on('click', '.change_status', function () {
        let id    = $(this).data('id');
        let status = $(this).data('status');
        let name  = $(this).data('name');
        let row   = table.row($(this).parent('tr'));
        let url   = "{{ route('pimage.change.status') }}";
        change_status(id,status,name,table,url);

    });

    $('#image').spartanMultiImagePicker({
        fieldName: 'fileUpload[]',
        rowHeight: '100px',
        groupClassName: 'col-md-4 com-sm-4 com-xs-4',
        maxFileSize: '',
        dropFileLabel: 'Drop Here',
        allowExt: 'png|jpg|jpeg',
        onExtensionErr: function(index, file){
            Swal.fire({icon:'error',title:'Oops...',text: 'Only png,jpg,jpeg file format allowed!'});
        }
    });

    $('input[name="image"]').prop('required',true);

    $('.remove-files').on('click', function(){
        $(this).parents('.col-md-12').remove();
    });




});



function showStoreFormModal(modal_title, btn_text)
{
    $('#store_or_update_form')[0].reset();
    $('#store_or_update_form #fileUpload')[0].reset();
    $('#store_or_update_form #update_id').val('');
    $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
    $('#store_or_update_form').find('.error').remove();

    $('#store_or_update_form #fileUpload img.spartan_image_placeholder').css('display','block');
    $('#store_or_update_form #fileUpload .spartan_remove_row').css('display','none');
    $('#store_or_update_form #fileUpload .img_').css('display','none');
    $('#store_or_update_form #fileUpload .img_').attr('src','');
    $('.selectpicker').selectpicker('refresh');
    $('#store_or_update_modal').modal({
        keyboard: false,
        backdrop: 'static',
    });
    $('#store_or_update_modal .modal-title').html('<i class="fas fa-plus-square"></i> '+modal_title);
    $('#store_or_update_modal #save-btn').text(btn_text);
}
function clearOldImage() {

    // $('#image').html(``);
    // $('#image').append(`<div class="col-md-4 com-sm-4 com-xs-4 spartan_item_wrapper" data-spartanindexrow="1" style="margin-bottom : 20px; "><div style="position: relative;"><div class="spartan_item_loader" data-spartanindexloader="3" style=" position: absolute; width: 100%; height: 100px; background: rgba(255,255,255, 0.7); z-index: 22; text-align: center; align-items: center; margin: auto; justify-content: center; flex-direction: column; display : none; font-size : 1.7em; color: #CECECE"><i class="fa fa-sync fa-spin"></i></div><label class="file_upload" style="width: 100%; height: 100px; border: 2px dashed #ddd; border-radius: 3px; cursor: pointer; text-align: center; overflow: hidden; padding: 5px; margin-top: 5px; margin-bottom : 5px; position : relative; display: flex; align-items: center; margin: auto; justify-content: center; flex-direction: column;"><a href="javascript:void(0)" data-spartanindexremove="3" style="position: absolute !important; right : 3px; top: 3px; display : none; background : #ED3C20; border-radius: 3px; width: 30px; height: 30px; line-height : 30px; text-align: center; text-decoration : none; color : #FFF;" class="spartan_remove_row"><i class="fa fa-times"></i></a><img style="width: 64px; margin: 0 auto; vertical-align: middle;" data-spartanindexi="1" src="data:image/png;base64,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" class="spartan_image_placeholder"> <p data-spartanlbldropfile="3" style="color : #5FAAE1; display: none; width : auto; ">Drop Here</p><img style="width: 100%; vertical-align: middle; display:none;" class="img_" data-spartanindeximage="3"><input class="form-control spartan_image_input" accept="image/*" data-spartanindexinput="3" style="display : none" name="fileUpload[]" type="file"></label> </div></div>`);

//     $('#store_or_update_form #image .img_').attr('src', '');
//     $('.spartan_remove_row').hide();
//     // Get the parent div element
//     const imageDiv = document.getElementById("image");
//
// // Get all img elements within the div
//     const imgElements = imageDiv.getElementsByClassName("spartan_item_wrapper");
//
// // Check if there are multiple img elements
//     if (imgElements.length > 1) {
//         // Remove all img elements except the first one
//         for (let i = imgElements.length - 1; i > 0; i--) {
//             imageDiv.removeChild(imgElements[i]);
//         }
//         // $('.spartan_item_wrapper').html(`<div style="position: relative;"><div class="spartan_item_loader" data-spartanindexloader="1" style=" position: absolute; width: 100%; height: 100px; background: rgba(255,255,255, 0.7); z-index: 22; text-align: center; align-items: center; margin: auto; justify-content: center; flex-direction: column; display : none; font-size : 1.7em; color: #CECECE"><i class="fa fa-sync fa-spin"></i></div><label class="file_upload" style="width: 100%; height: 100px; border: 2px dashed #ddd; border-radius: 3px; cursor: pointer; text-align: center; overflow: hidden; padding: 5px; margin-top: 5px; margin-bottom : 5px; position : relative; display: flex; align-items: center; margin: auto; justify-content: center; flex-direction: column;"><a href="javascript:void(0)" data-spartanindexremove="1" style="position: absolute !important; right : 3px; top: 3px; display : none; background : #ED3C20; border-radius: 3px; width: 30px; height: 30px; line-height : 30px; text-align: center; text-decoration : none; color : #FFF;" class="spartan_remove_row"><i class="fa fa-times"></i></a><img style="width: 64px; margin: 0 auto; vertical-align: middle;" data-spartanindexi="1" src="data:image/png;base64,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" class="spartan_image_placeholder"> <p data-spartanlbldropfile="1" style="color : #5FAAE1; display: none; width : auto; ">Drop Here</p><img style="width: 100%; vertical-align: middle; display:none;" class="img_" data-spartanindeximage="1"><input class="form-control spartan_image_input" accept="image/*" data-spartanindexinput="1" style="display : none" name="fileUpload[]" type="file"></label> </div>`);
//         $('#image').html(``);
//         $('#image').append(`<div class="col-md-4 com-sm-4 com-xs-4 spartan_item_wrapper" data-spartanindexrow="3" style="margin-bottom : 20px; "><div style="position: relative;"><div class="spartan_item_loader" data-spartanindexloader="3" style=" position: absolute; width: 100%; height: 100px; background: rgba(255,255,255, 0.7); z-index: 22; text-align: center; align-items: center; margin: auto; justify-content: center; flex-direction: column; display : none; font-size : 1.7em; color: #CECECE"><i class="fa fa-sync fa-spin"></i></div><label class="file_upload" style="width: 100%; height: 100px; border: 2px dashed #ddd; border-radius: 3px; cursor: pointer; text-align: center; overflow: hidden; padding: 5px; margin-top: 5px; margin-bottom : 5px; position : relative; display: flex; align-items: center; margin: auto; justify-content: center; flex-direction: column;"><a href="javascript:void(0)" data-spartanindexremove="3" style="position: absolute !important; right : 3px; top: 3px; display : none; background : #ED3C20; border-radius: 3px; width: 30px; height: 30px; line-height : 30px; text-align: center; text-decoration : none; color : #FFF;" class="spartan_remove_row"><i class="fa fa-times"></i></a><img style="width: 64px; margin: 0 auto; vertical-align: middle;" data-spartanindexi="3" src="data:image/png;base64,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" class="spartan_image_placeholder"> <p data-spartanlbldropfile="3" style="color : #5FAAE1; display: none; width : auto; ">Drop Here</p><img style="width: 100%; vertical-align: middle; display:none;" class="img_" data-spartanindeximage="3"><input class="form-control spartan_image_input" accept="image/*" data-spartanindexinput="3" style="display : none" name="fileUpload[]" type="file"></label> </div></div>`);
//     }else{
        // $('.spartan_item_wrapper').html(`<div style="position: relative;"><div class="spartan_item_loader" data-spartanindexloader="1" style=" position: absolute; width: 100%; height: 100px; background: rgba(255,255,255, 0.7); z-index: 22; text-align: center; align-items: center; margin: auto; justify-content: center; flex-direction: column; display : none; font-size : 1.7em; color: #CECECE"><i class="fa fa-sync fa-spin"></i></div><label class="file_upload" style="width: 100%; height: 100px; border: 2px dashed #ddd; border-radius: 3px; cursor: pointer; text-align: center; overflow: hidden; padding: 5px; margin-top: 5px; margin-bottom : 5px; position : relative; display: flex; align-items: center; margin: auto; justify-content: center; flex-direction: column;"><a href="javascript:void(0)" data-spartanindexremove="1" style="position: absolute !important; right : 3px; top: 3px; display : none; background : #ED3C20; border-radius: 3px; width: 30px; height: 30px; line-height : 30px; text-align: center; text-decoration : none; color : #FFF;" class="spartan_remove_row"><i class="fa fa-times"></i></a><img style="width: 64px; margin: 0 auto; vertical-align: middle;" data-spartanindexi="1" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAQAAAAAYLlVAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAAmJLR0QAAKqNIzIAAAAJcEhZcwAADdcAAA3XAUIom3gAAAAHdElNRQfiBA4PGSVZX/u4AAAGhUlEQVRo3u2ZbXBU5RXHf8/NOy+BSOWtCbB3N5CIQBCwzDjjuE4xQUVKZ1od+WIhATttRVFpK9UPRUorg5bUtxacynSmQ6e2Io6lSgi0UxFrjNNRKrD3uUuIaBgmljdrJpvc0w/P7mbzstmkuQtfOJ/uOffs8//d5z773HvOhat2hU31dp0yq8ob7cfA0mZ9EDw/DAB3rrdJLWaijxcnaLWvc2PFxYwATXklG3mMPB/Fe+wUq4MNGQD0X1iajFzgS1+ES8hPzITcG9o9CIBbKzsAOMdGeSvk+HPhkYLcKm8t3wFQ7cy2z6RLLNXntWjRH5ya6o90qrl36ZgWLfpP6TKsnHsoBjpy7p32qf8A9l42A/DNyLVpAOQGAPndjI/9lwf4z2a+ALAWpQHgBgCasiMPC2O8bw7SAcwEIJItAJDjAMxKB6AARLIHoDwAlZMO4ArbVYDc4SS3FnXN88bkN5d9fkVmwK3rbPPeYX/nWb0tWnjZZ8C5T36ThF7fXUzdZZ0ByVHbUn1VG5l/WQF0Jdf0juTcNFBea1GWAKx+24j0i0TmO//uPOs8nhWAlqPmkZLyw3d7+9Eqq0FVMlr9VP88CwDhLn7SK/BK4Eiq686ThuRN+qH+he8AYNfLE3TGnd9T20t+LgdkAgCXANjgPjXUcdP+DaPjvXVqtHo60GZ85bEpuqN7sTVGNQWOpWbqOXIAI/9La7t3iOkgjzoq9OgIAKLjvQYWCLLi+C2zTieigTb29M10rucAXwFge/AhOBnuPsQ0UI84KvTI/3kLIsXemywAIJR7cLB3xchsdYBrAVR98EGAGdHuMK0A6mG9jYw2AMCxsepNbky65bGDJ6ekkb/OajSljKq31yWiM10vzCcArM+M0A/g6Ji8fWoxAP/gZTNi98Ho5P4/PVmZlP9VjzxAuZYwp+MITw8LoG104RuYPe7tjqX2Kl4CYJbXDyFa0d3IpLj8A32HDTmExbxlP8Q9QwZoLfridW6Oy9fMvqTErosjVHQ3upN6Mt1ZXiOTAeTZ/vIAwYhKIIwbIkC0sHMvYQAOx5bOvgSgxK6TnQCqUhqdeOmqZ8pBphj50A/SDR08Yd3KZ2SwJECkoHsPX4/L1/RUtEqCa+Kl23Wq0ZkIujwhz3Pp5QHs4yxPbF5S6k4fKEdpAVDVso7bAdQ7ndV9C2pR0RdlDQAfeWusP/JVIx/8/mDyTo16gFspSAl9KK+rPcH3BgBAE0wn3wchYc8Hv5deXM+UelU98Dm1S9b1tC4SAEbmiFSXXxj4Z6LcF1g7NHl3mexmVErgDKpX6+MTtcrebw5TFuFg8qDE/i4vxp0X7EEmX98prxp51S5PyO3WlODk4CSvzPsGWzDjl8pb7uq+M/Cud1t6+eQsbFB3e7uDW1XaSurjCflHzf7AG1Zt4lGWhJvGDm4D4KKaY7cAWrRo0Ucixfhizh+0aNGek1wxkWL3YbeuKdkActZqT4sW3SAqCeCEfZK/24znbk+JPalFi3Nfyjw8E7/s+5NrwJ/iNFJgPWcOCn+cEl4IEH++AJD/GKZifurYWF9LMxU270Wqdup/B8sr+1JWATA29w5/Ae4C4HP775kyQ4dVOwArhlUbZrTrAWg2TrSqazyAMi+rUyO3AOR8Zh8HkGaWgKr0F6AUwLRk3D3e8l7Tu8xaBiC4W+0NwPssASb7W55fAyDHoClPqtMlyQoA5QIwzsI0UNQQhs9sbQCqBBbG5Gd9Sxljql2eBBK366zFCQDKfQE4BWAea6FN9jiryCqyitgPIDuNF5gY2gWAadu1WfEG3cLhqw1gHwJQE7/W7kBHoCPQIV6qZ1pWTXliqut/WvIegFp5wvYB4FUzA9GvZUos+TamxfFnK/8VzgOjcn7bUjJSffttUxF49ZKyuFUXAF09EXeSMlt1y6lD1rRPWQ/AzbGP9MpI6UgAlLAFgBvdrSnRfQBqb09EnpcJgLA63NX/e8EZzo2AIdcsQZBnCzaUxb88ODepC0GzPogWelt4EIBfB++Pf7A4ml/0uPxoeB2zIZhj1Qb+1jukF8kuVQlAS2xOxcWUb0bOAjarRX0bMSM04TU5rJpzmyFWpeaziG9hOivn1HLzxOizAbnTmeeNGrZQH1OKldwxyPm/xmoTNbc/O+AA5tSoZ6gY4MQFtd5+KQUmWwDQlFdSxxKqmJEM/YvXZGeotddsZA8gYXqcmivF1umO1sr27KtdtWHb/wAERFuYrBJ1jgAAACV0RVh0ZGF0ZTpjcmVhdGUAMjAxOC0wNC0xNFQxNToyNTozNyswMjowMKaBIu8AAAAldEVYdGRhdGU6bW9kaWZ5ADIwMTgtMDQtMTRUMTU6MjU6MzcrMDI6MDDX3JpTAAAAGXRFWHRTb2Z0d2FyZQB3d3cuaW5rc2NhcGUub3Jnm+48GgAAAABJRU5ErkJggg==" class="spartan_image_placeholder"> <p data-spartanlbldropfile="1" style="color : #5FAAE1; display: none; width : auto; ">Drop Here</p><img style="width: 100%; vertical-align: middle; display:none;" class="img_" data-spartanindeximage="1"><input class="form-control spartan_image_input" accept="image/*" data-spartanindexinput="1" style="display : none" name="fileUpload[]" type="file"></label> </div>`);
        // $('#image').html(``);
        // $('#image').append(`<div class="col-md-4 com-sm-4 com-xs-4 spartan_item_wrapper" data-spartanindexrow="3" style="margin-bottom : 20px; "><div style="position: relative;"><div class="spartan_item_loader" data-spartanindexloader="3" style=" position: absolute; width: 100%; height: 100px; background: rgba(255,255,255, 0.7); z-index: 22; text-align: center; align-items: center; margin: auto; justify-content: center; flex-direction: column; display : none; font-size : 1.7em; color: #CECECE"><i class="fa fa-sync fa-spin"></i></div><label class="file_upload" style="width: 100%; height: 100px; border: 2px dashed #ddd; border-radius: 3px; cursor: pointer; text-align: center; overflow: hidden; padding: 5px; margin-top: 5px; margin-bottom : 5px; position : relative; display: flex; align-items: center; margin: auto; justify-content: center; flex-direction: column;"><a href="javascript:void(0)" data-spartanindexremove="3" style="position: absolute !important; right : 3px; top: 3px; display : none; background : #ED3C20; border-radius: 3px; width: 30px; height: 30px; line-height : 30px; text-align: center; text-decoration : none; color : #FFF;" class="spartan_remove_row"><i class="fa fa-times"></i></a><img style="width: 64px; margin: 0 auto; vertical-align: middle;" data-spartanindexi="3" src="data:image/png;base64,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" class="spartan_image_placeholder"> <p data-spartanlbldropfile="3" style="color : #5FAAE1; display: none; width : auto; ">Drop Here</p><img style="width: 100%; vertical-align: middle; display:none;" class="img_" data-spartanindeximage="3"><input class="form-control spartan_image_input" accept="image/*" data-spartanindexinput="3" style="display : none" name="fileUpload[]" type="file"></label> </div></div>`);
    // }
}
</script>
@endpush
