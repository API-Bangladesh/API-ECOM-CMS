@extends('layouts.app')

@section('title')
    {{ $page_title }}
@endsection

@push('stylesheet')
<style>
    textarea {
        height: auto;
        min-height: 100px;
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
                    @if (permission('customorder-add'))
                        <button class="btn btn-primary btn-sm" onclick="showFormModal('Add New Custom Order','Save');clearOldImage()">
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
                                    <label for="name">Customer Name</label>
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Enter Customer Name">
                                </div>

                                <div class="form-group col-md-6 pt-24">
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
                                @if (permission('customorder-bulk-delete'))
                                    <th>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="select_all" onchange="select_all()">
                                            <label class="custom-control-label" for="select_all"></label>
                                        </div>
                                    </th>
                                @endif
                                <th>Sl</th>
                                <th>Customer Name</th>
                                <th>Email</th>
                                <th>Phone</th>
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
    @include('customorder::modal')
    @include('customorder::create_customer')
    @include('customorder::view_modal')
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
                    "url": "{{route('customorder.datatable.data')}}",
                    "type": "POST",
                    "data": function (data) {
                        data.name        = $("#form-filter #name").val();
                        data._token      = _token;
                    }
                },
                "columnDefs": [{
                    @if (permission('customorder-bulk-delete'))
                    "targets": [0,5],
                    @else
                    "targets": [4],
                    @endif
                    "orderable": false,
                    "className": "text-center"
                },
                    {
                        @if (permission('customorder-bulk-delete'))
                        "targets": [1,2,3,4,5],
                        @else
                        "targets": [0,1,3,4,5],
                        @endif
                        "className": "text-center"
                    },
                    {
                        @if (permission('customorder-bulk-delete'))
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
                        @if (permission('inventory-report'))
                    {
                        'extend':'colvis','className':'btn btn-secondary btn-sm text-white','text':'Column'
                    },
                    {
                        "extend": 'print',
                        'text':'Print',
                        'className':'btn btn-secondary btn-sm text-white',
                        "title": "inventory List",
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
                        "title": "inventory List",
                        "filename": "inventory",
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
                        "title": "inventory List",
                        "filename": "inventory",
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
                        "title": "inventory List",
                        "filename": "inventory",
                        "orientation": "landscape", //portrait
                        "pageSize": "A4", //A3,A5,A6,legal,letter
                        "exportOptions": {
                            columns: [1, 2, 3, 4]
                        },
                    },
                        @endif
                        @if (permission('customorder-bulk-delete'))
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
                let url = "{{route('customorder.store.or.update')}}";
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
                    error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail

                        if(jqXHR.status === 422) {
                            var errors='';
                            errors = $.parseJSON(jqXHR.responseText);
                            $('.text-danger').empty();
                            $.each(errors.errors, function (key, value) {

                                $('#store_or_update_modal input#' + key).addClass('is-invalid');
                                $('#store_or_update_modal textarea#' + key).addClass('is-invalid');
                                $('#store_or_update_modal select#' + key).parent().addClass('is-invalid');

                                    $('#store_or_update_modal #' + key).parents('.form-group').append(
                                        '<small class="error text-danger">' + value + '</small>');

                                    // $('#store_or_update_modal #' + key).parent().append(
                                    //     '<small class="error text-danger">' + value + '</small>');
                            });
                        }

                        if (jqXHR.status === 403) {
                            Swal.fire({
                                title: "Errr!",
                                text: 'You do not have the right permission!',
                                icon: "danger",
                                width:400,
                                button: "Ok!",
                            });
                        }
                    }
                    // error: function (xhr, ajaxOption, thrownError) {
                    //     console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
                    // }
                });
            });

            $(document).on('click', '.edit_data', function () {
                $('#content').html('');
                rowCounter = 0;
                let id = $(this).data('id');

                $('#store_or_update_form')[0].reset();
                $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
                $('#store_or_update_form').find('.error').remove();

                if (id) {
                    $.ajax({
                        url: "{{ route('customorder.edit') }}",
                        type: "POST",
                        data: { id: id, _token: _token},
                        dataType: "JSON",
                        success: function (data) {
                            console.log(data);
                            data.order_items.map(function (val, key) {

                                const rowId = `row-${rowCounter}`;
                                var invventoryOptionHtml ="<option value='' >Please select</option>";

                                data.all_inventories.map(function(inventoryProduct,key){
                                    if(inventoryProduct.id == val.inventory_id) {
                                        invventoryOptionHtml += "<option value='" + inventoryProduct.id + "' selected>" + inventoryProduct.title + "</option>";
                                    }else{
                                        invventoryOptionHtml += "<option  value=" + inventoryProduct.id + ">" + inventoryProduct.title + "</option>";
                                    }
                                })
                                var result = val.unit_price * val.quantity;
                                if (rowCounter ==0) {
                                    $('#inventory_id-0').val(val?.inventory?.id);
                                    $('#unit_price-0').val(val?.unit_price);
                                    $('#quantity-0').val(val?.quantity);
                                    $('#total-0').val(result);
                                    rowCounter++;
                                } else {
                                    // Create new row with variant and variant_option selects
                                    const div = document.createElement('div');
                                    div.classList.add('row');
                                    div.innerHTML = `<div class="form-group col-md-4">
                                  <label for="inventory_id-${rowCounter}">Products</label>
                                  <select name="inventory_id[]" id="inventory_id-${rowCounter}" class="form-control main-${rowCounter}" onchange="getVariantOptionList(this.value, 'row-${rowCounter}')" >
                                       ${invventoryOptionHtml}
                                    </select>
                                  </div>
                                  <div class="form-group col-md-2 ">
                                        <label for="unit_price-${rowCounter}">Price</label>
                                        <input type="number" readonly name="unit_price[]" id="unit_price-${rowCounter}" class="form-control " value="${val?.unit_price}" placeholder="Enter Price">
                                   </div>

                                  <div class="form-group col-md-2 ">
                                    <label for="quantity-${rowCounter}">Quantity</label>
                                    <input type="number" name="quantity[]" id="quantity-${rowCounter}" onchange="getQuantityList(this.value,'quantity-${rowCounter}',${rowCounter})" class="form-control " value="${val?.quantity}" placeholder="Enter Quantity">
                                  </div>

                                  <div class="form-group col-md-2 ">
                                    <label for="total-${rowCounter}">Total</label>
                                    <input type="number" disabled="" name="total[]" id="total-${rowCounter}" class="form-control " value="${result}" placeholder="Enter Total">
                                  </div>
                                  <div class="form-group col-md-2">
                                    <input class="mt-5" type="button" value="Remove" onclick="removeRow(this)">
                                  </div>`;
                                    // Append the new row to the 'content' element
                                    document.getElementById('content').appendChild(div);
                                    rowCounter++;
                                }
                            });

                            $('#store_or_update_modal #customer_id').val(data.customer_id);
                            $('#store_or_update_modal #billing_address').val(data.billing_address);
                            $('#store_or_update_modal #shipping_address').val(data.shipping_address);
                            $('#store_or_update_modal #total').val(data.total);
                            $('#store_or_update_modal #discount').val(data.discount);
                            $('#store_or_update_modal #shipping_charge').val(data.shipping_charge);
                            $('#store_or_update_modal #order_status_id').val(data.order_status_id);
                            $('#store_or_update_modal #payment_method_id').val(data.payment_method_id);
                            $('#store_or_update_modal #payment_status_id').val(data.payment_status_id);

                            $('#store_or_update_modal .grand_total_text').text(data.grand_total);
                            $('#store_or_update_modal #grand_total').val(data.grand_total);
                            $('#store_or_update_modal #update_id').val(data.id);
                            $('#store_or_update_modal #special_note').val(data.special_note);

                            var formattedDate = new Date(data.order_date).toISOString().slice(0, 16);
                            $('#store_or_update_modal #order_date').val(formattedDate);

                            $('#store_or_update_modal .selectpicker').selectpicker('refresh');

                            $('#store_or_update_modal').modal({
                                keyboard: false,
                                backdrop: 'static',
                            });
                            $('#store_or_update_modal .modal-title').html(
                                '<i class="fas fa-edit"></i> <span>Edit Custom Order</span>');
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
                let url   = "{{ route('customorder.delete') }}";
                delete_data(id, url, table, row, name);
            });

            $('.setInventoryTitle').change(function (){
                var selectedText  = $(this).find('option:selected').text();
                $('#title').val(selectedText);
            })

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
                    let url = "{{route('customorder.bulk.delete')}}";
                    bulk_delete(ids,url,table,rows);
                }
            }

            {{--$(document).on('click', '.change_status', function () {--}}
            {{--    let id    = $(this).data('id');--}}
            {{--    let status = $(this).data('status');--}}
            {{--    let name  = $(this).data('name');--}}
            {{--    let row   = table.row($(this).parent('tr'));--}}
            {{--    let url   = "{{ route('customorder.change.status') }}";--}}
            {{--    change_status(id,status,name,table,url);--}}

            {{--});--}}

            $('input[name="image"]').prop('required',true);

            $('.remove-files').on('click', function(){
                $(this).parents('.col-md-6').remove();
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
        }

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

        function getVariantOptionList(variant_id,variant_option_id='',number=0){
            $.ajax({
                url:"{{ url('vo-list') }}/"+variant_id,
                type:"GET",
                dataType:"JSON",
                success:function(data){
                    if(data.inventory_offer==''){
                        data.inventory.map(function(invent,key){
                            $(`#unit_price-${number}`).val(invent.sale_price);
                        });

                    }else if(data.inventory_offer){
                        data.inventory.map(function(invent,key){
                            $(`#unit_price-${number}`).val(invent.offer_price);
                        });
                    }
                    var opt = "<option value=''>Select Please</option>";
                    const loadedContentDiv = `.${variant_option_id}`;

                    // $('#store_or_update_form #variant_option_id').empty();
                    $.each(data, function(key, value) {
                        if (!$.trim(data)){
                            $('#store_or_update_form .variant_option_id').addClass('d-none');
                        }
                        else{

                            $('#store_or_update_form .variant_option_id').removeClass('d-none');
                            opt +='<option value="'+ key +'">'+ value +'</option>';
                            // loadedContentDiv.append(opt);
                        }
                    });
                    $(loadedContentDiv).html(opt);
                },
            });
        }

        function clearOldImage(){
            $('#store_or_update_form #image .img_').attr('src','');
            $('.spartan_remove_row').hide();
            $('#content').html('');
            $('.row-0').html('');
            $('.main-0').val('');
            $('#update_id').val(0);
            rowCounter=0;
        }

        //javascript dynamic append remove
        // let rowCounter = 0;
        function addRow() {
            if(rowCounter==0){
                rowCounter++;
            }
            const rowId = `row-${rowCounter}`;
            const div = document.createElement('div');
            div.classList.add('row');
            div.innerHTML = `<div class="form-group col-md-4 required">
                                    <label for="inventory_id[]">Products</label>
                                    <select name="inventory_id[]" id="inventory_id-${rowCounter}" class="form-control selectpicker main-${rowCounter}" onchange="getVariantOptionList(this.value,'row-${rowCounter}',${rowCounter})" >
                                        <option value='' >Please select </option>
                                        @foreach ($inventories as $inventory)
            <option value="{{ $inventory->id }}">{{ $inventory->title }}</option>
                                @endforeach
            </select>
        </div>
        <div class="form-group col-md-2 ">
            <label for="unit_price-${rowCounter}">Price</label>
            <input type="number" readonly name="unit_price[]" id="unit_price-${rowCounter}" class="form-control " value="" placeholder="Enter Price">
        </div>

        <div class="form-group col-md-2 required">
            <label for="quantity-${rowCounter}">Quantity</label>
            <input type="number" name="quantity[]" id="quantity-${rowCounter}" onchange="getQuantityList(this.value,'quantity-${rowCounter}',${rowCounter})" class="form-control " value="" placeholder="Enter Quantity">
        </div>

        <div class="form-group col-md-2 required">
            <label for="total-${rowCounter}">Total</label>
            <input type="number" readonly name="total[]" id="total-${rowCounter}" class="form-control " value="" placeholder="Enter Total">
        </div>
        <div class="form-group col-md-2">
          <input class="mt-5" type="button" value="Remove" onclick="removeRow(this)">
        </div>`; // Closing </div> added here
            document.getElementById('content').appendChild(div);
            rowCounter++;
            $('.selectpicker').selectpicker('refresh');
        }
        function removeRow(input) {
            input.parentNode.parentNode.remove();
            updateGrandTotal();
        }

        function updateGrandTotal(){
            var unitPrice = document.querySelectorAll('input[name="unit_price[]"]');
            var CountTotal = unitPrice.length;

            var individual_total =0;
            var all_product_total =0;
            var qty = 0;
            var pric = 0;

            for(var i=0; i<CountTotal; i++){
                qty = parseFloat($(`#quantity-${i}`).val())||1;
                pric = parseFloat($(`#unit_price-${i}`).val())||0;
                individual_total = qty*pric;
                all_product_total += individual_total;
            }
            $(`#total`).val(all_product_total);

            //discount and shipping
            var subtotal_val = parseFloat($('#total').val());
            var discount = parseFloat($('#discount').val())|| 0;
            var total_after_discount = subtotal_val - discount;

            //add shipping charge
            var shipping_charge = parseFloat($('#shipping_charge').val())|| 0;
            //var grand_total = total_after_discount+shipping_charge;
            var grand_total = (total_after_discount + shipping_charge);

            $('.grand_total_text').text(grand_total);
            $('#grand_total').val(grand_total);

        }

        function getOrderStatus(order_status_id,id) {
            Swal.fire({
                title: 'Are you sure to change ' + name + ' status?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!'
            }).then((result) => {
                // die();
                if (id && result.isConfirmed==true) {
                    $.ajax({
                        url: "{{route('customorder.change.order_status')}}",
                        type: "POST",
                        data: {id: id, order_status_id: order_status_id, _token: _token},
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

        $(document).on('click', '.change_payment_status', function () {
            let id    = $(this).data('id');
            let payment_status_id = $(this).data('status');
            let name  = '';
            let row   = table.row($(this).parent('tr'));
            let url   = "{{ route('customorder.change.status') }}";
            change_payment_status(id,payment_status_id,name,table,url);
        });

        function getCustomer(customer_id,type=null,form=null) {
            var address_id='';
            if(type=='address_id'){
              address_id=customer_id;
            }

            $.ajax({
                url: "{{route('customorder.customer_address')}}",
                type: "get",
                data: { customer_id: customer_id,type:type,address_id:address_id },
                dataType:"JSON",
                success: function(response) {
                    if(form==null){
                        $('#billing').empty();
                        $('#shipping').empty();
                        $('#billing').append(`<option value=""> Please select </option>`);
                        $('#shipping').append(`<option value=""> Please select </option>`);
                    }
                    //if shipping address set as billing address
                    if ($("#isDefaultShipping").is(":checked")) {
                        setTimeout(function(){
                            $('#billing_address').val($('#shipping_address').val());
                        },1200)
                    }

                    if(type=='customer_id') {
                        // Use the map function to create a new array of titles
                        response.data.map(function (item) {
                            $('#billing').append(`<option value="${item.id}">${item.title} </option>`);
                            $('#shipping').append(`<option value="${item.id}">${item.title} </option>`);
                        });
                        $('.selectpicker').selectpicker('refresh');

                    }else if(type=='address_id' && form=='billing'){
                        response.data.map(function (item) {
                            var complete_address = 'Name: '+item.customer.name+',\nAddress: '+item.address_line_1+', '+item.address_line_2+
                                ', Post Code: '+item.postcode+', Division: '+item.division.name+', District: '
                                +item.district.name+', Upazila: '+item.upazila.name+',\nPhone: '+item.phone;
                            $('#billing_address').val(complete_address);
                        })
                        $('.selectpicker').selectpicker('refresh');

                    }else if(type=='address_id' && form=='shipping'){
                        response.data.map(function (item) {
                            var complete_address = 'Name: '+item.customer.name+',\nAddress: '+item.address_line_1+', '+item.address_line_2+
                                ', Post Code: '+item.postcode+', Division: '+item.division.name+', District: '
                                +item.district.name+', Upazila: '+item.upazila.name+',\nPhone: '+item.phone;
                            $('#shipping_address').val(complete_address);
                        })
                        $('.selectpicker').selectpicker('refresh');
                    }
                },
                error: function(xhr, ajaxOption, thrownError) {
                    console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
                }
            });
        };

        $("#isDefaultShipping").click(function(){
            $('#billing_address').val($('#shipping_address').val());
        });

        //create customer
        $(document).ready(function() {
            $('.customer_create').selectpicker({
                noneResultsText: '<button type="button" class="btn btn-primary shown">Create</button>'
            });

            var createOption = $('<option>', {
                value: 'create',
                text: 'Create',
                class: 'btn btn-primary shown'
            });
            // Prepend the option to the select element
            $('#customer_id').prepend(createOption);
            // Refresh the SelectPicker to update the display
            $('#customer_id').selectpicker('refresh');
        });

        $(document).on('click', '.shown', function() {
            $('#store_or_update_modal').modal('hide');
            $('#myModal2').modal('show');
            $('#myModal2').modal({
                keyboard: false,
                backdrop: 'static'
            });
        });

        $(document).on('click', '.cls,closee', function() {
            $('#myModal2').modal('hide');
        });

        function getDistrictByDivision(div_id) {
            if (div_id) {
                $.ajax({
                    type: 'GET',
                    url: '{{ route("customorder.district_by_division") }}',
                    data: {
                        'div_id': div_id // Pass div_id as the 'dcId' parameter
                    },
                    success: function (data) {
                        var district_option = '';
                        data.map(function(district,key){
                            district_option +=`<option value="${district.id}">${district.name}</option>`;
                        });
                        $('#district_id').append(district_option);
                        $('.selectpicker').selectpicker('refresh');
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            }
        }

        function getUpazilaByDistrict(dis_id) {
            if (dis_id) {
                $.ajax({
                    type: 'GET',
                    url: '{{ route("customorder.upazila_by_district") }}',
                    data: {
                        'dis_id': dis_id // Pass div_id as the 'dcId' parameter
                    },
                    success: function (data) {
                        var dpazila_option = '';
                        data.map(function(dpazila,key){
                            dpazila_option +=`<option value="${dpazila.id}">${dpazila.name}</option>`;
                        });
                        $('#upazila_id').append(dpazila_option);
                        $('.selectpicker').selectpicker('refresh');
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            }
        }

    //save customer
    $(document).on('click', '#save-customer', function () {
        let form = document.getElementById('store_or_update_customer');
        let formData = new FormData(form);
        let url = "{{route('customorder.save_customer')}}";
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


   //single item quantity change calculation
    function getQuantityList(value='',className='',sl){
        //delay for edit form if product change then wait to update price
        setTimeout(() => {
            // Get the quantity and price values
            var quantity = parseFloat($(document).find(`#quantity-${sl}`).val());
            var price = parseFloat($(document).find(`#unit_price-${sl}`).val());

            // Calculate the single total
            var single_total = parseFloat(quantity * price);
            $(`#total-${sl}`).val(single_total);

            var all_product_total = 0;
            //count total number of product input fields
            var unitPriceInputs = document.querySelectorAll('input[name="unit_price[]"]');
            var TotalCount = unitPriceInputs.length;
            var individual_total = 0;
            var all_product_total = 0;
            var qty = 0;
            var pric = 0;

            for (var i = 0; i < TotalCount; i++) {
                qty = parseFloat($(`#quantity-${i}`).val()) || 1;
                pric = parseFloat($(`#unit_price-${i}`).val()) || 0;
                individual_total = qty * pric;
                all_product_total += individual_total;
            }
            $(`#total`).val(all_product_total);

            //shipping charge, discount and grand total
            var subtotal_val = parseFloat($('#total').val());
            var discount = parseFloat($('#discount').val()) || 0;
            ;
            var total_after_discount = subtotal_val - discount;

            //add shipping charge
            var shipping_charge = parseFloat($('#shipping_charge').val()) || 0;
            ;
            //var grand_total = total_after_discount+shipping_charge;
            var grand_total = (total_after_discount + shipping_charge);

            $('.grand_total_text').text(grand_total);
            $('#grand_total').val(grand_total);
        },2500);
    }

    //discount & shipping charge
    $('input[name="discount"],input[name="shipping_charge"]').on('change', function() {
        var subtotal_val = parseFloat($('#total').val());
        var discount = parseFloat($('#discount').val())|| 0;;
        var total_after_discount = subtotal_val - discount;

        //add shipping charge
        var shipping_charge = parseFloat($('#shipping_charge').val())|| 0;;
        //var grand_total = total_after_discount+shipping_charge;
        var grand_total = (total_after_discount + shipping_charge);

        $('.grand_total_text').text(grand_total);
        $('#grand_total').val(grand_total);
    })

    //view custom order
        $(document).on('click', '.view_data', function () {
            let id = $(this).data('id');
            rowCounter = 0;
            if (id) {
                $.ajax({
                    url: "{{route('customorder.view')}}",
                    type: "POST",
                    data: {id: id, _token: _token},
                    dataType: "JSON",
                    success: function (data) {
                        console.log(data);
                        var subTotal = data.total;
                        var billingArray = data.billing_address.split(",");
                        var billingData = [];
                        for (var k = 0; k < billingArray.length; k++) {
                            billingData += `<p class="p-m-b mb-0">${billingArray[k].charAt(0).toUpperCase() + billingArray[k].slice(1)}</p>`;
                        }

                        var shippingArray = data.shipping_address.split(",");

                        var shippingData = [];
                        var l = 0;
                        for (var k = 0; k < shippingArray.length; k++) {
                            shippingData += `<p class="p-m-b mb-0">${shippingArray[k].charAt(0).toUpperCase() + shippingArray[k].slice(1)}</p>`;
                        }
                        $('.billing').html(billingData);
                        $('.shipping').html(shippingData);

                        // $('.shipping').html(data.shipping_address+', '+data.customer.phone_number);
                        $('.order_id').html('# ' + data.id);

                        function formatDateToDMY(date) {
                            const options = {day: 'numeric', month: 'numeric', year: 'numeric'};
                            return new Date(date).toLocaleDateString(undefined, options);
                        }

                        {{--// $('.logo').attr("src","https://github.githubassets.com/images/modules/logos_page/GitHub-Mark.png");--}}
                        if (data.logo[6]?.value) {
                            var base = '{{ url('/') }}';
                            $('.logo').attr("src", base + '/storage/logo/' + data.logo[6]?.value);
                        }
                        $('.company_name_text').html(data.logo[0]?.value);
                        $('.company_address_text').html(data.logo[1]?.value);
                        $('.company_phone_text').html(data.logo[4]?.value);
                        $('.company_mail_text').html(data.logo[3]?.value);
                        {{--// console.log(data.logo[6].value);--}}
                        if (data.order_date) {
                            var inputDate = data.order_date;
                            const formattedDate = formatDateToDMY(inputDate);
                            $('.order_date').html(formattedDate);
                        }

                        //order items loop
                        var tr = '';
                        var sl = 1;
                        var totalPrice = 0;
                        var tr = ""; // Initialize the table row string
                        data.order_items.map(function (order_item, key) {
                            if (order_item.type == 'product') {
                                tr += "<tr> <th scope='row'>" + sl + "</th>" +
                                    "<td>" + order_item.inventory?.title + "</td>" +
                                    "<td>" + order_item?.quantity + "</td>" +
                                    "<td>BDT " + order_item?.unit_price + "</td>" +
                                    "<td>BDT " + order_item?.quantity * order_item?.unit_price + "</td>" +
                                    "</tr>";
                                totalPrice += order_item?.quantity * order_item?.unit_price;
                            }
                            sl++;
                        });
                        //sub total
                        tr += "<tr>" +
                            "<td class='bold' colspan='5'><hr></td>" +
                            "</tr>";

                        tr += "<tr class='m-0 p-0 scharge'>" +
                            "<td colspan='3'></td>" +
                            "<td><span class='text-right bold'>Sub Total</span></td>" +
                            "<td><span class='text-right bold'>BDT " + subTotal + "</span></td>" +
                            "</tr>";

                        //Shipping Charge
                        var shippingCharge = data?.shipping_charge;
                        var discount = data?.discount;
                        var grandTotal = data?.grand_total;
                        var totalWithshippingCharge = grandTotal + shippingCharge;

                        tr += "<tr class='m-0 p-0 scharge'>" +
                            "<td colspan='3'></td>" +
                            "<td><span class='text-right bold'>Shipping Charge </span></td>" +
                            "<td><span class='text-right bold'>BDT " + shippingCharge + "</span></td>" +
                            "</tr>" +
                            " <tr>" +
                            "</tr>";

                        //discount
                        tr += "<tr class='m-0 p-0 scharge'>" +
                            "<td colspan='3'></td>" +
                            "<td><span class='text-right bold'>Discount </span></td>" +
                            "<td><span class='text-right bold'>BDT " + discount + "</span></td>" +
                            "</tr>" +
                            " <tr>" +
                            "</tr>";

                        //grand total

                        tr += "<tr class='m-0 p-0 scharge'>" +
                            "<td colspan='3'></td>" +
                            "<td><span class='text-right bold'>Grand Total</span></td>" +
                            "<td><span class='text-right bold'>BDT " + grandTotal + "</span></td>" +
                            "</tr>";

                        tr += "<tr>" +
                            "<td class='bold' colspan='5'>" +
                            "<h3 class='text-center' style='border-top: 1px solid #ccc;font-size:13px; width: 84px;padding-top: 1px;margin-top: 0px;'>Authorize</h3>" +
                            "</td>" +
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

    </script>
@endpush
