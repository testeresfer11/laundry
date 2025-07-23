<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\{DriverDetail, Role,User, RatingReview};
use App\Traits\SendResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Hash,Validator};

class DriverController extends Controller
{
    use SendResponseTrait;
    /**
     * functionName : getList
     * createdDate  : 31-07-2024
     * purpose      : Get the list for all the driver
    */
    public function getList(Request $request){
        try{
            $role = Role::where('name' , config('constants.ROLES.DRIVER'))->first();

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

                    foreach ($users as $user) {
                        $user->avg_rating = RatingReview::where('driver_id', $user->id)->avg('rating') ?? 0;
                    }

            return view("admin.driver.list",compact("users"));
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method getList**/

    /**
     * functionName : add
     * createdDate  : 23-07-2024
     * purpose      : add the driver
    */
    public function add(Request $request){
        try{
            if($request->isMethod('get')){
                return view("admin.driver.add");
            }elseif( $request->isMethod('post') ){
                $validator = Validator::make($request->all(), [
                    'first_name'        => 'required|string|max:255',
                    'last_name'         => 'required|string|max:255',
                    'email'             => 'required|unique:users,email|email:rfc,dns',
                    'profile'           => 'image|max:2048',
                    'gender'            => 'required|in:Male,Female,Other',
                    'vehicle_type_id'   =>  'required|exists:vehicles,id',
                    'password'          => 'required|string|min:8',
                ]);
                
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                if($request->filled('password')){
                    $password = $request->password;
                }else{
                    $password = generateRandomString();
                }
                $role = Role::where('name' , config('constants.ROLES.DRIVER'))->first();
                $user = User::Create([
                    'role_id'           => $role->id,
                    'first_name'        => $request->first_name,
                    'last_name'         => $request->last_name,
                    'email'             => $request->email,
                    'password'          => Hash::make($password),
                    'is_email_verified' => 1,
                    'email_verified_at' => date('Y-m-d H:i:s')
                ]);
                
                $ImgName = null;
                if ($request->hasFile('profile')) {
                    $ImgName = uploadFile($request->file('profile'),'images/');
                }

                DriverDetail::create([
                    'user_id'           => $user->id,
                    'phone_number'      => $request->phone_number ? $request->phone_number : null,
                    'address'           => $request->address ? $request->address :null,
                    'profile'           => $ImgName,
                    'gender'            => $request->gender,
                    'country_code'      => $request->country_code ? $request->country_code :null,
                    'country_short_code'=> $request->country_short_code ? $request->country_short_code :null,
                    'vehicle_type_id'   => $request->vehicle_type_id,
                    'license_number'    => $request->license_number ? $request->license_number :null,
                    'description'       => $request->description ? $request->description :null,
                    'dob'               => $request->dob ? $request->dob :null,
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

                return redirect()->route('admin.driver.list')->with('success','Driver '.config('constants.SUCCESS.ADD_DONE'));
            }
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method add**/

    /**
     * functionName : view
     * createdDate  : 23-07-2024
     * purpose      : Get the detail of specific Driver
    */
    public function view($id){
        try{
            $user = User::findOrFail($id);
            return view("admin.driver.view",compact("user"));
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method view**/

    /**
     * functionName : edit
     * createdDate  : 23-07-2024
     * purpose      : edit the driver detail
    */
    public function edit(Request $request,$id){
        try{
            if($request->isMethod('get')){
                $user = User::find($id);
                return view("admin.driver.edit",compact('user'));
            }elseif( $request->isMethod('post') ){
                $validator = Validator::make($request->all(), [
                    'first_name'        => 'required|string|max:255',
                    'last_name'         => 'required|string|max:255',
                    'email'             => 'required|email:rfc,dns',
                    'profile'           => 'image|max:2048',
                    'vehicle_type_id'   =>  'required|exists:vehicles,id',
                    'status'            => 'required|in:1,0'
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }

                $user = User::find($id);

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
                $ImgName = $user->driverDetail ? $user->driverDetail->profile : null;
                if ($request->hasFile('profile')) {
                    deleteFile($ImgName,'images/');
                    $ImgName = uploadFile($request->file('profile'),'images/');
                }

                DriverDetail::updateOrCreate(['user_id' => $id],[
                    'phone_number'      => $request->phone_number ? $request->phone_number : null,
                    'address'           => $request->address ? $request->address :null,
                    'profile'           => $ImgName,
                    'gender'            => $request->gender,
                    'country_code'      => $request->country_code ? $request->country_code :null,
                    'country_short_code'=> $request->country_short_code ? $request->country_short_code :null,
                    'vehicle_type_id'   => $request->vehicle_type_id,
                    'license_number'    => $request->license_number ? $request->license_number :null,
                    'description'       => $request->description ? $request->description :null,
                    'dob'               => $request->dob ? $request->dob :null,
                ]);
                return redirect()->route('admin.driver.list')->with('success','Driver '.config('constants.SUCCESS.UPDATE_DONE'));
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
            $ImgName = User::find($id)->driverDetail->profile;

            // if($ImgName != null){
            //     deleteFile($ImgName,'images/');
            // }

            // DriverDetail::where('user_id',$id)->delete();
            User::where('id',$id)->delete();

            return response()->json(["status" => "success","message" => "Driver ".config('constants.SUCCESS.DELETE_DONE')], 200);
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

            return response()->json(["status" => "success","message" => "Driver status ".config('constants.SUCCESS.CHANGED_DONE')], 200);
        }catch(\Exception $e){
            return response()->json(["status" =>"error", $e->getMessage()],500);
        }
    }
    /**End method changeStatus**/
    

}
