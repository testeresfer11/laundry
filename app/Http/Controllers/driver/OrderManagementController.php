<?php

namespace App\Http\Controllers\driver;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\RatingReview;
use App\Models\RewardPoint;
use App\Models\ConfigSetting;
use App\Traits\SendResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class OrderManagementController extends Controller
{
    use SendResponseTrait;
    /**
     * functionName : view
     * createdDate  : 19-11-2024
     * purpose      : To view the order
    */
    public function view($id){
        try{
           
            $order = Order::with(['services.service', 'services.variant', 'taxes'])
                    ->where('id', $id)
                    ->first();
            if(is_null($order)){
                return $this->apiResponse('success',422, __('messages.Order not found!'));
            }
            $data = [
                'id'                => $order->id,
                'order_id'          => $order->order_id,
                'pickup_address'    => is_null($order->delivery_driver_id) ? json_decode($order->pickup_address) :json_decode($order->delivery_address) ,
                'pickup_date'       => is_null($order->delivery_driver_id)  ? $order->pickup_date : $order->delivery_date,
                'qty'               => $order->services()->sum('qty'),
                'status'            => $order->status,
                'pickup_time'       => is_null($order->delivery_driver_id)  ? $order->pickup_time : $order->delivery_time,
                'service_name'      => (collect($order->service->pluck('service'))->pluck('name'))->unique()->values()->toArray()
            ];
           
            $message = trans('messages.SUCCESS.FETCH_DONE');
            return $this->apiResponse('success',200, __('messages.Order') .' '. $message,$data);
        }catch(\Exception $e){
            return $this->apiResponse('error',500,$e->getMessage());
        }
    }
    /*end method view */

    /**
     * functionName : changeStatus
     * createdDate  : 20-11-2024
     * purpose      : status change the order
    */
    public function changeStatus($id,$status){
        try{

            orderHistory($id,$status);
                        
            $order = Order::find($id);

            $title = $body ='';

            switch($status){
                case Order::ORDER_REQUESTED:
                    $title = 'Order Requested';
                    $body   = 'Order has been requested successfully.';
                    break;
                    
                case Order::ORDER_ACCEPTED:
                    $title = 'Order Accepted';
                    $body   = 'Order has been accepted successfully.';
                    break;
                    
                case Order::ORDER_CANCELLED:
                    $title = 'Order Cancelled';
                    $body   ='Order has been cancelled.';
                    break;
                    
                case Order::ASSIGN_DRIVER:
                    $title = 'Driver Assigned';
                    $body   ='Pickup driver has been assigned.';
                    break;
                    
                case Order::ORDER_APPROVED:
                    $title = 'Order Approved';
                    $body  = 'Order has been approved.';
                    break;
                    
                case Order::ORDER_PAID:
                    $title = 'Order Paid';
                    $body   = 'Order has been paid successfully.';
                    break;
                
                case Order::ORDER_IN_PROGRESS:
                    $title = 'Order In Progress';
                    $body   = 'Your order is in progress.';
                    break;
                    
                case Order::ORDER_READY:
                    $title = 'Order Ready';
                    $body   = 'Your order is ready for delivery.';
                    break;
                    
                case Order::ORDER_DELIVERED:
                    $title = 'Order Delivered';
                    $body   = 'Order has been delivered successfully.';
                    break;
                    
                case Order::ORDER_COMPLETED:
                    $title = 'Order Completed';
                    $body   = 'Order has been completed successfully.';
                    break;
                    
                case Order::ASSIGN_DELIVERY_DRIVER:
                    $title = 'Delivery Driver Assigned';
                    $body   = 'Delivery driver has been assigned.';
                    break;
                    
                case Order::ON_THE_WAY:
                    $title = 'On the Way';
                    $body   = 'Your order is on the way.';
                    break;
                    
                case Order::REACHED:
                    $title = 'Reached Destination';
                    $body   = 'Driver has reached the destination.';
                    break;
                    
                case Order::PICKUP:
                    $title = 'Pickup';
                    $body   = 'Order is being picked up.';
                    break;
                    
                case Order::RECEIVED:
                    $title = 'Order Received';
                    $body   = 'Order has been received successfully.';
                    break;

                case Order::Rejected:
                    $title = 'Order Rejected';
                    $body   = 'Order has been rejected.';
                    break;
                
                default:
                    $title = 'Unknown Status';
                    $body   = 'Status is unknown.';
                    break;
            }

            // Send notification order rejected
            $this->sendPushNotification( $order->user_id,$title , $body);
            
            $message = trans('messages.SUCCESS.CHANGED_DONE');
            return $this->apiResponse('success',200, __('messages.Order status') .' '. $message,['status' => $status]);
           
        }catch(\Exception $e){
            return $this->apiResponse('error',500,$e->getMessage());
        }
    }
    /**End method changeStatus**/

    /**
     * functionName : history
     * createdDate  : 02-12-2024
     * purpose      : order history
    */
    public function history() {
        try{
            $orders = [];
        
            // Pickup Orders
            $pickupOrders = Order::where('pickup_driver_id', authId())
                                ->where('order_type', 'online')
                                ->whereIn('status', [Order::RECEIVED, Order::ORDER_APPROVED, Order::ORDER_CANCELLED, Order::ORDER_REJECTED, Order::ORDER_PAID, Order::ORDER_IN_PROGRESS, Order::ORDER_READY, Order::ASSIGN_DELIVERY_DRIVER, Order::ORDER_DELIVERED])
                                ->orderBy('created_at', 'desc')
                                ->get();
        
            foreach ($pickupOrders as $order) {
                $orders[] = [
                    'id'                => $order->id,
                    'user_id'           => $order->user_id,
                    'user_name'         => userNameById($order->user_id),
                    'order_id'          => $order->order_id,
                    'pickup_adress'     => json_decode($order->pickup_address),
                    'pickup_date'       => $order->pickup_date,
                    'pickup_time'       => $order->pickup_time,
                    'user_pic'          => userImage($order->user_id),
                    'service_type'      => $order->service_name,
                    'qty'               => $order->services()->sum('qty'),
                    'phone_number'      => ($order->user && $order->user->driverDetail &&  $order->user->driverDetail->phone_number) ?  $order->user->driverDetail->phone_number : null,
                    'country_code'      => ($order->user && $order->user->driverDetail &&  $order->user->driverDetail->country_code) ?  $order->user->driverDetail->country_code : null,
                    'country_short_code'=> ($order->user && $order->user->driverDetail &&  $order->user->driverDetail->country_short_code) ?  $order->user->driverDetail->country_short_code : null,
                ];
            }
        
            // Delivery Orders
            $deliveryOrders = Order::where('delivery_driver_id', authId())
                                ->where('order_type', 'online')
                                ->whereIn('status', [Order::ORDER_DELIVERED])
                                ->orderBy('created_at', 'desc')
                                ->get();
            
            foreach ($deliveryOrders as $order) {

                $ratingReview = RatingReview::where('order_id', $order->order_id)
                                ->where('driver_id', $order->delivery_driver_id)
                                ->first();

                $orders[] = [
                    'id'                => $order->id,
                    'user_id'           => $order->user_id,
                    'user_name'         => userNameById($order->user_id),
                    'order_id'          => $order->order_id,
                    'delivery_adress'     => json_decode($order->delivery_address),
                    'delivery_date'       => $order->delivery_date,
                    'delivery_time'       => $order->delivery_time,
                    'qty'               => $order->services()->sum('qty'),
                    'user_pic'          => userImage($order->user_id),
                    'service_type'      => $order->service_name,
                    'phone_number'      => ($order->user && $order->user->driverDetail &&  $order->user->driverDetail->phone_number) ?  $order->user->driverDetail->phone_number : null,
                    'country_code'      => ($order->user && $order->user->driverDetail &&  $order->user->driverDetail->country_code) ?  $order->user->driverDetail->country_code : null,
                    'country_short_code'=> ($order->user && $order->user->driverDetail &&  $order->user->driverDetail->country_short_code) ?  $order->user->driverDetail->country_short_code : null,
                    'rating'            => $ratingReview ? $ratingReview->rating : null,
                    'review'            => $ratingReview ? $ratingReview->review : null,
                ];
            }
        
            $message = trans('messages.SUCCESS.FETCH_DONE');
            return $this->apiResponse('success', 200, __('messages.Order history') .' '. $message, $orders);
        }catch(\Exception $e){
            return $this->apiResponse('error',500,$e->getMessage());
        }
    }

    /** 
    * functionName : Verify Delivery Code
    * createdDate  : 09-04-2025
    * purpose      : Verify the Delivery Code of order
    **/

    public function verify_delivery_code(Request $request){

        $validator = Validator::make($request->all(), [
            'order_id'      => 'required|exists:orders,order_id',
            'delivery_code' => 'required|numeric|digits:4',
        ]);

        if ($validator->fails()) {
            return $this->apiResponse('error',400,$validator->errors()->first());
        }
    
        // Find the order by order_id
        $order = Order::where('order_id', $request->order_id)->first();
    
        if (!$order) {
            return $this->apiResponse('error', 404, __('messages.Order not found!'));
        }
    
        // Verify if the delivery code matches
        if ($order->delivery_code === $request->delivery_code) {
            // Mark the order as delivered
            $order->status = 'Delivered'; 
            $order->save();
            
            orderHistory($order->id, Order::ORDER_DELIVERED);

            $receivedPoints = (int) ConfigSetting::where('key', 'received_point_per_order')->value('value');
            $expiryDays = (int) ConfigSetting::where('key', 'expiry_period')->value('value');
            $expiryPoint = (int) ConfigSetting::where('key', 'expiry_points')->value('value');
            $remainingPoints = $receivedPoints - $expiryPoint;
            $expiredDate = now()->addDays($expiryDays);

            $existingReward = RewardPoint::where('user_id', $order->user_id)
                                      ->where('order_id', $order->order_id)
                                      ->first();

            if ($existingReward) {
                return $this->apiresponse('error', 400, __('messages.Points have already been rewarded for this order.'));
            }

            $this->updateExpiredPoints($order->user_id);

            if (!$existingReward){
                RewardPoint::create([
                    'user_id'          => $order->user_id,
                    'order_id'         => $order->order_id,
                    'order_date'       => $order->created_at,
                    'received_points'  => $receivedPoints,
                    'received_date'    => now(),
                    'expired_date'     => $expiredDate,
                    'available_points' => $receivedPoints
                ]);
            }
            

            $this->sendPushNotification( $order->user_id, __('messages.Reward Points Credited!'), __('messages.You have received') . ' ' . $receivedPoints . ' ' . __('messages.points for your order') . ' ' . $order->order_id);           

            return $this->apiResponse('success', 200, __('messages.Delivery code verified. Order marked as Delivered.'));
        }
        
        // If the delivery code does not match
        return $this->apiResponse('error', 400, __('messages.Invalid delivery code'));

    }

    /** 
    * functionName : updateExpiredPoints
    * createdDate  : 25-04-2025
    * purpose      : Update existing expired points and expired date
    **/

    public function updateExpiredPoints($userId)
    {
        $expiredRewards = RewardPoint::where('user_id', $userId)
            ->where('expired_date', '<=', now())
            ->where('available_points', '>', 0)
            ->get();

        $expiredPoint = (int) ConfigSetting::where('key', 'expiry_points')->value('value');
        $expiryPeriod = (int) ConfigSetting::where('key', 'expiry_period')->value('value');

        foreach ($expiredRewards as $reward) {
            $reward->available_points = max(0, $reward->available_points - $expiredPoint);
            $reward->expired_date = Carbon::parse($reward->expired_date)->addDays($expiryPeriod);
            $reward->save();
        }
    }
}
