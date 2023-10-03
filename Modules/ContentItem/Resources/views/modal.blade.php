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
                                <x-form.selectbox labelName="Module Name" name="module_id" required="required" col="col-md-6" class="selectpicker">
                                    @if (!$content_modules->isEmpty())
                                    @foreach ($content_modules as $content_module)
                                    <option value="{{ $content_module->id }}">{{ $content_module->name }}</option>
                                    @endforeach
                                    @endif
                                </x-form.selectbox>
                                <x-form.selectbox labelName="Content Category" name="category_id" required="required" col="col-md-6"
                                    class="selectpicker">
                                    @if (!$content_categories->isEmpty())
                                    @foreach ($content_categories as $content_categorie)
                                    <option value="{{ $content_categorie->id }}">{{ $content_categorie->name }}</option>
                                    @endforeach
                                    @endif
                                </x-form.selectbox>
                                <x-form.selectbox labelName="Content Type" name="type_id" required="required" col="col-md-6"
                                    class="selectpicker">
                                    @if (!$content_types->isEmpty())
                                    @foreach ($content_types as $content_type)
                                    <option value="{{ $content_type->id }}">{{ $content_type->name }}</option>
                                    @endforeach
                                    @endif
                                </x-form.selectbox>
                                <x-form.textbox labelName="Item Link" name="item_link" col="col-md-6" placeholder="Enter name" />
                                <x-form.textbox labelName="Item Video Link" name="item_video_link" col="col-md-6" placeholder="Enter name" />
				<x-form.textbox labelName="Item Order" name="item_order" col="col-md-6" type="number" placeholder="Enter Order" />
                                <x-form.textbox type="date" labelName="Item Date" name="item_date" col="col-md-6" placeholder="Enter name" />
                                <x-form.textarea labelName="Short Description" name="item_short_desc" col="col-md-12" />
                                <x-form.textarea labelName="Long Description" name="item_long_desc" col="col-md-12" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group col-md-12">
                                <label for="image">Item Image</label>
                                <div class="col-md-12 px-0 text-center">
                                    <div id="image">

                                    </div>
                                </div>
                                <input type="hidden" name="old_image" id="old_image">
                            </div>
                            <div class="form-group col-md-12">
                                <label for="item_image_banner">Item Image Banner</label>
                                <div class="col-md-12 px-0 text-center">
                                    <div id="item_image_banner">

                                    </div>
                                </div>
                                <input type="hidden" name="old_item_image_banner" id="old_item_image_banner">
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
