@extends('admin.layouts.app')
@section('title', 'Tax')
@section('breadcrum')
<div class="page-header">
    <h3 class="page-title">Taxes</h3>
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tax Management</li>
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
            <h4 class="card-title">Tax Management</h4>
            
              <div class="admin-filters">
                <x-filter />
              </div>
              @can('tax-add')
                <a href="{{route('admin.tax.add')}}">
                    <button type="button" class="btn default-btn btn-md">
                    <span class="menu-icon">+ Add Tax</span>
                  </button>
                </a>
              @endcan
          </div>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th> Label </th>
                  <th> Percentage </th>
                  @can('tax-edit')
                    <th> Status </th>
                  @endcan
                  @canany(['tax-edit','tax-delete'])
                    <th> Action </th>
                  @endcanany
                </tr>
              </thead>
              <tbody>
                @forelse ($taxes as $tax)
                  <tr data-id="{{$tax->id}}">
                    <td> {{$tax->label}} </td>
                    <td> {{$tax->percentage}}% </td>
                    @can('tax-edit')
                      <td> 
                        <div class="toggle-user dark-toggle">
                          <input type="checkbox" name="is_active" data-id="{{$tax->id}}" class="switch" @if ($tax->status == 1) checked @endif data-value="{{$tax->status}}">
                        </div> 
                      </td>
                    @endcan
                    @canany(['tax-edit','tax-delete'])
                      <td> 
                        @can('tax-edit')
                          <span class="menu-icon">
                            <a href="{{route('admin.tax.edit',['id' => $tax->id])}}" title="Edit" class="text-success"><i class="mdi mdi-pencil"></i></a>
                          </span>&nbsp;&nbsp;
                        @endcan
                        @can('tax-delete')
                          <span class="menu-icon">
                            <a href="#" title="Delete" class="text-danger deleteTax" data-id="{{$tax->id}}"><i class="mdi mdi-delete"></i></a>
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
             {{ $taxes->links('pagination::bootstrap-4') }}
            </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
<script>
  $('.deleteTax').on('click', function() {
    var id = $(this).attr('data-id');
      Swal.fire({
          title: "Are you sure?",
          text: "You want to delete the Tax?",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#2ea57c",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes, delete it!"
        }).then((result) => {
          if (result.isConfirmed) {
              $.ajax({
                  url: "/tax/delete/" + id,
                  type: "GET", 
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
        text: "Do you want to change the status of the Tax?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#2ea57c",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, mark as status"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/tax/changeStatus",
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
