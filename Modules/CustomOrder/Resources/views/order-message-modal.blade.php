<div class="modal fade" id="store_or_update_modal" tabindex="-1" role="dialog" aria-labelledby="model-1" aria-hidden="true">
    <div class="modal-dialog" role="document">

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
                    <x-form.textarea labelName="Order text" required="required" name="order_text" col="col-md-12" />

                    <div class="form-group col-md-12 ">
                        <label for="media">Media</label>
                        <select name="media" id="media" class="form-control selectpicker" data-live-search="true" >
                            <option value="">Select Please</option>
                            <option value="Facebook">Facebook</option>
                            <option value="WhatsApp">WhatsApp</option>
                            <option value="Instagram">Instagram</option>
                            <option value="Phone">Phone</option>
                        </select>
                    </div>

                    <div class="form-group col-md-12 ">
                        <label for="page_id">ID</label>
                        <select name="page_id" id="page_id" class="form-control selectpicker" data-live-search="true" >
                            <option value="">Select Please</option>
                            <option class="show btn btn-primary" value="">Create</option>
                            @foreach($pages as $page)
                                <option value="{{$page->id}}">{{$page->page}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-8 page">
                        <label for="page">Add ID</label>
                        <input type="text" name="page" id="page" class="form-control " value="" placeholder="">
                    </div>
                    <div class="form-group col-md-4 save">
                        <button type="button" class="btn btn-primary btn-md mt-5" id="save">Save</button>
                    </div>

                    <div class="form-group col-md-12 ">
                        <label for="date_time">Date Time</label>
                        <input type="datetime-local" name="date_time" id="date_time" class="form-control " value="" placeholder="Enter Date Time">
                    </div>
                    <x-form.textarea labelName="Info" name="info" col="col-md-12" />
                </div>
            </div>
            <!-- /modal body -->

            <!-- Modal Footer -->
            <div class="modal-footer">
            <button type="button" class="btn btn-danger btn-sm cls" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary btn-sm" id="save-btn"></button>
            </div>
            <!-- /modal footer -->
        </form>
      </div>
      <!-- /modal content -->

    </div>
  </div>
