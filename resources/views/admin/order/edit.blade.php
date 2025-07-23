@extends('admin.layouts.app')
@section('title', 'Orders')
@section('breadcrum')
    <div class="page-header">
        <h3 class="page-title">Order Management</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page"> Orders</li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>
    </div>
@endsection
@section('content')
<div class="row ">
    <div class="col-lg-12">
        <div class="progress-bar mb-5">
            <div class="first-step">
                <div class="step-indicator {{orderStatusCheck($order->id,'Requested') ? 'active' : ''}}">
                    <div class="check-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                            class="bi bi-check-lg" viewBox="0 0 16 16">
                            <path
                                d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z" />
                        </svg>
                    </div>
                </div>
                <div class="line-title mt-2">
                    <p class="mb-0">Requested</p>
                </div>
            </div>

            @if(orderStatusCheck($order->id,'Cancelled'))
                <div class="second-step">
                    <div class="step-indicator {{orderStatusCheck($order->id,'Cancelled') ? 'active' : ''}}">
                        <div class="check-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                                class="bi bi-check-lg" viewBox="0 0 16 16">
                                <path
                                    d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z" />
                            </svg>
                        </div>
                    </div>
                    <div class="line-title mt-2">
                        <p class="mb-0">Cancelled</p>
                    </div>
                </div>
            @else
                <div class="second-step">
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
            @endif

            <div class="third-step">
                <div class="step-indicator {{orderStatusCheck($order->id,'Assign Pickup Driver') ? 'active' : ''}}">
                    <div class="check-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                            class="bi bi-check-lg" viewBox="0 0 16 16">
                            <path
                                d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z" />
                        </svg>
                    </div>
                </div>
                <div class="line-title mt-2">
                    <p class="mb-0">Assign Driver</p>
                </div>
            </div>

            <div class="four-step">
                <div class="step-indicator {{orderStatusCheck($order->id,'Approved') ? 'active' : ''}}">
                    <div class="check-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                            class="bi bi-check-lg" viewBox="0 0 16 16">
                            <path
                                d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z" />
                        </svg>
                    </div>
                </div>
                <div class="line-title mt-2">
                    <p class="mb-0">Approved</p>
                </div>
            </div>

            <div class="five-step">
                <div class="step-indicator {{orderStatusCheck($order->id,'Paid') ? 'active' : ''}}">
                    <div class="check-icon ">
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

            <div class="six-step">
                <div class="step-indicator {{orderStatusCheck($order->id,'Ready') ? 'active' : ''}}">
                    <div class="check-icon ">
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

            <div class="six-step">
                <div class="step-indicator {{orderStatusCheck($order->id,'Delivered') ? 'active' : ''}}">
                    <div class="check-icon after-none ">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                            class="bi bi-check-lg" viewBox="0 0 16 16">
                            <path
                                d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z" />
                        </svg>
                    </div>
                </div>
                <div class="line-title mt-2">
                    <p class="mb-0">Delivered</p>
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

<div class="col-12 d-flex justify-content-end mb-3">
    <a href="{{route('admin.storeOrder.invoice.download',['id' => $order->id])}}">
        <button class="btn btn-primary mr-2">
            <i class="mdi mdi-download"></i> 
            Invoice
        </button>
    </a>
</div>
    
