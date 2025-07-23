 <!-- Order reject modal start -->
 <div class="modal fade" id="orderReject" tabindex="-1" aria-labelledby="orderRejectLabel"aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fs-5 order-title" id="exampleModalLabel">Order Cancel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
            </div>
            <div class="modal-body">
            <form action="{{ route('admin.order.reject') }}" method="POST" id="order_reject_form" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="order_id" value="{{isset($order) ? $order->id : ''}}" id="order_id">
                <input type="hidden" name="order_status" id="order_status" value="">
                <div class="" id="reason_input">
                    <label for="name" class="form-label">Reason</label>
                    <textarea name="reason" id="reject_reason" cols="30" rows="5" class="form-control"></textarea>
                </div>  
                <div class="" id="image_input">
                    <label for="image" class="form-label">Image</label>
                    <input type="file" name="image" class="form-control" onchange="validateImageExtension()" id="reject_image">
                    <span id="error_message" style="color: red; display: none;"> Supported images type - jpeg, jpg, png, gif.</span>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancel</button>
            </form>
            </div>
        </div>
    </div>
</div>
<!--  Order reject modal end -->


 <!-- Assign Driver modal start -->
 <div class="modal fade" id="assign_driver" tabindex="-1" aria-labelledby="assign_driverLabel"aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fs-5" id="exampleModalLabel">Assign Driver</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
            </div>
            <div class="modal-body">
            <form action="{{ route('admin.order.assignDriver') }}" method="POST" id="assign_driver_form">
                @csrf
                <input type="hidden" name="order_id" value="{{isset($order) ? $order->id : ''}}">
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="name" class="form-label">Driver *</label>
                        <select name="driver_id" class="add-order-service-id form-control service_id">
                            <option value="">select Driver</option>
                            @foreach (getCommonList('driver') as $value)
                                <option value="{{$value->id}}">{{$value->full_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
            </div>
        </div>
    </div>
</div>
<!--  Assign Driver modal end -->

<script>
    function validateImageExtension() {
        const fileInput = document.getElementById('reject_image');
        const errorMessage = document.getElementById('error_message');
        const file = fileInput.files[0];
        
        // Allowed extensions
        const allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
        
        if (file && !allowedExtensions.exec(file.name)) {
            errorMessage.style.display = 'block'; // Show error message
        } else {
            errorMessage.style.display = 'none'; // Hide error message if valid
        }
    }
</script>