<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use App\Models\CustomerAddress;
use App\Traits\SendResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerAddressController extends Controller
{
    use SendResponseTrait;
    /**
     * functionName : getList
     * createdDate  : 03-09-2024
     * purpose      : get the list of User Addresses
    */
    public function getList(){
        try{
            $data = CustomerAddress::where('user_id',authId())
                        ->orderBy('id','desc')->get();
            
            $message = trans('messages.SUCCESS.FETCH_DONE');
            return $this->apiResponse('success',200, __('messages.Customer address') .' '. $message,$data );
        }catch(\Exception $e){
            return $this->apiResponse('error',500,$e->getMessage());
        }
    }
    /*end method getList */

    /**
     * functionName : add
     * createdDate  : 03-09-2024
     * purpose      : add the customer Addresses
    */
    public function add( Request $request ){
        try{
           $validator = Validator::make($request->all(), [
               'address'       => 'required|string|max:255',
               'type'          => 'required|in:home,work'
           ]);
           if ($validator->fails()) {
               return $this->apiResponse('error',422,$validator->errors()->first());
           }
           $default = 0;
           if($request->filled('default')){
               if($request->default == "on"){
                   CustomerAddress::where('user_id',authId())->update(['default' => 0]);
                   $default = 1;
               }
           }
           if(CustomerAddress::where('user_id',authId())->count() == 0){
               $default = 1;
           }
           CustomerAddress::create([
               'user_id'           => authId(),
               'landmark'          => $request->landmark ? $request->landmark : null,
               'house_no'          => $request->house_no ? $request->house_no : null,
               'address'           => $request->address ? $request->address :null,
               'city'              => $request->city ? $request->city :null,
               'state'             => $request->state ? $request->state :null,
               'country'           => $request->country ? $request->country :null,
               'lat'               => $request->lat ? $request->lat :null,
               'long'              => $request->long ? $request->long :null,
               'type'              => $request->type,
               'default'           => $default,
           ]);

           $message = trans('messages.SUCCESS.ADD_DONE');
           return $this->apiResponse('success',200, __('messages.Customer address') .' '. $message);
       }catch(\Exception $e){
           return $this->apiResponse('error',500,$e->getMessage());
       }
   }

    /* End Method Add */

    /**
     * functionName : edit
     * createdDate  : 03-09-2024
     * purpose      : edit the address of user
    */
    public function edit(Request $request,$id = null){
        try{
            if($request->isMethod('get')){
                $address = '';
                if($id ){
                    $address = CustomerAddress::find($id);
                }else{
                    $address = CustomerAddress::where('user_id',authId())->first();
                }

                $message = trans('messages.SUCCESS.FETCH_DONE');
                return $this->apiResponse('success',200, __('messages.Customer address') .' '. $message,$address);
            }elseif( $request->isMethod('post') ){
                $validator = Validator::make($request->all(), [
                    'address'       => 'required|string|max:255',
                    'type'          => 'required|in:home,work',
                ]);
                if ($validator->fails()) {
                    return $this->apiResponse('error',422,$validator->errors()->first());
                }
                $default = 0;
                if($request->filled('default')){
                    if($request->default == "on"){
                        CustomerAddress::where('user_id',$id)->update(['default' => 0]);
                        $default = 1;
                    }
                }

                if(CustomerAddress::where('user_id',$id)->count() == 0){
                    $default = 1;
                }

                CustomerAddress::where('id',$id)->update([
                    'address'           => $request->address ? $request->address :null,
                    'landmark'          => $request->landmark ? $request->landmark : null,
                    'house_no'          => $request->house_no ? $request->house_no : null,
                    'city'              => $request->city ? $request->city :null,
                    'state'             => $request->state ? $request->state :null,
                    'country'           => $request->country ? $request->country :null,
                    'lat'               => $request->lat ? $request->lat :null,
                    'long'              => $request->long ? $request->long :null,
                    'type'              => $request->type,
                    'default'           => $default,
                ]);

                $message = trans('messages.SUCCESS.UPDATE_DONE');
                return $this->apiResponse('success',200, __('messages.Customer address') .' '. $message);
            }
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method edit**/

    /**
     * functionName : delete
     * createdDate  : 03-09-2024
     * purpose      : Delete the user by id
    */
    public function delete($id){
        try{
            if(CustomerAddress::find($id) == null){
                return $this->apiResponse('error',422, __('messages.Please provide valid address id'));
            }
           CustomerAddress::find($id)->delete();

           $message = trans('messages.SUCCESS.DELETE_DONE');
           return $this->apiResponse('success',200, __('messages.Customer address') .' '. $message);
        }catch(\Exception $e){
            return $this->apiResponse('error',500,$e->getMessage());
        }
    }
    /**End method delete**/
}
