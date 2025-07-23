@extends('admin.layouts.app')
@section('title', 'In Store Orders')
@section('breadcrum')
<div class="page-header">
    <h3 class="page-title">In Store Orders</h3>
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="#">Order Management</a></li>
        <li class="breadcrumb-item active" aria-current="page">In Store Orders</li>
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
          <h4 class="card-title">In Store Orders</h4>
          
            <div class="admin-filters">   
              <form id="filter" >
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
              </div>
            
              @can('InStore-order-add')
                <a href="{{route('admin.storeOrder.create')}}">
                  <button type="button" class="btn default-btn btn-md">
                    <span class="menu-icon">+ Create Order</span>
                  </button>
                </a>
              @endcan
        </div>
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th> Order Id </th>
                <th> Order By </th>
                <th> Status </th>
                <th> Order At </th>
                @canany(['InStore-order-edit','InStore-order-view','order-invoice-download'])
                  <th> Action </th>
                @endcanany
              </tr>
            </thead>
            <tbody>
              @forelse ($orders as $order)
                <tr data-id="{{$order->id}}">
                  <td>{{$order->order_id}}</td>
                  <td>{{UserNameById($order->user_id)}}</td>
                  <td>
                    @switch($order->status)
                        @case("Accepted")
                            <span class="badge badge-pill badge-primary">Accepted</span>
                          @break
                        @case("Paid")
                            <span class="badge badge-pill badge-success">Paid</span>
                          @break
                        @case("Ready")
                          <span class="badge badge-pill badge-info">Ready</span>
                          @break
                        @case("Delivered")
                          <span class="badge badge-pill badge-success">Delivered</span>
                          @break
                        @default
                    @endswitch
                  <td>{{convertDate($order->created_at)}}</td>
                  @canany(['InStore-order-edit','InStore-order-view','order-invoice-download'])
                    <td> 
                      @can('InStore-order-view')
                        <span class="menu-icon">
                          <a href="{{route('admin.storeOrder.view',['id' => $order->id])}}" title="View" class="text-primary"><i class="mdi mdi-eye" style="font-size: 20px;"></i></a>
                        </span>&nbsp;&nbsp;&nbsp;
                      @endcan
                      @can('InStore-order-edit')
                        @if(!orderStatusCheck($order->id,'Paid'))
                          <span class="menu-icon">
                            <a href="{{route('admin.storeOrder.edit',['id' => $order->id])}}" title="Edit" class="text-success"><i class="mdi mdi-pencil"></i></a>
                          </span>&nbsp;&nbsp;&nbsp;
                        @endif

                        @if($order->status == 'Accepted')
                          <span class="menu-icon">
                            <a href="#" title="Mark as Paid" class="text-success PaidOrder" data-id="{{$order->id}}"><i class="mdi mdi-cash-usd" style="font-size: 20px;"></i></a>
                          </span> &nbsp;&nbsp;&nbsp;
                        @endif

                        @if($order->status == 'Paid')
                          <span class="menu-icon">
                            <a href="#" title="Mark as Ready" class="text-info orderStatusChange" data-id="{{$order->id}}" data-value="Ready"><i class="mdi mdi-read" style="font-size: 20px;"></i></a>
                          </span> &nbsp;&nbsp;&nbsp;
                        @endif

                        @if($order->status == 'Ready')
                          <span class="menu-icon">
                            <a href="#" title="Mark as Delivered" class="text-success orderStatusChange" data-id="{{$order->id}}" data-value="Delivered"><i class="mdi mdi-playlist-check" style="font-size: 20px;"></i></a>
                          </span> &nbsp;&nbsp;&nbsp;
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
                    <td colspan="5" class="no-record"> <div class="col-12 text-center">No record found </div></td>
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
@include('admin.storeOrder.popup')
@endsection
@include('admin.storeOrder.orderjs')