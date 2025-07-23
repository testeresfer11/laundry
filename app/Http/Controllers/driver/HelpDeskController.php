<?php

namespace App\Http\Controllers\driver;

use App\Http\Controllers\Controller;
use App\Models\{HelpDesk,QueryResponse, User};
use App\Notifications\HelpdeskMessageNotification;
use App\Traits\SendResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HelpDeskController extends Controller
{
    use SendResponseTrait;
    /**
     * functionName : add
     * createdDate  : 18-11-2024
     * purpose      : add the ticket
    */
    public function add(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'title'             => 'required|string|max:255',
            ]);
            if ($validator->fails()) {
                return $this->apiResponse('error',422,$validator->errors()->first());
            }
               
            HelpDesk::Create([
                'user_id'           => authId(),
                'title'             => $request->title,
                'description'       => $request->description ? $request->description : '',
            ]);

            $message = trans('messages.SUCCESS.ADD_DONE');
            return $this->apiResponse('success',200, __('messages.Ticket') .' '. $message);
        }catch(\Exception $e){
            return $this->apiResponse('error',400,$e->getMessage());
        }
    }
    /**End Method Add */

    /**
     * functionName : list
     * createdDate  : 18-11-2024
     * purpose      : get the ticket listing
    */
    public function list(){
        try{
            $data = HelpDesk::where('user_id',authId())->orderBy('id','desc')->get();

            $message = trans('messages.SUCCESS.FETCH_DONE');
            return $this->apiResponse('success',200, __('messages.Ticket list') .' '. $message,$data);
        }catch(\Exception $e){
            return $this->apiResponse('error',400,$e->getMessage());
        }
    }
    /*end method list */

    /**
     * functionName : response
     * createdDate  : 18-11-2024
     * purpose      : Add the response
    */
    public function response(Request $request,$id){
        try{
            if($request->isMethod('get')){
                $response = HelpDesk::with('response')->where('id',$id)->first();
                $data = [];
                array_push($data,[
                    'response'      => $response->title,
                    'created_at'    => $response->created_at,
                    'user_id'       => $response->user_id,
                    'user_name'     => userNameById($response->user_id),
                    'description'   => $response->description,
                    'type'          => 1,
                    'status'        => $response->status
                ]);
                foreach($response->response as $value){
                    array_push($data,[
                        'created_at'    => $value->created_at,
                        'user_id'       => $value->user_id,
                        'user_name'     => userNameById($value->user_id),
                        'description'   => null,
                        'type'          => $value->type,
                        'response'      => $value->response,
                        'response_image'=> $value->response_image ? url('storage/images/' . $value->response_image) : null,
                    ]);
                }

                $message = trans('messages.SUCCESS.FETCH_DONE');
                return $this->apiResponse('success',200, __('messages.Response') .' '. $message,$data);
            }elseif( $request->isMethod('post') ){

                $validator = Validator::make($request->all(), [
                    'response'        => 'required',
                    // 'type'            => 'required|in:1'
                ]);
                if ($validator->fails()) {
                    return $this->apiResponse('error',422,$validator->errors()->first());
                }
                HelpDesk::where('id' , $id)->update(['status'=> 'In Progress']);
               $response =  QueryResponse::create(['help_id'=>$id,'user_id' => authId(),'response' => $request->response]);
               //'type' => $request->type
               
                $data['created_at']    = $response->created_at;
                $data['user_id']       = $response->user_id;
                $data['user_name']     = userNameById($response->user_id);
                $data['description']   = null;
                $data['type']          = $response->type;
                $data['response']      = $response->response;
                
                //User::find(getAdmimId())->notify(new HelpdeskMessageNotification(userNameById(authId()),$helpdesk->title));

                $message = trans('messages.SUCCESS.ADD_DONE');
                return $this->apiResponse('success',200, __('messages.Reply') .' '. $message,$data);
            }
        }catch(\Exception $e){
            return $this->apiResponse('error',400,$e->getMessage());
        }
    }
    /**End method response**/

    /**
     * functionName : changeStatus
     * createdDate  : 18-11-2024
     * purpose      : Update the ticket status done mark as complete
    */
    public function changeStatus($id){
        try{
            HelpDesk::where('id',$id)->update(['status' => 'Completed']);

            $message = trans('messages.SUCCESS.CHANGED_DONE');
            return $this->apiResponse('success',200, __('messages.Ticket status') .' '. $message);
        }catch(\Exception $e){
            return $this->apiResponse('error',400,$e->getMessage());
        }
    }
    /**End method changeStatus**/
}
