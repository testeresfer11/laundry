<?php

namespace App\Http\Controllers;

use App\Traits\SendResponseTrait;
use Illuminate\Http\Request;
use App\Models\RewardPoint;
use App\Models\ConfigSetting;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class RewardPointController extends Controller
{
    use SendResponseTrait;

    /** 
    * functionName : Cron Job for points expiration
    * createdDate  : 22-04-2025
    * purpose      : Checks expiry date after every 24 hours and apply expire points functionality
    **/

    public function updateExpiredPointsCron()
    {
        $expiredRecords = [];
        $expiryPoints = (int) ConfigSetting::where('key', 'expiry_points')->value('value');
        
        $today = Carbon::now()->format('Y-m-d');
        
        $rewardPointsList = RewardPoint::whereDate('expired_date', '<=', $today)->get();
        
        foreach ($rewardPointsList as $rewardPoints) {
            $originalAvailable = $rewardPoints->available_points;

            $rewardPoints->expired_points += $expiryPoints;
            $available_points = max(0, $originalAvailable - $expiryPoints);
            $rewardPoints->available_points = $available_points;

            $rewardPoints->save();

            $expiredRecords[] = [
                'user_id' => $rewardPoints->user_id,
                'expired_points' => $expiryPoints,
                'available_points' => $available_points,
                'expiry_date' => $rewardPoints->expired_date,
            ];
        }

        if (empty($expiredRecords)) {
            Log::info('No reward points expired today.');
        } else {
            Log::info('Expired points updated for users.', $expiredRecords);
        }
    
        return $this->apiresponse('success', 200, __('messages.Reward points expiration job executed.'));
    }
}
