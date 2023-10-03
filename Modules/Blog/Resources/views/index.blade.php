@extends('layouts.app')

@section('title')
    {{ $page_title }}
@endsection

@push('stylesheet')
<link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css">
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
                @if (permission('blog-add'))
                <button class="btn btn-primary btn-sm" onclick="showStoreFormModal('Add New blog','Save')">
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
                            <div class="form-group col-md-3">
                                <label for="name">Blog Title</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Enter Blog Title">
                            </div>

                            <div class="form-group col-md-12">
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
                                @if (permission('blog-bulk-delete'))
                                <th>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="select_all" onchange="select_all()">
                                        <label class="custom-control-label" for="select_all"></label>
                                    </div>
                                </th>
                                @endif
                                <th>Blog Id</th>
                                <th>Order</th>
                                <th>Blog Image</th>
                                <th>Blog Banner Image</th>
                                <th>Name</th>
                                <th>Blog Category Name</th>
                                <th>Blog Author</th>
                                <th>Blog Date</th>
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
@include('blog::modal')
@endsection

@push('script')
<script src="js/spartan-multi-image-picker-min.js"></script>
<script src="js/moment.min.js"></script>
<script src="js/bootstrap-datetimepicker.min.js"></script>
<script>
var table;
$(document).ready(function(){
    $('.time').datetimepicker({format:'hh:mm A',ignoreReadonly:true});
    $('.date').datetimepicker({format:'YYYY-MM-DD',ignoreReadonly:true});
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
        "pageLength": 25, //number of data show per page
        "language": {
            processing: `<i class="fas fa-spinner fa-spin fa-3x fa-fw text-primary"></i> `,
            emptyTable: '<strong class="text-danger">No Data Found</strong>',
            infoEmpty: '',
            zeroRecords: '<strong class="text-danger">No Data Found</strong>'
        },
        "ajax": {
            "url": "{{route('blog.datatable.data')}}",
            "type": "POST",
            "data": function (data) {
                data.name        = $("#form-filter #name").val();
                data.sub_category_id    = $("#form-filter #sub_category_id").val();
                data.category_id = $("#form-filter #category_id").val();
                data._token      = _token;
            }
        },
        "columnDefs": [{
                @if (permission('blog-bulk-delete'))
                "targets": [0,9],
                @else
                "targets": [8],
                @endif
                "orderable": false,
                "className": "text-center"
            },
            {
                @if (permission('blog-bulk-delete'))
                "targets": [1,2,4,5,6,7,8,9,10],
                @else
                "targets": [0,1,3,4,5,6,7,8,9],
                @endif
                "className": "text-center"
            },
            {
                @if (permission('blog-bulk-delete'))
                "targets": [8,9],
                @else
                "targets": [7,8],
                @endif
                "className": "text-right"
            }
        ],
        "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6' <'float-right'B>>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'<'float-right'p>>>",

        "buttons": [
            @if (permission('blog-report'))
            {
                'extend':'colvis','className':'btn btn-secondary btn-sm text-white','text':'Column'
            },
            {
                "extend": 'print',
                'text':'Print',
                'className':'btn btn-secondary btn-sm text-white',
                "title": "blog List",
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
                "title": "blog List",
                "filename": "blog-list",
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
                "title": "blog List",
                "filename": "blog-list",
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
                "title": "blog List",
                "filename": "blog-list",
                "orientation": "landscape", //portrait
                "pageSize": "A4", //A3,A5,A6,legal,letter
                "exportOptions": {
                    columns: [1, 2, 3,4,5,6,7,8,9,10,11,12,13]
                },
            },
            @endif
            @if (permission('blog-bulk-delete'))
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

    $(document).on('click', '#save-btn', function () {
        let form = document.getElementById('store_or_update_form');
        let formData = new FormData(form);
        let url = "{{route('blog.store.or.update')}}";
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
                url: "{{route('blog.edit')}}",
                type: "POST",
                data: { id: id,_token: _token},
                dataType: "JSON",
                success: function (data) {
                    $('#store_or_update_form #update_id').val(data.id);
                    $('#store_or_update_form #name').val(data.name);
                    $('#store_or_update_form #blog_category_id').val(data.blog_category_id);
                    $('#store_or_update_form #blog_author').val(data.blog_author);
                    $('#store_or_update_form #blog_date').val(data.blog_date);
		    $('#store_or_update_form #blog_order').val(data.blog_order);
                    $('#store_or_update_form #blog_short_desc').val(data.blog_short_desc);
                    $('#store_or_update_form #blog_long_desc').val(data.blog_long_desc);

                    $('#store_or_update_form #old_image').val(data.image);
                    $('#store_or_update_form .selectpicker').selectpicker('refresh');
                    if(data.image){
                        var image = "{{ asset('storage/'.BLOG_IMAGE_PATH)}}/"+data.image;
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

                    $('#store_or_update_form #old_blog_banner_image').val(data.blog_banner_image);
                    $('#store_or_update_form .selectpicker').selectpicker('refresh');
                    if(data.blog_banner_image){
                        var blog_banner_image = "{{ asset('storage/'.BLOG_IMAGE_PATH)}}/"+data.blog_banner_image;
                        $('#store_or_update_form #blog_banner_image img.spartan_image_placeholder').css('display','none');
                        $('#store_or_update_form #blog_banner_image .spartan_remove_row').css('display','none');
                        $('#store_or_update_form #blog_banner_image .img_').css('display','block');
                        $('#store_or_update_form #blog_banner_image .img_').attr('src',blog_banner_image);
                    }else{
                        $('#store_or_update_form #blog_banner_image img.spartan_image_placeholder').css('display','block');
                        $('#store_or_update_form #blog_banner_image .spartan_remove_row').css('display','none');
                        $('#store_or_update_form #blog_banner_image .img_').css('display','none');
                        $('#store_or_update_form #blog_banner_image .img_').attr('src','');
                    }


                    $('#store_or_update_modal').modal({
                        keyboard: false,
                        backdrop: 'static',
                    });
                    $('#store_or_update_modal .modal-title').html(
                        '<i class="fas fa-edit"></i> <span>Edit ' + data.name + '</span>');
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
        let url   = "{{ route('blog.delete') }}";
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
            let url = "{{route('blog.bulk.delete')}}";
            bulk_delete(ids,url,table,rows);
        }
    }

    $(document).on('click', '.change_status', function () {
        let id    = $(this).data('id');
        let status = $(this).data('status');
        let name  = $(this).data('name');
        let row   = table.row($(this).parent('tr'));
        let url   = "{{ route('blog.change.status') }}";
        change_status(id,status,name,table,url);

    });

    $('#image').spartanMultiImagePicker({
        fieldName: 'image',
        maxCount: 1,
        rowHeight: '200px',
        groupClassName: 'col-md-12 com-sm-12 com-xs-12',
        maxFileSize: '',
        dropFileLabel: 'Drop Here',
        allowExt: 'png|jpg|jpeg',
        onExtensionErr: function(index, file){
            Swal.fire({icon:'error',title:'Oops...',text: 'Only png,jpg,jpeg file format allowed!'});
        }
    });

    $('input[name="image"]').prop('required',true);

    $('#blog_banner_image').spartanMultiImagePicker({
        fieldName: 'blog_banner_image',
        maxCount: 1,
        rowHeight: '200px',
        groupClassName: 'col-md-12 com-sm-12 com-xs-12',
        maxFileSize: '',
        dropFileLabel: 'Drop Here',
        allowExt: 'png|jpg|jpeg',
        onExtensionErr: function(index, file){
            Swal.fire({icon:'error',title:'Oops...',text: 'Only png,jpg,jpeg file format allowed!'});
        }
    });

    $('input[name="blog_banner_image"]').prop('required',true);



    $('.remove-files').on('click', function(){
        $(this).parents('.col-md-12').remove();
    });


});





function showStoreFormModal(modal_title, btn_text)
{
    $('#store_or_update_form')[0].reset();
    $('#store_or_update_form #update_id').val('');
    $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
    $('#store_or_update_form').find('.error').remove();

    $('#store_or_update_form #image img.spartan_image_placeholder').css('display','block');
    $('#store_or_update_form #image .spartan_remove_row').css('display','none');
    $('#store_or_update_form #image .img_').css('display','none');
    $('#store_or_update_form #image .img_').attr('src','');
    $('#store_or_update_form #blog_banner_image img.spartan_image_placeholder').css('display','block');
    $('#store_or_update_form #blog_banner_image .spartan_remove_row').css('display','none');
    $('#store_or_update_form #blog_banner_image .img_').css('display','none');
    $('#store_or_update_form #blog_banner_image .img_').attr('src','');
    $('.selectpicker').selectpicker('refresh');
    $('#store_or_update_modal').modal({
        keyboard: false,
        backdrop: 'static',
    });
    $('#store_or_update_modal .modal-title').html('<i class="fas fa-plus-square"></i> '+modal_title);
    $('#store_or_update_modal #save-btn').text(btn_text);
}
</script>
@endpush
