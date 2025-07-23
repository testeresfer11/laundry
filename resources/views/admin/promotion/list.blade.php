@extends('admin.layouts.app')
@section('title', 'Promotions')
@section('breadcrum')
<div class="page-header">
    <h3 class="page-title">Promotion</h3>
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Promotion Management</li>
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
            <h4 class="card-title">Promotion Management</h4>
            
              <div class="admin-filters">
                <x-filter />
              </div>
              @can('promotion-add')
                <a href="{{route('admin.promotion.add')}}">
                  <button type="button" class="btn default-btn btn-md">
                    <span class="menu-icon">+ Add Promotion</span>
                  </button>
                </a>
              @endcan
          </div>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th> Image </th>
                  <th> Title </th>
                  <th> Discount </th>
                  @can('promotion-edit')
                    <th> Status </th>
                  @endcan
                  @canany(['promotion-view','promotion-edit','promotion-delete'])
                    <th> Action </th>
                  @endcanany
                </tr>
              </thead>
              <tbody>
                @forelse ($promotions as $promotion)
                  <tr data-id="{{$promotion->id}}">
                    <td> <img src="{{file_exists(public_path('storage/images/'.$promotion->image)) ? asset('storage/images/'.$promotion->image) : asset('admin/images/faces/face15.jpg')}}" alt="" height="300px"></td>
                    <td> {{$promotion->title}} </td>
                    <td> {{$promotion->discount}}% </td>
                    @can('promotion-edit')
                      <td> 
                        <div class="toggle-user dark-toggle">
                          <input type="checkbox" name="is_active" data-id="{{$promotion->id}}" class="switch" @if ($promotion->status == 1) checked @endif data-value="{{$promotion->status}}">
                        </div> 
                      </td>
                    @endcan
                    @canany(['promotion-view','promotion-edit','promotion-delete'])
                    <td> 
                      @can('promotion-view')
                        <span class="menu-icon">
                          <a href="{{route('admin.promotion.view',['id' => $promotion->id])}}" title="View" class="text-primary"><i class="mdi mdi-eye"></i></a>
                        </span>&nbsp;&nbsp;&nbsp;
                      @endcan
                      @can('promotion-edit')
                      <span class="menu-icon">
                        <a href="{{route('admin.promotion.edit',['id' => $promotion->id])}}" title="Edit" class="text-success"><i class="mdi mdi-pencil"></i></a>
                      </span>&nbsp;&nbsp;
                      @endcan
                      @can('promotion-delete')
                      <span class="menu-icon">
                        <a href="#" title="Delete" class="text-danger deletePromotion" data-id="{{$promotion->id}}"><i class="mdi mdi-delete"></i></a>
                      </span> 
                      @endcan
                    </td>
                    @endcanany
                  </tr>
                @empty
                    <tr>
                      <td colspan="5" class="no-record"> <div class="col-12 text-center">No record found </div></td>
                    </tr>
                @endforelse
              </tbody>
            </table>
          </div>
            <div class="custom_pagination">
             {{ $promotions->links('pagination::bootstrap-4') }}
            </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
<script>
  $('.deletePromotion').on('click', function() {
    var id = $(this).data('id');
      Swal.fire({
          title: "Are you sure?",
          text: "You want to delete the promotion?",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#2ea57c",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes, delete it!"
        }).then((result) => {
          if (result.isConfirmed) {
              $.ajax({
                  url: `/admin/promotion/delete/${id}`,
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
        text: "Do you want to change the status of the promotion?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#2ea57c",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, mark as status"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/promotion/changeStatus",
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
