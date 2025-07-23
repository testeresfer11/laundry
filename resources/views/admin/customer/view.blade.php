@extends('admin.layouts.app')
@section('title', 'View Customer')
@section('breadcrum')
    <div class="page-header">
        <h3 class="page-title">Customers</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#">User Management</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.customer.list') }}">Customers</a></li>
                <li class="breadcrumb-item active" aria-current="page">View Customer</li>
            </ol>
        </nav>
    </div>
@endsection
@section('content')
<div>
    <h4 class="user-title">View Customer</h4>
    <div class="card">
        <div class="card-body">
            <form class="forms-sample">
                <div class="form-group">
                    <div class="row ">
                        <div class="col-12 col-md-3">
                            <div class="view-user-details">
                                <div class="text-center">
                                    <img class="customer-image"
                                        @if (isset($user->userDetail) && !is_null($user->userDetail->profile)) src="{{ asset('storage/images/' . $user->userDetail->profile) }}"
                        @else
                            src="{{ asset('admin/images/faces/user_dummy.png') }}" @endif
                                        onerror="this.src = '{{ asset('admin/images/faces/user_dummy.png') }}'"
                                        alt="User profile picture">
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="response-data ml-4">
                                <h6 class="f-14 mb-1"><span class="semi-bold qury">User Id :</span> 
                                    <span class="text-muted" >{{ $user->uuid }}</span>
                                </h6>
                                <h6 class="f-14 mb-1"><span class="semi-bold qury">Name :</span> 
                                    <span class="text-muted" >{{ $user->full_name }}</span>
                                </h6>
                                <h6 class="f-14 mb-1"><span class="semi-bold qury">Email :</span> 
                                    <span class="text-muted" >{{ $user->email ?? '' }}</span>
                                </h6>
                                <h6 class="f-14 mb-1"><span class="semi-bold qury">Gender :</span> 
                                    <span class="text-muted" >{{ $user->userDetail ? $user->userDetail->gender ?? 'N/A' : 'N/A' }}</span>
                                </h6>
                                <h6 class="f-14 mb-1"><span class="semi-bold qury">Phone Number :</span>
                                    <span class="text-muted" >{{ $user->userDetail ? $user->userDetail->phone_number ?? 'N/A' : 'N/A' }}</span>
                                </h6>
                                <h6 class="f-14 mb-1"><span class="semi-bold qury">Country Short Code :</span> 
                                    <span class="text-muted" >{{ $user->userDetail ? ($user->userDetail->country_short_code ? strtoupper($user->userDetail->country_short_code) : 'N/A') : 'N/A' }}</span>
                                </h6>
                                <h6 class="f-14 mb-1"><span class="semi-bold qury">Date Of Birth :</span> 
                                    <span class="text-muted" >{{ $user->userDetail ? date('d/m/Y', strtotime($user->userDetail->dob)) ?? 'N/A' : 'N/A' }}</span>
                                </h6>
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="amount-box border text-center py-3">
                                <h6 class="border-bottom pb-3">Wallet Balance</h6>
                                <div class="am-btn ">
                                    <span class="badge  amount-btn">$ {{$user->wallet ?$user->wallet->amount : 0}}</span>
                                </div>
                            </div>
                            <br>
                            <div class="amount-box border text-center py-3">
                                <h6 class="border-bottom pb-3">Available Points</h6>
                                <div class="am-btn ">
                                    <span class="badge  amount-btn"> {{$totalPoints}}</span>
                                </div>
                            </div>
                        </div>
                     </div>
                </div>
            </form>
            
        </div>
    </div>
</div>

<div class=" mt-3 row align-items-center mb-4">
    <div class="col-sm-6">
        <h4>Latest Orders</h4>
    </div>
    @if($orders->count() > 10)
        <div class="col-sm-6 text-end">
            <a href="{{route('admin.order.list',['type' => 'all','search_keyword' => $user->full_name ])}}">
                <button type="button" class="btn default-btn btn-md">
                    <span class="menu-icon">view All</span>
                </button>
            </a>
        </div>
    @endif
</div>

<div class="row">
    <div class="col-sm-12 grid-margin stretch-card">
        <div class="card">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th> Order Id </th>
                            <th> Order Type </th>
                            <th> Status </th>
                            <th> Order At </th>
                            <th> Action </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr data-id="{{$order->id}}">
                                <td>{{$order->order_id}}</td>
                                <td>{{$order->order_type}}
                                <td>{{$order->status}}
                                <td>{{convertDate($order->created_at)}}</td>
                                <td> 
                                    <span class="menu-icon">
                                        <a href="{{route('admin.order.view',['id' => $order->id])}}" title="View" class="text-primary"><i class="mdi mdi-eye"></i></a>
                                    </span>&nbsp;&nbsp;&nbsp;

                                    <span class="menu-icon">
                                        <a href="{{route('admin.storeOrder.invoice.download',['id' => $order->id])}}" title="Download Invoice" class=""><i class="mdi mdi-download" style="font-size: 20px;"></i></a>
                                    </span>&nbsp;&nbsp;&nbsp;

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="no-record"> 
                                    <div class="col-12 text-center">No record found</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
