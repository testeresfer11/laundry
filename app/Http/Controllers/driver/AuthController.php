<?php

namespace App\Http\Controllers\driver;

use App\Http\Controllers\Controller;
use App\Http\Resources\DriverResource;
use App\Http\Resources\UserResource;
use App\Models\{DriverDetail, OtpManagement,User, UserDetail};
use App\Traits\SendResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth,Hash,Validator,App};

class AuthController extends Controller
{
    use SendResponseTrait;

    /**
     * functionName : login
     * createdDate  : 11-09-2024
     * purpose      : login the customer
    */
    public function login(Request $request){
        try{
            $validate = Validator::make($request->all(),[
                        'email'     => 'required|email:rfc,dns|exists:users,email',
                        'password'  => 'required|min:8'
                    ],[
                        'email.exists' => __('messages.The entered email is invalid.'),
                        'email.email'  => __('messages.The email field must be a valid email address'),
                    ]);
            if ($validate->fails()) {
                return $this->apiResponse('error',422,$validate->errors()->first());
            }
                $credentials = $request->only(['email', 'password']);

            $user = User::where('email', $request->email)->withTrashed()->first();

            if(is_null($user)){

                $message = trans('messages.ERROR.SOMETHING_WRONG');
                return $this->apiResponse('error',400, $message);
            }
            if($user->deleted_at != null){

                $message = trans('messages.ERROR.DELETED_ACCOUNT');
                return $this->apiResponse('error',400, $message);
            }

            if($user->status == 0)
                return $this->apiResponse('error',400,__('messages.Account is deactivated by the admin.'));

            if (!Auth::attempt($credentials)) {

                $message = trans('messages.ERROR.INVALID_CREDENTIAL');
                return $this->apiResponse('error',400, $message);
            }
            
            $user                 = $request->user();

            if (getRoleNameById($user->id) != config('constants.ROLES.DRIVER')){

                $message = trans('messages.ERROR.SOMETHING_WRONG');
                return $this->apiResponse('error',400, $message);
            }

            $data = [
                'token'             => $user->createToken('AuthToken')->plainTextToken,
                'id'                => $user->id,
                'uuid'              => $user->uuid,
                'full_name'         => $user->full_name,
                'first_name'        => $user->first_name,
                'last_name'         => $user->last_name,
                'email'             => $user->email,
                'is_verified'       => $user->is_email_verified,
                'phone_number'      => ($user->userDetail && $user->userDetail->phone_number) ? $user->userDetail->phone_number : null,
                'country_code'      => ($user->userDetail && $user->userDetail->country_code) ? $user->userDetail->country_code : null,
                'country_short_code'=> ($user->userDetail && $user->userDetail->country_short_code) ? $user->userDetail->country_short_code : null,
                'profile'           => ($user->userDetail && $user->userDetail->profile) ? $user->userDetail->profile : null,
                'dob'               => ($user->userDetail && $user->userDetail->dob) ? $user->userDetail->dob : null,
                'gender'            => ($user->userDetail && $user->userDetail->gender) ? $user->userDetail->gender : null,
            ];

            $message = trans('messages.SUCCESS.LOGIN');
            return $this->apiResponse('success',200, $message,$data);
        }catch(\Exception $e){
            return $this->apiResponse('error',500,$e->getMessage());
        }
    }
    /*end method login */

    /**
     * functionName : forgetPassword
     * createdDate  : 11-09-2024
     * purpose      : send the email for the forget password
    */
    public function forgetPassword(Request $request){
        try{
            $validate = Validator::make($request->all(),[
                'email'     => 'required|email:rfc,dns|exists:users,email',
            ],[
                'email.exists' => __('messages.The entered email is invalid.'),
                'email.email'  => __('messages.The email field must be a valid email address'),
            ]);
            if ($validate->fails()) {
                return $this->apiResponse('error',422,$validate->errors()->first());
            }
            $user = User::where('email', $request->email)->withTrashed()->first();

            if($user->deleted_at != null){
                $message = trans('messages.ERROR.DELETED_ACCOUNT');
                return $this->apiResponse('error',400, $message);
            }
            
            if (getRoleNameById($user->id) != config('constants.ROLES.DRIVER')){
                $message = trans('messages.ERROR.SOMETHING_WRONG');
                return $this->apiResponse('error',400, $message);
            }

            $this->sendOtp($request->email);

            $message = trans('messages.SUCCESS.SENT_DONE');
            return $this->apiResponse('success',200, __('messages.Password reset email') .' '. $message);
        }catch(\Exception $e){
            return $this->apiResponse('error',500,$e->getMessage());
        }
    }
    /*end method forgetPassword */

