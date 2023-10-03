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

                    <x-form.textbox labelName="Contact Name" name="name" required="required" col="col-md-12" placeholder="Enter contact name"/>
                    <x-form.textarea labelName="Address" name="contact_address" col="col-md-12" />
                    <x-form.textbox labelName="Contact Email" name="contact_email" col="col-md-12" placeholder="Enter Contact Email"/>
                    <x-form.textbox labelName="Contact Phone" name="contact_phone" col="col-md-12" placeholder="Enter Contact Phone"/>
                    <x-form.textbox labelName="Contact Map Key" name="contact_map_key" col="col-md-12" placeholder="Enter Contact Map Key"/>
                    <x-form.textbox labelName="Contact Link" name="contact_link" col="col-md-12" placeholder="Enter Contact Link"/>
                    <x-form.textbox labelName="Longitude" name="contact_longitude" col="col-md-12" placeholder="Enter Longitude"/>
                    <x-form.textbox labelName="Latitude" name="contact_latitude" col="col-md-12" placeholder="Enter Latitude"/>                </div>
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
