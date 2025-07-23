<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use DateTime;
use DateInterval;
use App\Models\{ConfigSetting, CustomerAddress, HolidayManagement, Order, OrderService, OrderTax, OrderDeclineReason, Promotion, RewardPoint, RedeemPoint, ServiceVariant, Tax, TimeShedule, User};
use App\Notifications\OrderCreateNotification;
use App\Traits\SendResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class OrderManagementController extends Controller
{
    use SendResponseTrait;
    
    /**
     * functionName : availability
     * createdDate  : 10-09-2024
     * purpose      : check the avaiblity 
    */



public function availability(Request $request)
{
    try {
        // Validate the pickup_date
        $validator = Validator::make($request->all(), [
            'pickup_date' => 'required|date|after_or_equal:' . now()->toDateString(), // Ensure it's today or a future date
            'timezone'     => 'required|string', // Ensure timezone is provided and is a string
        ]);
        if ($validator->fails()) {
            return $this->apiResponse('error', 422, $validator->errors()->first());
        }

        // Get the pickup date from the request
        $pickupDate = strtotime($request->pickup_date);
        $today = strtotime(date('Y-m-d')); // Get today's date in timestamp

        $holiday_pickup = HolidayManagement::where('h_date', $request->pickup_date)->where('status', 1)->count();

        $slots = [];
        if ($holiday_pickup) {
            // If it's a holiday, return no slots
            $slots = [];
        } else {
            // Get the day name from the pickup date
            $dayName = date('l', strtotime($request->pickup_date)); 
            $holiday_pickup = TimeShedule::where('day', $dayName)->where('status', 1)->first();

            if ($holiday_pickup) {
                // Set the timezone to Asia/Kolkata
                $timezone = $request->timezone;

                // Set start and end times using the schedule
                $start = Carbon::createFromFormat('H:i', $holiday_pickup->start_time, $timezone);
                $end = Carbon::createFromFormat('H:i', $holiday_pickup->end_time, $timezone);

                // Get the configured time slot interval (in minutes)
                $slot = ConfigDetail('time-slot', 'TIME_SLOT');
                
                // Ensure the interval is an integer (in case it's a string in config)
                $interval = (int)$slot;  // Convert it to an integer

                // Get the current time in Asia/Kolkata using Carbon
                $currentTime = Carbon::now($timezone);

                // Check if the pickup date is today
                $isToday = (date('Y-m-d', strtotime($request->pickup_date)) === date('Y-m-d'));

                // Generate time slots
                while ($start < $end) {
                    $slotStart = $start->copy(); // Clone to preserve original for loop
                    $slotEnd = $start->copy()->addMinutes($interval);

                    // Break the loop if slot end goes beyond the defined end time
                    if ($slotEnd > $end) {
                        break;
                    }
                    // If the pickup date is today, skip past slots
                    if ($isToday && $slotStart <= $currentTime) {
                        // Skip the passed slots by continuing the loop
                        $start->addMinutes($interval); // Move to the next slot
                        continue;
                    }

                    // Format the slot start and end time
                    $slotStart1 = $slotStart->format('H:i');
                    $start->addMinutes($interval); // Move to the next slot
                    $slotEnd1 = $slotEnd->format('H:i');

                    // Add the future slot to the list
                    $slots[] = "{$slotStart1} - {$slotEnd1}";
                }
            }
        }

        return $this->apiResponse('success', 200, __('messages.Availability slots fetched successfully.'), $slots);
    } catch (\Exception $e) {
        return $this->apiResponse('error', 500, $e->getMessage());
    }
}



    /*end method availability */

    /**
     * functionName : tax
     * createdDate  : 11-09-2024
     * purpose      : To manage the taxes 
    */
    public function tax(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'amount'       => 'required',
                'pickup_id' => 'required|exists:customer_addresses,id',
                'delivery_id' => 'required|exists:customer_addresses,id',
            ]);

            if ($validator->fails()) {
                return $this->apiResponse('error',422,$validator->errors()->first());
            }
           
            $detail = ConfigSetting::where('type','delivery-cost')->pluck('value','key');
            
            $minimumOrder = floatval($detail['MINIMUM_ORDER_AMOUNT']);
            $freeDeliveryThreshold = floatval($detail['FREE_DELIVERY']);
            $amount = floatval($request->amount);
           
            if($amount < $minimumOrder){
                return $this->apiResponse('error',201, __('messages.you can\'t place order less than') . ' $' . $minimumOrder);
            }
            
            $originalAmount = $amount;
            
            $taxes = Tax::where('status',1)->get();
            $data = [];
            
            foreach($taxes as $tax){
                $tax_amount = round(( $amount * $tax->percentage ) / 100,2);
                array_push($data,[
                    'name'  => $tax->label,
                    'amount' => $tax_amount,
                    'rate'  => $tax->percentage
                ]);
                $amount +=$tax_amount;
            }
            if($originalAmount >= $freeDeliveryThreshold){
                array_push($data,[
                    'name'  => 'Delivery Fee',
                    'amount' => 0,
                    'rate'  => 0
                ]);
            }else{
                $admin_address = CustomerAddress::where('user_id',1)->first();
                $pickup_address = CustomerAddress::find(intval($request->pickup_id));
                $delivery_address = CustomerAddress::find(intval($request->delivery_id));
                
                $admin_address = CustomerAddress::where('user_id',getAdmimId())->first();

                $pickup_address = CustomerAddress::find($request->pickup_id);
                $delivery_address = CustomerAddress::find($request->delivery_id);

                if( $admin_address  && $pickup_address && $delivery_address && $pickup_address->lat && $pickup_address->long && $delivery_address->lat && $delivery_address->long && $admin_address->lat && $admin_address->long){
                    
                    $delivery_charge = isset($detail['DELIVERY_CHARGE']) ? floatval($detail['DELIVERY_CHARGE']) : 1;
                   
                    // Calculate distance-based delivery fee
                    $km1 = haversineGreatCircleDistance(floatval($pickup_address->lat),floatval( $pickup_address->long), floatval($admin_address->lat),floatval($admin_address->long));
                    $km2 = haversineGreatCircleDistance(floatval($admin_address->lat),floatval($admin_address->long), floatval($delivery_address->lat), floatval($delivery_address->long));
                    
                    $delivery_fee = round((floatval($km1) + floatval($km2)) * $delivery_charge, 2);

                    array_push($data, [
                        'name'   => 'Delivery Fee',
                        'amount' => $delivery_fee,
                        'rate'   => 0
                    ]);
                    $amount += $delivery_fee;
                }
            }

            if ($request->discount_id) {
                $promotion = Promotion::where('id', $request->discount_id)
                                      ->where('exp_date', '>', now())
                                      ->first();
            
                if (!$promotion) {
                    return $this->apiResponse('error', 422, __('messages.Invalid promotion or expired.'));
                }
            
                if ($request->amount < $promotion->min_order) {
                    return $this->apiResponse('error', 422, __('messages.This promotion requires a minimum order of') . ' ' . $promotion->min_order);
                }

                // Apply discount if minimum is met
                $discount = ($request->amount * $promotion->discount) / 100;

                array_push($data, [
                    'name'   => 'Total Discount',
                    'amount' => number_format($discount, 2),
                    'rate'   => $promotion->discount
                ]);

                $amount -= $discount;
            }

            $responseData = [
                'taxes'         => $data,
                'total_amount'  => number_format($amount,2)
            ];
            
            $message = trans('messages.SUCCESS.FETCH_DONE');
            return $this->apiResponse('success',200, __('messages.Taxes') .' '. $message, $responseData);
        }catch(\Exception $e){
            return $this->apiResponse('error',500,$e->getMessage());
        }
    }
    /*end method tax */

    /**
     * functionName : create
     * createdDate  : 11-09-2024
     * purpose      : To create the order
    */
    public function create(Request $request){
        try{

            $minPoints = ConfigSetting::where('key', 'maximum_received_point_used_per_order')->value('value');

            $validator = Validator::make($request->all(), [
                'order_services'                => 'required|array',
                'order_services.*.variant_id'   => 'required|exists:service_variants,id',
                'order_services.*.qty'          => 'required',
                'taxes'                         => 'required|array',
                'taxes.*.name'                  => 'required',
                'taxes.*.amount'                => 'required',
                'total_price'                   => 'required',
                'pickup_id'                     => 'required|exists:customer_addresses,id',
                'delivery_id'                   => 'required|exists:customer_addresses,id',
                'pickup_time'                   => 'required',
                'pickup_date'                   => 'required',
                'redeemed_points'               => 'nullable|integer|min:'. $minPoints,
            ]);
            if ($validator->fails()) {
                return $this->apiResponse('error',422,$validator->errors()->first());
            }

            $pickup_address = CustomerAddress::find($request->pickup_id)->makeHidden(['status', 'default', 'type', 'id', 'created_at', 'updated_at','deleted_at','user_id']);
            $delivery_address = CustomerAddress::find($request->delivery_id)->makeHidden(['status', 'default', 'type', 'id', 'created_at', 'updated_at','deleted_at','user_id']);

            //  Create the main order
            $order = Order::create([
                'user_id'           => authId(),
                'status'            => Order::ORDER_REQUESTED,
                'total_amount'      => $request->total_price,
                'pickup_address'    => json_encode($pickup_address),
                'delivery_address'  => json_encode($delivery_address),
                'pickup_time'       => $request->pickup_time,
                'pickup_date'       => $request->pickup_date,
                'promotion_id'      => $request->promotion_id,
                'delivery_code'     => str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT)
            ]);

            if ($request->input('redeemed_points') && $request->redeemed_points > 0) {
                $maxAllowedPoints = (int) ConfigSetting::where('key', 'maximum_received_point_used_per_order')->value('value');
            
                if ($request->redeemed_points > $maxAllowedPoints) {
                    return $this->apiResponse('error', 422, __('messages.You can only redeem up to') .' '. $maxAllowedPoints .' '.  __('messages.points per order.'));
                }
            
                $userReward = RewardPoint::where('user_id', authId())->first();
                $totalAvailablePoints = RewardPoint::where('user_id', authId())->sum('available_points');
            
                if (!$userReward || $totalAvailablePoints < $request->redeemed_points) {
                    return $this->apiResponse('error', 422, __('messages.You do not have enough reward points.'));
                }
            
                // Deduct redeemed points from available_points
                $pointsToDeduct = $request->redeemed_points;
                $userRewards = RewardPoint::where('user_id', authId())
                                ->where('available_points', '>', 0)
                                ->orderBy('created_at', 'asc') // FIFO style deduction
                                ->get();
            
                foreach ($userRewards as $reward) {
                    if ($pointsToDeduct <= 0) break;
            
                    $deduct = min($reward->available_points, $pointsToDeduct);
                    $reward->available_points -= $deduct;
                    $reward->save();
            
                    $pointsToDeduct -= $deduct;
                }
            
                // Save redeemed points to order
                $order->redeemed_points = $request->redeemed_points;
                $order->save();
            
                // Add a record to the redeem_points table
                RedeemPoint::create([
                    'user_id' => authId(),
                    'order_id' => $order->order_id,
                    'order_date' => $order->created_at,
                    'redeemed_points' => $request->redeemed_points,
                    'redeemed_date'  =>  Carbon::now()->format('y-m-d'),
                    'available_points' => $totalAvailablePoints - $request->redeemed_points
                ]);
            }
          
            // Create the order services
            foreach($request->order_services as $value){
                $service_variant = ServiceVariant::find($value['variant_id']);
                if($service_variant){
                    OrderService::create([
                        'order_id'      => $order->id,
                        'service_id'    => $service_variant->service_id,
                        'variant_id'    => $service_variant->variant_id,
                        'amount'        => $service_variant->price,
                        'qty'           => $value['qty'],
                    ]);
                }
            }

            // Create the order taxes
            foreach($request->taxes as $value){
                OrderTax::create([
                    'user_id'       => authId(),
                    'order_id'      => $order->id,
                    'rate'          => $value['rate'],
                    'title'         => $value['name'],
                    'amount'        => $value['amount'],
                ]);
            }

            orderHistory($order->id,Order::ORDER_REQUESTED);

            User::find(getAdmimId())->notify(new OrderCreateNotification($order->user->full_name,$order->id));

            // $this->sendPushNotification('userId_'.authId(),'Order Created Successfully','card has been category by'.userNameById(authId()));

            $message = trans('messages.SUCCESS.CREATE_DONE');
            return $this->apiResponse('success',200, __('messages.Order') . ' ' . $message);
        }catch(\Exception $e){
            return $this->apiResponse('error',500,$e->getMessage());
        }
    }
    /*end method tax */


    /**
     * functionName : getList
     * createdDate  : 05-11-2024
     * purpose      : To get list of the order
    */
    public function getList(Request $request){
        try{
            if($request->filled('type') && $request->filled('type') == 'history'){
                $orders = Order::with('services.service','services.variant')
                ->where('user_id',authId())
                ->whereIN('status',[Order::ORDER_DELIVERED,Order::ORDER_CANCELLED])
                ->orderBy('id','desc')->get()
                ->makeHidden(['service'])
                ->each(function ($order) {
                    $order->order_service       = $order->order_service;
                    $order->qty                 = $order->services()->sum('qty');
                    $order->pickup_address      = json_decode($order->pickup_address);
                    $order->delivery_address    = json_decode($order->delivery_address);
                    $order->service_name        = $order->service ? collect(value: $order->service->pluck('service'))->pluck('name')->unique()->values()->toArray() : [];
                });
            }else{
                $orders = Order::with(['services.service', 'services.variant'])
                ->where('user_id',authId())
                ->whereNotIn('status', [Order::ORDER_DELIVERED, Order::ORDER_CANCELLED])
                ->orderBy('id','desc')->get()
                ->makeHidden(['services'])
                ->each(function ($order) {
                    $order->qty                 = $order->services()->sum('qty');
                    $order->order_service       = $order->order_service;
                    $order->pickup_address      = json_decode($order->pickup_address);
                    $order->delivery_address    = json_decode($order->delivery_address);
                    $order->service_name        = $order->services ? collect(value: $order->services->pluck('service'))->pluck('name')->unique()->values()->toArray() : [];
                });
            }
           
            $message = trans('messages.SUCCESS.FETCH_DONE');
            return $this->apiResponse('success', 200, __('messages.Order') . ' ' . __('messages.List') . ' ' . $message, $orders);
        }catch(\Exception $e){
            return $this->apiResponse('error',500,$e->getMessage());
        }
    }
    /*end method getList */


    /**
     * functionName : view
     * createdDate  : 05-11-2024
     * purpose      : To view the order
    */
    public function view($id){
        try{
           
            $order = Order::with(['services.service', 'services.variant', 'taxes', 'declineReason'])
                ->where('user_id',authId())
                ->where('id', $id)
                ->first();
            if(is_null($order)){
                return $this->apiResponse('success',422, __('messages.Order not found!'));
            }

            // Check if delivery date and delivery time are set
            $deliveryStatus = !empty($order->delivery_date) && !empty($order->delivery_time);
            $ImgName = $order->declineReason && $order->declineReason->image ? $order->declineReason->image : '';

            $ImgUrl = url('storage/images/'.$ImgName);
            $reason = $order->declineReason && $order->declineReason->reason ? $order->declineReason->reason : '';

            $data = [
                'id'                => $order->id,
                'order_id'          => $order->order_id,
                'pickup_address'    => json_decode($order->pickup_address),
                'delivery_address'  => json_decode($order->delivery_address),
                'created_at'        => $order->created_at,
                'pickup_date'       => $order->pickup_date,
                'taxes'             => $order->taxes,
                'total_amount'      => $order->total_amount,
                'qty'               => $order->services()->sum('qty'),
                'status'            => $order->status,
                'sub_total'         => collect($order->services)->map(function($service) {
                                        return $service['qty'] * $service['amount'];
                                    })->sum(),
                'pickup_driver_detail'     => $order->pickupDriver,
                'delivery_driver_detail'   => $order->deliveryDriver,
                'rating'                   => 0,
                'delivery_status'          => $deliveryStatus,
                'delivery_code'            => $order->delivery_code,
                'reason'                   => $reason,
                'ImgUrl'                   => $ImgUrl,
            ];
           
            $message = trans('messages.SUCCESS.FETCH_DONE');
            return $this->apiResponse('success',200, __('messages.Order') . ' ' . $message,$data);
        }catch(\Exception $e){
            return $this->apiResponse('error',500,$e->getMessage());
        }
    }
    /*end method view */

    /**
     * functionName : deliveryAvail
     * createdDate  : 13-03-2025
     * purpose      : To pick delivery date and time
    */
    
    public function delivery(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'order_id'      => 'exists:orders,order_id',
                'delivery_date' => 'required|date',
                'delivery_time' => 'required'
            ]);

            if ($validator->fails()) {
                return $this->apiResponse('error',422,$validator->errors()->first());
            }

            $order = Order::firstWhere('order_id',$request->order_id);

            if(is_null($order)){
                return $this->apiResponse('success',422, __('messages.Order not found!'));
            }

            // Save the delivery date and time to the order
            $order->delivery_date = $request->delivery_date;
            $order->delivery_time = $request->delivery_time;

            if (!empty($order->delivery_date) && !empty($order->delivery_time)) {
                $order->delivery_status = 1;  // Update delivery status to 1
            }
            
            $order->save();

            return $this->apiResponse('success',200, __('messages.Delivery date and time updated successfully.'));
        }catch(\Exception $e){
            return $this->apiResponse('error',500,$e->getMessage());
        }
    }
}
