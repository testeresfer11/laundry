<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use App\Traits\SendResponseTrait;
use App\Models\Order;
use App\Models\RewardPoint;
use App\Models\RedeemPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RedeemPointController extends Controller
{
    use SendResponseTrait;

    /**
     * functionName : redeemedPoint
     * createdDate  : 24-04-2024
     * purpose      : Insert Redeem points API
    */

    public function redeemedPoint(Request $request)
    {
        try {
            $user = Auth::user();

            $redeemedPoints = (int) $request->input('redeemed_points');
            $orderId = $request->input('order_id');

            $order = Order::where('order_id', $request->order_id)->first();

            $existingRedemption = RedeemPoint::where('user_id', $user->id)
                                      ->where('order_id', $orderId)
                                      ->first();

            if ($existingRedemption) {
                return $this->apiresponse('error', 400, __('messages.Points have already been redeemed for this order.'));
            }

            $totalAvailablePoints = RewardPoint::where('user_id', $user->id)->sum('available_points');

            if ($redeemedPoints > $totalAvailablePoints) {
                return $this->apiresponse('error', 400, __('messages.You do not have enough available points.'));
            }

            if (!$orderId) {
                return $this->apiresponse('error', 400, __('messages.Order Id is not Valid.'));
            }
        
            $pointsToDeduct = $redeemedPoints;
            $rewardPoints = RewardPoint::where('user_id', $user->id)
                                    ->where('available_points', '>', 0)
                                    ->orderBy('id') // or however you prioritize deduction
                                    ->get();

            foreach ($rewardPoints as $point) {
                if ($pointsToDeduct <= 0) break;

                $deduct = min($point->available_points, $pointsToDeduct);
                $point->available_points -= $deduct;
                $point->save();

                $pointsToDeduct -= $deduct;
            }

            RedeemPoint::create([
                'user_id' => $user->id,
                'order_id' => $orderId,
                'order_date' => $order->created_at,
                'redeemed_points' => $redeemedPoints,
                'redeemed_date'  =>  Carbon::now()->format('y-m-d'),
                'available_points' => $totalAvailablePoints - $redeemedPoints
            ]);

            

            return $this->apiresponse('success', 200, __('messages.Points redeemed successfully!'));
        } catch (\Exception $e) {
            return $this->apiresponse('error', 500, $e->getMessage());
        }
    }

    /**
     * functionName : getRedeemedPoints
     * createdDate  : 25-04-2024
     * purpose      : View List of Redeemed Points API
    */

    public function getRedeemedPoints()
    {
        try {
            $user = Auth::user();

            $totalAvailablePoints = RewardPoint::where('user_id', $user->id)->sum('available_points');

            $pointsPerOrder = RedeemPoint::with('order')
            ->where('user_id', $user->id)
            ->get()
            ->map(function ($redeemPoint) {
                return [
                    'order_id' => $redeemPoint->order_id,
                    'points_redeemed' => $redeemPoint->redeemed_points,
                    'redeemed_at' => $redeemPoint->redeemed_date,
                ];
            });

            $data = [
                'user_id' => $user->id,
                'total_points' => $totalAvailablePoints,
                'points_by_order' => $pointsPerOrder,
            ];

            $message = trans('messages.SUCCESS.FETCH_DONE');
            return $this->apiresponse('success', 200, __('messages.Redeemed Points') .' '. $message, $data);
        } catch (\Exception $e) {
            return $this->apiresponse('error', 500, $e->getMessage());
        }
    }

}