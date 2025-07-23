<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\{CustomerAddress, User,UserDetail};
use App\Traits\SendResponseTrait;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\{Auth, Hash, DB, Validator};
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    use SendResponseTrait;
    /**
     * functionName : profile
     * createdDate  : 23-07-2024
     * purpose      : Get and update the profile detail
    */
    public function profile(Request $request){
        try{
            if($request->isMethod('get')){
                $user = User::with('userAddress')->find(authId()); 
                
                return view("admin.profile.detail",compact('user'));
            }elseif( $request->isMethod('post') ){
                $validator = Validator::make($request->all(), [
                    'first_name'    => 'required|string|max:255|alpha',
                    'last_name'     => 'required|string|max:255|alpha',
                    'email'         => 'required|email:rfc,dns|unique:users,email,' . authId(),
                    'phone_number'  => 'nullable|numeric|digits_between:10,15',
                    'profile'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                    'address'       => 'nullable|string|max:255',
                    'city'          => 'nullable|string|max:100',
                    'state'         => 'nullable|string|max:100',
                    'country'       => 'nullable|string|max:100',
                    'type'          => 'nullable|string|in:home,work',
                    'latitude'      => 'nullable',
                    'longitude'     => 'nullable',
                ]);
                
                if ($validator->fails()) {
                    if($request->ajax()){
                        return response()->json(["status" =>"error", 'message' => $validator->errors()->first()],422);
                    }
                }
                User::where('id' , authId())->update([
                    'first_name'        => $request->first_name,
                    'last_name'         => $request->last_name,
                ]);
                
                $ImgName = User::find(authId())->userDetail->profile;
                if ($request->hasFile('profile')) {
                    deleteFile($ImgName,'images/');
                    $ImgName = uploadFile($request->file('profile'),'images/');
                }

                UserDetail::where('user_id',authId())->update([
                    'phone_number'      => $request->phone_number ? $request->phone_number : null,
                    'address'           => $request->address ? $request->address :null,
                    'profile'           => $ImgName,
                ]);
                
                CustomerAddress::updateOrCreate(
                    [
                        'user_id' => authId(),
                        'type'    => $request->type,
                    ],
                    [
                        'address'   => $request->address   ?? null,
                        'city'      => $request->city      ?? null,
                        'state'     => $request->state     ?? null,
                        'country'   => $request->country   ?? null,
                        'lat'       => $request->latitude  ?? null,
                        'long'      => $request->longitude ?? null,
                        'default'   => 1
                    ]
                );
                
                return response()->json(["status" => "success" , "message" => __('messages.Profile detail') .' '. __('messages.SUCCESS.UPDATE_DONE')],200);
            }
        }catch(\Exception $e){
            if($request->ajax()){
                return response()->json(["status" =>"error", $e->getMessage()],500);
            }
            return redirect()->back()->with("error", $e->getMessage(),500);
        }
    }
    /**End method profile**/

    /**
     * functionName : changePassword
     * createdDate  : 23-07-2024
     * purpose      : Get the profile detail
    */
    public function changePassword(Request $request){
        try {
            if($request->isMethod('get')){
                return view("admin.profile.change-password");
            } elseif($request->isMethod('post')) {
                $validator = Validator::make($request->all(), [
                    'current_password'      => 'required|min:8',
                    'password'              => 'required|confirmed|min:8',
                    'password_confirmation' => 'required',
                ]);
                if ($validator->fails()) {
                    if($request->ajax()){
                        return response()->json(["status" => "error", "message" => $validator->errors()->first()], 422);
                    }
                }
    
                $user = User::find(authId());
                if ($user && Hash::check($request->current_password, $user->password)) {
                    // Ensure the new password is not the same as the current password
                    if (Hash::check($request->password, $user->password)) {
                        return response()->json([
                            'status' => 'error',
                            'message' => __('messages.The new password cannot be the same as the current password.')
                        ], 422);
                    }
    
                    $changePassword = User::where("id", $user->id)->update([
                        "password" => Hash::make($request->password_confirmation)
                    ]);
                    if($changePassword){
                        return response()->json(["status" => "success", "message" => __('messages.Password') .' '. __('messages.SUCCESS.CHANGED_DONE')], 200);
                    }
                } else {
                    return response()->json([
                        'status'    => 'error',
                        'message'   => __('messages.Current Password is invalid.')
                    ], 422);
                }
            }
        } catch (\Exception $e) {
            if($request->ajax()){
                return response()->json(["status" => "error", "message" => $e->getMessage()], 500);
            }
            return redirect()->back()->with("error", $e->getMessage(), 500);
        }
    }
    
    /**End method changePassword**/

    /**
     * functionName : login
     * createdDate  : 23-07-2024
     * purpose      : logged in user
    */
    public function login(Request $request){
        try{
            if($request->isMethod('get')){
                if(Auth::check())
                    return redirect()->route('admin.dashboard');
                return view('admin.auth.login');
            }else{
                $validator = Validator::make($request->all(), [
                   'email'     => ['required','email',
                                Rule::exists('users', 'email')->where(function ($query) {
                                    $query->where('status',1);
                                })],
                    'password'  => 'required|min:8'
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->with("error",$validator->errors()->first());
                }
   
                $user = User::where('email', strtolower($request->email))->first();
    
                // if (!$user->email_verified_at)
                //     return redirect()->back()->with("error", 'Email not verified!');
    
                $role = getRoleNameById($user->id);
    
                if($role == config('constants.ROLES.USER'))
                    return redirect()->back()->with( "error", __('messages.Invalid role! You are not a') .' '. $role);
    
                if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->has('remember')))
                {
                    // If remember me is checked, store email in session
                    if ($request->has('remember')) {
                        session(['remembered_email' => $request->email]);
                    } else {
                        session()->forget('remembered_email');
                    }

                    return redirect()->route('admin.dashboard')->with('success', __('messages.SUCCESS.LOGIN'));
                }
                return redirect()->back()->with("error", __('messages.ERROR.INCORRECT_PASSWORD'));
            }
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method login**/

    /**
     * functionName : logout
     * createdDate  : 23-07-2024
     * purpose      : LogOut the logged in user
    */
    // public function logout(Request $request){
    //     try{
    //         Auth::logout();
    //         return redirect()->route('login')->with('success','Logout Successfully!   ');
    //     }catch(\Exception $e){
    //         return redirect()->back()->with("error", $e->getMessage());
    //     }
    // }

    public function logout(Request $request){
        try {
            $email = session('remembered_email');
    
            Auth::logout();
    
            // Only flash the email if it was remembered
            if ($email) {
                $request->session()->flash('logout_email', $email);
            }
    
            return redirect()->route('login')->with('success', __('messages.SUCCESS.LOGOUT_DONE'));
        } catch (\Exception $e) {
            return redirect()->back()->with("error", $e->getMessage());
        }
    }    
    /**End method logout**/


        /**
     * functionName : forgetPassword
     * createdDate  : 04-07-2024
     * purpose      : Forgot password
    */
    public function forgetPassword(Request $request){
        try{
            if($request->isMethod('get')){
                return view('admin.auth.forget-password');
            }else{
                $validator = Validator::make($request->all(), [
                    'email' => ['required','email',
                                Rule::exists('users', 'email')],
                    ]);

                if ($validator->fails()) {
                    return redirect()->back()->with('error',$validator->errors()->first());
                }
                do{
                    $token = str::random(8);;
                }while(DB::table('password_reset_tokens')->where('token',$token)->count());
                DB::table('password_reset_tokens')->where('email',$request->email)->delete();
                DB::table('password_reset_tokens')->insert(['email' => $request->email,'token' => $token,'created_at' => date('Y-m-d H:i:s')]);

                $user = User::where('email',$request->email)->first();
                $url = route('reset-password',['token'=>$token]);
                $template = $this->getTemplateByName('Web_Forget_password');
                \Log::info($template);
                if( $template ) { 
                    $stringToReplace    = ['{{$name}}','{{$token}}'];
                    $stringReplaceWith  = [$user->full_name,$url];
                    $newval             = str_replace($stringToReplace, $stringReplaceWith, $template->template);
                    $emailData          = $this->mailData($user->email, $template->subject, $newval, 'Web_Forget_password', $template->id);
                    $this->mailSend($emailData);
                }
                return redirect()->route('login')->with('success', __('messages.Password reset email has been sent successfully'));
            }
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method forgetPassword**/

        /**
     * functionName : resetPassword
     * createdDate  : 04-07-2024
     * purpose      : Reset your password
    */
    public function resetPassword(Request $request ,$token){
        try{
            if($request->isMethod('get')){
                $reset = DB::table('password_reset_tokens')->where('token',$token)->first();
                if(!$reset)
                    return redirect()->route('login')->with('error', __('messages.ERROR.SOMETHING_WRONG'));
                $startTime = Carbon::parse($reset->created_at);
                $finishTime = Carbon::parse(now());
                $differnce = $startTime->diffInMinutes($finishTime);
               
                if($differnce > 60){
                    return redirect()->route('forget-password')->with('error', __('messages.ERROR.TOKEN_EXPIRED'));
                }
                return view('admin.auth.reset-password',compact('token'));
            }else{

                $validator = Validator::make($request->all(), [
                    "password"              => "required|confirmed|min:8",
                    "password_confirmation" => "required",
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with('error',$validator->errors()->first());
                }

                $reset =  DB::table('password_reset_tokens')->where('token',$token)->first();

                User::where('email',$reset->email)->update(['password'=> Hash::make($request->password)]);
                DB::table('password_reset_tokens')->where('token',$token)->delete();

                return redirect()->route('login')->with('success', __('messages.Password') .' '. __('messages.SUCCESS.UPDATE_DONE'));
            }

        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method resetPassword**/
}
