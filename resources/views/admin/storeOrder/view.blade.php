@extends('admin.layouts.app')
@section('title', 'Orders')
@section('breadcrum')
    <div class="page-header">
       <h3 class="page-title">In Store Orders</h3>
       <nav aria-label="breadcrumb">
           <ol class="breadcrumb">
               <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
               <li class="breadcrumb-item"><a href="#">Order Management</a></li>
               <li class="breadcrumb-item"> <a href="{{ route('admin.storeOrder.list') }}">In Store Orders</a></li>
               <li class="breadcrumb-item active" aria-current="page">View Order</li>
           </ol>
       </nav>
   </div>
@endsection
@section('content')
   <div class="row ">
       <div class="col-lg-12">
           <div class="progress-bar mb-5">
               <div class="first-step">
                   <div class="step-indicator {{orderStatusCheck($order->id,'Accepted') ? 'active' : ''}}">
                       <div class="check-icon">
                           <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                               class="bi bi-check-lg" viewBox="0 0 16 16">
                               <path
                                   d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z" />
                           </svg>
                       </div>
                   </div>
                   <div class="line-title mt-2">
                       <p class="mb-0">Accepted</p>
                   </div>
               </div>
               <div class="second-step">
                   <div class="step-indicator {{orderStatusCheck($order->id,'Paid') ? 'active' : ''}}">
                       <div class="check-icon">
                           <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                               class="bi bi-check-lg" viewBox="0 0 16 16">
                               <path
                                   d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z" />
                           </svg>
                       </div>
                   </div>
                   <div class="line-title mt-2">
                       <p class="mb-0">Paid</p>
                   </div>
               </div>
               <div class="third-step">
                   <div class="step-indicator {{orderStatusCheck($order->id,'Ready') ? 'active' : ''}}">
                       <div class="check-icon">
                           <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                               class="bi bi-check-lg" viewBox="0 0 16 16">
                               <path
                                   d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z" />
                           </svg>
                       </div>
                   </div>
                   <div class="line-title mt-2">
                       <p class="mb-0">Ready</p>
                   </div>
               </div>
               <div class="four-step">
                   <div class="step-indicator  {{orderStatusCheck($order->id,'Delivered') ? 'active' : ''}}">
                       <div class="check-icon after-none">
                           <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                               class="bi bi-check-lg" viewBox="0 0 16 16">
                               <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z" />
                           </svg>
                       </div>
                   </div>
                   <div class="line-title mt-2">
                       <p class="mb-0"> Delivered</p>
                   </div>
               </div>
           </div>
       </div>
       
       <div class="col-lg-12 grid-margin stretch-card">
           <div class="card">
               <div class="card-body">
                   <div class="row">
                       <div class="col-lg-2">
                           <div class="order-id border-right">
                               <h5 class="fs-14">Order ID:</h5>
                               <P class="fs-light  mb-0">{{$order->order_id}}</P>
                           </div>
                       </div>
                       <div class="col-lg-2">
                           <div class="purchase-id ">
                               <h5 class="fs-14">User Name:</h5>
                               <P class="fs-light  mb-0">{{$order->user ? $order->user->full_name : 'N/A'}}</P>
                           </div>
                       </div>
                       <div class="col-lg-2 ">
                           <div class="date-id border-right">
                               <h5 class="fs-14">Date Added:</h5>
                               <P class="fs-light mb-0">{{convertDate($order->created_at,'d-m-Y')}}</P>
                           </div>
                       </div>
                       <div class="col-lg-2">
                           <div class="pay-id border-right">
                               <h5 class="fs-14">Payment Method:</h5>
                               <P class="fs-light mb-0">{{$order->payment ? $order->payment->payment_type : '-'}}</P>
                           </div>
                       </div>
                       <div class="col-lg-2">
                           <div class="items-id border-right">
                               <h5 class="fs-14">No. of items</h5>
                               <P class="fs-light  mb-0">{{$order->services()->count()}}</P>
                           </div>
                       </div>
                       <div class="col-lg-2">
                           <div class="status-id ">
                               <h5 class="fs-14">Order Status:</h5>
                               <P class="fs-light mb-0">{{$order->status}}</P>
                           </div>
                       </div>
                   </div>
               </div>
           </div>
       </div>
   </div>

   <div class="col-12 d-flex justify-content-end">
       <a href="{{route('admin.storeOrder.invoice.download',['id' => $order->id])}}"><button class="btn btn-primary mr-2"><i class="mdi mdi-download"></i> Invoice</button></a>
  </div>
   <div class="product-details">
       <div class="row">
           <div class="col-lg-12">
               <div class="pro-heading mb-4">
                   <h3 class="page-title">Service Details</h3>
               </div>
               @foreach ($order->services()->get()->groupBy('service_id') as $service)
                   <div class="product-content card mb-4">
                       <h4 class="service-heading">{{ $service->first()->service ? $service->first()->service->name : 'N/A' }}</h4>
                       <table class="table ">
                           <thead>
                           <th>Sr.No</th>
                           <th>Variant</th>
                           <th>Amount</th>
                           <th>Quantity</th>
                           </thead>
                           @foreach ($service as $key => $serviceInstance)
                               @php
                                   $variant = \App\Models\Variant::find($serviceInstance->variant_id);
                               @endphp
                               <tr>
                                   <td>{{ $loop->iteration }}</td>
                                   <td>{{ $variant ? $variant->name : 'N/A' }}</td>
                                   <td>${{ $serviceInstance->amount }}</td>
                                   <td>{{ $serviceInstance->qty }}</td>
                               </tr>
                           @endforeach
                       </table>
                   </div>
               @endforeach
           </div>
           <div class="col-lg-12  taxes-section justify-content-end ">
               <div class="card">
                   <div class="card-body">
                       <div class="invoice-id">
                           <h4 class="service-heading">Payment Details</h4>
                           <div class="taxes-section">
                               <p>
                                   <span class="tax-head">Sub Total <span>-</span></span>
                                   <span>${{$order->total_amount - $order->taxes()->sum('amount')}}</span>
                               </p>
                               @foreach ($order->taxes as $item)
                                   <p>
                                       <span class="tax-head">{{$item->title}} <span>-</span></span>
                                       <span>${{$item->amount}}</span>
                                   </p>
                               @endforeach
   
                               <p>
                                   <span class="tax-head">Grand Total <span>-</span></span>
                                   <span>${{$order->total_amount}}</span>
                               </p>
                           </div>
                       </div>
                   </div>
               </div>
           </div>
   
           <!-- Align Accept and Reject buttons to the right -->
           @if($order->status == "Accepted")
               <div class="col-12 d-flex justify-content-end mt-3">
                   <button class="btn btn-primary mr-2 PaidOrder" data-id="{{$order->id}}">Mark As Paid</button>
               </div>
           @elseif($order->status == "Paid")
               <div class="col-12 d-flex justify-content-end mt-3">
                   <button class="btn btn-info mr-2 orderStatusChange" data-id="{{$order->id}}" data-value="Ready">Mark As Ready</button>
               </div>
           @elseif($order->status == "Ready")
               <div class="col-12 d-flex justify-content-end mt-3">
                   <button class="btn btn-success mr-2 orderStatusChange" data-id="{{$order->id}}" data-value="Delivered">Mark As Complete</button>
               </div>
           @endif
       </div>
   </div>
@include('admin.storeOrder.popup')
@endsection
@include('admin.storeOrder.orderjs')