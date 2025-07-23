<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\{Order, Role,User,UserDetail,RewardPoint};
use App\Traits\SendResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Hash,Validator};

class CustomerController extends Controller
{
    use SendResponseTrait;
    /**
     * functionName : getList
     * createdDate  : 23-07-2024
     * purpose      : Get the list for all the customer
    */
    public function getList(Request $request){
        try{
            $role = Role::where('name' , config('constants.ROLES.CUSTOMER'))->first();

            $users = User::where("role_id",$role->id)
                    ->when($request->filled('search_keyword'),function($query) use($request){
                        $query->where(function($query) use($request){
                            $query->where('first_name','like',"%$request->search_keyword%")
                                ->orWhere('last_name','like',"%$request->search_keyword%")
                                ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$request->search_keyword}%"])
                                ->orWhere('email','like',"%$request->search_keyword%");
                        });
                    })
                    ->when($request->filled('status'),function($query) use($request){
                        $query->where('status',$request->status);
                    })->orderBy("id","desc")->paginate(10);

            return view("admin.customer.list",compact("users"));
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method getList**/

    /**
     * functionName : add
     * createdDate  : 23-07-2024
     * purpose      : add the customer
    */
    public function add(Request $request){
        try{
            if($request->isMethod('get')){
                return view("admin.customer.add");
            }elseif( $request->isMethod('post') ){
                $validator = Validator::make($request->all(), [
                    'first_name'    => 'required|string|max:255',
                    'last_name'     => 'required|string|max:255',
                    'email'         => 'required|unique:users,email|email:rfc,dns',
                    'profile'       => 'image|max:2048',
                    'gender'        => 'required|in:Male,Female,Other',
                    'status'        => 'required|in:0,1',
                    'password'      => 'required|string|min:8',
                    'phone_number'  => 'required|numeric',
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                if($request->filled('password')){
                    $password = $request->password;
                }else{
                    $password = generateRandomString();
                }
                $role = Role::where('name' , config('constants.ROLES.CUSTOMER'))->first();
                $user = User::Create([
                    'role_id'           => $role->id,
                    'first_name'        => $request->first_name,
                    'last_name'         => $request->last_name,
                    'email'             => $request->email,
                    'password'          => Hash::make($password),
                    'is_email_verified' => 1,
                    'email_verified_at' => date('Y-m-d H:i:s'),
                    'status'            => $request->status
                ]);

                //$ImgName = User::find(authId())->userDetail->profile;
                //$ImgName = 'faces/user_dummy.png';
                if ($request->hasFile('profile')) {
                    $ImgName = uploadFile($request->file('profile'), 'images/');
                }
                UserDetail::create([
                    'user_id'               => $user->id,
                    'phone_number'          => $request->phone_number ? $request->phone_number : null,
                    'address'               => $request->address ? $request->address :null,
                    'profile'               => $ImgName,
                    'gender'                => $request->gender,
                    'country_code'          => $request->country_code ? $request->country_code :null,
                    'country_short_code'    => $request->country_short_code ? $request->country_short_code :null,
                    'address2'              => $request->address2 ? $request->address2 :null,
                    'dob'                   => $request->dob ? $request->dob : null,
                ]);


                $template = $this->getTemplateByName('Account_detail');
                if( $template ) { 
                    $stringToReplace    = ['{{$name}}','{{$password}}','{{$email}}'];
                    $stringReplaceWith  = [$user->full_name,$password ,$user->email];
                    $newval             = str_replace($stringToReplace, $stringReplaceWith, $template->template);
                    $emailData          = $this->mailData($user->email, $template->subject, $newval, 'Account_detail', $template->id);
                    $this->mailSend($emailData);
                }

                // User::find(authId())->notify(new UserNotification($user->full_name));

                return redirect()->route('admin.customer.list')->with('success','Customer '.config('constants.SUCCESS.ADD_DONE'));
            }
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method add**/

    /**
     * functionName : view
     * createdDate  : 23-07-2024
     * purpose      : Get the detail of specific user
    */
    public function view($id){
        try{
            $user = User::findOrFail($id);

            $orders  = Order::where('order_type','online')
                            ->where('user_id',$id)
                            ->latest()->get();
            $totalPoints = RewardPoint::where('user_id', $user->id)->sum('available_points');
            
            return view("admin.customer.view",compact("user","orders","totalPoints"));
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method view**/

    /**
     * functionName : edit
     * createdDate  : 23-07-2024
     * purpose      : edit the customer detail
    */
    public function edit(Request $request,$id){
        try{
            if($request->isMethod('get')){
                $user = User::find($id);
                return view("admin.customer.edit",compact('user'));
            }elseif( $request->isMethod('post') ){
                $validator = Validator::make($request->all(), [
                    'first_name'    => 'required|string|max:255',
                    'last_name'     => 'required|string|max:255',
                    'email'         => 'required|email:rfc,dns',
                    'profile'       => 'image|max:2048',
                    'status'        => 'required|in:0,1'
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                
                $user       = User::find($id);
                $password   = $user->password;
                if($request->filled('password')){
                    $password = Hash::make($request->password);
                    $template = $this->getTemplateByName('password_change');
                    if( $template ) { 
                        $stringToReplace    = ['{{$name}}','{{$password}}','{{$email}}'];
                        $stringReplaceWith  = [$user->full_name,$request->password ,$user->email];
                        $newval             = str_replace($stringToReplace, $stringReplaceWith, $template->template);
                        $emailData          = $this->mailData($user->email, $template->subject, $newval, 'password_change', $template->id);
                        $this->mailSend($emailData);
                    }
                }
                User::where('id' , $id)->update([
                    'first_name'        => $request->first_name,
                    'last_name'         => $request->last_name,
                    'status'            => $request->status,
                    'password'          => $password
                ]);

                $user = User::find($id);
                $ImgName = $user->userDetail ? $user->userDetail->profile : null;
                if ($request->hasFile('profile')) {
                    deleteFile($ImgName,'images/');
                    $ImgName = uploadFile($request->file('profile'),'images/');

                }

                UserDetail::updateOrCreate(['user_id' => $id],[
                    'phone_number'      => $request->phone_number ? $request->phone_number : null,
                    'address'           => $request->address ? $request->address :null,
                    'profile'           => $ImgName,
                    'country_code'      => $request->country_code ? $request->country_code :null,
                    'country_short_code'=> $request->country_short_code ? $request->country_short_code :null,
                    'address2'          => $request->address2 ? $request->address2 :null,
                    'dob'               => $request->dob ? $request->dob :null,
                ]);
                return redirect()->route('admin.customer.list')->with('success','Customer '.config('constants.SUCCESS.UPDATE_DONE'));
            }
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method edit**/

    /**
     * functionName : delete
     * createdDate  : 23-07-2024
     * purpose      : Delete the user by id
    */
    public function delete($id){
        try{
            $ImgName = User::find($id)->userDetail->profile;

            // if($ImgName != null){
            //     deleteFile($ImgName,'images/');
            // }

            // UserDetail::where('user_id',$id)->delete();
            User::where('id',$id)->delete();

            return response()->json(["status" => "success","message" => "Customer ".config('constants.SUCCESS.DELETE_DONE')], 200);
        }catch(\Exception $e){
            return response()->json(["status" =>"error", $e->getMessage()],500);
        }
    }
    /**End method delete**/

    /**
     * functionName : changeStatus
     * createdDate  : 23-07-2024
     * purpose      : Update the user status
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
            User::where('id',$request->id)->update(['status' => $request->status]);

            return response()->json(["status" => "success","message" => "Customer status ".config('constants.SUCCESS.CHANGED_DONE')], 200);
        }catch(\Exception $e){
            return response()->json(["status" =>"error", $e->getMessage()],500);
        }
    }
    /**End method changeStatus**/

}
