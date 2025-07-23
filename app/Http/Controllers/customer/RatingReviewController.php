<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\RatingReview;
use App\Traits\SendResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Validator, Auth, App};

class RatingReviewController extends Controller
{
    use SendResponseTrait;
    /**
     * functionName : rating
     * createdDate  : 10-02-2025
     * purpose      : To rate the driver
    */
    public function rating(Request $request){
        try{

            $validator = Validator::make($request->all(), [
                'rating'    => 'required',
                'order_id'  => 'required|exists:orders,order_id',
                'type'      => 'required|in:driver,laundry',
                'driver_id' => 'required_if:type,driver|exists:users,id',
            ]);

            if ($validator->fails()) {
                return $this->apiResponse('error',422,$validator->errors()->first());
            }

            // Find the order
            $order = Order::where('order_id', $request->order_id)->first();
           
            if (!$order) {
                return $this->apiResponse('error', 404, __('messages.Order not found!'));
            }

            // if ($order->user_id !== Auth::id()) {
            //     return $this->apiResponse('error', 403, 'You can only rate your own orders.');
            // }

            // Check if the driver is valid
            if ($order->delivery_driver_id === null) {
                return $this->apiResponse('error', 400, __('messages.No driver assigned to this order.'));
            }

            if ($order->status !== 'Delivered') {
                return $this->apiResponse('error', 400, __('messages.You can only rate after the order is delivered.'));
            }

            // Check if the user has already rated this driver for the order
            $existingRating = RatingReview::where('order_id', $request->order_id)
            ->where('driver_id', $request->driver_id)
            ->where('user_id', authId())
            ->first();

            $user = User::Where('id', $request->driver_id)->first();
            App::setLocale($user->lang);

            if ($existingRating) {
            return $this->apiResponse('error', 400, __('messages.You have already rated this driver for this order.'));
            }

            RatingReview::create([
                'user_id'   => authId(),
                'type'      => $request->type,
                'order_id'  => $request->order_id,
                'rating'    => $request->rating,
                'review'    => $request->review ? $request->review : null,
                'driver_id' => ($request->type == 'driver' && isset($request->driver_id)) ? $request->driver_id : null,
            ]); 

            $message = trans('messages.SUCCESS.ADD_DONE');
            return $this->apiResponse('success',200, __('messages.Driver Rating') .' '. $message);
        }catch(\Exception $e){
            return $this->apiResponse('error',500,$e->getMessage());
        }
    }
    /*end method rating */

    /*
    *createdDate  : 03-04-2025
    *purpose      : To view driver ratings
    */
     public function viewDriverRating(Request $request)
    {
        try {
            // Validate the request parameters
            $validator = Validator::make($request->all(), [
                'order_id'  => 'required|exists:orders,order_id',
                'driver_id' => 'required|exists:users,id',
            ]);

            if ($validator->fails()) {
                return $this->apiResponse('error', 422, $validator->errors()->first());
            }

            $ratings = RatingReview::where('order_id', $request->order_id)
                           ->where('driver_id', $request->driver_id)
                           ->where('type', 'driver')->get(['rating','review']);


            if ($ratings->isEmpty()) {
                return $this->apiResponse('success', 200, __('messages.No ratings found for this driver.'));
            }

            // Calculate average rating for the driver
            $averageRating = $ratings->avg('rating');

            $data = $ratings->count() === 1 ? $ratings->first() : $ratings;

            $message = trans('messages.SUCCESS.FETCH_DONE');
            return $this->apiResponse('success', 200, __('messages.Driver Rating') .' '. $message, $data);
        } catch (\Exception $e) {
            return $this->apiResponse('error', 500, $e->getMessage());
        }
    }

}
