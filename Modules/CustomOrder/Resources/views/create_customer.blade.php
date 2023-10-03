<style>
    /* CSS for slim and small scrollbar */
    .modal-body {
        overflow-x: scroll;
        height: 550px;
        scrollbar-width: thin; /* For Firefox */
        scrollbar-color: #888888 #f0f0f0; /* For Firefox */
    }

    /* Webkit browsers (e.g., Chrome, Safari) */
    .modal-body::-webkit-scrollbar {
        width: 1px; /* Width of the scrollbar */
    }

    /* Webkit browsers (e.g., Chrome, Safari) */
    .modal-body::-webkit-scrollbar-thumb {
        background-color: #888888; /* Color of the scrollbar thumb */
    }

    /* Webkit browsers (e.g., Chrome, Safari) */
    .modal-body::-webkit-scrollbar-track {
        background-color: #f0f0f0; /* Color of the scrollbar track */
    }
</style>

<div id="myModal2" class="modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">

        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h3 class="modal-title text-white" id="model-1"><i class="fas fa-plus-square"></i> Add New Customer</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body">

                <h2 style="margin:0 auto;display:block;box-shadow: 5px 5px 4px 1px #4a6b70"
                    class="mb-5 text-center col-md-4 border p-1">Customer</h2>

                <form id="store_or_update_customer" method="post">
                    @csrf
                    <div class="row">
                        <input type="hidden" name="update_customerid" id="update_customerid"/>
                        <div class="form-group col-md-4 required">
                            <label for="name">Full Name</label>
                            <input type="text" name="name" required="" id="name" class="form-control required" value=""
                                   placeholder="Enter Full Name">
                        </div>

                        <div class="form-group col-md-4 required">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" required="" class="form-control required"
                                   value="" placeholder="Enter Email">
                        </div>

                        <div class="form-group col-md-4 required">
                            <label for="password">Password</label>
                            <input type="password" name="password" required="" id="password" class="form-control "
                                   value="" placeholder="Enter Password">
                        </div>

                    </div>

                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="gender">Gender</label>
                            <select name="gender" id="gender" aria-label="Default select example"
                                    class="form-control selectpicker">
                                <option> Select gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Others">Others</option>
                            </select>
                        </div>

                        <div class="form-group col-md-4 required">
                            <label for="phone_number">Phone Number</label>
                            <input type="number" name="phone_number" required="" id="phone_number" class="form-control "
                                   value="" placeholder="Enter Phone Number">
                        </div>

                        <div class="form-group col-md-4">
                            <label for="date_of_birth">Date Of Birth</label>
                            <input type="date" name="date_of_birth" id="date_of_birth"
                                   class="form-control required" value="" placeholder="Enter Date of birth">
                        </div>

                    </div>
                    {{-- address start--}}
                    <h2 style="margin:0 auto;display:block;box-shadow: 5px 5px 4px 1px #4a6b70"
                        class="mb-5 text-center col-md-4 border p-1">Address</h2>
                    <div class="row">
                        <input type="hidden" name="update_addressid" id="update_addressid"/>
                        <div class="form-group col-md-6 required">
                            <label for="title">Address title</label>
                            <input type="text" name="title" required="" id="title" class="form-control"
                                   placeholder="Enter Address title">
                        </div>

                        <div class="form-group col-md-6 required">
                            <label for="name">Your name</label>
                            <input type="text" name="customer_name" required="" id="customer_name" class="form-control " value=""
                                   placeholder="Enter Name">
                        </div>

                    </div>

                    <div class="row">
                        <div class="form-group col-md-6 required">
                            <label for="address_line_1">Address Line 1</label>
                            <textarea name="address_line_1" required="" id="address_line_1" class="form-control "
                                      placeholder=""></textarea>
                        </div>

                        <div class="form-group col-md-6 required">
                            <label for="address_line_2">Address Line 2</label>
                            <textarea name="address_line_2" required="" id="address_line_2" class="form-control "
                                      placeholder=""></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 form-group required">
                            <label for="division_id">Division</label>
                            <select name="division_id" id="division_id" required="" class="form-control selectpicker"
                                    onchange="getDistrictByDivision(this.value)" data-live-search="true"
                                    data-live-search-placeholder="Search" title="Choose one of the following"
                                    tabindex="null">
                                <option value="">Select Please</option>
                                @foreach($divisions as $division)
                                    <option value="{{$division->id}}">{{$division->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 form-group required">
                            <label for="district_id">District</label>
                            <select name="district_id" required="" id="district_id" class="form-control selectpicker"
                                    onchange="getUpazilaByDistrict(this.value)" data-live-search="true"
                                    data-live-search-placeholder="Search" title="Choose one of the following"
                                    tabindex="null">
                                <option value="">Select Please</option>
                            </select>
                        </div>

                        <div class="col-md-4 form-group required">
                            <label for="upazila_id">City/Area</label>
                            <select name="upazila_id" required="" id="upazila_id" class="form-control selectpicker"
                                    data-live-search="true" data-live-search-placeholder="Search"
                                    title="Choose one of the following" tabindex="null">
                                <option value="">Select Please</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-4 required">
                            <label for="postcode">Post Code </label>
                            <input type="number" name="postcode" required="" id="postcode" class="form-control " value=""
                                   placeholder="Enter Post code">
                        </div>

                        <div class="form-group col-md-4 required">
                            <label for="phone">Phone </label>
                            <input type="text" name="phone" required="" id="phone" class="form-control " value=""
                                   placeholder="Enter Phone number">
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-sm" id="close-btn" data-dismiss="modal">Close
                        </button>
                        <button type="button" class="btn btn-primary btn-sm" id="save-customer">Save</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
