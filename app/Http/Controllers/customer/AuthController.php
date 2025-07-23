<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\{Country, OtpManagement, Role,User,UserDetail, Wallet};
use App\Traits\SendResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash,Validator, Http, App};
use Illuminate\Support\Str;

class AuthController extends Controller
{
    use SendResponseTrait;
    
    /**
     * functionName : register
     * createdDate  : 19-08-2024
     * purpose      : Register the customer
    */
    public function register(Request $request){
        try{
            
            $validator = Validator::make($request->all(), [
                'first_name'            => 'required|max:255',
                'last_name'             => 'required|max:255',
                'email'                 => ['required', 'email:rfc,dns', 'unique:users,email,NULL,id,deleted_at,NULL'],
                'password'              => 'required|min:8',
                'password_confirmation' => 'required_with:password|same:password',
                'country_short_code'    => 'required',
                'country_code'          => 'required',
                'phone_number'          => 'required',
                'dob'                   => 'required',
                'gender'                => 'required|in:Male,Female,Other',
                'lang'                  => 'nullable',
            ],[
                "password_confirmation.required_with" => "The password confirmation field is required."
            ]);
            if ($validator->fails()) {
                return $this->apiResponse('error',422,$validator->errors()->first());
            }

            $email = $request->input('email');
            $password = $request->input('password');
        
            $existingUser = User::where('email', $request->email)->withTrashed()->first();

            if ($existingUser) {
                if ($existingUser->trashed()) {
                        return $this->apiResponse('error', 409, __('messages.This email is already registered, but the account is temporary deleted.'));
                } else {
                    return $this->apiResponse('error', 500, __('messages.Failed to send restoration request. Please try again later.'));
                }
            }

            $role = Role::where('name' , config('constants.ROLES.CUSTOMER'))->first();
            $user =  User::create([
                        'role_id'           => $role->id,
                        'first_name'        => $request->first_name,
                        'last_name'         => $request->last_name,
                        'email'             => $request->email,
                        'password'          => Hash::make($request->password),
                        'lang'              => 'en',
                    ]);


            $ImgName = '';
            if ($request->hasFile('profile')) {
                $ImgName = uploadFile($request->file('profile'),'images/');

            }

            if($user){

                Wallet::create([
                    'user_id' => $user->id,
                    'amount'  => 0
                ]);

                do{
                    $otp  = rand(1000,9999);
                }while( OtpManagement::where('otp',$otp)->count());
                
                OtpManagement::updateOrCreate(['email' => $user->email],['otp'   => $otp,]);

                $template = $this->getTemplateByName('Otp_Verification');
                if( $template ) { 
                    $stringToReplace    = ['{{$name}}','{{$otp}}'];
                    $stringReplaceWith  = [$user->full_name,$otp];
                    $newval             = str_replace($stringToReplace, $stringReplaceWith, $template->template);
                    $emailData          = $this->mailData($user->email, $template->subject, $newval, 'Otp_Verification', $template->id);
                    \Log::info('Template content:', ['subject' => $template->subject, 'body' => $newval]);
                    \Log::info('Email data:', $emailData);
                    $this->mailSend($emailData);
                }

                UserDetail::updateOrCreate(['user_id'=> $user->id],[
                    'country_code'          => $request->country_code ? $request->country_code :'',
                    'country_short_code'    => $request->country_short_code ? $request->country_short_code :'',
                    'phone_number'          => $request->phone_number ? $request->phone_number : '',
                    'gender'                => $request->gender ? $request->gender : '',
                    'dob'                   => $request->dob ? $request->dob : '',
                    'profile'               => $ImgName,
                ]);

                $message = trans('messages.SUCCESS.RESGISTERD_DONE');
                return $this->apiResponse('success',200, __('messages.Customer') .' '. $message);
            }
        }catch(\Exception $e){
            return $this->apiResponse('error',500,$e->getMessage(), $e->getLine());
        }
    }
    /*end method register */

