@extends('layouts.app')

@section('title')
    {{ $page_title }}
@endsection

@push('stylesheet')
<style>
.hidden{
    display:none;
}
</style>
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
                @if (permission('combo-add'))
                <button class="btn btn-primary btn-sm" onclick="showFormModal('Add New Content Category','Save');clearOldImage()">
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
                                <label for="name">Content Category Name</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Enter Content Category Name">
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
                                @if (permission('combo-bulk-delete'))
{{--                                <th>--}}
{{--                                    <div class="custom-control custom-checkbox">--}}
{{--                                        <input type="checkbox" class="custom-control-input" id="select_all" onchange="select_all()">--}}
{{--                                        <label class="custom-control-label" for="select_all"></label>--}}
{{--                                    </div>--}}
{{--                                </th>--}}
                                @endif
                                <th>Sl</th>
                                <th>Combo Name</th>
                                <th>SKU</th>
                                <th>Combo Sale Price</th>
                                <th>Combo Offer Price</th>
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
@include('combo::modal')
@endsection

@push('script')
<script src="js/spartan-multi-image-picker-min.js"></script>
<!-- include summernote css/js -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script>
var table;
 let rowCounter = 0;
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
            "url": "{{route('combo.datatable.data')}}",
            "type": "POST",
            "data": function (data) {
                data.name        = $("#form-filter #name").val();
                data._token      = _token;
            }
        },
        "columnDefs": [{
                @if (permission('combo-bulk-delete'))
                "targets": [0,5],
                @else
                "targets": [4],
                @endif
                "orderable": false,
                "className": "text-center"
            },
            {
                @if (permission('combo-bulk-delete'))
                "targets": [1,2,3,4,5],
                @else
                "targets": [0,1,3,4,5],
                @endif
                "className": "text-center"
            },
            {
                @if (permission('combo-bulk-delete'))
                "targets": [4,5],
                @else
                "targets": [3,5],
                @endif
                "className": "text-right"
            }
        ],
        "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6' <'float-right'B>>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'<'float-right'p>>>",

        "buttons": [
            @if (permission('combo-report'))
            {
                'extend':'colvis','className':'btn btn-secondary btn-sm text-white','text':'Column'
            },
            {
                "extend": 'print',
                'text':'Print',
                'className':'btn btn-secondary btn-sm text-white',
                "title": "Content Category List",
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
                "title": "Content Category List",
                "filename": "content-category-list",
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
                "title": "Content Category List",
                "filename": "content-category-list",
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
                "title": "Content Category List",
                "filename": "content-category-list",
                "orientation": "landscape", //portrait
                "pageSize": "A4", //A3,A5,A6,legal,letter
                "exportOptions": {
                    columns: [1, 2, 3, 4]
                },
            },
            @endif
            @if (permission('combo-bulk-delete'))
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
        let url = "{{route('combo.store.or.update')}}";
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
            console.log(data);

            if(data.code==404){
                Swal.fire({
                    type:'info',
                    title:'Quantity Unavilable',
                    text:'Product Quantity is not available!',
                    icon: 'warning',
                });
            }
            $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
            $('#store_or_update_form').find('.error').remove();
            if (data.status == false) {

                $.each(data.errors, function (key, value) {
                    // Split the string at the period (.)

                    var parts = key.split('.');
                    // Parse the integer and decimal parts
                    var integerPart = isNaN(parts[0]) ? parts[0] : 0;
                    var decimalPart = parseInt(parts[1]);

                    if(integerPart=='quantity'||integerPart=='inventory_id'){

                        $('#store_or_update_form input#' + integerPart+'-'+decimalPart).addClass('is-invalid');
                        $('#store_or_update_form textarea#' + integerPart+'-'+decimalPart).addClass('is-invalid');
                        $('#store_or_update_form select#' + integerPart+'-'+decimalPart).parent().addClass('is-invalid');
                        if(key == 'code'){
                            $('#store_or_update_form #' + integerPart+'-'+decimalPart).parents('.form-group').append(
                                '<small class="error text-danger">' + value + '</small>');
                        }else{
                            $('#store_or_update_form #' + integerPart+'-'+decimalPart).parent().append(
                                '<small class="error text-danger">' + value + '</small>');
                        }
                    }


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
                    $(this).find('#store_or_update_modal').trigger('reset');
                     document.getElementById('content').innerHTML = '';

                }

            }

        },
        error: function (xhr, ajaxOption, thrownError) {
            // console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
            console.log(xhr.responseText.errors);
        }
    });
    });

     $(document).on('click', '.edit_data', function () {
                $('#content').html('');
                rowCounter=0;
                let id = $(this).data('id');
                $('#store_or_update_form')[0].reset();
                $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
                $('#store_or_update_form').find('.error').remove();

                if (id) {
                    $.ajax({
                        url: "{{ route('combo.edit') }}",
                        type: "POST",
                        data: { id: id, _token: _token},
                        dataType: "JSON",
                        success: function (data) {
                            console.log(data);
                            $('#old_is_manage_stock').val(data.is_manage_stock);
                            if(data.code==404){
                                Swal.fire({
                                    type:'info',
                                    title:'Quantity Unavilable',
                                    text:'Product Quantity is not available!',
                                    icon: 'warning',
                                });
                            }
                            // return false;
                            data.inventory_combo_items.map(function (val, key) {
                                const rowId = `row-${rowCounter}`;

                                var inventoryHtml ="<option value='' >Select please</option>";
                                data.all_inventory.map(function(inventory, ke){

                                    if(inventory.id == val.inventory_id) {
                                        inventoryHtml += "<option value='" + inventory.id + "' selected>" + inventory.title + "</option>";
                                    }else{
                                        inventoryHtml += "<option  value=" + inventory.id + ">" + inventory.title + "</option>";
                                    }
                                });

                                if (rowCounter == 0) {
                                    // Handle the first row differently
                                    $('.main-0').val(val.inventory_id);
                                    $('.row-0').val(val.quantity);
                                    // $('.main-0').prop('disabled', true);
                                    // $('#quantity-0').prop('disabled', true);
                                    // $('#addnew').css('display', 'none');
                                    // $('.' + rowId).val(val.variant_option_id);
                                    rowCounter++;
                                } else {
                                    // Create new row with variant and variant_option selects
                                    const div = document.createElement('div');
                                    div.classList.add('row');

                                    div.innerHTML = `<div class="form-group col-md-3 required">
                                        <label for="inventory">Inventory</label>
                                        <select name="inventory_id[]" id="inventory_id-${rowCounter}" class="form-control selectpicker" data-live-search="true">
                                            ${inventoryHtml}
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="quantity">Quantity</label>
                                        <input type="number" name="quantity[]" min="1" id="quantity-${rowCounter}" class="form-control row-${rowCounter}" value="${val.quantity}" placeholder="Enter Quantity">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <input class="mt-5 rmv" type="button" value="Remove" onclick="removeRow(this)">
                                    </div>
                                    `;

                                    // Append the new row to the 'content' element
                                    document.getElementById('content').appendChild(div);
                                    // $('#inventory_id-'+rowCounter).prop('disabled', true);
                                    // $('#quantity-'+rowCounter).prop('disabled', true);
                                    rowCounter++;

                                }
                            });

                            $('#store_or_update_form #old_image').val(data.image);
                            if(data.image){
                                var image = "{{ asset('storage/'.Combo_IMAGE_PATH)}}/"+data.image;
                                $('#store_or_update_form #image img.spartan_image_placeholder').css('display','none');
                                $('#store_or_update_form #image .spartan_remove_row').css('display','block');
                                $('#store_or_update_form #image .img_').css('display','block');
                                $('#store_or_update_form #image .img_').attr('src',image);
                            }else{
                                $('#store_or_update_form #image img.spartan_image_placeholder').css('display','block');
                                $('#store_or_update_form #image .spartan_remove_row').css('display','none');
                                $('#store_or_update_form #image .img_').css('display','none');
                                $('#store_or_update_form #image .img_').attr('src','');
                            }

                            $('#store_or_update_form #old_lifestyle_image').val(data.lifestyle_image);
                            if(data.lifestyle_image){
                                var image2 = "{{ asset('storage/'.COMBO_lIFESTYLE_IMAGE_PATH)}}/"+data.lifestyle_image;
                                $('#store_or_update_form #lifestyle_image img.spartan_image_placeholder').css('display','none');
                                $('#store_or_update_form #lifestyle_image .spartan_remove_row').css('display','block');
                                $('#store_or_update_form #lifestyle_image .img_').css('display','block');
                                $('#store_or_update_form #lifestyle_image .img_').attr('src',image2);
                            }else{
                                $('#store_or_update_form #lifestyle_image img.spartan_image_placeholder').css('display','block');
                                $('#store_or_update_form #lifestyle_image .spartan_remove_row').css('display','none');
                                $('#store_or_update_form #lifestyle_image .img_').css('display','none');
                                $('#store_or_update_form #lifestyle_image .img_').attr('src','');
                            }

                            $('#store_or_update_form #update_id').val(data.id);
                            $('#store_or_update_form #combo_category_id').val(data.combo_category_id);
                            $('#store_or_update_form #title').val(data.title);
                            $('#store_or_update_form #product_short_desc').val(data.product_short_desc);
                            $('#store_or_update_form #product_long_desc').val(data.product_long_desc);
                            $('#store_or_update_form #sku').val(data.sku);
                            $('#store_or_update_form #min_order_quantity').val(data.min_order_quantity);
                            $('#store_or_update_form #sale_price').val(data.sale_price);
                            $('#store_or_update_form #offer_price').val(data.offer_price);
                            if(data.offer_start){
                                $('#store_or_update_form #offer_start').val(data.offer_start.substring(0,10));
                            }
                            if(data.offer_end) {
                                $('#store_or_update_form #offer_end').val(data.offer_end.substring(0, 10));
                            }
                            $('#store_or_update_form #sku').val(data.sku);

                            if (data.stock_quantity===''||data.stock_quantity===null) {
                                $('#store_or_update_form #stock_quantity').val(1);
                            } else {
                                $('#store_or_update_form #stock_quantity').val(data.stock_quantity);
                            }
                            $('#store_or_update_form #reorder_quantity').val(data.reorder_quantity);
                            $('#store_or_update_form #min_order_quantity').val(data.min_order_quantity);
                            //$('#store_or_update_form #variant_option_id').val(data.variant_option_id);

                            const is_special_deal = document.getElementById('is_special_deal');
                            const is_manage_stock = document.getElementById('is_manage_stock');
                            if(data.is_special_deal==1){
                                is_special_deal.checked= !is_special_deal.checked;
                            }
                            if(data.is_manage_stock==1){
                                is_manage_stock.checked= !is_manage_stock.checked;
                            }

                            // $('#store_or_update_form #old_image').val(data[0].image);
                            $('#store_or_update_form .selectpicker').selectpicker('refresh');

                            $('#store_or_update_modal').modal({
                                keyboard: false,
                                backdrop: 'static',
                            });
                            $('#store_or_update_modal .modal-title').html(
                                '<i class="fas fa-edit"></i> <span>Edit Inventory</span>');
                            $('#store_or_update_modal #save-btn').text('Update');

                            //hide add button
                            $('.addnew').addClass('hidden');
                            $('.rmv').addClass('hidden');
                        },
                        error: function (xhr, ajaxOption, thrownError) {
                            console.log(xhr);
                            console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
                        }
                    });
                }
            });

    $(document).on('click', '.delete_data', function () {
        let id    = $(this).data('id');
        let name  = $(this).data('title');
        let row   = table.row($(this).parent('tr'));
        let url   = "{{ route('combo.delete') }}";
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
            let url = "{{route('combo.bulk.delete')}}";
            bulk_delete(ids,url,table,rows);
        }
    }

    $(document).on('click', '.change_status', function () {
        let id    = $(this).data('id');
        let status = $(this).data('status');
        let name  = $(this).data('name');
        let row   = table.row($(this).parent('tr'));
        let url   = "{{ route('combo.change.status') }}";
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

    $('#lifestyle_image').spartanMultiImagePicker({
        fieldName: 'lifestyle_image',
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

    $('.selectpicker').selectpicker('refresh');
    $('#store_or_update_modal').modal({
        keyboard: false,
        backdrop: 'static',
    });
    $('#store_or_update_modal .modal-title').html('<i class="fas fa-plus-square"></i> '+modal_title);
    $('#store_or_update_modal #save-btn').text(btn_text);
    //hide add button
    $('.addnew').removeClass('hidden');
    $('.rmv').removeClass('hidden');

}

 function addRow() {
        var updated = $('#update_id').val();
        if(updated==null||updated==''){
            rowCounter++;
        }
        const rowId = `row-${rowCounter}`;
        const div = document.createElement('div');
        div.classList.add('row');

        div.innerHTML = `<div class="form-group col-md-3">
                        <label for="quantity">Inventory</label>
                        <select name="inventory_id[]" id="inventory_id-${rowCounter}" class="form-control" data-live-search="true">
                            <option value="">Select Please</option>
                            @if (!$Inventories->isEmpty())
                                @foreach ($Inventories as $Inventory)
                                <option value="{{ $Inventory->id }}">{{ $Inventory->title }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                        <div class="form-group col-md-3">
                            <label for="quantity">Quantity</label>
                            <input type="number" name="quantity[]" min="1" id="quantity-${rowCounter}" class="form-control row-${rowCounter}" value="" placeholder="Enter Quantity">
                        </div>
                        <div class="form-group col-md-3">
                            <input class="mt-5 rmv" type="button" value="Remove" onclick="removeRow(this)">
                        </div>
                        `;

        document.getElementById('content').appendChild(div);

        // Initialize the SelectPicker plugin for the newly created select box

        $(`#inventory_id-${rowCounter}`).selectpicker();

        rowCounter++;
    }

    // Function to remove the row
    function removeRow(button) {
        const row = button.parentNode.parentNode;
        row.parentNode.removeChild(row);
    }

    $(document).on('click', '#modal-close-btn', function () {

        document.getElementById('content').innerHTML = '';
    });

    $(document).ready(function() {
        $('.selectpicker').selectpicker('refresh');
        $('#product_long_desc').summernote();
    });

    function clearOldImage() {
        $('#store_or_update_form #image .img_').attr('src', '');
        $('#store_or_update_form #lifestyle_image .img_').attr('src', '');
        const aElement1 = document.querySelector("#image > div > div > label > a");
        $(aElement1).hide();
        const aElement2 = document.querySelector("#lifestyle_image > div > div > label > a");
        $(aElement2).hide();
        $('.addnew').removeClass('hidden');
        $('.rmv').removeClass('hidden');

        $('.selectpicker').selectpicker('refresh');
    }
</script>
@endpush
