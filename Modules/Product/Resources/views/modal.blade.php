<div class="modal fade" id="store_or_update_modal" tabindex="-1" role="dialog" aria-labelledby="model-1"
    aria-hidden="true">
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

                        <input type="hidden" name="update_id" id="update_id" />

                        <div class="col-md-9">
                            <div class="row">
                              <x-form.textbox labelName="Name" name="name" required="required" col="col-md-6"
                                placeholder="Enter name" />
                            <x-form.selectbox labelName="Category" name="category_id" required="required" onchange="getSubCategoryList(this.value)" col="col-md-6" class="selectpicker">
                                @if (!$categories->isEmpty())
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                @endif
                            </x-form.selectbox>
                            <x-form.selectbox labelName="Sub Category" name="sub_category_id" col="col-md-6 sub_category_id" class="selectpicker" />

                            <x-form.selectbox labelName="Variant" name="variant_id" onchange="getVariantOptionList(this.value)" col="col-md-6" class="selectpicker">
                                @if (!$variants->isEmpty())
                                    @foreach ($variants as $variant)
                                        <option value="{{ $variant->id }}">{{ $variant->name }}</option>
                                    @endforeach
                                @endif
                            </x-form.selectbox>
                            <x-form.selectbox labelName="Variant Option" name="variant_option_id" col="col-md-6 variant_option_id" class="selectpicker" />
                            <x-form.selectbox labelName="Segment" name="segment_id" col="col-md-6" class="selectpicker">
                                @if (!$segments->isEmpty())
                                    @foreach ($segments as $segment)
                                        <option value="{{ $segment->id }}">{{ $segment->name }}</option>
                                    @endforeach
                                @endif
                            </x-form.selectbox>
                            <x-form.selectbox labelName="Pack Type" name="pack_id" col="col-md-6" class="selectpicker">
                                @if (!$packTypes->isEmpty())
                                    @foreach ($packTypes as $packType)
                                        <option value="{{ $packType->id }}">{{ $packType->name }}</option>
                                    @endforeach
                                @endif
                            </x-form.selectbox>
			    <x-form.textbox type="number" labelName="Product Order Id" name="product_order" col="col-md-6" placeholder="Enter Order id" />
                            <x-form.textbox labelName="Product Link" name="product_link" col="col-md-6" placeholder="Enter Product Link" />
                            <x-form.textbox labelName="Product BNCN" name="product_bncn" col="col-md-6" placeholder="Enter Product BNCN" />


                                <x-form.textarea labelName="Product Short Description" name="product_short_desc" col="col-md-12" />
                                <x-form.textarea labelName="Product Long Description" name="product_long_desc" col="col-md-12" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group col-md-12 required">
                                <label for="image">Product Image</label>
                                <div class="col-md-12 px-0 text-center">
                                    <div id="image">

                                    </div>
                                </div>
                                <input type="hidden" name="old_image" id="old_image">
                            </div>

                            <div class="form-group col-md-12 required">
                                <label for="lifestyle_image">Life Style Banner</label>
                                <div class="col-md-12 px-0 text-center">
                                    <div id="lifestyle_image">

                                    </div>
                                </div>
                                <input type="hidden" name="old_lifestyle_image" id="old_lifestyle_image">
                            </div>

                            <div class="form-group col-md-12">
                                <label for="video">Product Video</label>
                                <input type="hidden" name="old_product_video_path" id="old_product_video_path" class="fileName">
                                <input type="file" name="product_video_path" id="product_video_path">
				<p id="pro_video"></p>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="video">Product Brochure</label>
                                <input type="hidden" name="old_product_brochure" id="old_product_brochure" class="fileName1">
                                <input type="file" name="product_brochure" id="product_brochure">
				<p id="pro_broc"></p>
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
