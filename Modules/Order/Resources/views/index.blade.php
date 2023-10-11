@extends('layouts.app')

@section('title')
    {{ $page_title }}
@endsection

@push('stylesheet')
<style>
    .p-m-b{
        margin-bottom: 0px;
        font-size: 12px;
    }
</style>
@endpush

@section('content')
    <div class="dt-content">
        <meta name="csrf-token" content="{{ csrf_token() }}">
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
                        <form id="form-filter">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="name">Shipping Address</label>
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Enter shipping address">
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
                                @if (permission('order-bulk-delete'))
                                    <th>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="select_all" onchange="select_all()">
                                            <label class="custom-control-label" for="select_all"></label>
                                        </div>
                                    </th>
                                @endif
                                <th>Sl</th>
                                <th>Order Date</th>
                                <th>Order ID</th>
                                <th>Order Type</th>
                                <th>Total</th>
                                <th>Order Status</th>
                                <th>Payment Status</th>
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
    @include('order::modal')
    @include('order::view_modal')
@endsection

@push('script')
    <script src="js/spartan-multi-image-picker-min.js"></script>
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
                    "url": "{{route('order.datatable.data')}}",
                    "type": "POST",
                    "data": function (data) {
                        data.name        = $("#form-filter #name").val();
                        data.category_id = $("#form-filter #category_id").val();
                        data._token      = _token;
                    }
                },
                "columnDefs": [{
                    @if (permission('order-bulk-delete'))
                    "targets": [0,5],
                    @else
                    "targets": [4],
                    @endif
                    "orderable": false,
                    "className": "text-center"
                },
                    {
                        @if (permission('order-bulk-delete'))
                        "targets": [1,2,3,4,5],
                        @else
                        "targets": [0,1,3,4,5],
                        @endif
                        "className": "text-center"
                    },
                    {
                        @if (permission('order-bulk-delete'))
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
                        @if (permission('order-report'))
                    {
                        'extend':'colvis','className':'btn btn-secondary btn-sm text-white','text':'Column'
                    },
                    {
                        "extend": 'print',
                        'text':'Print',
                        'className':'btn btn-secondary btn-sm text-white',
                        "title": "Sub Category List",
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
                        "title": "Sub Category List",
                        "filename": "order",
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
                        "title": "Sub Category List",
                        "filename": "order",
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
                        "title": "Sub Category List",
                        "filename": "order",
                        "orientation": "landscape", //portrait
                        "pageSize": "A4", //A3,A5,A6,legal,letter
                        "exportOptions": {
                            columns: [1, 2, 3, 4]
                        },
                    },
                        @endif
                        @if (permission('order-bulk-delete'))
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
                let url = "{{route('order.store.or.update')}}";
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
                                $(this).find('#store_or_update_modal').trigger('reset');

                            }
                        }

                    },
                    error: function (xhr, ajaxOption, thrownError) {
                        console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
                    }
                });
            });

            $(document).on('click', '.view_data', function () {
                let id = $(this).data('id');
                rowCounter = 0;
                if (id) {
                    $.ajax({
                        url: "{{route('order.view')}}",
                        type: "POST",
                        data: {id: id, _token: _token},
                        dataType: "JSON",
                        success: function (data) {
                            // console.log(data);
                            console.log(data.grand_total);
                            var subTotal = data.total;
                            var billingArray = data.billing_address.split(",");
                            var billingData =[];
                            for(var k=0;k<billingArray.length;k++){
                                billingData+=`<p class="p-m-b">${billingArray[k].charAt(0).toUpperCase()+billingArray[k].slice(1)}</p>`;
                            }

                            var shippingArray = data.shipping_address.split(",");

                            var shippingData =[];
                            var l=0;
                            //console.log(shippingArray);
                            for(var k=0;k<shippingArray.length;k++){
                                shippingData+=`<p class="p-m-b">${shippingArray[k].charAt(0).toUpperCase()+shippingArray[k].slice(1)}</p>`;
                            }

                            $('.billing').html(billingData);
                            $('.shipping').html(shippingData);
                            // $('.shipping').html(data.shipping_address+', '+data.customer.phone_number);
                            $('.order_id').html('# '+data.id);

                            function formatDateToDMY(date) {
                                const options = { day: 'numeric', month: 'numeric', year: 'numeric' };
                                return new Date(date).toLocaleDateString(undefined, options);
                            }
                            // $('.logo').attr("src","https://github.githubassets.com/images/modules/logos_page/GitHub-Mark.png");
                            if(data.logo[6]?.value){
                                var base = '{{ url('/') }}';
                                $('.logo').attr("src",base+'/storage/logo/'+data.logo[6]?.value);
                            }
                            $('.company_name_text').html(data.logo[0]?.value);
                            $('.company_address_text').html(data.logo[1]?.value);
                            $('.company_phone_text').html(data.logo[4]?.value);
                            $('.company_mail_text').html(data.logo[3]?.value);
                            // console.log(data.logo[6].value);
                            if(data.order_date){
                                var inputDate = data.order_date;
                                const formattedDate = formatDateToDMY(inputDate);
                                $('.order_date').html(formattedDate);
                            }

                            //order items loop
                            var tr ='';
                            var sl =1;
                            var totalPrice =0;
                            var tr = ""; // Initialize the table row string
                            data.order_items.map(function (order_item, key) {

                                if (order_item.type == 'combo') {
                                    var sub_item = ""; // Initialize the sub-item string

                                    order_item.combo?.combo_items.map(function (combo_item, key1) {
                                        if (typeof combo_item.inventory?.title !== 'undefined') {
                                            sub_item += `<p class="p-m-b">${combo_item.inventory?.title} - Qty: ${combo_item?.quantity}</p>`;
                                        }
                                    });

                                    // Construct the table row using template literals

                                    tr += `
                                        <tr>
                                            <th scope='row'>${sl}</th>
                                            <td>
                                                ${order_item.combo?.title}
                                                <div class='ml-2'>
                                                    ${sub_item}
                                                </div>
                                            </td>
                                            <td>${order_item?.quantity}</td>
                                            <td>BDT ${order_item?.unit_price}</td>
                                            <td>BDT ${order_item?.quantity * order_item?.unit_price}</td>
                                        </tr>`;
                                    totalPrice = order_item?.quantity * order_item?.unit_price;
                                }
                            else if(order_item.type=='product'){
                                    tr += "<tr> <th scope='row'>" + sl + "</th>" +
                                        "<td>" + order_item.inventory?.title + "</td>" +
                                        "<td>" + order_item?.quantity + "</td>" +
                                        "<td>BDT " + order_item?.unit_price + "</td>" +
                                        "<td>BDT " + order_item?.quantity * order_item?.unit_price + "</td>" +
                                        "</tr>";
                                    totalPrice +=order_item?.quantity * order_item?.unit_price;
                                }
                                sl++;
                            });
                            //sub total
                            tr+="<tr>"+
                                "<td class='bold' colspan='5'><hr></td>"+
                                "</tr>";

                            tr+="<tr class='m-0 p-0 scharge'>"+
                                "<td colspan='3'></td>"+
                                    "<td><span class='text-right bold'>Sub Total</span></td>"+
                                    "<td><span class='text-right bold'>BDT "+subTotal+"</span></td>"+
                                "</tr>";

                            //Shipping Charge

                            var shippingCharge = data.shipping_charge;
                            var grandTotal = data.grand_total;
                            var totalWithshippingCharge = grandTotal+shippingCharge;

                            tr += "<tr class='m-0 p-0 scharge'>" +
                                "<td colspan='3'></td>" +
                                "<td><span class='text-right bold'>Shipping Charge </span></td>" +
                                "<td><span class='text-right bold'>BDT " + shippingCharge + "</span></td>" +
                                "</tr>" +
                                " <tr>" +
                                "</tr>";

                            //grand total

                            tr+="<tr class='m-0 p-0 scharge'>"+
                                "<td colspan='3'></td>"+
                                    "<td><span class='text-right bold'>Grand Total</span></td>"+
                                    "<td><span class='text-right bold'>BDT "+grandTotal+"</span></td>"+
                                "</tr>";

                            tr+="<tr>"+
                                "<td class='bold' colspan='5'>" +
                                "<h3 class='text-center' style='border-top: 1px solid #ccc;font-size:13px; width: 84px;padding-top: 1px;margin-top: 0px;'>Authorize</h3>"+
                                "</td>"+
                                "</tr>";

                            $('#table_tr').html(tr);

                            //view_modal
                            $('#view_modal').modal({
                                keyboard: false,
                                backdrop: 'static',
                            });
                        },
                        error:function(xhr, ajaxOption, thrownError){
                            console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
                        }
                    })
                }
            });

            $(document).on('click', '.edit_data', function () {
                let id = $(this).data('id');
                var productHtml='';
                $('#content').html('');
                rowCounter=0;
                $('#store_or_update_form')[0].reset();
                $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
                $('#store_or_update_form').find('.error').remove();
                if (id) {
                    $.ajax({
                        url: "{{route('order.edit')}}",
                        type: "POST",
                        data: { id: id,_token: _token},
                        dataType: "JSON",
                        success: function (data) {
                            table.ajax.reload();
                            console.log(data.order_items);
                            rowCounter=0;
                            console.log(rowCounter);
                            data.order_items.map(function (order_item, key) {
                                const productId = `product-${rowCounter}`;
                                const priceId = `price-${rowCounter}`;
                                const quantityId = `quantity-${rowCounter}`;

                                var priceInteger =order_item.unit_price;
                                var quantityInteger =order_item.quantity;

                                if(order_item.type=='product'){
                                    productHtml ="";
                                    data.inventories.map(function(inventory, ke){

                                        if(inventory.id == order_item.inventory_id) {
                                            productHtml += "<input type='hidden' name='product_id[]' value='" + inventory.id + "'>" + inventory.title + "";
                                        }
                                    });
                                }else if(order_item.type=='combo'){
                                    productHtml ='';
                                    data.combos.map(function(combo, key){
                                        if(combo.id == order_item.combo_id) {
                                            productHtml += "<input type='hidden' name='product_id[]' value='" + combo.id + "'>" + combo.title + "";
                                        }
                                    });
                                }

                                if (rowCounter == 0) {
                                    // Handle the first row differently
                                    $('.product-0').html(productHtml);
                                    $('.price-0').val(priceInteger);
                                    $('.quantity-0').val(quantityInteger);
                                    $('.type-0').val(order_item.type);
                                    rowCounter++;
                                } else if(rowCounter>0) {
                                    console.log(rowCounter);
                                    console.log('rowCounter');
                                    // Create new row with variant and variant_option selects
                                    const div = document.createElement('div');
                                    div.classList.add('row');

                                    div.innerHTML = `<div class="form-group col-md-4 required">
                                        ${productHtml}
                                  </div>

                                 <div class="form-group col-md-4 price_id ">
                                    <label for="price[]">Price</label>
                                    <input type="number" name="price[]" disabled class="form-control price-${rowCounter}" value="" placeholder="Enter price">
                                    <input type="hidden" class="type-${rowCounter}" value="${order_item.type}" name="type[]">
                                 </div>
                                <div class="form-group col-md-4 price_id ">
                                    <label for="price[]">Quantity</label>
                                    <input type="number" name="quantity[]" class="form-control quantity-${rowCounter}" value="" placeholder="Enter quantity">
                                </div>`;

                                    // Append the new row to the 'content' element
                                    document.getElementById('content').appendChild(div);
                                    $('.' + priceId).val(priceInteger);
                                    $('.' + quantityId).val(quantityInteger);
                                    rowCounter++;
                                    console.log(div);
                                }
                            });

                            $('#store_or_update_form #update_id').val(data.id);
                            $('#store_or_update_form #shipping_address').val(data.shipping_address);
                            $('#store_or_update_form #billing_address').val(data.billing_address);
                            $('#store_or_update_form #shipping_charge').val(data.shipping_charge);
                            $('#store_or_update_form #payment_method_id').val(data.payment_method_id);
                            $('#store_or_update_form #payment_details').val(data.payment_details);
                            $('#store_or_update_form #payment_status_id').val(data.payment_status_id);
                            $('#store_or_update_form #total').val(data.total);
                            $('#store_or_update_form #discount').val(data.discount);
                            $('#store_or_update_form #tax').val(data.tax);
                            $('#store_or_update_form #grand_total').val(data.grand_total);
                            $('#store_or_update_form .selectpicker').selectpicker('refresh');

                            $('#store_or_update_modal').modal({
                                keyboard: false,
                                backdrop: 'static',
                            });
                            $('#store_or_update_modal .modal-title').html(
                                '<i class="fas fa-edit"></i> <span>Edit  Order</span>');
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
                let url   = "{{ route('order.delete') }}";
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
                    let url = "{{route('order.bulk.delete')}}";
                    bulk_delete(ids,url,table,rows);
                }
            }

            $(document).on('click', '.change_payment_status', function () {
                let id    = $(this).data('id');
                let payment_status_id = $(this).data('status');
                let name  = '';
                let row   = table.row($(this).parent('tr'));
                let url   = "{{ route('order.change.status') }}";
                change_payment_status(id,payment_status_id,name,table,url);
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

            $('.remove-files').on('click', function(){
                $(this).parents('.col-md-12').remove();
            });
        });

            function getOrderStatus(order_id,id) {
                Swal.fire({
                    title: 'Are you sure to change ' + name + ' status?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes!'
                }).then((result) => {
                    console.log(result);
                    // die();
                    if (id && result.isConfirmed==true) {
                        $.ajax({
                            url: "{{route('order.change.order_status')}}",
                            type: "POST",
                            data: {id: id, order_id: order_id, _token: _token},
                            dataType: "JSON",
                            success: function (data) {
                                Swal.fire("Status Changed", data.message, "success").then(function () {
                                    table.ajax.reload(null, false);
                                });
                            },
                            error: function () {
                                Swal.fire('Oops...', "Somthing went wrong with ajax!", "error");
                            }
                        });
                    }
                })
            }

            function getPrice(id, type = '', price_id = '') {
                if (id) {
                    $.ajax({
                        url: "{{route('order.change.product_price')}}",
                        type: "POST",
                        data: {id: id,type:type, _token: _token},
                        dataType: "JSON",
                        success: function (data) {
                            console.log(price_id);
                            console.log(data.sale_price);
                            $('.' + price_id).val(data.sale_price);
                        }
                    })
                }
            }

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
            }
    </script>
@endpush
