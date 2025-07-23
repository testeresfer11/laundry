<!-- Add Variant modal start -->
<div class="modal fade" id="add_variant" tabindex="-1" aria-labelledby="add_variantLabel"aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title fs-5" id="exampleModalLabel">Add Variant</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
          </div>
          <div class="modal-body">
            <form action="{{ route('admin.variant.add') }}" method="POST" id="add_variant_form">
                @csrf
                <div class="">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Name">
                </div>

                <div class="mb-2">
                    <label for="gender" class="form-label">Gender</label>
                    <select name="gender" id="gender" class="form-control">
                        <option value="Men">Men</option>
                        <option value="Women">Women</option>
                    </select>
                </div>

                <div class="mb-2">
                    <label for="Image" class="form-label">Image</label>
                    <input type="file" name="image" class="form-control file-upload-info" placeholder="Upload Image" accept="image/*">
                </div>
                
                <button type="submit" class="btn btn-primary">Add</button>
            </form>
          </div>
      </div>
  </div>
</div>
<!-- Add Variant modal end -->

<!-- Edit Variant modal start -->
<div class="modal fade" id="edit_variant" tabindex="-1" aria-labelledby="edit_variantLabel"aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fs-5" id="exampleModalLabel">Edit Variant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
            </div>
            <div class="modal-body">
              <form action="{{ route('admin.variant.edit') }}" method="POST" id="edit_variant_form">
                  @csrf
                    <div class="">
                    <input type="hidden" name="edit_variant_id" id="edit_variant_id" value="">
                      <label for="name" class="form-label" >Name</label>
                      <input type="text" class="form-control" id="edit_variant_name"name="name">
                    </div>
                  
                    <div class="mb-2">
                      <label for="edit_variant_gender" class="form-label" >Gender</label>
                      <select name="gender" id="edit_variant_gender"  class="form-control" >
                        <option value="Men">Men</option>
                        <option value="Women">Women</option>
                      </select>
                    </div>

                    <div class="mb-2">
                        <label for="Image" class="form-label">Image</label>
                        <input type="file" name="image" class="form-control file-upload-info" placeholder="Upload Image" accept="image/*">
                    </div>

                  <button type="submit" class="btn btn-primary">Update</button>
              </form>
            </div>
        </div>
    </div>
</div>
<!-- Edit Variant modal end -->

<!-- Edit Shedule time start -->
<div class="modal fade" id="edit_shedule_time" tabindex="-1" aria-labelledby="edit_shedule_timeLabel"aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fs-5" id="SheduleLabel">Edit Shedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
            </div>
            <div class="modal-body">
              <form action="{{ route('admin.config-setting.timeShedule.edit') }}" method="POST" id="edit_shedule_time_form">
                  @csrf
                  <input type="hidden" name="edit_shedule_id" id="edit_shedule_id" value="">
                  <div class="mb-5">
                      <label for="start_time" class="form-label" >Start Time</label>
                      <input type="time" class="form-control" id="edit_start_time"name="start_time">
                  </div>
                  <div class="mb-5">
                    <label for="end_time" class="form-label" >End Time</label>
                    <input type="time" class="form-control" id="edit_end_time"name="end_time">
                </div>
                  <button type="submit" class="btn btn-primary">Update</button>
              </form>
            </div>
        </div>
    </div>
</div>
<!-- Edit Shedule time end -->

<!-- Add Vehicle modal start -->
<div class="modal fade" id="add_vehicle" tabindex="-1" aria-labelledby="add_vehicleLabel"aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fs-5" id="exampleModalLabel">Add Vehicle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
            </div>
            <div class="modal-body">
              <form action="{{ route('admin.vehicle.add') }}" method="POST" id="add_vehicle_form">
                  @csrf
                  <div class="mb-5">
                      <label for="name" class="form-label">Name</label>
                      <input type="text" class="form-control" id="name" name="name">
                  </div>
                  
                  <button type="submit" class="btn btn-primary">Add</button>
              </form>
            </div>
        </div>
    </div>
</div>
<!-- Add Vehicle modal end -->
  
<!-- Edit Vehicle modal start -->
<div class="modal fade" id="edit_vehicle" tabindex="-1" aria-labelledby="edit_vehicleLabel"aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fs-5" id="exampleModalLabel">Edit Vehicle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
            </div>
            <div class="modal-body">
            <form action="{{ route('admin.vehicle.edit') }}" method="POST" id="edit_vehicle_form">
                @csrf
                <div class="mb-5">
                        <input type="hidden" name="edit_vehicle_id" id="edit_vehicle_id" value="">
                    <label for="name" class="form-label" >Name</label>
                    <input type="text" class="form-control" id="edit_vehicle_name"name="name">
                </div>
                
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
            </div>
        </div>
    </div>
</div>
<!-- Edit Vehicle modal end -->

<!-- Add Holiday modal start -->
<div class="modal fade" id="add_holiday" tabindex="-1" aria-labelledby="add_holidayLabel"aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fs-5" id="exampleModalLabel">Add Holiday</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
            </div>
            <div class="modal-body">
              <form action="{{ route('admin.config-setting.holiday.add') }}" method="POST" id="add_holiday_form">
                  @csrf
                  <div class="mb-5">
                      <label for="name" class="form-label">Date</label>
                      <input type="date" class="form-control"  name="h_date">
                  </div>

                  <div class="mb-5">
                      <label for="name" class="form-label">Description</label>
                      <textarea name="description" class="form-control"></textarea>
                      
                  </div>
                  
                  <button type="submit" class="btn btn-primary">Add</button>
              </form>
            </div>
        </div>
    </div>
</div>
<!-- Add Holiday modal end -->

<!-- Edit Holiday modal start -->
<div class="modal fade" id="edit_holiday" tabindex="-1" aria-labelledby="edit_holidayLabel"aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fs-5" id="exampleModalLabel">Edit Holiday</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
            </div>
            <div class="modal-body">
            <form action="{{ route('admin.config-setting.holiday.edit') }}" method="POST" id="edit_holiday_form">
                @csrf
                <div class="mb-5">
                    <input type="hidden" name="edit_holiday_id" id="edit_holiday_id" value="">
                    <label for="name" class="form-label" >Date</label>
                    <input type="date" class="form-control" id="edit_holiday_date" name="h_date">
                </div>
                <div class="mb-5">
                    <label for="name" class="form-label" >Description</label>
                    <textarea name="description" class="form-control" id="edit_holiday_description" ></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
            </div>
        </div>
    </div>
</div>
<!-- Edit Holiday modal end -->

<!-- Add order service modal start -->
<div class="modal fade" id="orderservice" tabindex="-1" aria-labelledby="orderserviceLabel"aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fs-5" id="exampleModalLabel">Add Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="row">
                        <div class="col-12">
                            <label for="name" class="form-label">Service *</label>
                            <select name="service_id" class="add-order-service-id form-control service_id">
                                <option value="">select service</option>
                                @foreach (getCommonList('service') as $key => $value)
                                    <option value="{{$key}}">{{$value}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <button class="btn btn-primary add-order-service">Add Service</button>
            </div>
        </div>
    </div>
</div>
<!-- Add order service modal end -->