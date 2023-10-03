<div class="modal fade" id="store_or_update_modal" tabindex="-1" role="dialog" aria-labelledby="model-1"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">

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

                        <input type="hidden" name="update_id" id="update_id" />

                        <div class="col-md-12">
                            <div class="row">
                              <x-form.textbox labelName="Name Of Retailer" name="name" required="required" col="col-md-6"
                                placeholder="Enter name" />
                                <x-form.textbox labelName="Retail Code" name="retail_code" col="col-md-6" required="required" placeholder="Enter Retail Code" />
                                <x-form.textbox labelName="Owner Name" name="owner_name" col="col-md-6" placeholder="Enter Owner Name" />
                                <x-form.textbox labelName="Postal Code" name="postal_code" col="col-md-6" placeholder="Enter Postal Code" />
                                <x-form.textarea labelName="Address" name="address" col="col-md-6" />
                                <x-form.textbox labelName="Zone" name="zone" col="col-md-6" placeholder="Enter name" />
                                <x-form.textbox labelName="Sales Person" name="sales_person" col="col-md-6" placeholder="Enter Sales Person Name" />
                                <x-form.textbox labelName="Phone" name="phone" col="col-md-6" placeholder="Enter phone" />
                                <x-form.selectbox labelName="Division" name="division" col="col-md-6" class="selectpicker">
                                    @if (!$divisions->isEmpty())
                                    @foreach ($divisions as $division)
                                    <option value="{{ $division->name }}">{{ $division->name }}</option>
                                    @endforeach
                                    @endif
                                </x-form.selectbox>
                                <x-form.selectbox labelName="District" name="district" col="col-md-6" class="selectpicker">
                                    @if (!$districts->isEmpty())
                                    @foreach ($districts as $district)
                                    <option value="{{ $district->name }}">{{ $district->name }}</option>
                                    @endforeach
                                    @endif
                                </x-form.selectbox>
                                <x-form.textbox labelName="Latitude" name="lat" col="col-md-6" placeholder="Enter Latitude" />
                                <x-form.textbox labelName="Longitude" name="long" col="col-md-6" placeholder="Enter Longitude" />
                            </div>
                        </div>


                    </div>
                </div>
                <!-- /modal body -->

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-sm" id="save-btn"></button>
                </div>
                <!-- /modal footer -->
            </form>
        </div>
        <!-- /modal content -->

    </div>
</div>
