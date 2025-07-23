@extends('admin.layouts.app')
@section('title', 'Service')
@section('breadcrum')
<div class="page-header">
    <h3 class="page-title">Services</h3>
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="#">Product Management</a></li>
        <li class="breadcrumb-item active" aria-current="page">Services</li>
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
            <h4 class="card-title">Service Management</h4>
            
              <div class="admin-filters">
                <x-filter />
              </div>
              @can('service-add')
                <a href="{{route('admin.service.add')}}">
                  <button type="button" class="btn default-btn btn-md">
                    <span class="menu-icon">+ Add Service</span>
                  </button>
                </a>
              @endcan
          </div>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th> Name </th>
                  <th> Image </th>
                  @can('service-edit')
                    <th> Status </th>
                  @endcan
                  @canany(['service-edit','service-variant-add','service-delete'])
                    <th> Action </th>
                  @endcanany
                </tr>
              </thead>
              <tbody>
                @forelse ($services as $service)
                  <tr data-id="{{$service->id}}">
                    <td> {{$service->name}} </td>
                    <td> 
                      <img src="{{file_exists(public_path('storage/images/'.$service->image)) ? asset('storage/images/'.$service->image) : asset('admin/images/faces/face15.jpg')}}" alt="" height="300px">
                    </td>
                    @can('service-edit')
                    <td> 
                      <div class="toggle-user dark-toggle">
                        <input type="checkbox" name="is_active" data-id="{{$service->id}}" class="switch" @if ($service->status == 1) checked @endif data-value="{{$service->status}}">
                      </div> 
                    </td>
                    @endcan
                    @canany(['service-edit','service-variant-add','service-delete'])
                      <td> 
                        @can('service-variant-add')
                          <span class="menu-icon">
                            <a href="{{route('admin.service.variant',['id' => $service->id])}}" title="Add Variant" class="text-info"><i class="mdi mdi-plus-circle-multiple-outline"></i></a>
                          </span>&nbsp;&nbsp;&nbsp;
                        @endcan
                        @can('service-edit')
                          <span class="menu-icon">
                            <a href="{{route('admin.service.edit',['id' => $service->id])}}" title="Edit" class="text-success"><i class="mdi mdi-pencil"></i></a>
                          </span>&nbsp;&nbsp;
                        @endcan
                        @can('service-delete')
                          <span class="menu-icon">
                            <a href="#" title="Delete" class="text-danger deleteService" data-id="{{$service->id}}"><i class="mdi mdi-delete"></i></a>
                          </span> 
                        @endcan
                      </td>
                      @endcanany
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
             {{ $services->links('pagination::bootstrap-4') }}
            </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
<script>
  $(document).on('click', '.deleteService', function () {
    var id = $(this).data('id');
      Swal.fire({
          title: "Are you sure?",
          text: "You want to delete the Service?",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#2ea57c",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes, delete it!"
        }).then((result) => {
          if (result.isConfirmed) {
              $.ajax({
                  url: `/admin/service/delete/${id}`,
                  type: "POST", 
                  data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                  },
                  success: function(response) {
                    if (response.api_response === "success") {
                      $(`tr[data-id="${id}"]`).remove();
                        toastr.success(response.message);
                      } else {
                        toastr.error(response.message);
                      }
                  },
                  error: function (xhr) {
                    toastr.error(xhr.responseJSON?.message || "An error occurred.");
                }
              });
          }
      });
  });

  $('.switch').on('click', function() {
    var status = $(this).data('value');
    var action = (status == 1) ? 0 : 1;
    var id = $(this).data('id');

    Swal.fire({
        title: "Are you sure?",
        text: "Do you want to change the status of the service?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#2ea57c",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, mark as status"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/service/changeStatus",
                type: "GET",
                data: { id: id, status: action },
                success: function(response) {
                    if (response.status == "success") {
                      $('.switch[data-id="' + id + '"]').data('value',!action);
                        toastr.success(response.message);
                    } else {
                      $('.switch[data-id="' + id + '"]').data('value',action);
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