<div class="payment-shipping-status mt-4">
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <h5 class="fs-14 d-inline">Pickup Date:</h5>
                        <p class="fs-light d-inline mb-0"> {{convertDate($order->pickup_date,'d-m-Y') ?? 'N/A'}}</p>
                    </div>
                    <div class="payment-id">
                        <h5 class="fs-14">Pickup Address:</h5>
                        @php
                            $pickup_address = json_decode($order->pickup_address);
                            $delivery_address = json_decode($order->delivery_address);
                        @endphp
                        <P class="fs-light  mb-0">{{$pickup_address->address ?? ''}}</P>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">

                    <div class="mb-3">
                        <h5 class="fs-14 d-inline">Delivery Date:</h5>
                        <p class="fs-light d-inline mb-0"> {{convertDate($order->delivery_date,'d-m-Y') ?? 'N/A'}}</p>
                    </div>

                    <div class="shipping-id ">
                        <h5 class="fs-14">Delivery Address:</h5>
                        <P class="fs-light  mb-0">{{$delivery_address->address ?? ''}}</P>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if($order->deliveryDriver != null || $order->pickupDriver != null)
        <div class="row mt-4">
            @if($order->pickupDriver != null)
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="fs-14">Pickup Driver Detail:</h5>
                                @if($order->pickupDriver && ! orderStatusCheck($order->id ,'Pickup'))
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#assign_driver">Change Driver</button>
                                @endif
                            </div>
                            
                            <div class="shipping-id">
                                @if($order->pickupDriver)
                                <p class="fs-light mb-0"> 
                                    {{$order->pickupDriver->full_name ?? ''}}<br>
                                    {{$order->pickupDriver->email ?? ''}}<br>
                                    {{$order->pickupDriver->driverDetail ? ($order->pickupDriver->driverDetail->phone_number ? $order->pickupDriver->driverDetail->phone_number : '') : ''}}<br>
                                    {{$order->pickupDriver->driverDetail ? ($order->pickupDriver->driverDetail->vehicle ? ($order->pickupDriver->driverDetail->vehicle->name ? $order->pickupDriver->driverDetail->vehicle->name : '') : '') : ''}}<br>
                                </p>
                                @else
                                <p class="fs-light mb-0">N/A</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if($order->deliveryDriver != null)
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="fs-14">Delivery Driver Detail:</h5>
                                @if($order->deliveryDriver && ! orderStatusCheck($order->id ,'Paid'))
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#assign_driver">Change Driver</button>
                                @endif
                            </div>
                            
                            <div class="shipping-id">
                                @if($order->deliveryDriver)
                                <p class="fs-light mb-0"> 
                                    {{$order->deliveryDriver->full_name ?? ''}}<br>
                                    {{$order->deliveryDriver->email ?? ''}}<br>
                                    {{$order->deliveryDriver->driverDetail ? ($order->deliveryDriver->driverDetail->phone_number ? $order->deliveryDriver->driverDetail->phone_number : '') : ''}}<br>
                                    {{$order->deliveryDriver->driverDetail ? ($order->deliveryDriver->driverDetail->vehicle ? ($order->deliveryDriver->driverDetail->vehicle->name ? $order->deliveryDriver->driverDetail->vehicle->name : '') : '') : ''}}<br>
                                </p>
                                @else
                                <p class="fs-light mb-0">N/A</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif
</div>

<div class="product-details mt-5">
    <div class="row">
        <div class="col-lg-12">
            <div class="pro-heading mb-4 tst">
                <h3 class="page-title">Service Details</h3>
            </div>
        
            @foreach ($order->services()->get()->groupBy('service_id') as $service)
                <div class="product-content card mb-4">
                    <h4 class="service-heading">{{ $service->first()->service ? $service->first()->service->name : 'N/A' }}</h4>
                    <table class="table ">
                        <thead>
                        <th>Sr.No</th> 
                        <th>Variant</th>
                        <th>Price Per Variant</th>
                        <th>Quantity</th>
                        <th>Total Amount</th>
                        <th>Action</th>
                        </thead>
                        @foreach ($service as $key => $serviceInstance)
                            @php
                                $variant = \App\Models\Variant::find($serviceInstance->variant_id);
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $variant ? $variant->name : 'N/A' }}</td>
                                <td class="price" data-value="{{$serviceInstance->amount}}">${{ $serviceInstance->amount}}</td>
                                <td><input type="number" value="{{ $serviceInstance->qty }}" class="form-control qty" min="1"></td>
                                <td class="amount">{{ $serviceInstance->amount * $serviceInstance->qty  }}</td>
                                <td>
                                    <h6 class="btn default-btn btn-md update_service" data-id="{{$serviceInstance->id}}">
                                        <i class="fas fa-edit"></i>
                                    </h6>
                                    
                                    <h6 class="btn btn-danger btn-md remove_service" data-id="{{$serviceInstance->id}}">
                                        <i class="fas fa-trash-alt"></i>
                                    </h6>
                                    
                                    
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            @endforeach

        </div>
    </div>
    <div class="col-lg-12 px-0   taxes-section justify-content-end ">
        <div class="card">
            <div class="card-body">
                <div class="invoice-id">
                    <h4 class="service-heading">Payment Details</h4>
                    <div class="taxes-section">
                        <p>
                            <span class="tax-head">Sub Total <span>-</span></span>
                            <span>${{$order->total_amount}}</span>
                        </p>
                        @foreach ($order->taxes as $item)
                            <p>
                                <span class="tax-head">{{$item->title}} <span>-</span></span>
                                <span>${{$item->amount}}</span>
                            </p>
                        @endforeach

                        <p>
                            <span class="tax-head">Grand Total <span>-</span></span>
                            <span>${{$order->total_amount + $order->taxes()->where('title','!=','Total Discount')->sum('amount') + $order->taxes()->where('title','Total Discount')->sum('amount')}}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('admin.order.popup')
@endsection
@section('scripts')
@include('admin.order.orderjs')
@endsection
