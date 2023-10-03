<div class="modal fade" id="store_or_update_modal" tabindex="-1" role="dialog" aria-labelledby="model-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">

        <!-- Modal Content -->
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header bg-primary">
                <h3 class="modal-title text-white" id="model-1"></h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <!-- /modal header -->
            <form id="store_or_update_form" method="post">
            @csrf
            <!-- Modal Body -->
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="update_id" id="update_id"/>
                        <x-form.selectbox labelName="Product Name" name="product_id" required="required" col="col-md-6" class="setInventoryTitle selectpicker">
                            @if (!$products->isEmpty())
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            @endif
                        </x-form.selectbox>

                        <x-form.textbox type="number" labelName="Product Sale Price" name="sale_price" col="col-md-3" placeholder="Enter Sale Price" />

                        <x-form.textbox type="number" labelName="Offer Price" name="offer_price" col="col-md-3" placeholder="Enter Offer Price"/>

                        <x-form.textbox labelName="Inventory Product Title" name="title" required="required" col="col-md-6" placeholder="Enter Inventory Product Title"/>

                        <x-form.textbox type="date" labelName="Offer Start" name="offer_start" col="col-md-3" />

                        <x-form.textbox type="date" labelName="Offer End" name="offer_end" col="col-md-3" />

                        {{--                <x-form.textbox labelName="SKU" name="sku" required="required" col="col-md-6" placeholder="Enter SKU"/>--}}

                        <x-form.textbox type="number" labelName="Min Order Quantity" name="min_order_quantity" placeholder="Enter Min Order Quantity" col="col-md-6" />

                        <x-form.textbox type="number" labelName="Stock Quantity" name="stock_quantity" col="col-md-3" placeholder="Enter Stock Quantity"/>

                        <x-form.textbox type="number" labelName="Reorder Quantity" name="reorder_quantity" placeholder="Enter Reorder Quantity" col="col-md-3" />

                        <div class="form-group col-md-6">
                            <label for="image">Inventory Image</label>
                            <div class="col-md-12 px-0 text-center">
                                <div id="image">

                                </div>
                            </div>
                            <input type="hidden" name="old_image" id="old_image">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="title">Is Special Deal</label>
                            <li class="branch">
                                <input type="checkbox" id="is_special_deal" value="1" name="is_special_deal" class="form-check-input" >
                                <label class="form-check-label" for="is_special_deal">Yes</label>
                            </li>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="title">Is Manage Stock</label>
                            <li class="branch">
                                <input type="checkbox" value="1" name="is_manage_stock" id="is_manage_stock" class="form-check-input" >
                                <label class="form-check-label" for="is_manage_stock">Yes</label>
                            </li>
                        </div>

                        {{--                <x-form.selectbox onchange="getVariantOptionList(this.value,'row-0')" labelName="Variants" name="variant_id[]" required="required" col="col-md-3" class="selectpicker main-0">--}}
                        {{--                    @foreach ($variants as $variant)--}}
                        {{--                       <option value="{{ $variant->id }}">{{ $variant->name }}</option>--}}
                        {{--                    @endforeach--}}
                        {{--                </x-form.selectbox>--}}

                        <div class="form-group col-md-3">
                            <label for="variant_id[]">Variants</label>
                            <select name="variant_id[]" id="variant_id[]" class="form-control selectpicker main-0" onchange="getVariantOptionList(this.value,'row-0')" data-live-search="true" >
                                <option value=""> Select Please</option>
                                @foreach ($variants as $variant)
                                    <option value="{{ $variant->id }}">{{ $variant->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <x-form.selectbox labelName="Variant Option" name="variant_option_id[]" col="col-md-3 variant_option_id" class="row-0 form-control variant_option_id " />


                        <div class="form-group col-md-6 ">
                            <input class="mt-5" type="button" id="addnew" value="Add New" onclick="addRow()">
                        </div>

                    </div>

                    <div id="content">
                    </div>
                </div>
                <!-- /modal body -->

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" id="close-btn" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-sm" id="save-btn"></button>
                </div>
                <!-- /modal footer -->
            </form>
        </div>
        <!-- /modal content -->

    </div>
</div>
