<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use App\Models\FirebaseNotification;
use App\Traits\SendResponseTrait;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use SendResponseTrait;
    /**
     * functionName : list
     * createdDate  : 11-11-2024
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

            //$message = trans('messages.SUCCESS.FETCH_DONE');
            return $this->apiResponse('success',200, ('Notification list' . config('constants.SUCCESS.FETCH_DONE')),$response);
        }catch(\Exception $e){
            return $this->apiResponse('error',400,$e->getMessage());
        }
    }
    /**End method list**/

    /**
     * functionName : clear
     * createdDate  : 11-11-2024
     * purpose      : Get all the notification of the logged in user
    */
    public function clear(Request $request){
        try{
            FirebaseNotification::where('user_id', authId())
                ->delete();

            //$message = trans('messages.SUCCESS.DELETE_DONE');
            return $this->apiResponse('success',200, ('Notifications' .config('constants.SUCCESS.DELETE_DONE')));
        }catch(\Exception $e){
            return $this->apiResponse('error',400,$e->getMessage());
        }
    }
    /**End method clear**/
}
