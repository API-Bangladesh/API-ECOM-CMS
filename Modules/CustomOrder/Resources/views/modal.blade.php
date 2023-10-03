<div class="modal fade" id="store_or_update_modal" role="dialog" aria-labelledby="model-1" aria-hidden="true">
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

                        <div class="form-group col-md-4 required">
                            <label for="customer_id">Customer</label>
                            <select name="customer_id" id="customer_id" class="form-control customer_create selectpicker" onchange="getCustomer(this.value,'customer_id')" data-live-search="true" data-live-search-placeholder="Search" title="Choose one of the following" tabindex="null">
                                <option value="">Select Please</option>
                                @if (!$customers->isEmpty())
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="billing_address">Set Billing Address</label>
                            <select name="billing" id="billing" class="form-control selectpicker" onchange="getCustomer(this.value,'address_id','billing')" data-live-search="true" >
                                <option value=""> Select Please</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="shipping">Set Shipping Address</label>
                            <select name="shipping" id="shipping" class="form-control selectpicker" onchange="getCustomer(this.value,'address_id','shipping')" data-live-search="true" >
                                <option value=""> Select Please</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <x-form.textarea labelName="Billing Address" placeholder="Billing Address" name="billing_address" id="billing_address" col="col-md-4 required" />
                        <x-form.textarea labelName="Shipping Address" placeholder="Shipping Address" name="shipping_address" id="shipping_address" col="col-md-4 required" />
                        <div class="form-group col-md-4 required">
                            <label for="order_date">Delivery Time</label>
                            <input type="datetime-local" name="order_date" id="order_date" class="form-control " value="" placeholder="Enter Order Date">
                        </div>
                    </div>

{{--                    <div class="row">--}}
{{--                        <x-form.textbox type="number" labelName="Phone" placeholder="Enter Phone" name="phone" col="col-md-4" />--}}
{{--                        <x-form.textbox labelName="Email" name="email" placeholder="Enter Email" col="col-md-4" />--}}

{{--                    </div>--}}

                    <div class="products" style="border: 1px solid #00008b1f;padding: 15px 10px;margin: 0px 0px 10px 0px;">
                        <div class="row">
                            <div class="form-group col-md-4 required">
                                <label for="inventory_id-0">Products</label>
                                <select name="inventory_id[]" id="inventory_id-0" class="form-control selectpicker" onchange="getVariantOptionList(this.value, 'row-0', 0); getQuantityList(this.value, 'quantity-0', 0)" data-live-search="true" >
                                    <option value=""> Select Please</option>
                                    @foreach ($inventories as $inventory)
                                        <option value="{{ $inventory->id }}">{{ $inventory->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-2 ">
                                <label for="unit_price-0">Price</label>
                                <input type="number" readonly name="unit_price[]" id="unit_price-0" class="form-control "  placeholder="Enter Price">
                            </div>

                            <div class="form-group col-md-2 required">
                                <label for="quantity-0">Quantity</label>
                                <input type="number" name="quantity[]" id="quantity-0" onchange="getQuantityList(this.value,'quantity-0',0)" class="form-control " placeholder="Enter Quantity">
                            </div>

                            <div class="form-group col-md-2 required">
                                <label for="total-0">Total</label>
                                <input type="number" readonly name="total[]" id="total-0" class="form-control "  placeholder="Enter Total">

                            </div>
                            <div class="form-group col-md-2">
                                <input class="mt-5" type="button" id="addnew" value="Add New" onclick="addRow()">
                            </div>

                        </div>

                        <div id="content">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="special_note">Special note</label>
                                    <input type="text" name="special_note" id="special_note" class="form-control " value="" placeholder="Enter Special Note">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 offset-4">
                            <div class="row">
                                <div class="form-group col-md-12 required">
                                    <label for="total"> Total</label>
                                    <input type="number" readonly name="total" id="total" class="form-control " placeholder="Enter Total">
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="discount">Discount</label>
                                    <input type="number" name="discount" id="discount" class="form-control" value="" placeholder="Enter Discount">
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="shipping_charge">Shipping Cost</label>
                                    <input type="number" name="shipping_charge" id="shipping_charge" class="form-control" value="" placeholder="Enter Shipping Cost">
                                </div>
                                <div class="form-group col-md-12">
                                    <label class="font-weight-bold" for="grand_total">Grand Total:</label>
                                    <span class="font-weight-bold grand_total_text">&nbsp; &nbsp; 0.0TK</span>
                                    <input type="hidden" name="grand_total" id="grand_total">
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="order_status_id">Order Status</label>
                                    <select name="order_status_id" id="order_status_id" class="form-control selectpicker" data-live-search="true" >
                                        <option value=""> Select Please</option>
                                        <option value="6">NEW</option>
                                        <option value="7">CONFIRMED</option>
                                        <option value="1">PENDING</option>
                                        <option value="2">PROCESSING</option>
                                        <option value="3">SHIPPED</option>
                                        <option value="4">DELIVERED</option>
                                        <option value="5">CANCELED</option>
                                        <option value="8">RETURNED</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="payment_method_id">Payment Method</label>
                                    <select name="payment_method_id" id="payment_method_id" class="form-control selectpicker" data-live-search="true" >
                                        <option value=""> Select Please</option>
                                        @foreach ($payment_methods as $payment_method)
                                            <option value="{{ $payment_method->id }}">{{ $payment_method->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="payment_method_id">Payment Status</label>
                                    <select name="payment_status_id" id="payment_status_id" class="form-control selectpicker" data-live-search="true" >
                                        <option value=""> Select Please</option>
                                        <option value="{{\Modules\Order\Entities\Order::PAYMENT_STATUS_PAID}}">PAID</option>
                                        <option value="{{\Modules\Order\Entities\Order::PAYMENT_STATUS_UNPAID}}">UNPAID</option>
                                    </select>
                                </div>
                            </div>
                        </div>
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
