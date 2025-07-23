<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
    <style>

        .pdf-structure span {
            padding-bottom: 6px;
        }
        .pdf-structure > *:not(:last-child) {
            border-bottom: 1px solid #ccc;
            padding-bottom:15px;
            margin-bottom:15px;
        }
        h4 {
            margin: 5px 0;
        }
        p {
            margin-bottom: 5px ;
        }

    </style>
</head>
<body>
    <div class="pdf-structure" style="padding: 15px; margin: 0 auto; border: 1px solid #ccc; border-radius: 20px;">
        <table style="width:100%;">
            <tr>
                <td style="width:50%">

                    <h4 style="margin-bottom: 0px;">
                        <span style="fontWeight: 600;font-size: 15px;">{{$order->user ? $order->user->full_name : 'N/A'}}</span><br/>
                        <span style="fontWeight: 600;font-size: 15px;">Address-</span>
                        <span style="fontWeight:400; font-size: 14px; margin-bottom: 4px;">
                            {{$order->user ? ($order->user->userAddress ? ($order->user->userAddress->address ?? 'N/A') : 'N/A') : 'N/A'}}</span>
                        </span>
                        <span style="fontWeight:400; font-size: 14px; margin-bottom: 4px;">
                            {{$order->user ? ($order->user->userDetail ? ($order->user->userDetail->phone_number ?? 'N/A') : 'N/A') : 'N/A'}}</span>
                        </span>
                    </h4>
                </td>
                <td style="width:50%"> 
                    @if(orderStatusCheck($order->id,'Cancelled'))
                        <h4 style="margin-bottom-0px;">
                            <span style="fontWeight: 600;font-size: 15px;color:red;margin-bottom: 4px;">Cancelled Reason :- {{$order->declineReason ? $order->declineReason->reason : ''}}</span>
                        </h4>
                    @endif

                    <h4 style="margin-bottom: 0px;">
                        <span style="fontWeight: 600;font-size: 15px;">Invoice</span>
                        <span style="fontWeight:300; font-size: 14px; margin-bottom: 4px;">#{{$order->order_id}}</span>
                    </h4>
                    <h4 style="margin-bottom: 0px;">
                        <span style="fontWeight: 600; font-size: 15px;">Order Date -</span>
                        <span style="fontWeight: 400; font-size: 14px; margin-bottom: 4px;"> {{convertDate($order->created_at)}} </span>
                    </h4>
                    
                    <h4 style="margin-bottom: 0px;">
                        <span style="fontWeight: 600; font-size: 15px;">Order Status -</span>
                        <span style="fontWeight: 400; font-size: 14px; margin-bottom: 4px;"> {{$order->status }} </span>
                    </h4>
                    <h4 style="margin-bottom: 0px;">
                        <span style="fontWeight: 600; font-size: 15px;">Pickup Date -</span>
                        <span style="fontWeight: 400; font-size: 14px; margin-bottom: 4px;"> {{convertDate($order->pickup_date,'d-m-Y') ?? 'N/A'}}</span>
                    </h4>
                    <h4 style="margin-bottom: 0px;">
                        <span style="fontWeight: 600; font-size: 15px;">Payment Method -</span>
                        <span style="fontWeight: 400; font-size: 14px; margin-bottom: 4px;"> {{$order->payment ? $order->payment->payment_type : ''}} </span>
                    </h4>
                </td>
            </tr>
        </table>
        @if($order->order_type == "online")
            <div class="pdf-table" style="margin-top: 30px; margin-bottom: 20px;">
            <table style="width: 100%;  border:1px solid #ccc;">
                <thead>
                    <tr>
                        <th style=" background-color: #e5e5e5;">
                            <p style="font-size: 14px; fontWeight: 600; background-color: #e5e5e5; padding-bottom: 8px;">
                                Pickup Address
                            </p>
                        </th>
                        <th style=" background-color: #e5e5e5;">
                            <p style="font-size: 14px; fontWeight: 600;  padding-bottom: 8px;">
                                Delivery Address
                            </p>
                        </th>
                    </tr>
                </thead>
                <tbody>
                <tr>
                    <td style="padding: 10px">
                        <p style="margin-bottom: 0; font-size: 14px;">
                        
                            @php
                            $billingAddress = [];
                            if($order->pickup_address != null)
                            {
                                $billingPayload = json_decode($order->pickup_address);

                                foreach($billingPayload as $key => $value)
                                {
                                    if($value != null )
                                    {
                                        $billingAddress[$key] = $value;
                                    }
                                }
                                unset($billingAddress['state_id']);
                                unset($billingAddress['country_id']);
                                unset($billingAddress['status']);
                                unset($billingAddress['lat']);
                                unset($billingAddress['long']);
                            }
                            @endphp

                            @forelse($billingAddress as $key => $value)
                                <span style="display: block;"> {{str_replace("_", " ", ucFirst($key))}} :
                                    {{$value}}
                                </span>                           
                            @empty
                                <span style="display: block;">933-82 Shikawatashi,</span>
                            @endforelse

                        </p>
                    </td>
                    <td style="padding: 10px">
                        <p style="margin-bottom: 0; font-size: 14px;">
                        
                            @php
                            $shippingAddress = [];
                            if($order->delivery_address != null)
                            {
                                $shippingPayload = json_decode($order->delivery_address);

                                foreach($shippingPayload as $key => $value)
                                {
                                    if($value != null )
                                    {
                                        $shippingAddress[$key] = $value;
                                    }
                                }
                                unset($shippingAddress['state_id']);
                                unset($shippingAddress['country_id']);
                                unset($shippingAddress['status']);
                                unset($billingAddress['lat']);
                                unset($billingAddress['long']);
                            }
                            @endphp

                            @forelse($shippingAddress as $key => $value)
                                <span style="display: block;"> {{str_replace("_", " ", ucFirst($key))}} :
                                    {{$value}}
                                </span>                           
                            @empty
                                <span style="display: block;">933-82 Shikawatashi,</span>
                            @endforelse

                        </p>
                    </td>
                </tr>
                </tbody>
            </table>
            </div>
        @endif

        <!--  Order Items Detail Start -->
        <h4 style="margin-bottom: 0px;">
            <span style="fontWeight: 400; font-size: 14px; margin-bottom: 4px;"> Service Details </span>
        </h4>
        
        @foreach ($order->services()->get()->groupBy('service_id') as $service)
            <div class="pdf-table" style="margin-top: 30px; margin-bottom: 20px;">
            <table style="width: 100%;  border:1px solid #ccc;">
                <tr>
                    <td colspan="4"><h4 class="service-heading">{{ $service->first()->service ? $service->first()->service->name : 'N/A' }}</h4></td>
                </tr>
                
                <thead>
                    <tr>
                        <th style=" background-color: #e5e5e5; width: 20%;">
                            <p style="font-size: 14px; fontWeight: 600; background-color: #e5e5e5; padding-bottom: 8px;">
                                Sr. No.
                            </p>
                        </th>
                        <th style=" background-color: #e5e5e5; width: 30%;">
                            <p style="font-size: 14px; fontWeight: 600; background-color: #e5e5e5; padding-bottom: 8px;">
                                Variant
                            </p>
                        </th>
                        <th style=" background-color: #e5e5e5; width: 30%;">
                            <p style="font-size: 14px; fontWeight: 600; background-color: #e5e5e5; padding-bottom: 8px;">
                                Amount (per variant)
                            </p>
                        </th>
                        <th style=" background-color: #e5e5e5; width: 20%;">
                            <p style="font-size: 14px; fontWeight: 600; background-color: #e5e5e5; padding-bottom: 8px;">
                                Qty
                            </p>
                        </th>   
                        <th style=" background-color: #e5e5e5; width: 30%;">
                            <p style="font-size: 14px; fontWeight: 600; background-color: #e5e5e5; padding-bottom: 8px;">
                                Amount
                            </p>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($service as $key => $serviceInstance)
                        @php
                            $variant = \App\Models\Variant::find($serviceInstance->variant_id);
                            $unit_price = $serviceInstance->amount / $serviceInstance->qty;
                        @endphp
                        <tr>
                            <td class="width: 20%;">
                                <p style="margin-bottom: 0; font-size: 14px; text-align: center;">{{ $loop->iteration }}</p>
                            </td>
                            <td class="width: 30%;">
                                <p style="margin-bottom: 0; font-size: 14px; text-align: center;">{{ $variant ? $variant->name : 'N/A' }}</p>
                            </td>
                            <td class="width: 30%;">
                                <p style="margin-bottom: 0; font-size: 14px; text-align: center;">${{ $unit_price }}</p>
                            </td>
                            <td class="width: 20%;">
                                <p style="margin-bottom: 0; font-size: 14px; text-align: center;">{{ $serviceInstance->qty }}</p>
                            </td>
                            <td class="width: 30%;">
                                <p style="margin-bottom: 0; font-size: 14px; text-align: center;">${{ $serviceInstance->amount }}</p>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5">
                                <p style="margin-bottom: 0; font-size: 14px;">No Service added</p>
                            </td>
                        </tr>
                    @endforelse --
                </tbody>
            </table>
            </div>
        @endforeach
        <!--  Order Items Detail End -->

        <!--  Pricing Start -->
        <div style="margin-top: 30px; margin-bottom: 20px;">
            <div>
                <p style="margin-bottom: 0;font-size: 14px;margin-left: auto; text-align: right;">
                    <span style="display: block; float: left; width: 80%; text-align: right">Sub Total -</span>
                    <span style="display: block;">${{$order->total_amount}}</span>
                </p>
                @foreach ($order->taxes as $item)
                    <p style="margin-bottom: 0;font-size: 14px; margin-left: auto; text-align: right;">
                        <span style="display: block; float: left; width: 80%;">{{$item->title}}-</span>
                        <span style="display: block;">${{$item->amount}}</span>
                    </p>
                @endforeach
               <hr>
               <p style="margin-bottom: 0;font-size: 20px;margin-left: auto; text-align: right;">
                    <span style="display: block; float: left; width: 80%;">Grand Total -</span>
                    <span style="display: block;">${{$order->total_amount + $order->taxes()->where('title','!=','Total Discount')->sum('amount') + $order->taxes()->where('title','Total Discount')->sum('amount')}}</span>
                </p>
            </div>
        </div>
        <!--  Pricing End -->
    </div>
</body>
</html>