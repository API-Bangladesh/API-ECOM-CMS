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

                        <input type="hidden" name="update_id" id="update_id" />

                        <div class="col-md-9">
                            <div class="row">
                                <x-form.textbox labelName="Document Name" name="name" required="required" col="col-md-4"
                                    placeholder="Enter name" />
                                <div class="col-md-6 riMenuInputs">
                                    <div class="ant-card ant-card-bordered gx-card">
                                        <div class="ant-card-head">
                                            <div class="ant-card-head-wrapper">
                                                <div class="ant-card-head-title">Category List *</div>
                                            </div>
                                        </div><br>
                                        <div class="ant-card-body">
                                            <div class="ant-checkbox-group" id="document_category_id">
                                                @if (!$document_categories->isEmpty())
                                                    @foreach ($document_categories as $document_categorie)
                                                        <label class="ant-checkbox-wrapper ant-checkbox-group-item">
                                                                <span class="ant-checkbox">
                                                                    <input type="radio" required="required" name="document_category_id"
                                                                        class="ant-checkbox-input" value="{{ $document_categorie->id }}">
                                                                    <span class="ant-checkbox-inner"></span>
                                                                </span>
                                                                <span>{{ $document_categorie->name }}</span>
                                                            </label>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <x-form.textbox labelName="Document Count" name="document_count" col="col-md-4"
                                    placeholder="Enter Document Count" />

				<x-form.textbox labelName="Document Order" name="document_order" col="col-md-4"
                                    placeholder="Enter Document Order" />
                                <div class="form-group col-md-4 required ">
                                    <label for="document_file">Document File</label>
                                        <input type="hidden" name="old_document_file" id="old_document_file" class="fileName">
                                        <input type="file" name="document_file" id="document_file">


                                </div>
                                <x-form.textarea labelName="Document Description" name="document_desc" col="col-md-12" />

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group col-md-12 required">
                                <label for="image">Document Image</label>
                                <div class="col-md-12 px-0 text-center">
                                    <div id="image">

                                    </div>
                                </div>
                                <input type="hidden" name="old_image" id="old_image">
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
