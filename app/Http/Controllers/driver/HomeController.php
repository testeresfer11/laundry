<?php

namespace App\Http\Controllers\driver;

use App\Http\Controllers\Controller;
use App\Models\{ContentPage,ManagefAQ, Order};
use App\Traits\SendResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    use SendResponseTrait;
    /**
     * functionName : contentPages
     * createdDate  : 18-11-2024
     * purpose      : get the content pages data with slug
    */
    public function contentPages($slug){
        try{
            if($slug != 'f-a-q'){
                $data = ContentPage::where('slug',$slug)->select('id','title','slug','content')->first();
            }else{
                $data = ManagefAQ::where('status',1)->select('id','question','answer')->get();
            }

            $message = trans('messages.SUCCESS.FETCH_DONE');
            return $this->apiResponse('success',200, __('messages.Content detail') .' '. $message,$data);
        }catch(\Exception $e){
            return $this->apiResponse('error',400,$e->getMessage());
        }
    }
    /**End method contentPages*/


    /**
     * functionName : index
     * createdDate  : 19-11-2024
     * purpose      : get the detail for the home page
    */
    public function index(Request $request) {
        try {
            $ongoing = $this->getOrders(authId(), now(), '<');
            $upcoming = $this->getOrders(authId(), now(), '>');
    
            $data = [
                'ongoing_orders' => $ongoing,
                'upcoming_orders' => $upcoming
            ];
    
            $message = trans('messages.SUCCESS.FETCH_DONE');
            return $this->apiResponse('success', 200,  __('messages.Home detail') .' '. $message, $data);
        } catch (\Exception $e) {
            return $this->apiResponse('error', 500, $e->getMessage());
        }
    }
    /*end method index */


    private function getOrders($driverId, $date, $operator) {
        $orders = [];
    
        // Pickup Orders
        $pickupOrders = Order::where('pickup_driver_id', $driverId)
                             ->where('order_type', 'online')
                             ->where('pickup_date', $operator, $date)
                             ->whereNull('delivery_driver_id')
                             ->whereIn('status', ['Assign Pickup Driver', 'On the way', 'Reached', 'Pickup'])
                             ->whereHas('user', function ($query) {
                                $query->whereNull('deleted_at');
                               }) 
                             ->get();
    
        foreach ($pickupOrders as $order) {
            $orders[] = [
                'id'                => $order->id,
                'user_name'         => userNameById($order->user_id),
                'user_id'           => $order->user_id,
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
                'status'    => $order->status
            ];
        }
    
        // Delivery Orders
        $deliveryOrders = Order::where('delivery_driver_id', $driverId)
                               ->where('order_type', 'online')
                               ->where('delivery_date', $operator, $date)
                               ->whereNotNull('delivery_driver_id')
                               ->where('status', 'Assign Delivery Driver')
                               ->whereHas('user', function ($query) {
                                    $query->whereNull('deleted_at');
                                })
                               ->get();
        
        foreach ($deliveryOrders as $order) {
            $orders[] = [
                'id'                => $order->id,
                'user_name'         => userNameById($order->user_id),
                'user_id'           => $order->user_id,
                'order_id'          => $order->order_id,
                'delivery_adress'   => json_decode($order->delivery_address),
                'delivery_date'     => $order->delivery_date,
                'qty'               => $order->services()->sum('qty'),
                'delivery_time'     => $order->delivery_time,
                'user_pic'          => userImage($order->user_id),
                'service_type'      => $order->service_name,
                'phone_number'      => ($order->user && $order->user->driverDetail &&  $order->user->driverDetail->phone_number) ?  $order->user->driverDetail->phone_number : null,
                'country_code'      => ($order->user && $order->user->driverDetail &&  $order->user->driverDetail->country_code) ?  $order->user->driverDetail->country_code : null,
                'country_short_code'=> ($order->user && $order->user->driverDetail &&  $order->user->driverDetail->country_short_code) ?  $order->user->driverDetail->country_short_code : null,
            ];
        }
    
        return $orders;
    }
    
}
