@extends('admin.layouts.app')
@section('title', 'customer address')
@section('breadcrum')
<div class="page-header">
    <h3 class="page-title">Customer Address</h3>
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="#">User Management</a></li>
        <li class="breadcrumb-item"><a href="{{route('admin.customer.list')}}">Customers</a></li>
        <li class="breadcrumb-item active" aria-current="page">Addresses</li>
    </ol>
    </nav>
</div>
@endsection
@section('content')

{{--  New Customer Address design--}}
<div>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title">{{$user->full_name}} Address Management</h4>
                    
                        <a href="{{route('admin.customer.address.add',['id' => $user->id])}}">
                            <button type="button" class="btn default-btn btn-md">
                                <span class="menu-icon">+ Add Address</span>
                            </button>
                        </a>
                    </div>
                    {{-- <div class="admin-filters">
                    <x-filter />
                    </div> --}}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
      @forelse ($addresses as $key => $data)
        <div class="col-xl-4 col-lg-3 col-sm-6 grid-margin stretch-card mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="equipment-card">
                                @if($data->default == 1)
                                    <div class="badge"></div>
                                @endif
                                <div class="equipment-card-cont">
                                    <h3 class="mb-3">{{$data->type}}</h3>
                                    <div class="row">
                                        <div class="col-12 col-md-12 mb-2">  
                                            <p class="mb-0 f-13">
                                                <span class="mr-4 d-block">House No</span> 
                                                <span class="text-muted  d-block">{{$data->house_no ?? 'N/A'}}</span> 
                                            </p>
                                        </div>
                                        <div class="col-12 col-md-12 mb-2">  
                                            <p class="mb-0 f-13">
                                                <span class="mr-4 d-block">Landmark</span> 
                                                <span class="text-muted  d-block">{{$data->landmark ?? 'N/A'}}</span> 
                                            </p>
                                        </div>
                                        <div class="col-12 col-md-12 mb-2">  
                                            <p class="mb-0 f-13">
                                                <span class="mr-4 d-block">Address</span> 
                                                <span class="text-muted  d-block">{{$data->address ?? ''}}</span> 
                                            </p>
                                        </div>
                                        <div class="col-12 col-md-12 mb-2">  
                                            <p class="mb-0 f-13"><span class="mr-4">Status</span> 
                                                <span class="text-muted">
                                                    <div class="toggle-user dark-toggle justify-content-between">
                                                        <input type="checkbox" name="is_active" data-id="{{$data->id}}" class="switch" @if ($data->status == 1) checked @endif data-value="{{$data->status}}">
                                                        <span class="menu-icon">
                                                            <a href="{{route('admin.customer.address.edit',['id' => $data->id])}}" title="Edit" class="text-success"><i class="mdi mdi-pencil"></i></a>
                                                        </span>&nbsp;&nbsp;
                                                        <span class="menu-icon">
                                                            <a href="#" title="Delete" class="text-danger deleteAddress" data-id="{{$data->id}}"><i class="mdi mdi-delete"></i></a>
                                                        </span> 
                                                    </div>
                                        </div> 
                                    </div>
                                </div>   
                            </div>
                        </div>
                    </div>            
                </div>
            </div>
        </div>
      @empty
      <div class="col-12 text-center"><img class="mt-4" src="{{asset('admin/images/faces/no-record.png')}}" height="300px"></div>
      @endforelse
    </div>
    <div class="custom_pagination">
      {{ $addresses->appends(request()->query())->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
@section('scripts')
<script>
    $('.deleteAddress').on('click', function() {
      var id = $(this).attr('data-id');
        Swal.fire({
            title: "Are you sure?",
            text: "You want to delete the Address?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#2ea57c",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
          }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/customer/address/delete/" + id,
                    type: "GET", 
                    success: function(response) {
                      if (response.status == "success") {
                        toastr.success(response.message);
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
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
        text: "Do you want to change the status of the Address?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#2ea57c",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, mark as status"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ route('admin.customer.address.changeStatus')}}",
                type: "GET",
                data: { id: id, status: action },
                success: function(response) {
                    if (response.status == "success") {
                        toastr.success(response.message);
                        $('.switch[data-id="' + id + '"]').data('value',!action);
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
