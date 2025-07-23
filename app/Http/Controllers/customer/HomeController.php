<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use App\Models\{ContentPage,ManagefAQ, Order, Promotion, Service, Tax};
use App\Traits\SendResponseTrait;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    use SendResponseTrait;
    /**
     * functionName : index
     * createdDate  : 27-08-2024
     * purpose      : get the detail for the home page
    */
    public function index(Request $request){
        try{
            
            $services = Service::where('status',1)->select('id','image','name')->get();

            //$promotion_ids = Tax::where('user_id',authId())
            //->pluck('promotion_id')->toArray();
            
            $banners = Promotion::where('status',1)
                        ->where('exp_date', '>', now())->get();
                        //->whereNotIn('id',$promotion_ids)->get();
            
            $orders = Order::where('user_id',authId())
                ->whereNotIN('status',[Order::ORDER_REQUESTED,Order::ORDER_DELIVERED,Order::ORDER_CANCELLED])
                ->orderBy('id','desc')->get()
                ->makeHidden(['service'])
                    ->each(function ($order) {
                        $order->pickup_address      = json_decode($order->pickup_address);
                        $order->delivery_address    = json_decode($order->delivery_address);
                        $order->service_name        = (collect($order->service->pluck('service'))->pluck('name'))->unique()->values()->toArray();
                    });
            $data = [
                'services'          => $services,
                'banners'           => $banners,
                'active_orders'     => $orders
            ];

            $message = trans('messages.SUCCESS.FETCH_DONE');
            return $this->apiResponse('success',200, __('messages.Home detail') .' '. $message,$data );
        }catch(\Exception $e){
            return $this->apiResponse('error',500,$e->getMessage());
        }
    }
    /*end method index */


    /**
     * functionName : contentPages
     * createdDate  : 15-10-2024
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
            return $this->apiResponse('success',200, 'Content detail '. $message,$data);
        }catch(\Exception $e){
            return $this->apiResponse('error',400,$e->getMessage());
        }
    }
   

    function getPromotions($id = null)
    {
        $query = Promotion::query();
    
        if ($id != null) {
            $query->where('id', $id);
        }
        //$promotion_ids = Order::where('user_id',authId())
                        //->pluck('promotion_id')->toArray();
                        
        $promotions = $query->where('status',1)->where('exp_date', '>', now())->get();
    
        return $this->apiResponse('success', 200, __('messages.Promotions Fetched!'), $promotions);
    }
    
}
