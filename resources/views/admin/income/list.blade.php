@extends('admin.layouts.app')
@section('title', 'Income Report')
@section('breadcrum')
<div class="page-header">
    <h3 class="page-title">Income Report</h3>
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Income Report</li>
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
                    <h4 class="card-title">Income Report</h4>
                    @can('income-export')
                        <a href="{{route('admin.income.export',request()->all())}}">
                            <button type="button" class="btn default-btn btn-md px-1">
                                <span class="menu-icon">
                                    <i class="mdi mdi-download" style="font-size: 18px;"></i>Download Report
                                </span>
                            </button>
                        </a>
                    @endcan
                </div>
                <div class="row">
                    <div class="admin-filters" style="width: 100%;">
                    <form id="filter">
                        <div class="row align-items-end justify-content-end mb-3">
                        <div class="col-3 d-flex gap-2">
                            <input type="text" class="form-control"  placeholder="Search" name="search_keyword" value="{{request()->filled('search_keyword') ? request()->search_keyword : ''}}" >            
                        </div>
                            <div class="col-3 gap-2">
                            <label for="From">From</label>
                                <input type="date" class="form-control"  placeholder="Search" name="from_date" value="{{request()->filled('from_date') ? request()->from_date : ''}}" >            
                            </div>
                            <div class="col-3 gap-2">
                            <label for="To">To</label>
                                <input type="date" class="form-control"  placeholder="Search" name="to_date" value="{{request()->filled('to_date') ? request()->to_date : ''}}" >            
                            </div>
                        
                            <div class="col-3">
                            <div class="filter-btns d-flex gap-2 align-items-center justify-content-between">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                @if(request()->filled('search_keyword') || request()->filled('from_date') || request()->filled('to_date'))
                                    <button class="btn btn-danger" id="clear_filter">Clear Filter</button>
                                @endif
                            </div>
                            </div>
                        </div>
                    </form>
                    </div>
                    
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th> Order Id </th>
                                <th> Order By </th>
                                <th> Order Type </th>
                                <th> Services </th>
                                <th> Amount </th>
                                <th> Order At </th>
                                <th> Action </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                                <tr data-id="{{$order->id}}">
                                    <td>{{$order->order_id}}</td>
                                    <td>{{$order->user ? $order->user->full_name : 'N/A'}}</td>
                                    <td>{{$order->order_type}}</td>
                                    <td>{{implode(", ",$order->service_name)}}</td>
                                    <td>${{$order->total_amount}}</td>
                                    <td>{{convertDate($order->created_at)}}</td>
                                    <td> 
                                    <span class="menu-icon">
                                        <a href="{{route('admin.order.view',['id' => $order->id])}}" title="View" class="text-primary"><i class="mdi mdi-eye"></i></a>
                                    </span>&nbsp;&nbsp;&nbsp;

                                    <span class="menu-icon">
                                        <a href="{{route('admin.storeOrder.invoice.download',['id' => $order->id])}}" title="Download Incoice" class=""><i class="mdi  mdi-download" style="font-size: 20px;"></i></a>
                                    </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="no-record"> <div class="col-12 text-center">No record found </div></td>
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
