@extends('admin.layouts.app')
@section('title', 'Points')
@section('breadcrum')
<div class="page-header">
    <h3 class="page-title">Points</h3>
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Points Management</li>
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
                    <h4 class="card-title">Point Management</h4>
                
                    <div class="admin-filters">
                        <x-filter />
                    </div>
                    @can('point-list')
                        <a href="{{route('admin.points.add')}}">
                            <button type="button" class="btn default-btn btn-md">
                            <span class="menu-icon">+ Add Offer</span>
                        </button>
                        </a>
                    @endcan
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th> Offer Name </th>
                                <th> Offer Type </th>
                                <th> Points </th>
                                <th> Maximum Order</th>
                                @can('point-edit')
                                    <th> Status </th>
                                @endcan
                                
                                <th> Action </th>
                            </tr>
                        </thead>
                        <tbody>
                                @forelse ($points as $point)
                                    <tr data-id="{{$point->id}}">
                                        <td> {{$point->offer_name}} </td>
                                        <td> {{$point->offer_type}} </td>
                                        <td> {{$point->points}} </td>
                                        <td> {{$point->max_order_amount ?? 'N/A'}} </td>
                                        @can('point-edit')
                                        <td> 
                                            <div class="toggle-user dark-toggle">
                                            <input type="checkbox" name="is_active" data-id="{{$point->id}}" class="switch" @if ($point->status == 1) checked @endif data-value="{{$point->status}}">
                                            </div> 
                                        </td>
                                        @endcan
                                        @canany(['point-edit','point-edit','point-delete'])
                                        <td> 
                                            @can('point-view')
                                            <span class="menu-icon">
                                                <a href="{{route('admin.points.view',['id' => $point->id])}}" title="View" class="text-primary"><i class="mdi mdi-eye"></i></a>
                                            </span>&nbsp;&nbsp;&nbsp;
                                            @endcan
                                            @can('point-edit')
                                            <span class="menu-icon">
                                                <a href="{{route('admin.points.edit',['id' => $point->id])}}" title="Edit" class="text-success"><i class="mdi mdi-pencil"></i></a>
                                            </span>&nbsp;&nbsp;
                                            @endcan
                                            @can('point-delete')
                                            <span class="menu-icon">
                                                <a href="#" title="Delete" class="text-danger deletePoint" data-id="{{$point->id}}"><i class="mdi mdi-delete"></i></a>
                                            </span> 
                                            @endcan
                                        </td>
                                        @endcanany
                                    </tr>
                                @empty
                                    <tr>
                                    <td colspan="6" class="no-record"> <div class="col-12 text-center">No record found </div></td>
                                    </tr>
                                @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="custom_pagination">
                    {{ $points->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
  </div>
@endsection
@section('scripts')
<script>
  $(document).on('click', '.deletePoint', function () {
    var id = $(this).data('id');
      Swal.fire({
          title: "Are you sure?",
          text: "You want to delete the point?",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#2ea57c",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes, delete it!"
        }).then((result) => {
          if (result.isConfirmed) {
              $.ajax({
                  url: `/admin/points/delete/${id}`,
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

  $('.switch').on('click', function() {
    var status = $(this).data('value');
    var action = (status == 1) ? 0 : 1;
    var id = $(this).data('id');

    Swal.fire({
        title: "Are you sure?",
        text: "Do you want to change the status of the point?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#2ea57c",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, mark as status"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/points/changeStatus",
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
