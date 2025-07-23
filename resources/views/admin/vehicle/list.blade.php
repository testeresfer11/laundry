@extends('admin.layouts.app')
@section('title', 'Vehicle')
@section('breadcrum')
<div class="page-header">
    <h3 class="page-title">Vehicle</h3>
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Vehicle Management</li>
    </ol>
    </nav>
</div>
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <h4 class="card-title">Vehicle Management</h4>
        
            <div class="admin-filters">
            <x-filter />
            </div>
            @can('vehicle-add')
                <a href="#">
                <button class="btn default-btn btn-sm" data-bs-toggle="modal" data-bs-target="#add_vehicle">
                    <span class="menu-icon">+ Add Vehicle</span>
                </button>
                </a>
            @endcan
          </div>
          <div class="table-responsive">
            <table class="table table-striped" >
              <thead>
                <tr>
                  <th> Name </th>
                  @can('vehicle-edit')
                  <th> Status </th>
                  @endcan
                  @canany(['vehicle-edit','vehicle-delete'])
                  <th> Action </th>
                  @endcanany
                </tr>
              </thead>
              <tbody id="vehicle_list">
                @forelse ($vehicles as $vehicle)
                  <tr data-id ={{$vehicle->id}}>
                    <td>{{$vehicle->name}} </td>
                    <td> <div class="toggle-user dark-toggle">
                      <input type="checkbox" name="is_active" data-id="{{$vehicle->id}}" class="switch" @if ($vehicle->status == 1) checked @endif data-value="{{$vehicle->status}}">
                    </div> </td>
                    @canany(['vehicle-edit','vehicle-delete'])
                        <td> 
                            @can('vehicle-edit')
                                <span class="menu-icon">
                                    <a href="#"title="Edit" data-id="{{$vehicle->id}}" class="text-success EditVehicle" data-bs-toggle="modal" data-bs-target="#edit_vehicle"><i class="mdi mdi-pencil"></i></a>
                                </span>&nbsp;&nbsp;
                            @endcan
                            @can('vehicle-delete')
                                <span class="menu-icon">
                                    <a href="#" title="Delete" class="text-danger deleteVehicle" data-id="{{$vehicle->id}}"><i class="mdi mdi-delete"></i></a>
                                </span>
                            @endcan 
                        </td>
                    @endcanany
                  </tr>
                @empty
                    <tr>
                      <td colspan="3" class="no-record"> <div class="col-12 text-center">No record found </div></td>
                    </tr>
                @endforelse
              </tbody>
            </table>
          </div>
            <div class="custom_pagination">
             {{ $vehicles->links('pagination::bootstrap-4') }}
            </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
