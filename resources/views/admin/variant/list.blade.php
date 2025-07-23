@extends('admin.layouts.app')
@section('title', 'Variant')
@section('breadcrum')
<div class="page-header">
    <h3 class="page-title">Variants</h3>
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="#">Product Management</a></li>
        <li class="breadcrumb-item active" aria-current="page">Variants</li>
    </ol>
    </nav>
</div>
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <h4 class="card-title">Variant Management</h4>

            <div class="admin-filters">
              <x-filter />
            </div>
            @can('variant-add')
              <button class="btn default-btn btn-md mb-3" data-bs-toggle="modal" data-bs-target="#add_variant">
                <span class="menu-icon">+ Add Variant</span>
              </button>
            @endcan
          </div>
          <div class="table-responsive">
            <table class="table table-striped" >
              <thead>
                <tr>
                  <th> Image </th>
                  <th> Name </th>
                  <th> Gender </th>
                  @can('variant-edit')
                    <th> Status </th>
                  @endcan
                  @canany(['variant-edit','variant-delete'])
                    <th> Action </th>
                  @endcanany
                </tr>
              </thead>
              <tbody id="variant_list">
                @forelse ($variants as $variant)
                  <tr data-id ={{$variant->id}}>
                    <td> 
                      <img src="{{file_exists(public_path('storage/images/'.$variant->image)) ? asset('storage/images/'.$variant->image) : asset('admin/images/faces/face15.jpg')}}" alt="" height="300px">
                    </td>
                    <td>{{$variant->name}} </td>
                    <td>{{$variant->gender ?? 'N/A'}} </td>
                    @can('variant-edit')
                      <td> 
                        <div class="toggle-user dark-toggle">
                          <input type="checkbox" name="is_active" data-id="{{$variant->id}}" class="switch" @if ($variant->status == 1) checked @endif data-value="{{$variant->status}}">
                        </div> 
                      </td>
                    @endcan
                    @canany(['variant-edit','variant-delete'])
                      <td> 
                        @can('variant-edit')
                          <span class="menu-icon">
                            <a href="#"title="Edit" data-id="{{$variant->id}}" class="text-success EditVariant" data-bs-toggle="modal" data-bs-target="#edit_variant"><i class="mdi mdi-pencil"></i></a>
                          </span>&nbsp;&nbsp;
                        @endcan
                        @can('variant-edit')
                          <span class="menu-icon">
                            <a href="#" title="Delete" class="text-danger deleteVariant" data-id="{{$variant->id}}"><i class="mdi mdi-delete"></i></a>
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
             {{ $variants->links('pagination::bootstrap-4') }}
            </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
<script>
  $(document).on('click', '.deleteVariant', function() {
    var id = $(this).data('id');
      Swal.fire({
          title: "Are you sure?",
          text: "You want to delete the Variant?",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#2ea57c",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes, delete it!"
        }).then((result) => {
          if (result.isConfirmed) {
              $.ajax({
                  url: "/admin/variant/delete/" + id,
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

  $(document).on('click', '.switch', function() {
    var status = $(this).data('value');
    var action = (status == 1) ? 0 : 1;
    var id = $(this).data('id');

    Swal.fire({
        title: "Are you sure?",
        text: "Do you want to change the status of the variant?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#2ea57c",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, mark as status"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/variant/changeStatus",
                type: "GET",
                data: { id: id, status: action },
                success: function(response) {
                    if (response.status == "success") {
                        toastr.success(response.message);
                        $('.switch[data-id="' + id + '"]').data('value', !status);
                        // setTimeout(function() {
                        //     location.reload();
                        // }, 2000);
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
