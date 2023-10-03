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

                        <div class="col-md-7">
                            <div class="row">

                            <x-form.selectbox labelName="Blog Category Name" name="blog_category_id" required="required" col="col-md-12" class="selectpicker">
                                @if (!$blogCategories->isEmpty())
                                    @foreach ($blogCategories as $blogCategorie)
                                        <option value="{{ $blogCategorie->id }}">{{ $blogCategorie->name }}</option>
                                    @endforeach
                                @endif
                            </x-form.selectbox>
                            	<x-form.textbox labelName="Name" name="name" required="required" col="col-md-12" placeholder="Enter name" />
                            	<x-form.textbox labelName="Blog Author" name="blog_author" col="col-md-12" placeholder="Enter Blog Author" />
                            	<x-form.textbox labelName="Blog Date" name="blog_date" col="col-md-12" class="date" />
				<x-form.textbox labelName="Order" name="blog_order" type="number" col="col-md-12" placeholder="Enter Order" />
                                <x-form.textarea labelName="Blog Short Description" name="blog_short_desc" col="col-md-12" />
                                <x-form.textarea labelName="Blog Long Description" name="blog_long_desc" col="col-md-12" />
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group col-md-12">
                                <label for="image">Blog Image</label>
                                <div class="col-md-12 px-0 text-center">
                                    <div id="image">

                                    </div>
                                </div>
                                <input type="hidden" name="old_image" id="old_image">
                            </div>

                            <div class="form-group col-md-12">
                                <label for="blog_banner_image">Blog Banner Image</label>
                                <div class="col-md-12 px-0 text-center">
                                    <div id="blog_banner_image">

                                    </div>
                                </div>
                                <input type="hidden" name="old_blog_banner_image" id="old_blog_banner_image">
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
