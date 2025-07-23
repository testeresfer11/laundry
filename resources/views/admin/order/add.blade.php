@extends('admin.layouts.app')
@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@23.6.1/build/css/intlTelInput.css">
<style>
  .hidden {
    display: none;
  }
</style>
@endsection
@section('title', 'Add Order')
@section('breadcrum')
<div class="page-header">
    <h3 class="page-title"> Orders</h3>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="#">Order Management</a></li>
        <li class="breadcrumb-item"><a href="{{route('admin.order.list',['type' => 'all'])}}">Online Orders</a></li>
        <li class="breadcrumb-item active" aria-current="page">Create Order</li>
      </ol>
    </nav>
</div>
@endsection
@section('content')
<div>
    <div class="variant-list hidden">
        <select name="variant_id[]" class="form-control variant-list-select">
        <option value="">Select Variant</option>
        </select>
    </div>
    <form class="forms-sample" id="create-order" action="{{route('admin.order.create')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="service_variant" value="">
        <div class="service-order-management">
            <div class="row ">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h4 class="card-title">Create Order</h4>
                                <div>
                                    <button type = "submit" class="btn btn-success btn-md ">Create</button>
                                    <a href="" class="add_address" >
                                        <h3 class="btn btn-secondary btn-md"  >
                                        <span class="menu-icon">+ Add New Address</span>
                                        </h3>
                                    </a>
                                    <h3 class="btn default-btn btn-md" data-bs-toggle="modal" data-bs-target="#orderservice">
                                        <span class="menu-icon">+ Add Service</span>
                                    </h3>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="Users">User *</label>
                                        <select name="user_id" id="user" class="form-control">
                                            <option value="">Select User</option>
                                            @foreach ($users as $user)
                                            <option value="{{$user->id}}">{{$user->full_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6">
                                    <label for="Users">Pickup Date *</label>
                                    <input type="date" class="form-control dateToInput" name="pickup_date" id="pickup_date">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="pickup_id">Pickup Address *</label>
                                        <select name="pickup_id" id="pickup_id" class="form-control">
                                            <option value="">Select Pickup Address</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label for="delivery_id">Delivery Address *</label>
                                        <select name="delivery_id" id="delivery_id" class="form-control">
                                            <option value="">Select Delivery Address</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>    
@endsection
@section('scripts')

<script>
$(document).ready(function() {
  $('.add_address').hide();
  $(document).on('change','.service-list-select',function() {
    var row = $(this).closest('tr');
    // var serviceId = row.find('.service-list-select').val();
    serviceId = $('.service-name').data('value');
    if(serviceId){
      $.ajax({
          url: `/admin/order/service/variant/${serviceId}`,
          type: 'GET',
          success: function(response) {
            row.find('.variant-list-select').html(response.data);
            row.find('.price').empty();
            row.find('.qty').empty();
            row.find('.amount').empty();
          },
          error: function() {
          }
        });
    }
  });

  $(document).on('change', '.variant-list-select'  ,function() {
      var row = $(this).closest('tr');
      var variantId = row.find('.variant-list-select').val();
      var serviceId = row.data('id');
      // var serviceId =  $('.service-name').data('value')
      if (variantId && serviceId) {
        $.ajax({
          url: `/admin/order/service/variant/price/${serviceId}/${variantId}`,
          type: 'GET',
          success: function(response) {
            row.find('.price').val(response.data);
            // row.find('.qty').empty();
            // row.find('.amount').empty();
            row.find('.qty').val(1);
            row.find('.amount').val(response.data);
          },
          error: function() {
          }
        });
      } 
  });

  service = $('.service-list.hidden').html();
  var variant = $('.variant-list.hidden').html();
  $(document).on('click','.add-service-row',function() {
    serviceId = $(this).data('id');
    tableData = $(this).closest('.table-responsive');
    if(serviceId){
      $.ajax({
          url: `/admin/order/service/variant/${serviceId}`,
          type: 'GET',
          success: function(response) {
            var newRowHtml = `
                <tr data-id = ${serviceId}>
                    <td>${response.data}</td>
                    <td><input type="number" class="form-control price" name="price[]"  min="0" readonly></td>
                    <td><input type="number" class="form-control qty"  name="qty[]" min="0"></td>
                    <td><input type="number" class="form-control amount" name="amount[]"   min="0" readonly></td>
                    <td><i class="mdi mdi-delete remove-row"></i></td>
                </tr>`;
            $('.table[data-id="' + serviceId + '"] tbody').append(newRowHtml);
          },
          error: function() {
          }
        });
    }
  });

  $(document).on('change', '.qty'  ,function() {
    var row = $(this).closest('tr');
    var price = row.find('.price').val();
    var qty = row.find('.qty').val();
    row.find('.amount').val(price * qty);
  });

  $(document).on('click', '.remove-row', function() {
      $(this).closest('tr').remove();
  });

  $(document).on('click', '.remove-service', function() {
      $(this).closest('.order-service').remove();
  });

  $(document).on('click', '.add-order-service', function() {
    var service_id = $('.add-order-service-id').val();
    var service_name = $('.add-order-service-id option:selected').text();

    if (service_id) {
      var isServiceExists = false;

      $('.order-service').each(function() {
          var element = $(this);
          var dataId = element.data('id');
          if (dataId !== undefined && dataId == service_id) {
              toastr.info('You have already selected the service');
              isServiceExists = true;
              return false; 
          }
      });

      if (!isServiceExists) {
          $('.service-order-management').append(`
              <div class="row order-service" data-id="${service_id}">
                  <div class="col-lg-12 grid-margin stretch-card">
                      <div class="card">
                          <div class="card-body">
                              <div class="d-flex justify-content-between">
                                  <h4 class="card-title">Order Services (${service_name})</h4>
                                  <div>
                                      <h3 class="btn btn-danger btn-md remove-service" data-id="${service_id}">
                                          <span class="menu-icon"> Remove</span>
                                      </h3>
                                      <h3 class="btn default-btn btn-md add-service-row" data-id="${service_id}">
                                          <span class="menu-icon">+ Add Variant</span>
                                      </h3>
                                  </div>
                              </div>
                              <div class="table-responsive">
                                  <table class="table table-striped service-table" data-id="${service_id}">
                                      <thead>
                                          <tr>
                                              <th> Variant Name </th>
                                              <th> Price (per variant) </th>
                                              <th> Quantity </th>
                                              <th> Amount </th>
                                              <th> Action </th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                      </tbody>
                                  </table>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>`);
          
          $('#orderservice').modal('hide');
          $('.add-order-service-id').val('');
      }
    }
  });

  $(document).on('change','#user',function() {
    var userId = $(this).val();
    if(userId){
      $.ajax({
          url: `/admin/customer/address/list/${userId}`,
          type: 'GET',
          success: function(response) {
            if(response.api_response = "success"){
              $('#delivery_id').empty();
              $('#pickup_id').empty();
              $('.add_address').show();
              var addressUrl = "{{ route('admin.customer.address.add', ['id' => 'USER_ID']) }}".replace('USER_ID', userId);
              $('.add_address').attr('href', addressUrl);
              html = ``;
              $.each(response.data, function(key, value) {
                html += `<option value="${key}">${value}</option>`
              });
              $('#delivery_id').html(`<option value="">Select Delivery Address</option>`+html);
              $('#pickup_id').html(`<option value="">Select Pickup Address</option>`+html);
            }else{
              $('#delivery_id').empty();
              $('#pickup_id').empty();
            }
          },
          error: function() {
          }
        });
    }
  });


  $("#create-order").submit(function(e){
    e.preventDefault();
  }).validate({
      rules: {
          user_id: {
            required: true,
          },
          pickup_id:{
            required: true,
          },
          pickup_date:{
            required: true,
          },
          delivery_id:{
            required: true,
          },
      },
      messages: {
          user_id: {
            required: "Please select the user",
          },
          pickup_id:{
            required: "Please select dpickup address",
          },
          delivery_id:{
            required: "Please select delivery address",
          },
          pickup_date:{
            required: "Please select pickup date",
          },
      },
      errorPlacement: function(error, element) {
          error.addClass('invalid-feedback');
          if (element.prop('type') === 'file') {
              error.insertAfter(element.closest('.form-control'));
          } else {
              error.insertAfter(element);
          }
      },
      submitHandler: function(form) {
          var formData = []; // Initialize an empty array to store data
          var err = false;
          // Iterate over each row in the table
          $('.service-table tr').each(function() {
              var element = $(this);
              var service_id  = element.data('id');
              var variant_id  = element.find('.variant_id').val();
              var price       = element.find('.price').val();
              var amount      = element.find('.amount').val();
              var qty         = element.find('.qty').val();

             
              if (service_id !== undefined && service_id) {
                  if (variant_id === undefined || variant_id === '') {
                      toastr.error('Please select the variant type');
                      err = true; 
                  } else if (qty === '' || qty === '0') {
                      toastr.error('Please enter the variant quantity');
                      err = true;  
                  }

                  var rowData = {
                      service_id: service_id,
                      variant_id: variant_id,
                      price: price,
                      qty: qty,
                      amount: amount,
                  };
                  formData.push(rowData);
              }
          });

          var formDataJson = JSON.stringify(formData);
          $('input[name=service_variant]').val(formDataJson);
          
          if($('input[name=service_variant]').val() == '[]'){
            toastr.error('Please add the services');
            err = true; 
          }
          if(err == false){
            form.submit();
          }
      }
  });

});

</script>
@stop