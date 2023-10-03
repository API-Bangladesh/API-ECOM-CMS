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
                    @if (permission('inventory-add'))
                        <button class="btn btn-primary btn-sm" onclick="showFormModal('Add New Inventory Product','Save');clearOldImage()">
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
                                    <label for="name">inventory Name</label>
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Enter Inventory Product">
                                </div>
                                {{--                                <x-form.selectbox labelName="Category" name="category_id" col="col-md-3">--}}
                                {{--                                    @if (!$categories->isEmpty())--}}
                                {{--                                        @foreach ($categories as $category)--}}
                                {{--                                            <option value="{{ $category->id }}">{{ $category->name }}</option>--}}
                                {{--                                        @endforeach--}}
                                {{--                                    @endif--}}
                                {{--                                </x-form.selectbox>--}}
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
                                @if (permission('inventory-bulk-delete'))
                                    <th>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="select_all" onchange="select_all()">
                                            <label class="custom-control-label" for="select_all"></label>
                                        </div>
                                    </th>
                                @endif
                                <th>Sl</th>
                                <th>Title</th>
                                <th>Sale Price</th>
                                <th>Stock Quantity</th>
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
    @include('inventory::modal')
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
                    "url": "{{route('inventory.datatable.data')}}",
                    "type": "POST",
                    "data": function (data) {
                        data.name        = $("#form-filter #name").val();
                        data._token      = _token;
                    }
                },
                "columnDefs": [{
                    @if (permission('inventory-bulk-delete'))
                    "targets": [0,5],
                    @else
                    "targets": [4],
                    @endif
                    "orderable": false,
                    "className": "text-center"
                },
                    {
                        @if (permission('inventory-bulk-delete'))
                        "targets": [1,2,3,4,5],
                        @else
                        "targets": [0,1,3,4,5],
                        @endif
                        "className": "text-center"
                    },
                    {
                        @if (permission('inventory-bulk-delete'))
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
                        @if (permission('inventory-bulk-delete'))
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
                let url = "{{route('inventory.store.or.update')}}";
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

            $(document).on('click', '.edit_data', function () {
                $('#content').html('');
                rowCounter = 0;
                let id = $(this).data('id');

                $('#store_or_update_form')[0].reset();
                $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
                $('#store_or_update_form').find('.error').remove();

                if (id) {
                    $.ajax({
                        url: "{{ route('inventory.edit') }}",
                        type: "POST",
                        data: { id: id, _token: _token},
                        dataType: "JSON",
                        success: function (data) {
                            console.log(data);
                            $('#store_or_update_form #old_image').val(data.image);
                            if(data.image){
                                var image = "{{ asset('storage/'.INVENTORY_SINGLE_IMAGE_PATH)}}/"+data.image;
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

                            data.inventory_variants.map(function (val, key) {
                                const rowId = `row-${rowCounter}`;

                                var variantHtml ="<option value='' >Select please</option>";
                                data.variants.map(function(variant, ke){

                                    if(variant.id == val.variant_id) {
                                        variantHtml += "<option value='" + variant.id + "' selected>" + variant.name + "</option>";
                                    }else{
                                        variantHtml += "<option  value=" + variant.id + ">" + variant.name + "</option>";
                                    }
                                });

                                var variantOptionHtml ="<option value='' >Select please</option>";
                                data.all_variant_options.map(function(variant_option, ke){
                                    if(variant_option.variant_id == val.variant_id) {

                                        if(variant_option.id == val.variant_option_id) {
                                            variantOptionHtml += "<option value='" + variant_option.id + "' selected>" + variant_option.name + "</option>";
                                        }else{
                                            variantOptionHtml += "<option  value=" + variant_option.id + ">" + variant_option.name + "</option>";
                                        }
                                    }
                                });

                                if (rowCounter ==0) {
                                    // Handle the first row differently
                                    var options = `<select name="variant_option_id[]" id="variant_option_id" class="form-control row-0">
                                        ${variantOptionHtml}
                                    </select>`;
                                    $('.main-0').val(val.variant_id);
                                    // $('.row-0').html(variantOptionHtml);
                                    $('.row-0').html('');
                                    $('.row-0').html(options);
                                    $('.row-0').val(val.variant_option_id);
                                    // $('.' + rowId).val(val.variant_option_id);
                                    rowCounter++;
                                } else {
                                    // Create new row with variant and variant_option selects
                                    const div = document.createElement('div');
                                    div.classList.add('row');

                                    div.innerHTML = `<div class="form-group col-md-3">
                                  <label for="variant_id[]">Variants</label>
                                  <select name="variant_id[]" id="variant_id[]" class="form-control main-${rowCounter}" onchange="getVariantOptionList(this.value, 'row-${rowCounter}')" >
                                       ${variantHtml}
                                    </select>
                                  </div>
                                  <div class="form-group col-md-3 variant_option_id">
                                    <label for="variant_option_id">Variant Option</label>
                                    <select name="variant_option_id[]" id="variant_option_id" class="form-control row-${rowCounter}">
                                        ${variantOptionHtml}
                                    </select>
                                  </div>
                                  <div class="form-group col-md-6">
                                    <input class="mt-5" type="button" value="Remove" onclick="removeRow(this)">
                                  </div>`;

                                    // Append the new row to the 'content' element
                                    document.getElementById('content').appendChild(div);
                                    rowCounter++;
                                }
                            });

                            $('#store_or_update_form #update_id').val(data.id);
                            $('#store_or_update_form #product_id').val(data.product_id);
                            $('#store_or_update_form #title').val(data.title);
                            // $('#store_or_update_form #sku').val(data.sku);
                            $('#store_or_update_form #min_order_quantity').val(data.min_order_quantity);
                            $('#store_or_update_form #sale_price').val(data.sale_price);
                            $('#store_or_update_form #offer_price').val(data.offer_price);
                            if(data.offer_start){
                                $('#store_or_update_form #offer_start').val(data.offer_start.substring(0,10));
                            }
                            if(data.offer_end) {
                                $('#store_or_update_form #offer_end').val(data.offer_end.substring(0, 10));
                            }
                            // $('#store_or_update_form #sku').val(data.sku);
                            $('#store_or_update_form #stock_quantity').val(data.stock_quantity);
                            $('#store_or_update_form #reorder_quantity').val(data.reorder_quantity);
                            $('#store_or_update_form #min_order_quantity').val(data.min_order_quantity);
                            $('#store_or_update_form #variant').val(data.variant);
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
                let url   = "{{ route('inventory.delete') }}";
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
                    let url = "{{route('inventory.bulk.delete')}}";
                    bulk_delete(ids,url,table,rows);
                }
            }

            $(document).on('click', '.change_status', function () {
                let id    = $(this).data('id');
                let status = $(this).data('status');
                let name  = $(this).data('name');
                let row   = table.row($(this).parent('tr'));
                let url   = "{{ route('inventory.change.status') }}";
                change_status(id,status,name,table,url);

            });

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

        function getVariantOptionList(variant_id,variant_option_id=''){
             // console.log(variant_option_id);
            $.ajax({
                url:"{{ url('vo-list') }}/"+variant_id,
                type:"GET",
                dataType:"JSON",
                success:function(data){
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
                    // console.log(loadedContentDiv);
                },
            });
        }

        function clearOldImage(){
            $('#store_or_update_form #image .img_').attr('src','');
            $('.spartan_remove_row').hide();
            $('#content').html('');
            $('.row-0').html('');
            $('.main-0').val('');
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
            div.innerHTML = `<div class="form-group col-md-3">
                                    <label for="variant_id[]">Variants</label>
                                    <select name="variant_id[]" id="variant_id[]" class="form-control main-${rowCounter}" data-live-search="true" onchange="getVariantOptionList(this.value,'row-${rowCounter}')" >
                                        <option value='' >Select please</option>
                                        @foreach ($variants as $variant)
            <option value="{{ $variant->id }}">{{ $variant->name }}</option>
                                        @endforeach
            </select>
        </div>

<div class="form-group col-md-3 variant_option_id ">
<label for="variant_option_id">Variant Option</label>
<select name="variant_option_id[]" id="variant_option_id" data-live-search="true" class="form-control variant_option_id ${rowId}"></select>
                            </div>
                            <div class="form-group col-md-6 ">
                              <input class="mt-5" type="button" value="Remove" onclick="removeRow(this)">
                            </div>`; // Closing </div> added here
            document.getElementById('content').appendChild(div);
            rowCounter++;
            console.log(rowCounter)
        }
        function removeRow(input) {
            input.parentNode.parentNode.remove();
        }

    </script>
@endpush
