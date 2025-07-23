<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use App\Traits\SendResponseTrait;
use App\Models\RewardPoint;
use App\Models\ConfigSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class RewardPointsController extends Controller
{
    use SendResponseTrait;
    /**
     * functionName : points earned
     * createdDate  : 22-04-2024
     * purpose      : To view points earned by user
    */
    public function getPointsEarned()
    {
        $userId = auth()->id();

        $totalAvialablePoints = RewardPoint::where('user_id', $userId)->sum('available_points');

        $pointsPerOrder = RewardPoint::with('order')
        ->where('user_id', $userId)
        ->get()
        ->map(function ($rewardPoint) {
            return [
                'order_id' => $rewardPoint->order_id,
                'points_received' => $rewardPoint->received_points,
                'rewarded_at' => $rewardPoint->received_date,
                'points_expired' => $rewardPoint->expired_points ?? null,
                'expired_at' => $rewardPoint->expired_date,
            ];
        });

        $data = [
            'user_id' => $userId,
            'total_points' => $totalAvialablePoints,
            'points_by_order' => $pointsPerOrder,
        ];

        $message = trans('messages.SUCCESS.FETCH_DONE');
        return $this->apiResponse('success',200, __('messages.Reward Points') .' '. $message,$data);   
    }

    /**
     * functionName : history
     * createdDate  : 24-04-2024
     * purpose      : To view history of expired points
    */

    public function history()
    {
        $userId = auth()->id();

        $totalAvialablePoints = RewardPoint::where('user_id', $userId)->sum('available_points');

        $pointsPerOrder = RewardPoint::with('order')
        ->where('user_id', $userId)
        ->get()
        ->filter(function ($rewardPoint) {
            return !is_null($rewardPoint->expired_points);
        })
        ->map(function ($rewardPoint) {
            return [
                'order_id' => $rewardPoint->order_id,
                'points_received' => $rewardPoint->received_points,
                'rewarded_at' => $rewardPoint->received_date,
                'points_expired' => $rewardPoint->expired_points,
                'expired_at' => $rewardPoint->expired_date,
            ];
        })
        ->values(); // reset keys after filtering

        $data = [
            'user_id' => $userId,
            'total_points' => $totalAvialablePoints,
            'points_by_order' => $pointsPerOrder,
        ];

        $message = trans('messages.SUCCESS.FETCH_DONE');
        return $this->apiResponse('success',200, __('messages.History') .' '. $message,$data);
    }

    /**
     * functionName : getPointSettings
     * createdDate  : 24-04-2024
     * purpose      : To view available points and maximum point which can be used 
     *                per order for frontend need.
    */

    public function getPointSettings()
    {
        $userId = auth()->id();

        $totalAvialablePoints = RewardPoint::where('user_id', $userId)->sum('available_points');

        $maximumPointsUsed = (int) ConfigSetting::where('key', 'maximum_received_point_used_per_order')->value('value');

        $pointRate = (int) ConfigSetting::where('key', 'received_point_rate')->value('value');

        $data = [
            'avialable_points' => $totalAvialablePoints,
            'maximum_received_point_used_per_order' => $maximumPointsUsed,
            'rate_of_received_points_per_order' => $pointRate
        ];

        $message = trans('messages.SUCCESS.FETCH_DONE');
        return $this->apiresponse('success', 200, __('messages.Point Settings') .' '. $message,$data);
    }

}