    /**
     * functionName : login
     * createdDate  : 19-08-2024
     * purpose      : login the customer
    */
    public function login(Request $request){
        try{
            $validate = Validator::make($request->all(),[
                        'email'     => ['required', 'email:rfc,dns'],
                        'password'  => 'required'
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
            
            $email = $request->input('email');
            $password = $request->input('password');

            $existingUser = User::where('email', $request->email)->withTrashed()->first();

            if ($existingUser){
                if (Hash::check($password, $existingUser->password)) {
                    if ($existingUser->trashed()) {
                        return $this->apiResponse('error', 409, __('messages.This email is already registered, but the account is temporary deleted.'));
                    }
                }
                else{
                    $message = trans('messages.ERROR.INVALID_CREDENTIAL');
                    return $this->apiResponse('error',400, $message);
                }
            }

            if($user->deleted_at != null){
                $message = trans('messages.ERROR.DELETED_ACCOUNT');
                return $this->apiResponse('error',400, $message);
            }

            if($user && $user->is_email_verified == 0){
                if($user){
                    //$this->sendOtp($user->email);
                }
                $data = [
                   'is_verified'       => $user->is_email_verified
                ];

                $message = trans('messages.SUCCESS.VERIFY_LOGIN');
                return $this->apiResponse('success',200, __('messages.User') .' '. $message,$data);
            }
            if($user->status == 0)
                return $this->apiResponse('error',400, __('messages.Account is deactivated by the admin.'));

            if (!Auth::attempt($credentials)) {
                $message = trans('messages.ERROR.INCORRECT_PASSWORD');
                return $this->apiResponse('error',400, $message);
            }
            
            $user                 = $request->user();

            $message = trans('messages.ERROR.SOMETHING_WRONG');

            if (getRoleNameById($user->id) != config('constants.ROLES.CUSTOMER')){
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
                'profile'           => ($user->userDetail && $user->userDetail->profile) ? $user->userDetail->profile : null,
                'phone_number'      => ($user->userDetail && $user->userDetail->phone_number) ? $user->userDetail->phone_number : null,
                'country_code'      => ($user->userDetail && $user->userDetail->country_code) ? $user->userDetail->country_code : null,
                'country_short_code'=> ($user->userDetail && $user->userDetail->country_short_code) ? $user->userDetail->country_short_code : null,
                'gender'            => ($user->userDetail && $user->userDetail->gender) ? $user->userDetail->gender : null,
                'dob'               => ($user->userDetail && $user->userDetail->dob) ? $user->userDetail->dob : null,
            ];

            return $this->apiResponse('success',200, __('messages.SUCCESS.LOGIN'),$data);
        }catch(\Exception $e){
            return $this->apiResponse('error',500,$e->getMessage());
        }
    }
    /*end method login */

    /**
     * functionName : forgetPassword
     * createdDate  : 19-08-2024
     * purpose      : send the email for the forget password
    */
    public function forgetPassword(Request $request){
        try{
            $validate = Validator::make($request->all(),[
                'email'     => 'required|email:rfc,dns|exists:users,email',
                'type'      => 'required|in:resend_otp,forget_password'
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
            
            if (getRoleNameById($user->id) != config('constants.ROLES.CUSTOMER')){
                $message = trans('messages.ERROR.SOMETHING_WRONG');
                return $this->apiResponse('error',400, $message);
            }

            $this->sendOtp($request->email);

            if($request->type == 'resend_otp'){
                $message = trans('messages.SUCCESS.SENT_DONE');
                return $this->apiResponse('success',200, __('messages.OTP') .' '. $message);
            }

            $message = trans('messages.SUCCESS.SENT_DONE');
            return $this->apiResponse('success',200, __('messages.Password reset email') .' '. $message);
        }catch(\Exception $e){
            return $this->apiResponse('error',500,$e->getMessage());
        }
    }
    /*end method forgetPassword */

    /**
     * functionName : sendOtp
     * createdDate  : 19-08-2024
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
     * createdDate  : 19-08-2024
     * purpose      : To verify the email via otp
    */
    public function verifyOtp(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'email'                 => 'required|email:rfc,dns|exists:otp_management,email',
                'otp'                   => 'required|exists:otp_management,otp',
                'type'                  => 'required|in:otp_verify,forget_password'
            ],[
                'otp.exists' => __('messages.The selected otp is invalid.')
            ]);
            if ($validator->fails()) {
                return $this->apiResponse('error',422,$validator->errors()->first());
            }

            // $otp = OtpManagement::where(function($query) use($request){
            //     $query->where('email',$request->email)
            //             ->where('otp',$request->otp);
            // });
            // if($otp->clone()->count() == 0)
            //     return $this->apiResponse('error',422,'Please provide valid email address or otp');

            $otp = OtpManagement::where('email', $request->email)
                ->where('otp', $request->otp)
                ->first();

            if (!$otp) {
                return $this->apiResponse('error', 422, __('messages.Please provide valid email address or otp'));
            }
            
            $startTime = Carbon::parse($otp->updated_at);
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

            $user = User::with('userDetail')->where('email', $request->email)->first();
            
            if (!$user) {
                return $this->apiResponse('error', 404, __('messages.User not found'));
            }
            
            $template = $this->getTemplateByName('email_verification_success');
                if( $template ) { 
                    $stringToReplace    = ['{{$name}}'];
                    $stringReplaceWith  = [$user->full_name];
                    $newval             = str_replace($stringToReplace, $stringReplaceWith, $template->template);
                    $emailData          = $this->mailData($user->email, $template->subject, $newval, 'email_verification_success', $template->id);
                    $this->mailSend($emailData);
                }

            if($request->type == 'otp_verify'){
                
                
                Auth::login($user);
                $data = [
                    'token'             => $user->createToken('AuthToken')->plainTextToken,
                    'id'                => $user->id,
                    'uuid'              => $user->uuid,
                    'full_name'         => $user->full_name,
                    'first_name'        => $user->first_name,
                    'last_name'         => $user->last_name,
                    'email'             => $user->email,
                    'is_verified'       => $user->is_email_verified,
                    'profile'           => $user->userDetail ? $user->userDetail->profile : null,
                    'phone_number'      => $user->userDetail ? $user->userDetail->phone_number : null,
                    'country_code'      => $user->userDetail ? $user->userDetail->country_code : null,
                    'country_short_code' => $user->userDetail ? $user->userDetail->country_short_code : null,
                    'gender'            => $user->userDetail ? $user->userDetail->gender : null,
                    'dob'               => $user->userDetail ? $user->userDetail->dob : null,
                ];

                $message = trans('messages.SUCCESS.LOGIN');
                return $this->apiResponse('success',200, $message, $data);
            }  
            
            $message = trans('messages.SUCCESS.VERIFY_DONE');
            return $this->apiResponse('success',200, __('messages.User') .' '. $message);
        }catch(\Exception $e){
            return $this->apiResponse('error',500,$e->getMessage());
        }
    }
    /*end method verifyOtp */

    /**
     * functionName : setNewPassword
     * createdDate  : 19-06-2024
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
    /*end method changePassword */

    /**
     * functionName : logOut
     * createdDate  : 21-09-2024
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
     * createdDate  : 30-05-2024
     * purpose      : change new password
    */
    public function changePassword(Request $request){
        try {
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
     * createdDate  : 30-05-2024
     * purpose      : get and update the logged in user profile
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
                $data =  new UserResource($user);

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
                    'lang'                  => 'nullable',
                ]);

                if ($validator->fails()) {
                    return $this->apiResponse('error',422,$validator->errors()->first());
                }

                $languageMap = [
                    'english'    => 'en',
                    'spanish'    => 'es',
                    'french'     => 'fr',
                    'german'     => 'de',
                    'arabic'     => 'ar',
                    'hindi'      => 'hi',
                    'indonesian' => 'id',
                    'italian'    => 'it',
                    'dutch'      => 'nl',
                    'portuguese' => 'pt',
                    'russian'    => 'ru',
                    'filipino'   => 'tl',
                    'urdu'       => 'ur'
                ];
                
                // Convert the input to lowercase and map it
                $langInput = strtolower($request->lang);
                $languageCode = $languageMap[$langInput] ?? 'en'; // default to 'en' if not found

                User::where('id' , authId())->update([
                    'first_name'        => $request->first_name,
                    'last_name'         => $request->last_name,
                    'lang'              => $languageCode,
                ]);

                $user = User::find(authId());

                $ImgName = $user->userDetail ? $user->userDetail->profile : '';

                if ($request->hasFile('profile')) {
                    deleteFile($ImgName,'images/');
                    $ImgName = uploadFile($request->file('profile'),'images/');

                }

                UserDetail::updateOrCreate(['user_id' => authId()],[
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
                    'lang'              => $user->lang,
                    'is_verified'       => $user->is_email_verified,
                    'profile'           => ($user->userDetail && $user->userDetail->profile) ? $user->userDetail->profile : null,
                    'gender'            => ($user->userDetail && $user->userDetail->gender) ? $user->userDetail->gender : null,
                    'dob'               => ($user->userDetail && $user->userDetail->dob) ? $user->userDetail->dob : null,
                    'phone_number'      => ($user->userDetail && $user->userDetail->phone_number) ? $user->userDetail->phone_number : null,
                    'country_code'      => ($user->userDetail && $user->userDetail->country_code) ? $user->userDetail->country_code : null,
                    'country_short_code'=> ($user->userDetail && $user->userDetail->country_short_code) ? $user->userDetail->country_short_code : null,
                ];
                
                $message = trans('messages.SUCCESS.UPDATE_DONE');
                return $this->apiResponse('success',200, __('messages.Profile') .' '. $message,$data);
            }
        }catch(\Exception $e){
            return $this->apiResponse('error',400,$e->getMessage());
        }
    }
     /*end method profile */

     function countries($id = null)
     {
         $query = Country::query();
     
         if ($id != null) {
             $query->where('id', $id);
         }
     
         $countries = $query->get()->map(function($country) {
             return [
                 'id'        => $country->id,
                 'iso'       => $country->iso,
                 'name'      => $country->name,
                 'nicename'  => $country->nicename,
                 'iso3'      => $country->iso3,
                 'numcode'   => $country->numcode,
                 'phonecode' => $country->phonecode,
             ];
         });
     
         return $this->apiResponse('success', 200, __('messages.Countries Fetched!'), $countries);
     }

    /**
     * functionName : locationUpdate
     * createdDate  : 26-11-2024
     * purpose      :user location update 
    */
    public function locationUpdate(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'user_id'       => 'required|exists:users,id',
                'latitude'      => 'required',
                'longitude'     => 'required',
            ]);
    
            \Log::info($request->all());
            if ($validator->fails()) {
                return $this->apiResponse('error', 422, $validator->errors()->first());
            }
    
            User::where('id',$request->user_id)->update([
                'live_latitude'     => $request->latitude,
                'live_longitude'    => $request->longitude,
            ]);

            $user = User::where('email',$request->email)->first();

            $message = trans('messages.SUCCESS.UPDATE_DONE');
            return $this->apiResponse('success',200, __('messages.Location') .' '. $message);
        }catch(\Exception $e){
            \Log::info($e->getMessage());
            return $this->apiResponse('error',500,$e->getMessage());
        }
    }
    /*end method locationUpdate */

    /** 
    * functionName : deleteUser
    * createdDate  : 05-04-2025
    * purpose      : for soft deleting user.
    **/

    public function deleteUser(Request $request)
    {
        try {
            $user = User::where('id',auth()->id())->first();

            if (!$user) {
                return $this->apiResponse('error', 401, __('messages.Unauthorized'));
            }

            if ($user->id !== $request->user()->id) {
                return $this->apiResponse('error', 403, __('messages.You cannot delete this account'));
            }

            $user->delete();

            return $this->apiResponse('success', 200, __('messages.Your account has been deleted successfully'));
        } catch (\Exception $e) {
            return $this->apiResponse('error',500,$e->getMessage());
        }
    }

    /** 
    * functionName : restoration_request
    * createdDate  : 07-04-2025
    * purpose      : for calling sendrestoration function.
    **/

    public function restoration_request(Request $request){
        
        $email = $request->input('email');
        
        $existingUser = User::where('email', $email)->withTrashed()->first();

        if($existingUser){
            
            $this->sendRestorationRequestToAdmin($existingUser);

            return $this->apiResponse('success', 200, __('messages.Your account restoration request has been sent to the admin successfully.'));
        } 
        else {
            return $this->apiResponse('error', 404, __('messages.Incorrect email or password.'));
        }
    }

    /** 
    * functionName : sendRestorationRequestToAdmin
    * createdDate  : 07-04-2025
    * purpose      : For sending restoration request on mail of Admin
    **/

    private function sendRestorationRequestToAdmin($existingUser)
    {
        $admin = User::where('role_id', 1)->first();

        if ($admin) {
            $adminEmail = $admin->email;
        } else {
            $adminEmail = 'admin01@yopmail.com';
        }

        $message = "User with email " . $existingUser->email . " is trying to register again. Please restore the account.";

        $data = [
            'to' => $adminEmail,
            'subject' => 'User Account Restoration Request',
            'userName' => $existingUser->full_name,
            'email' => $existingUser->email,
            'message' => $message
        ];

        $body = view('email.restoration_request', $data)->render();

        \Mail::send('email.sendEmail', ['body' => $body], function($message) use($data) {
            $message->to($data['to'])  
                    ->subject($data['subject']); 
        });
    }

    /** 
    * functionName : Social Login API
    * createdDate  : 14-04-2025
    * purpose      : For social login e.g., google,apple,facebook etc.,
    **/

    public function handleSocialLogin(Request $request){
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'first_name' => 'nullable|string',
            'last_name'  => 'nullable|string',
            'email'      => 'nullable|email',
            'provider'   => 'required|string',
            'provider_id'=> 'required|string',
        ]);
    
        // Return validation errors if any
        if ($validator->fails()) {
            return $this->apiResponse('error', 422, $validator->errors()->first());
        }
    
        // // Check if the user already exists by email
        // $user = User::where('provider_id',$request->provider_id)->first();
        // if ($user) {
        //     // Generate access token for existing user
        //     $accessToken = $user->createToken('AuthToken')->plainTextToken;
    
        //     // Return successful login response
        //     return $this->apiResponse('success', 200, 'Login successful', [
        //         'access_token' => $accessToken,
        //         'id' => $user->user_id,
        //         'name' => $user->first_name . ' ' . $user->last_name,
        //         'email' => $user->email,
        //     ]);
        // }
    
        // If no user exists, check for a user with the same provider and provider_id
        $user = User::where('provider', $request->provider)
                    ->where('provider_id', $request->provider_id)
                    ->first();

                    
        $provider = strtolower($request->provider);

        if ($user) {    
            // Generate access token for existing user
            $accessToken = $user->createToken('AuthToken')->plainTextToken;
    
            // Return successful login response
            return $this->apiResponse('success', 200, __('messages.Login successful'), [
                'token'             => $accessToken,
                'id'                => $user->id,
                'uuid'              => $user->uuid,
                'full_name'         => $user->full_name,
                'first_name'        => $user->first_name,
                'last_name'         => $user->last_name,
                'email'             => $user->email,
                'is_verified'       => $user->is_email_verified,
                'profile'           => ($user->userDetail && $user->userDetail->profile) ? $user->userDetail->profile : null,
                'phone_number'      => ($user->userDetail && $user->userDetail->phone_number) ? $user->userDetail->phone_number : null,
                'country_code'      => ($user->userDetail && $user->userDetail->country_code) ? $user->userDetail->country_code : null,
                'country_short_code'=> ($user->userDetail && $user->userDetail->country_short_code) ? $user->userDetail->country_short_code : null,
                'gender'            => ($user->userDetail && $user->userDetail->gender) ? $user->userDetail->gender : null,
                'dob'               => ($user->userDetail && $user->userDetail->dob) ? $user->userDetail->dob : null,
            ]);
        }
        
        if($provider !== 'apple'){
            $existingUser = User::where('email', $request->email)->withTrashed()->first();

            App::setLocale($existingUser->lang);

            if ($existingUser) {
                if ($existingUser->trashed()) {
                        return $this->apiResponse('error', 409, __('messages.This email is already registered, but the account is temporarily deleted.'));
                } else {
                    return $this->apiResponse('error', 500, __('messages.Failed to send restoration request. Please try again later.'));
                }
            }
        } else {
            $existingUser = User::where('provider_id', $request->provider_id)->withTrashed()->first();

            App::setLocale($existingUser->lang);

            if ($existingUser) {
                if ($existingUser->trashed()) {
                        return $this->apiResponse('error', 409, __('messages.This account is temporary deleted.'));
                } else {
                    return $this->apiResponse('error', 500, __('messages.Failed to send restoration request. Please try again later.'));
                }
            }
        }

        $role = Role::where('name' , config('constants.ROLES.CUSTOMER'))->first();

        // If user does not exist, create a new one
        $user = User::create([
            'role_id'           => $role->id,
            'first_name'        => $request->first_name ?? null,
            'last_name'         => $request->last_name ?? null,
            'email'             => $request->email ?? null,
            'email_verified_at' => Carbon::now(),
            'password'          => Hash::make(Str::random(10)),
            'provider'          => $request->provider,
            'provider_id'       => $request->provider_id,
        ]);

        // Generate access token for new user
        $accessToken = $user->createToken('AuthToken')->plainTextToken;
    
        // Return successful registration response
        return $this->apiResponse('success', 200, __('messages.Registration successful'), [
            'token'             => $accessToken,
                'id'                => $user->id,
                'uuid'              => $user->uuid,
                'full_name'         => $user->full_name,
                'first_name'        => $user->first_name,
                'last_name'         => $user->last_name,
                'email'             => $user->email,
                'is_verified'       => $user->is_email_verified,
                'profile'           => ($user->userDetail && $user->userDetail->profile) ? $user->userDetail->profile : null,
                'phone_number'      => ($user->userDetail && $user->userDetail->phone_number) ? $user->userDetail->phone_number : null,
                'country_code'      => ($user->userDetail && $user->userDetail->country_code) ? $user->userDetail->country_code : null,
                'country_short_code'=> ($user->userDetail && $user->userDetail->country_short_code) ? $user->userDetail->country_short_code : null,
                'gender'            => ($user->userDetail && $user->userDetail->gender) ? $user->userDetail->gender : null,
                'dob'               => ($user->userDetail && $user->userDetail->dob) ? $user->userDetail->dob : null,
        ]);
    }

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
