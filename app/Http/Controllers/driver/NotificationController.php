<?php

namespace App\Http\Controllers\driver;

use App\Http\Controllers\Controller;
use App\Models\FirebaseNotification;
use App\Models\User;
use App\Traits\SendResponseTrait;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use SendResponseTrait;
    /**
     * functionName : list
     * createdDate  : 18-11-2024
     * purpose      : Get all the notification of the logged in user
    */
    public function list(Request $request){
        try{
            $data = FirebaseNotification::where('user_id', authId())
                    ->orderBy('id','desc')
                    ->get();
            $response = [];
            foreach($data as $value){
                array_push($response,[
                    'id'            => $value->id,
                    'detail'        => json_decode($value->data),
                    'title'         => $value->title,
                    'description'   => $value->body,
                    'created_at'    => $value->created_at
                ]);
            }

            $message = trans('messages.SUCCESS.FETCH_DONE');
            return $this->apiResponse('success',200, __('messages.Notification list') .' '. $message,$response);
        }catch(\Exception $e){
            return $this->apiResponse('error',400,$e->getMessage());
        }
    }
    /**End method list**/

    /**
     * functionName : clear
     * createdDate  : 18-11-2024
     * purpose      : Get all the notification of the logged in user
    */
    public function clear(Request $request){
        try{
            FirebaseNotification::where('user_id', authId())
                ->delete();

            $message = trans('messages.SUCCESS.DELETE_DONE');
            return $this->apiResponse('success',200, __('messages.notifications') .' '. $message);
        }catch(\Exception $e){
            return $this->apiResponse('error',400,$e->getMessage());
        }
    }
    /**End method clear**/
    public function address(Request $request){
        try{
            $response=User::with('userAddress')->where('role_id',1)->first();

            $message = trans('messages.SUCCESS.FETCH_DONE');
             return $this->apiResponse('success',200, __('messages.Notification list') .' '. $message,$response);
        }catch(\Exception $e){
            return $this->apiResponse('error',400,$e->getMessage());
        }
    }
}