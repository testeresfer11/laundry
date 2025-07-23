@extends('admin.layouts.app')
@section('title', 'Time Shedule')
@section('breadcrum')
<div class="page-header">
    <h3 class="page-title">Time Shedule</h3>
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0)">Settings</a></li>
        <li class="breadcrumb-item active" aria-current="page">Time Shedule</li>
    </ol>
    </nav>
</div>
@endsection
@section('content')
<!-- Manage the time slot modal -->
<div class="modal fade" id="Timeslots" tabindex="-1" aria-labelledby="TimeslotsLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fs-5" id="exampleModalLabel">Manage Time Slots</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="row">
                        <div class="col-12">
                            <label for="timeSlots" class="form-label">Time Period (in mins) *</label>
                            <input  type="number" id="timeSlots" name="time_slot" class="form-control" placeholder="Enter time in minutes" min="1" step="1" required aria-label="Time period in minutes" max="100" value="{{ConfigDetail('time-slot','TIME_SLOT')}}">
                        </div>
                    </div>
                </div>
                <div class="text-end mt-3">
                    <button class="btn btn-primary update-time-slots">Update</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Add order service modal end -->
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h4 class="card-title">Time Shedule Management</h4>
                    <div>
                        <h3 class="btn default-btn btn-md" data-bs-toggle="modal" data-bs-target="#Timeslots">
                            <span class="menu-icon">Manage Time Slots</span>
                        </h3>
                        <h3 class="btn default-btn btn-md">
                            <a href="{{route('admin.config-setting.holiday.list')}}">
                            <span class="menu-icon">Holiday Management</span>
                            </a>
                        </h3>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped" >
                        <thead>
                            <tr>
                            <th> Day </th>
                            <th> Start Time </th>
                            <th> End Time </th>
                            <th> Status </th>
                            <th> Action </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $value)
                                <tr data-id="{{$value->id}}">
                                    <td>{{$value->day}} </td>
                                    <td>{{$value->start_time ?? 'N/A'}} </td>
                                    <td>{{$value->end_time ?? 'N/A'}} </td>
                                    <td> <div class="toggle-user dark-toggle">
                                    <input type="checkbox" name="is_active" data-id="{{$value->id}}" class="switch" @if ($value->status == 1) checked @endif data-value="{{$value->status}}">
                                    </div> </td>
                                    <td> 
                                    <span class="menu-icon">
                                        <a href="#"title="Edit" data-id="{{$value->id}}" class="text-success EditSheduleTime" data-bs-toggle="modal" data-bs-target="#edit_shedule_time"><i class="mdi mdi-pencil"></i></a>
                                    </span>&nbsp;&nbsp;
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                <td colspan="5" class="no-record"> <div class="col-12 text-center">No record found </div></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script>
      //edit shedule time model
      $(document).on('click', '.EditSheduleTime', function () {
          id = $(this).data('id');
          $.ajax({
              url: `/admin/config-setting/edit/${id}`,
              method: 'GET',
              contentType: false,
              processData: false,
              success: function(response) {
                  if(response.api_response == 'success'){
                    $("#SheduleLabel").text(`Edit ${response.data.day} Shedule`);
                    $('#edit_shedule_id').val(response.data.id);
                    $('#edit_start_time').val(response.data.start_time);
                    $('#edit_end_time').val(response.data.end_time);
                  }else{
                      toastr.error(response.message);
                  }
              },
          });
      });

      $("#edit_shedule_time_form").submit(function(e){
          e.preventDefault();
      }).validate({
          rules: {
              start_time: {
                  required: true,
              },
              end_time: {
                  required: true,
              },
          },
          messages: {
              start_time: {
                  required: "Start time is required.",
              },
              end_time: {
                  required: "End time is required.",
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
                          $('#edit_shedule_time_form')[0].reset();

                          $('#edit_shedule_time').modal('hide');
                        
                          // Construct the HTML for the new table row
                          var status = '';
                          if(response.data.status == 1){
                              status = 'checked';
                          }
                          var html = `
                              <td>${response.data.day ?? 'N/A'}</td>
                              <td>${response.data.start_time ?? 'N/A'}</td>
                              <td>${response.data.end_time ?? 'N/A'}</td>
                              <td> 
                                  <div class="toggle-user dark-toggle">
                                  <input type="checkbox" name="is_active" data-id="${response.data.id}" class="switch" ${status} data-value="${response.data.status}">
                                  </div> 
                              </td>
                              <td>
                                  <span class="menu-icon">
                                      <a href="#"title="Edit" data-id="${response.data.id}" class="text-success EditSheduleTime" data-bs-toggle="modal" data-bs-target="#edit_shedule_time"><i class="mdi mdi-pencil"></i></a>
                                  </span>&nbsp;&nbsp;
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

      $('.switch').on('click', function() {
        var status = $(this).data('value');
        var action = (status == 1) ? 0 : 1;
        var id = $(this).data('id');

        Swal.fire({
            title: "Are you sure?",
            text: "Do you want to change the status?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#2ea57c",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, mark as status"
        }).then((result) => {
            if (result.isConfirmed) {
              $('.preloader').show();
                $.ajax({
                    url: "/config-setting/changeStatus",
                    type: "GET",
                    data: { id: id, status: action },
                    success: function(response) {
                        if (response.status == "success") {
                          toastr.success(response.message);
                            
                          $('.switch[data-id="' + id + '"]').data('value',!status);
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


      $('.update-time-slots').on('click', function() {
        var slot = $("input[name='time_slot']").val();
        if(slot == 0 || slot == null){
          toastr.error('Time slot period is required');
          return true;
        }
        $('.preloader').show();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: `/admin/config-setting/timeSlot`,
                type: "POST",
                data: { time_slot: slot },
                success: function(response) {
                    if (response.status == "success") {
                        $('#Timeslots').modal('hide');
                    toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(error) {
                    console.log('error', error);
                }
            });
      });
    </script>
@stop