<script>
    //Add Vehicle form validation
    $("#add_vehicle_form").submit(function(e){
        e.preventDefault();
    }).validate({
        rules: {
            name: {
                required: true,
                noSpace: true,
                minlength: 3,
                maxlength: 25,
            },
        },
        messages: {
            name: {
                required: "Name is required.",
                minlength: "Name must consist of at least 3 characters.",
                maxlength: "Name must not be greater than 25 characters.",
            },
            
        },
        submitHandler: function(form) {     
            var formData = new FormData(form);
            $.ajax({
                url: form.action,
                method: form.method,
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if(response.api_response == 'success'){
                        $('#add_vehicle_form')[0].reset();

                        $('#add_vehicle').modal('hide');
                    
                        // Construct the HTML for the new table row
                        var html = `<tr data-id=${response.data.id}>
                            <td>${response.data.name ?? 'N/A'}</td>
                            <td><div class="toggle-user dark-toggle">
                                <input type="checkbox" name="is_active" data-id="${response.data.id}" class="switch" checked data-value="${response.data.status}">
                                </div>
                            </td>
                            <td>
                                <span class="menu-icon">
                                    <a href="#"title="Edit" data-id="${response.data.id}" class="text-success EditVehicle" data-bs-toggle="modal" data-bs-target="#edit_vehicle"><i class="mdi mdi-pencil"></i></a>
                                </span>&nbsp;&nbsp;
                                <span class="menu-icon">
                                    <a href="#" title="Delete" class="text-danger deleteVehicle" data-id="${response.data.id}"><i class="mdi mdi-delete"></i></a>
                                </span> 
                            </td>
                        </tr>`;
                        // Append the new row to the table
                        $(".no-record").remove();
                        $("#vehicle_list").prepend(html);

                        toastr.success(response.message);
                    }else{
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    $('.error').text('');

                    var errors = xhr.responseJSON.errors;
                    if (errors) {
                        for (var key in errors) {
                            if (errors.hasOwnProperty(key)) {
                                $('#' + key + '-error').text(errors[key][0]);
                            }
                        }
                    }
                }
            });
        }
    });

    //edit vehicle model
    $(document).on('click', '.EditVehicle', function () {
       const id = $(this).data('id');
        $.ajax({
            url: `/vehicle/edit/${id}`,
            method: 'GET',
            contentType: false,
            processData: false,
            success: function(response) {
                if(response.api_response == 'success'){
                    $('#edit_vehicle_id').val(response.data.id);
                    $('#edit_vehicle_name').val(response.data.name);
                }else{
                    toastr.error(response.message);
                }
            },
        });
    });


    $("#edit_vehicle_form").submit(function(e){
        e.preventDefault();
    }).validate({
        rules: {
            name: {
                required: true,
                noSpace: true,
                minlength: 3,
            },
        },
        messages: {
            name: {
                required: "Name is required.",
                minlength: "Name must consist of at least 3 characters."
            },
        },
        submitHandler: function(form) {     
            var formData = new FormData(form);
            $.ajax({
                url: form.action,
                method: form.method,
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if(response.api_response == 'success'){
                        $('#edit_vehicle_form')[0].reset();

                        $('#edit_vehicle').modal('hide');
                    
                        // Construct the HTML for the new table row
                        var status = '';
                        if(response.data.status == 1){
                            status = 'checked';
                        }
                        var html = `
                            <td>${response.data.name ?? 'N/A'}</td>
                            <td> 
                                <div class="toggle-user dark-toggle">
                                <input type="checkbox" name="is_active" data-id="${response.data.id}" class="switch" ${status} data-value="${response.data.status}">
                                </div> 
                            </td>
                            <td>
                                <span class="menu-icon">
                                    <a href="#"title="Edit" data-id="${response.data.id}" class="text-success EditVehicle" data-bs-toggle="modal" data-bs-target="#edit_vehicle"><i class="mdi mdi-pencil"></i></a>
                                </span>&nbsp;&nbsp;
                                <span class="menu-icon">
                                    <a href="#" title="Delete" class="text-danger deleteVehicle" data-id="${response.data.id}"><i class="mdi mdi-delete"></i></a>
                                </span> 
                            </td>
                        `;

                        // Append the new row to the table
                        $(`tr[data-id="${response.data.id}"]`).empty();
                        $(`tr[data-id="${response.data.id}"]`).append(html);
                        toastr.success(response.message);
                    }else{
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    $('.error').text('');

                    var errors = xhr.responseJSON.errors;
                    if (errors) {
                        for (var key in errors) {
                            if (errors.hasOwnProperty(key)) {
                                $('#' + key + '-error').text(errors[key][0]);
                            }
                        }
                    }
                }
            });
        }
    });

    $(document).on('click', '.deleteVehicle', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: "Are you sure?",
            text: "You want to delete the Vehicle?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#2ea57c",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
            }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/vehicle/delete/${id}`,
                    type: "POST", 
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status == "success") {
                            $(`tr[data-id="${id}"]`).remove();
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }
                    }
                });
            }
        });
    });

    $(document).on('click', '.switch', function() {
        var status = $(this).data('value');
        var action = (status == 1) ? 0 : 1;
        var id = $(this).data('id');

        Swal.fire({
            title: "Are you sure?",
            text: "Do you want to change the status of the vehicle?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#2ea57c",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, mark as status"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/vehicle/changeStatus",
                    type: "GET",
                    data: { id: id, status: action },
                    success: function(response) {
                        if (response.status == "success") {
                            toastr.success(response.message);
                            $('.switch[data-id="' + id + '"]').data('value', !status);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(error) {
                        console.log('error', error);
                    }
                });
            } else {
            var switchToToggle = $('.switch[data-id="' + id + '"]');
            switchToToggle.prop('checked', !switchToToggle.prop('checked'));
            }
        });
    });

</script>

@stop
