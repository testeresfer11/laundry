<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\{CustomerAddress,User};
use App\Traits\SendResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerAddressController extends Controller
{
    use SendResponseTrait;

    /**
     * functionName : getList
     * createdDate  : 16-08-2024
     * purpose      : Get the list for all the customer
    */
    public function getList(Request $request,$id){
        try{

            $addresses = CustomerAddress::where('user_id',$id)->orderBy("id","desc")->paginate(10);
            $user = User::find($id); 

            if($request->ajax()){
                return $this->apiResponse("success",200,"customer address list ".config('constants.SUCCESS.FETCH_DONE'),CustomerAddress::where('user_id',$id)->where('status',1)->orderBy("id","desc")->pluck('address','id'));
            }
            return view("admin.customer.address.list",compact("addresses","user"));
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method getList**/

    /**
     * functionName : add
     * createdDate  : 16-08-2024
     * purpose      : add the address of user
    */
    public function add(Request $request,$id){
        try{
            if($request->isMethod('get')){
                $user = User::find($id);
                return view("admin.customer.address.add",compact('user'));
            }elseif( $request->isMethod('post') ){
                $validator = Validator::make($request->all(), [
                    'address'       => 'required|string|max:255',
                    'type'          => 'required|in:home,work'
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
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
                CustomerAddress::create([
                    'user_id'           => $id,
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
                return redirect()->route('admin.customer.address.list',['id' => $id])->with('success','Customer address '.config('constants.SUCCESS.ADD_DONE'));
            }
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method add**/

        /**
     * functionName : delete
     * createdDate  : 23-07-2024
     * purpose      : Delete the user by id
    */
    public function delete($id){
        try{
           CustomerAddress::find($id)->delete();

            return response()->json(["status" => "success","message" => "Customer address ".config('constants.SUCCESS.DELETE_DONE')], 200);
        }catch(\Exception $e){
            return response()->json(["status" =>"error", $e->getMessage()],500);
        }
    }
    /**End method delete**/

    /**
     * functionName : changeStatus
     * createdDate  : 16-08-2024
     * purpose      : Update the address status
    */
    public function changeStatus(Request $request){
        try{
            
            $validator = Validator::make($request->all(), [
                'id'        => 'required',
                "status"    => "required|in:0,1",
            ]);
            if ($validator->fails()) {
                if($request->ajax()){
                    return response()->json(["status" =>"error", "message" => $validator->errors()->first()],422);
                }
            }
            CustomerAddress::where('id',$request->id)->update(['status' => $request->status]);

            return response()->json(["status" => "success","message" => "Customer address status ".config('constants.SUCCESS.CHANGED_DONE')], 200);
        }catch(\Exception $e){
            return response()->json(["status" =>"error", $e->getMessage()],500);
        }
    }
    /**End method changeStatus**/

    /**
     * functionName : edit
     * createdDate  : 16-08-2024
     * purpose      : edit the address of user
    */
    public function edit(Request $request,$id){
        try{
            if($request->isMethod('get')){
                $address = CustomerAddress::find($id);
                $user = User::find($address->user_id);
                return view("admin.customer.address.edit",compact('address','user'));
            }elseif( $request->isMethod('post') ){
                $validator = Validator::make($request->all(), [
                    'address'       => 'required|string|max:255',
                    'type'          => 'required|in:home,work',
                    'address_id'    => 'required'
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
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

                CustomerAddress::where('id',$request->address_id)->update([
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
                return redirect()->route('admin.customer.address.list',['id' => $id])->with('success','Customer address'.config('constants.SUCCESS.UPDATE_DONE'));
            }
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method edit**/
}
