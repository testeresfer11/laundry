@extends('admin.layouts.app')
@section('title', 'Orders')
@section('breadcrum')
<div class="page-header">
    <h3 class="page-title">Order Management</h3>
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="#">Order Management</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ucfirst(request()->type)}} Orders</li>
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
                    <h4 class="card-title">{{ucfirst(request()->type)}} Orders</h4>
                
                    <div class="admin-filters">   
                        <form id="filter">
                            <div class="row align-items-center justify-content-end mb-3">
                                <div class="col-6 d-flex gap-2">
                                    <input type="text" class="form-control"  placeholder="Search keyword" name="search_keyword" value="{{request()->filled('search_keyword') ? request()->search_keyword : ''}}">            
                                </div>
                            
                                <div class="col-6">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                    @if(request()->filled('search_keyword') || request()->filled('status') || request()->filled('category_id'))
                                        <button class="btn btn-danger" id="clear_filter">Clear Filter</button>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                    @if(request()->type == 'all')
                    @can('order-add')
                        <a href="{{route('admin.order.create')}}">
                        <button type="button" class="btn default-btn btn-md">
                            <span class="menu-icon">+ Create Order</span>
                        </button>
                        </a>
                    @endcan
                    @endif
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th> Order Id </th>
                                <th> Order By </th>
                                <th> Status </th>
                                <th> Services </th>
                                <th> Order At </th>
                                @canany(['order-edit','order-view','order-invoice-download'])
                                    <th> Action </th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                                <tr data-id="{{$order->id}}">
                                    <td>{{$order->order_id}}</td>
                                    <td>{{$order->user ? $order->user->full_name : 'N/A'}}</td>
                                    <td>{{$order->status}}</td>
                                    <td> {{implode(", ",$order->service_name)}}</td>
                                    <td>{{convertDate($order->created_at)}}</td>
                                    @canany(['order-edit','order-view','order-invoice-download'])
                                    <td> 
                                        @can('order-view')
                                        <span class="menu-icon">
                                            <a href="{{route('admin.order.view',['id' => $order->id])}}" title="View" class="text-primary"><i class="mdi mdi-eye"></i></a>
                                        </span>&nbsp;&nbsp;&nbsp;
                                        @endcan
                                        @can('order-edit')
                                        @if(orderStatusCheck($order->id,'Requested	'))
                                            <span class="menu-icon">
                                            <a href="{{route('admin.order.edit',['id' => $order->id])}}" title="Edit" class="text-success"><i class="mdi mdi-pencil"></i></a>
                                            </span>&nbsp;&nbsp;&nbsp;
                                        @endif
                                        @endcan
                                        @can('order-invoice-download')
                                        <span class="menu-icon">
                                            <a href="{{route('admin.storeOrder.invoice.download',['id' => $order->id])}}" title="Download Incoice" class=""><i class="mdi  mdi-download" style="font-size: 20px;"></i></a>
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
                    {{ $orders->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
