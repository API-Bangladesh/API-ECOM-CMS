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
                <div class="col-md-9">
                <div class="row">
                    <input type="hidden" name="update_id" id="update_id"/>
                    <x-form.selectbox labelName="Combo Category Name" name="combo_category_id" required="required" col="col-md-6" class="selectpicker">
                        @if (!$ComboCategories->isEmpty())
                            @foreach ($ComboCategories as $ComboCategory)
                            <option value="{{ $ComboCategory->id }}">{{ $ComboCategory->name }}</option>
                            @endforeach
                        @endif
                    </x-form.selectbox>

                    <x-form.textbox type="number" labelName="Combo Sale Price" name="sale_price" col="col-md-3" placeholder="Enter Combo Sale Price" />


                    <x-form.textbox type="number" labelName=" Combo Offer Price" name="offer_price" col="col-md-3" placeholder="Enter Combo Offer Price"/>

                    <x-form.textbox labelName="Combo Name" name="title" required="required" col="col-md-6" placeholder="Enter Combo Name"/>

                    <x-form.textbox type="date" labelName="Offer Start" name="offer_start" col="col-md-3" />

                    <x-form.textbox type="date" labelName="Offer End" name="offer_end" col="col-md-3" />

                    <x-form.textbox labelName="Product Short Desc" name="product_short_desc" col="col-md-6" placeholder="Enter Product Short Desc"/>

                    <x-form.textbox type="number" labelName="Stock Quantity" min="1" name="stock_quantity" col="col-md-3" placeholder="Enter Stock Quantity"/>

                    <x-form.textbox type="number" labelName="Reorder Quantity" name="reorder_quantity" placeholder="Enter Reorder Quantity" col="col-md-3" />

                    <x-form.textarea labelName="Product Long Desc" name="product_long_desc" col="col-md-6" placeholder="Enter Product Long Desc"/>

                    <x-form.textbox type="number" labelName="Min Order Quantity" name="min_order_quantity" placeholder="Enter Min Order Quantity" col="col-md-3" />

                    <div class="form-group col-md-3">
                        <label for="title">Is Special Deal</label>
                        <li class="branch">
                            <input type="checkbox" id="is_special_deal" value="1" name="is_special_deal" class="form-check-input" >
                            <label class="form-check-label" for="is_special_deal">Yes</label>
                        </li>
                    </div>

{{--                    <x-form.selectbox labelName="Inventory" name="inventory_id[]" id="inventory_id-0" col="col-md-3" class="selectpicker main-0">--}}
{{--                        @if (!$Inventories->isEmpty())--}}
{{--                            @foreach ($Inventories as $Inventory)--}}
{{--                                <option value="{{ $Inventory->id }}">{{ $Inventory->title }}</option>--}}
{{--                            @endforeach--}}
{{--                        @endif--}}
{{--                    </x-form.selectbox>--}}
                    <div class="form-group col-md-3 ">
                        <label for="inventory_id[]">Inventory</label>
                        <select name="inventory_id[]" id="inventory_id-0" class="form-control selectpicker main-0">
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
                        <input type="number" name="quantity[]" min="1" id="quantity-0" class="form-control row-0" value="" placeholder="Enter Quantity">
                    </div>

                    <div class="form-group col-md-3 ">
                        <input class="mt-5 addnew" type="button" id="addnew" value="Add New" onclick="addRow()">
                    </div>

                    <div class="form-group col-md-3">
                        <label for="title">Is Manage Stock</label>
                        <li class="branch">
                            <input type="checkbox" value="1" name="is_manage_stock" id="is_manage_stock" class="form-check-input" >
                            <label class="form-check-label" for="is_manage_stock">Yes</label>
                        </li>
                    </div>

                    <div id="content" class="col-md-12">
                    </div>

                </div>

                </div>

                    <div class="col-md-3">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="image">Combo Image</label>
                                <div class="col-md-12 px-0 text-center">
                                    <div id="image">

                                    </div>
                                </div>
                                <input type="hidden" name="old_image" id="old_image">
                            </div>

                            <div class="form-group col-md-12">
                                <label for="lifestyle_image">Combo lifestyle Image</label>
                                <div class="col-md-12 px-0 text-center">
                                    <div id="lifestyle_image">

                                    </div>
                                </div>
                                <input type="hidden" name="old_lifestyle_image" id="old_lifestyle_image">
                            </div>

                        </div>
                    </div>
                    </div>
            </div>
            <!-- /modal body -->

            <!-- Modal Footer -->
            <div class="modal-footer">
            <button type="button" class="btn btn-danger btn-sm" id="modal-close-btn" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary btn-sm" id="save-btn"></button>
            </div>
            <!-- /modal footer -->
        </form>
      </div>
      <!-- /modal content -->

    </div>
  </div>
