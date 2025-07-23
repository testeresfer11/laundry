@extends('admin.layouts.app')
@section('title', 'Customers')
@section('breadcrum')
<div class="page-header">
    <h3 class="page-title">Customers</h3>
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="#">User Management</a></li>
        <li class="breadcrumb-item active" aria-current="page">Customers</li>
    </ol>
    </nav>
</div>
@endsection
@section('content')
<div class="row ">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h4 class="card-title">Customer Management</h4>
                    
                    <div class="admin-filters">
                        <x-filter />
                    </div>
                    @can('customer-add')
                        <a href="{{route('admin.customer.add')}}">
                        <button type="button" class="btn default-btn btn-md">
                            <span class="menu-icon">+ Add Customer</span>
                        </button>
                        </a>
                    @endcan
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                            <th> Profile </th>
                            <th> Customer Id </th>
                            <th> Name </th>
                            <th> Email </th>
                            @can('customer-edit')
                            <th> Status </th>
                            @endcan
                            @canany(['customerAddress-list','customer-view','customer-edit','customer-delete'])
                            <th> Action </th>
                            @endcanany
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr data-id="{{$user->id}}">
                                    <td class="py-1">
                                    <img src="{{userImageById($user->id)}}"
                                    alt="User profile picture">
                                    </td>
                                    <td>{{$user->uuid}}</td>
                                    <td> {{$user->full_name}} </td>
                                    <td>{{$user->email}}</td>
                                    @can('customer-edit')
                                    <td> 
                                        <div class="toggle-user dark-toggle">
                                        <input type="checkbox" name="is_active" data-id="{{$user->id}}" class="switch" @if ($user->status == 1) checked @endif data-value="{{$user->status}}">
                                        </div> 
                                    </td>
                                    @endcan
                                    @canany(['customerAddress-list','customer-view','customer-edit','customer-delete'])
                                    <td> 
                                        @can('customerAddress-list')
                                        <span class="menu-icon">
                                            <a href="{{route('admin.customer.address.list',['id' => $user->id])}}" title="Address" class="text-primary"><i class="mdi mdi-folder-plus"></i></a>
                                        </span>&nbsp;&nbsp;&nbsp;
                                        @endcan
                                        @can('customer-view')
                                        <span class="menu-icon">
                                            <a href="{{route('admin.customer.view',['id' => $user->id])}}" title="View" class="text-primary"><i class="mdi mdi-eye"></i></a>
                                        </span>&nbsp;&nbsp;&nbsp;
                                        @endcan
                                        @can('customer-edit')
                                        <span class="menu-icon">
                                            <a href="{{route('admin.customer.edit',['id' => $user->id])}}" title="Edit" class="text-success"><i class="mdi mdi-pencil"></i></a>
                                        </span>&nbsp;&nbsp;
                                        @endcan
                                        @can('customer-delete')
                                        <span class="menu-icon">
                                            <a href="#" title="Delete" class="text-danger deleteCustomer" data-id="{{$user->id}}"><i class="mdi mdi-delete"></i></a>
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
                    {{ $users->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
  </div>
@endsection
@section('scripts')
<script>
  // $('.deleteCustomer').on('click', function() {
    $(document).on('click', '.deleteCustomer', function() {
    var id = $(this).attr('data-id');
      Swal.fire({
          title: "Are you sure?",
          text: "You want to delete the Customer?",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#2ea57c",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes, delete it!"
        }).then((result) => {
          if (result.isConfirmed) {
              $.ajax({
                  url: "/customer/delete/" + id,
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
        text: "Do you want to change the status of the customer?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#2ea57c",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, mark as status"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/customer/changeStatus",
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
