@extends('admin.layouts.app')
@section('title', 'Holiday')
@section('breadcrum')
<div class="page-header">
    <h3 class="page-title">Holiday</h3>
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Time Shedule</a></li>
        <li class="breadcrumb-item active" aria-current="page">Holiday</li>
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
                    <h4 class="card-title">Holiday Management</h4>
                    <button class="btn default-btn btn-md" data-bs-toggle="modal" data-bs-target="#add_holiday">
                        <span class="menu-icon">+ Add Holiday</span>
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped" >
                        <thead>
                            <tr>
                            <th> Date </th>
                            <th> Description </th>
                            <th> Status </th>
                            <th> Action </th>
                            </tr>
                        </thead>
                        <tbody id="holiday_list">
                            @forelse ($holidays as $holiday)
                            <tr data-id ={{$holiday->id}}>
                                <td>{{$holiday->h_date}} </td>
                                <td>{{$holiday->description}} </td>
                                <td> <div class="toggle-user dark-toggle">
                                <input type="checkbox" name="is_active" data-id="{{$holiday->id}}" class="switch" @if ($holiday->status == 1) checked @endif data-value="{{$holiday->status}}">
                                </div> </td>
                                <td> 
                                <span class="menu-icon">
                                    <a href="#"title="Edit" data-id="{{$holiday->id}}" class="text-success EditHoliday" data-bs-toggle="modal" data-bs-target="#edit_holiday"><i class="mdi mdi-pencil"></i></a>
                                </span>&nbsp;&nbsp;
                                <span class="menu-icon">
                                    <a href="#" title="Delete" class="text-danger deleteHoliday" data-id="{{$holiday->id}}"><i class="mdi mdi-delete"></i></a>
                                </span> 
                                </td>
                            </tr>
                            @empty
                                <tr>
                                <td colspan="4" class="no-record"> <div class="col-12 text-center">No record found </div></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="custom_pagination">
                {{ $holidays->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>

    //Add Holiday form validation
    $("#add_holiday_form").submit(function(e){
        e.preventDefault();
    }).validate({
        rules: {
            h_date: {
                required: true,
            },
            description:{
                required: true,
                noSpace: true,
                minlength: 3,
            },
        },
        messages: {
            h_date: {
                required: "Date is required.",
            },
            description: {
                required: "Description is required.",
                minlength: "Description must consist of at least 3 characters."
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
                        $('#add_holiday_form')[0].reset();

                        $('#add_holiday').modal('hide');
                    
                        // Construct the HTML for the new table row
                        var html = `<tr data-id=${response.data.id}>
                            <td>${response.data.h_date ?? 'N/A'}</td>
                            <td>${response.data.description ?? 'N/A'}</td>
                            <td><div class="toggle-user dark-toggle">
                                <input type="checkbox" name="is_active" data-id="${response.data.id}" class="switch" checked data-value="${response.data.status}">
                                </div>
                            </td>
                            <td>
                                <span class="menu-icon">
                                    <a href="#"title="Edit" data-id="${response.data.id}" class="text-success EditHoliday" data-bs-toggle="modal" data-bs-target="#edit_holiday"><i class="mdi mdi-pencil"></i></a>
                                </span>&nbsp;&nbsp;
                                <span class="menu-icon">
                                    <a href="#" title="Delete" class="text-danger deleteHoliday" data-id="${response.data.id}"><i class="mdi mdi-delete"></i></a>
                                </span> 
                            </td>
                        </tr>`;
                        // Append the new row to the table
                        $(".no-record").remove();
                        $("#holiday_list").prepend(html);

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

    //edit Holiday model
    $(document).on('click', '.EditHoliday', function () {
        id = $(this).data('id');
        $.ajax({
            url: '/config-setting/holiday/edit/'+id,
            method: 'GET',
            contentType: false,
            processData: false,
            success: function(response) {
                if(response.api_response == 'success'){
                    $('#edit_holiday_id').val(response.data.id);
                    $('#edit_holiday_date').val(response.data.h_date);
                    $('#edit_holiday_description').val(response.data.description);
                }else{
                    toastr.error(response.message);
                }
            },
        });
    });


    $("#edit_holiday_form").submit(function(e){
        e.preventDefault();
    }).validate({
        rules: {
            h_date: {
                required: true,
            },
            description: {
                required: true,
                noSpace: true,
                minlength: 3,
            },
        },
        messages: {
            h_date: {
                required: "Date is required.",
            },
            description: {
                required: "Description is required.",
                minlength: "Description must consist of at least 3 characters."
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
                        $('#edit_holiday_form')[0].reset();

                        $('#edit_holiday').modal('hide');
                    
                        // Construct the HTML for the new table row
                        var status = '';
                        if(response.data.status == 1){
                            status = 'checked';
                        }
                        var html = `
                            <td>${response.data.h_date ?? 'N/A'}</td>
                            <td>${response.data.description ?? 'N/A'}</td>
                            <td> 
                                <div class="toggle-user dark-toggle">
                                <input type="checkbox" name="is_active" data-id="${response.data.id}" class="switch" ${status} data-value="${response.data.status}">
                                </div> 
                            </td>
                            <td>
                                <span class="menu-icon">
                                    <a href="#"title="Edit" data-id="${response.data.id}" class="text-success EditHoliday" data-bs-toggle="modal" data-bs-target="#edit_holiday"><i class="mdi mdi-pencil"></i></a>
                                </span>&nbsp;&nbsp;
                                <span class="menu-icon">
                                    <a href="#" title="Delete" class="text-danger deleteHoliday" data-id="${response.data.id}"><i class="mdi mdi-delete"></i></a>
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
  $(document).on('click', '.deleteHoliday', function() {
    var id = $(this).attr('data-id');
      Swal.fire({
          title: "Are you sure?",
          text: "You want to delete the Holiday?",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#2ea57c",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes, delete it!"
        }).then((result) => {
          if (result.isConfirmed) {
              $.ajax({
                  url: "/config-setting/holiday/delete/" + id,
                  type: "GET", 
                  success: function(response) {
                    if (response.status == "success") {
                        toastr.success(response.message);
                        $(`tr[data-id="${id}"]`).remove();
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
        text: "Do you want to change the status of the holiday?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#2ea57c",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, mark as status"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/config-setting/holiday/changeStatus",
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