    /**
     * functionName : sendOtp
     * createdDate  : 11-09-2024
     * purpose      : send otp email
    */
    public function sendOtp($email){
        try{
            $user = User::where('email',$email)->first();
            do{
                $otp  = rand(1000,9999);
            }while( OtpManagement::where('otp',$otp)->count());
            
            OtpManagement::updateOrCreate(['email' => $user->email],['otp'   => $otp,]);

            $template = $this->getTemplateByName('Forget_password');
            if( $template ) { 
                $stringToReplace    = ['{{$name}}','{{$otp}}'];
                $stringReplaceWith  = [$user->full_name,$otp];
                $newval             = str_replace($stringToReplace, $stringReplaceWith, $template->template);
                $emailData          = $this->mailData($user->email, $template->subject, $newval, 'Forget_password', $template->id);
                $this->mailSend($emailData);
            }

            return true;
        }catch(\Exception $e){
            return $this->apiResponse('error',500,$e->getMessage());
        }
    }
    /*end method sendOtp */

    /**
     * functionName : verifyOtp
     * createdDate  : 11-09-2024
     * purpose      : To verify the email via otp
    */
    public function verifyOtp(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'email'                 => 'required|email:rfc,dns|exists:otp_management,email',
                'otp'                   => 'required|exists:otp_management,otp',
            ],[
                'otp.exists' => __('messages.The selected otp is invalid.')
            ]
        );
            
            if ($validator->fails()) {
                return $this->apiResponse('error',422,$validator->errors()->first());
            }

            $otp = OtpManagement::where(function($query) use($request){
                $query->where('email',$request->email)
                        ->where('otp',$request->otp);
            });
            if($otp->clone()->count() == 0)
                return $this->apiResponse('error',422, __('messages.Please provide valid email address or otp'));

            
            $startTime = Carbon::parse($otp->clone()->first()->updated_at);
            $finishTime = Carbon::parse(now());
            $differnce = $startTime->diffInMinutes($finishTime);
           
            if($differnce > 60){
                return $this->apiResponse('error',400, __('messages.ERROR.TOKEN_EXPIRED'));
            }

            User::where('email',$request->email)->update([
                'is_email_verified' => 1,
                'email_verified_at' => date('Y-m-d H:i:s')
            ]);

            $otp->delete();

            $user = User::where('email', $request->email)->first();

            $message = trans('messages.SUCCESS.VERIFY_DONE');
            return $this->apiResponse('success',200, __('messages.User') .' '. $message);
        }catch(\Exception $e){
            return $this->apiResponse('error',500,$e->getMessage());
        }
    }
    /*end method verifyOtp */

    /**
     * functionName : setNewPassword
     * createdDate  : 11-09-2024
     * purpose      : change the password
    */
    public function setNewPassword(Request $request){
        try{
            $validate = Validator::make($request->all(),[
                'email'                 => 'required|email:rfc,dns|exists:users,email',
                'password'              => 'required|min:8',
                'password_confirmation' => 'required_with:password|same:password',
            ],[
                "password_confirmation.required_with" => "The password confirmation field is required."
            ]);
            if ($validate->fails()) {
                return $this->apiResponse('error',422,$validate->errors()->first());
            }

            User::where('email',$request->email)->update(['password' => Hash::make($request->password)]);
            $user = User::where('email',$request->email)->first();
            
            $message = trans('messages.SUCCESS.CHANGED_DONE');
            return $this->apiResponse('success',200, __('messages.Password') .' '. $message);
        }catch(\Exception $e){
            return $this->apiResponse('error',500,$e->getMessage());
        }
    }
    /*end method setNewPassword */

    /**
     * functionName : logOut
     * createdDate  : 11-09-2024
     * purpose      : Logout the login user
    */
    public function logOut(Request $request){
        try{
            $user =  Auth::guard('api')->user();
            $user->currentAccessToken()->delete();

            $message = trans('messages.SUCCESS.LOGOUT_DONE');
            return $this->apiResponse('success',200, $message);
        }catch(\Exception $e){
            return $this->apiResponse('error',500,$e->getMessage());
        }
    }
    /*end method logOut */

    /**
     * functionName : changePassword
     * createdDate  : 11-09-2024
     * purpose      : change new password
    */
    public function changePassword(Request $request){
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'current_password'      => 'required|min:8',
                'password'              => 'required|min:8',
                'password_confirmation' => 'required_with:password|same:password',
            ], [
                "password_confirmation.required_with" => "The password confirmation field is required."
            ]);
    
            if ($validator->fails()) {
                return $this->apiResponse('error', 422, $validator->errors()->first());
            }
    
            $user = Auth::guard('api')->user();
            if ($user && Hash::check($request->current_password, $user->password)) {
                if (Hash::check($request->password, $user->password)) {
                    return $this->apiResponse('error', 422, __('messages.The new password cannot be the same as the current password.'));
                }
    
                $changePassword = User::where("id", $user->id)->update([
                    "password" => Hash::make($request->password_confirmation)
                ]);
                if ($changePassword) {
                    $message = trans('messages.SUCCESS.CHANGED_DONE');
                    return $this->apiResponse('success', 200, __('messages.Password') .' '. $message);
                }
            } else {
                return $this->apiResponse('error', 422, __('messages.Current Password is invalid.'));
            }
    
        } catch (\Exception $e) {
            return $this->apiResponse('error', 500, $e->getMessage());
        }
    }
    
    /**End method changePassword**/

    /**
     * functionName : profile
     * createdDate  : 18-11-2024
     * purpose      : get and update the logged in driver profile
    */
    public function profile(Request $request)
    {
        try{
            if($request->isMethod('get')){
                $user = Auth::guard('api')->user();
                if(!$user){
                    $message = trans('messages.ERROR.NOT_FOUND');
                    return $this->apiResponse('error',404, __('messages.Profile') .' '. $message);
                }
                $data =  new DriverResource($user);

                $message = trans('messages.SUCCESS.FETCH_DONE');
                return $this->apiResponse('success',200, __('messages.Profile') .' '. $message,$data);
            }elseif( $request->isMethod('post') ){
                $validator = Validator::make($request->all(), [
                    'first_name'            => 'required|string|max:255',
                    'last_name'             => 'required|string|max:255',
                    'profile'               => 'image',
                    'country_short_code'    => 'required',
                    'country_code'          => 'required',
                    'phone_number'          => 'required',
                    'gender'                => 'required|in:Male,Female,Other',
                    'dob'                   => 'required',
                ]);

                if ($validator->fails()) {
                    return $this->apiResponse('error',422,$validator->errors()->first());
                }

                User::where('id' , authId())->update([
                    'first_name'        => $request->first_name,
                    'last_name'         => $request->last_name,
                ]);

                $user = User::find(authId());

                $ImgName = $user->userDetail ? $user->userDetail->profile : '';

                if ($request->hasFile('profile')) {
                    deleteFile($ImgName,'images/');
                    $ImgName = uploadFile($request->file('profile'),'images/');

                }

                DriverDetail::updateOrCreate(['user_id' => authId()],[
                    'phone_number'      => $request->phone_number ? $request->phone_number : '',
                    'country_code'      => $request->country_code ? $request->country_code :'',
                    'country_short_code'=> $request->country_short_code ? $request->country_short_code :'',
                    'profile'           => $ImgName,
                    'gender'            => $request->gender,
                    'dob'               => $request->dob ? $request->dob : null,
                ]);
                
                $user = User::find(authId());

                $data = [
                    'token'             => $request->bearerToken(),
                    'id'                => $user->id,
                    'uuid'              => $user->uuid,
                    'full_name'         => $user->full_name,
                    'first_name'        => $user->first_name,
                    'last_name'         => $user->last_name,
                    'email'             => $user->email,
                    'is_verified'       => $user->is_email_verified,
                    'profile'           => ($user->driverDetail && $user->driverDetail->profile) ? $user->driverDetail->profile : null,
                    'gender'            => ($user->driverDetail && $user->driverDetail->gender) ? $user->driverDetail->gender : null,
                    'dob'               => ($user->driverDetail && $user->driverDetail->dob) ? $user->driverDetail->dob : null,
                    'phone_number'      => ($user->driverDetail && $user->driverDetail->phone_number) ? $user->driverDetail->phone_number : null,
                    'country_code'      => ($user->driverDetail && $user->driverDetail->country_code) ? $user->driverDetail->country_code : null,
                    'country_short_code'=> ($user->driverDetail && $user->driverDetail->country_short_code) ? $user->driverDetail->country_short_code : null,
                ];
                
                $message = trans('messages.SUCCESS.UPDATE_DONE');
                return $this->apiResponse('success',200, __('messages.Profile') .' '. $message,$data);
            }
        }catch(\Exception $e){
            return $this->apiResponse('error',400,$e->getMessage());
        }
    }
     /*end method profile */

    public function getLanguage()
    {
        $user = Auth::guard('api')->user();

        $message = trans('messages.SUCCESS.FETCH_DONE');
        return $this->apiResponse('success', 200, __('messages.Language') .' '. $message);
    }
    
    public function updateLanguage(Request $request)
    {
        $user = Auth::guard('api')->user();
        
        if (!$request->has('lang')) {
            return $this->apiResponse('error', 422, __('messages.ERROR.MISSING_LANGUAGE'));
        }

        // Convert the input to lowercase and map it
        $langInput = $request->lang;
        $languageCode = $langInput ?? 'en'; // default to 'en' if not found

        $updated = $user->update([
            'lang' => $languageCode,
        ]);
    
        if ($updated) {
            $user->refresh(); 
    
            return $this->apiResponse('success', 200, __('messages.Language') . ' ' . __('messages.SUCCESS.UPDATE_DONE'));
        } else {
            return $this->apiResponse('error', 500, __('messages.ERROR.UPDATE_FAILED'));
        }
    }
}

