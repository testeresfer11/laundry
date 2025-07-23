@extends('admin.layouts.app')
@section('title', 'Notification')
@section('breadcrum')
<div class="page-header">
    <h3 class="page-title">Notification</h3>
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Notifications</li>
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
                    <h4 class="card-title">Notifications</h4>
                    @if($notifications->count())
                    @can('notification-delete')
                        <button type="button" class="btn btn-danger btn-md ClearAllNotification">
                        <span class="menu-icon"> Clear All</span>
                        </button>
                    @endcan
                    @endif
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th> Notification </th>
                                <th> Created At </th>
                                @can('notification-delete')
                                    <th> Action </th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($notifications as $notification)
                                <tr data-id="{{$notification->id}}">
                                    <td><a href="{{($notification->data)['route']}}" target="_blank"> {{($notification->data)['description'] }} </a></td>
                                    <td>{{ convertDate($notification->created_at) }}</td>
                                    @can('notification-delete')
                                    <td>
                                        <a href="javascript:void(0)" class="deleteNotification text-danger " data-id="{{$notification->id}}"> <i class="mdi mdi-delete"></i> </a>
                                    </td>
                                    @endcan
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
                    {{ $notifications->appends(request()->query())->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
  $(document).on('click', '.deleteNotification', function () {
    var id = $(this).data('id');
    Swal.fire({
        title: "Are you sure?",
        text: "You want to delete the Notification?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#2ea57c",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/admin/notification/delete/${id}`,
                type: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
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

  $('.ClearAllNotification').on('click', function() {
      Swal.fire({
          title: "Are you sure?",
          text: "You want to clear all Notifications?",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#2ea57c",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes, delete it!"
        }).then((result) => {
          if (result.isConfirmed) {
              $.ajax({
                  url: "/admin/notification/delete/clear",
                  type: "GET", 
                  success: function(response) {
                        if (response.api_response == "success") {
                            setTimeout(function(){
                            location.reload();
                          }, 1000);
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }
                    }
              });
          }
      });
  });
</script>

@stop
