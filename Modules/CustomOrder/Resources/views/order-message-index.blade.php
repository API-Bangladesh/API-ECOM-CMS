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
                @if (permission('ordermessage-add'))
                <button class="btn btn-primary btn-sm" onclick="showFormModal('Add New Order Message','Save');">
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
                                <label for="name">Order Text</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Enter Order Text">
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
                                @if (permission('ordermessage-bulk-delete'))
                                <th>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="select_all" onchange="select_all()">
                                        <label class="custom-control-label" for="select_all"></label>
                                    </div>
                                </th>
                                @endif
                                <th>Sl</th>
                                <th>Order Text</th>
                                <th>Media</th>
                                <th>Date Time</th>
                                <th>Info</th>
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
@include('customorder::order-message-modal')
@include('customorder::message_order_create_modal')
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
            "url": "{{route('ordermessage.datatable.data')}}",
            "type": "POST",
            "data": function (data) {
                data._token      = _token;
            }
        },
        "columnDefs": [{
                @if (permission('ordermessage-bulk-delete'))
                "targets": [0,4],
                @else
                "targets": [3],
                @endif
                "orderable": false,
                "className": "text-center"
            },
            {
                @if (permission('ordermessage-bulk-delete'))
                "targets": [1,2,3,4],
                @else
                "targets": [0,1,2,3],
                @endif
                "className": "text-center"
            },
            {
                @if (permission('ordermessage-bulk-delete'))
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
            @if (permission('ordermessage-report'))
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
                "title": "Order Message List",
                "filename": "ordermessage-list",
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
                "filename": "ordermessage-list",
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
                "title": "Order Message List",
                "filename": "ordermessage-list",
                "orientation": "landscape", //portrait
                "pageSize": "A4", //A3,A5,A6,legal,letter
                "exportOptions": {
                    columns: [1, 2, 3, 4]
                },
            },
            @endif
            @if (permission('ordermessage-bulk-delete'))
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
        let url = "{{route('ordermessage.store.or.update')}}";
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

                }
            }

        },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail

                if(jqXHR.status === 422) {
                    var errors = $.parseJSON(jqXHR.responseText);
                    $.each(errors.errors, function (key, value) {
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
    });
    });

    $(document).on('click', '.edit_data', function () {

        let id = $(this).data('id');
        $('#store_or_update_form')[0].reset();
        $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
        $('#store_or_update_form').find('.error').remove();
        if (id) {
            $.ajax({
                url: "{{route('ordermessage.edit')}}",
                type: "POST",
                data: { id: id,_token: _token},
                dataType: "JSON",
                success: function (data) {

                    $('#store_or_update_form #update_id').val(data.id);
                    $('#store_or_update_form #order_text').val(data.order_text);
                    $('#store_or_update_form #media').val(data.media);
                    $('#store_or_update_form #date_time').val(data.date_time);
                    $('#store_or_update_form #info').val(data.info);
                    $('#store_or_update_form .selectpicker').selectpicker('refresh');

                    $('#store_or_update_modal').modal({
                        keyboard: false,
                        backdrop: 'static',
                    });
                    $('#store_or_update_modal .modal-title').html(
                        '<i class="fas fa-edit"></i> <span>Edit ' + data.inventory_id + '</span>');
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
        let url   = "{{ route('ordermessage.delete') }}";
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
            let url = "{{route('ordermessage.bulk.delete')}}";
            bulk_delete(ids,url,table,rows);
        }
    }

    $(document).on('click', '.change_status', function () {
        let id    = $(this).data('id');
        let status = $(this).data('status');
        let name  = $(this).data('name');
        let row   = table.row($(this).parent('tr'));
        let url   = "{{ route('ordermessage.change.status') }}";
        change_status(id,status,name,table,url);

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

//create customer
$(document).ready(function() {
    $('.page').hide();
    $('.save').hide();
    //if create option is selected
    // Listen for changes in the select element
    $('#page_id').on('change', function() {
        // Check if the selected option has the class "shown"
        if ($('option:selected', this).hasClass('shown')) {
            // Show the 'row' div
            $('.page').show();
            $('.save').show();
        } else {
            // Hide the 'row' div
            $('.page').hide();
            $('.save').hide();
        }
    });
});

//save page
$(document).on('click', '#save', function (event) {
    let form = document.getElementById('store_or_update_form');
    let formData = new FormData(form);
    let url = "{{route('customorder.save_page')}}";
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
            $('#save').addClass('kt-spinner kt-spinner--md kt-spinner--light');
        },
        complete: function(){
            $('#save').removeClass('kt-spinner kt-spinner--md kt-spinner--light');
        },
        success: function (data) {
            get_pages();
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
                    // $('#store_or_update_modal').modal('hide');
                }
            }

        },
        error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
            $('.error').empty();
            if(jqXHR.status === 422) {
                var errors = $.parseJSON(jqXHR.responseText);
                $.each(errors.errors, function (key, value) {
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
    });
});

function get_pages(){
    let url = "{{route('customorder.get_pages')}}";
    $.ajax({
        url: url,
        type: "GET",
        dataType: "JSON",
        contentType: false,
        processData: false,
        cache: false,
        success: function (data) {
            $('#page_id').empty();
            var option = '<option value="">Please select</option>';
            data.map(function(page,key){
                option += `<option value="${page.id}">${page.page}</option>`;
            });
            $('#page_id').append(option);
            $('#page_id').selectpicker('refresh');

        },error:function (error){
            console.log(error);
        }
    })
}
$('.close','.cls').click(function (){
   alert('close');
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
    },1500);
}
//Get Customer Address
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

//Shipping address place at billing address
$("#isDefaultShipping").click(function(){
    $('#billing_address').val($('#shipping_address').val());
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
});

// Message Order Create Modal Open
function showMessageFormModal(id = null, media=null, order_id=null) {
    // Reset other elements
    $('#message_store_or_update_form')[0].reset();
    $('#content').empty();
    $('#inventory_id-0').val('').trigger('change'); // Empty select

    // Default load today's date
    var currentDate = new Date().toISOString().slice(0, 16);
    $("#order_date").val(currentDate);

    // Show the modal
    $('#message_order_store_or_update_modal').modal({
        keyboard: false,
        backdrop: 'static',
    });
    $('#message_order_store_or_update_modal .modal-title').html(
        '<i class="fas fa-edit"></i> <span>Add Message Custom Order</span>');
    $('#message_order_store_or_update_modal #save-custom-btn').text('Save ');

    //If order message data exist then show in edit mode
    if (order_id && order_id !==0) {
        rowCounter = 0;
        $.ajax({
            url: "{{ route('ordermessage.message_order_edit') }}",
            type: "POST",
            data: { id: order_id, _token: _token,'message_id':id},
            dataType: "JSON",
            success: function (data) {
                console.log(data);
                // Order message display using the updateOrderText function
                $('#myTextarea').val(data?.order_message?.order_text);
                $('#order_message_id').val(id);
                $('.media').val(media);

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

                $('#message_order_store_or_update_modal #customer_id').val(data.customer_id);
                $('#message_order_store_or_update_modal #billing_address').val(data.billing_address);
                $('#message_order_store_or_update_modal #shipping_address').val(data.shipping_address);
                $('#message_order_store_or_update_modal #total').val(data.total);
                $('#message_order_store_or_update_modal #discount').val(data.discount);
                $('#message_order_store_or_update_modal #shipping_charge').val(data.shipping_charge);
                $('#message_order_store_or_update_modal #order_status_id').val(data.order_status_id);
                $('#message_order_store_or_update_modal #payment_method_id').val(data.payment_method_id);
                $('#message_order_store_or_update_modal #payment_status_id').val(data.payment_status_id);

                $('#message_order_store_or_update_modal .grand_total_text').text(data.grand_total);
                $('#message_order_store_or_update_modal #grand_total').val(data.grand_total);
                $('#message_order_store_or_update_modal #update_id').val(data.id);
                $('#message_order_store_or_update_modal #special_note').val(data.special_note);

                var formattedDate = new Date(data.order_date).toISOString().slice(0, 16);
                $('#message_order_store_or_update_modal #order_date').val(formattedDate);

                $('#message_order_store_or_update_modal .selectpicker').selectpicker('refresh');

                $('#message_order_store_or_update_modal').modal({
                    keyboard: false,
                    backdrop: 'static',
                });
                $('#message_order_store_or_update_modal .modal-title').html(
                    '<i class="fas fa-edit"></i> <span>Edit Custom Order</span>');
                $('#message_order_store_or_update_modal #save-custom-btn').text('Update');

            },
            error: function (xhr, ajaxOption, thrownError) {
                console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
            }
        });
    }
    else if(id){
        $.ajax({
            url: "{{ route('ordermessage.edit') }}",
            type: "POST",
            data: {id: id, _token: _token},
            dataType: "JSON",
            success: function (data) {
                // Order message display using the updateOrderText function
                $('#myTextarea').val(data.order_text);
                $('#order_message_id').val(id);
                $('.media').val(media);
            },
            error: function (xhr, ajaxOption, thrownError) {
                console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
            }
        })
    }
}

$(document).on('click', '#save-custom-btn', function () {
    let form = document.getElementById('message_store_or_update_form');
    let formData = new FormData(form);
    let url = "{{route('ordermessage.messagestore.or.update')}}";
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
            $('#save-custom-btn').addClass('kt-spinner kt-spinner--md kt-spinner--light');
        },
        complete: function(){
            $('#save-custom-btn').removeClass('kt-spinner kt-spinner--md kt-spinner--light');
        },
        success: function (data) {
            $('#message_store_or_update_form').find('.is-invalid').removeClass('is-invalid');
            $('#message_store_or_update_form').find('.error').remove();
            if (data.status == false) {
                $.each(data.errors, function (key, value) {
                    $('#message_store_or_update_form input#' + key).addClass('is-invalid');
                    $('#message_store_or_update_form textarea#' + key).addClass('is-invalid');
                    $('#message_store_or_update_form select#' + key).parent().addClass('is-invalid');
                    if(key == 'code'){
                        $('#message_store_or_update_form #' + key).parents('.form-group').append(
                            '<small class="error text-danger">' + value + '</small>');
                    }else{
                        $('#message_store_or_update_form #' + key).parent().append(
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
                    $('#message_order_store_or_update_modal').modal('hide');
                    $(this).find('#message_order_store_or_update_modal').trigger('reset');

                }
            }

        },
        error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail

            if(jqXHR.status === 422) {
                var errors='';
                errors = $.parseJSON(jqXHR.responseText);
                $('.text-danger').empty();
                $.each(errors.errors, function (key, value) {

                    $('#message_order_store_or_update_modal input#' + key).addClass('is-invalid');
                    $('#message_order_store_or_update_modal textarea#' + key).addClass('is-invalid');
                    $('#message_order_store_or_update_modal select#' + key).parent().addClass('is-invalid');

                    $('#message_order_store_or_update_modal #' + key).parents('.form-group').append(
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

</script>
@endpush
