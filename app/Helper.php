<?php

use App\Models\{ConfigSetting, Country,Order,OrderHistory,Role,Service,ServiceVariant,User,Variant,Vehicle};
use App\Models\RatingReview;
use Illuminate\Support\Facades\{Auth, Storage};
use Carbon\Carbon;

/**
 * functionName : authId
 * createdDate  : 23-07-2024
 * purpose      : Get the id of the logged in user
 */
if(!function_exists('authId')){
    function authId(){
        if(Auth::check() && Auth::user())
            return Auth::user()->id;
        else if(Auth::guard('api')->user())
            return Auth::guard('api')->user()->id;
        return null;
    }
}
/** end methd authId */

/*
 * functionName : getRoleNameById
 * createdDate  : 23-07-2024
 * purpose      : Get the role name with the user Id
*/
if(!function_exists('getRoleNameById')){
    function getRoleNameById($id){
        $user = User::find($id);
        if($user){
            return $user->role->name;
        }
        return null;
    }
}
/** end methd getRoleNameId */

/*
 * functionName : userNameById
 * createdDate  : 23-07-2024
 * purpose      : Get user name by id
*/
if(!function_exists('userNameById')){
    function userNameById($id){
        $user = User::find($id);
        if($user){
            return ($user->first_name ?? '' ).' '.($user->last_name ?? '') ;
        }
        return '';
    }
}
/** end methd userNameById */


/*
 * functionName : convertDate
 * createdDate  : 23-07-2024
 * purpose      : convert the date format 
*/
if(!function_exists('convertDate')){
    function convertDate($date, $format = 'd M Y, h:i A'){
        if($date){
            $date = Carbon::parse($date);
            $formattedDate = $date->format($format);
            return $formattedDate;
        }
        return 'N/A';
    }
}
/** end methd convertDate */

/*
 * functionName : UserImageById
 * createdDate  : 23-07-2024
 * purpose      : To get the userImage by id
*/
if(!function_exists('userImageById')){
    function userImageById($id){
       $user =  User::find($id);
       if($user){
        if(isset($user->userDetail) && !is_null($user->userDetail->profile) && file_exists(public_path('storage/images/'.$user->userDetail->profile) ))
            return asset('storage/images/' . $user->userDetail->profile);
        else if ((isset($user->driverDetail) && !is_null($user->driverDetail->profile) && file_exists(public_path('storage/images/'.$user->driverDetail->profile) )))
            return asset('storage/images/' . $user->driverDetail->profile);
        else
            return asset('admin/images/faces/user_dummy.png') ;
       }
       return asset('admin/images/faces/user_dummy.png') ;
    }
}
/** end methd userImageById */

/*
 * functionName : UserImage
 * createdDate  : 02-12-2024
 * purpose      : To get the userImage by id
*/
if(!function_exists('userImage')){
    function userImage($id){
       $user =  User::find($id);
       if($user){
        if(isset($user->userDetail) && !is_null($user->userDetail->profile) && file_exists(public_path('storage/images/'.$user->userDetail->profile) ))
            return  $user->userDetail->profile;
        else if ((isset($user->driverDetail) && !is_null($user->driverDetail->profile) && file_exists(public_path('storage/images/'.$user->driverDetail->profile) )))
            return  $user->driverDetail->profile;
        else
            return null ;
       }
       return null;
    }
}
/** end methd userImage */

/*
 * functionName : replyDiffernceCalculate
 * createdDate  : 23-07-2024
 * purpose      : To get the differnce of the post uploading
*/
if(!function_exists('replyDiffernceCalculate')){
    function replyDiffernceCalculate($date){
        $startDate = Carbon::now();
        $endDate = Carbon::parse($date);
        $formattedDate = $startDate->diff($endDate);
        if($formattedDate->format('%S') < 60 && $formattedDate->format('%I') == 0 && $formattedDate->format('%H') == 0 && $formattedDate->format('%d') == 0 && $formattedDate->format('%m') == 0 && $formattedDate->format('%y') == 0)
            return 'Few sec';
        // return $formattedDate->format('%S').' sec';
        elseif($formattedDate->format('%I') < 60 && $formattedDate->format('%H') == 0 &&  $formattedDate->format('%d') == 0 && $formattedDate->format('%m') == 0 && $formattedDate->format('%y') == 0)
            return $formattedDate->format('%I').' mins';
        elseif($formattedDate->format('%H') < 24 && $formattedDate->format('%d') == 0 && $formattedDate->format('%m') == 0 && $formattedDate->format('%y') == 0)
            return $formattedDate->format('%H').' hrs';
        elseif($formattedDate->format('%d') < 31 && $formattedDate->format('%m') == 0 && $formattedDate->format('%y') == 0)
            return $formattedDate->format('%d').' days';
        elseif($formattedDate->format('%m') < 31 && $formattedDate->format('%y') == 0)
            return $formattedDate->format('%d').' days';
        elseif($formattedDate->format('%y') < 31)
            return $formattedDate->format('%y').' years';
        return '';  
    }
}
/** end methd replyDiffernceCalculate */

/*
 Method Name:    readNotification
 Purpose:        read notifications
 Params:         
*/  
if (!function_exists('readNotification')) {
    function readNotification($userId)
    {
        // User::find($userId)->notifications()
        //     ->whereNull('read_at')
        //     ->update(['read_at'=>now()]);
    }
 }
/* End Method read notifications */


/*
 Method Name: Upload Files
 Purpose:     Upload Files
 Params:      $request,$path
*/  
if(!function_exists('uploadFile'))
{
    function uploadFile($file, $path)
    {
        if ($file) {
            // $ext      = $file->getClientOriginalExtension();
            // $filename = Carbon::now()->format('YmdHis') . '_' . rand(00000, 99999) . '.' . $ext;
            // $file->move(public_path('images'), $filename);

            // // $result   = Storage::disk('public')->putFileAs($path, $file, $filename);
            // return $filename ? $filename : false;

            $imageName = time().'.'.$file->getClientOriginalExtension();  
            $file->storeAs('public/'.$path, $imageName);
            return $imageName ? $imageName : false;
        }
        return false;
    }
}

/*
 Method Name: Delete Files
 Purpose:     Delete Files
 Params:      $name,$path
*/  

if(!function_exists('deleteFile'))
{
   function deleteFile($name,$path)
   {
        if($name)
        {
            $deleteImage = 'public/'.$path . $name;
            if (Storage::exists($deleteImage)) {
                Storage::delete($deleteImage);
            }
            // $filePath = public_path($path . $name);
            // if (File::exists($filePath)) {
            //     // Delete the file
            //     File::delete($filePath);
            // }
        }
        return false;
   }
}

/*
 Method Name:    encryptData
 Purpose:        encrypt data
 Params:         [data, encryptionMethod, secret]
*/  
if (!function_exists('encryptData')) {
    function encryptData(string $data, string $encryptionMethod = null, string $secret = null)
    {
        $encryptionMethod = config('constants.encryptionMethod');
        $secret = config('constants.secrect');
        try {
            $iv = substr($secret, 0, 16);
            $jsencodeUserdata = str_replace('/', '!', openssl_encrypt($data, $encryptionMethod, $secret, 0, $iv));
            $jsencodeUserdata = str_replace('+', '~', $jsencodeUserdata);
 
            return $jsencodeUserdata;
        } catch (\Exception $e) {
            return null;
        }
    }
 }
 /* End Method encryptData */
 
 /*
 Method Name:    decryptData
 Purpose:        Decrypt data
 Params:         [data, encryptionMethod, secret]
 */  
 if (!function_exists('decryptData')) {
    function decryptData(string $data, string $encryptionMethod = null, string $secret = null)
    {
        // return $data;
        $encryptionMethod = config('constants.encryptionMethod');
        $secret = config('constants.secrect');
        
        try {
            $iv = substr($secret, 0, 16);
            $data = str_replace('!', '/', $data);
            $data = str_replace('~', '+', $data);
            $jsencodeUserdata = openssl_decrypt($data, $encryptionMethod, $secret, 0, $iv);
            return $jsencodeUserdata;
        } catch (\Exception $e) {
           return null;
        }
    }
 }

/*
 Method Name: Delete Files
 Purpose:     Delete Files
 Params:      $name,$path
*/  

if(!function_exists('generateRandomString'))
{
    function generateRandomString($length = 8) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_+-=[]{}|;:,.<>?';
        $charactersLength = strlen($characters);
        $randomString = '';

        // Ensure at least one of each type of character
        $hasDigit = false;
        $hasSpecial = false;
        $hasLowercase = false;
        $hasUppercase = false;

        while (strlen($randomString) < $length) {
            $char = $characters[rand(0, $charactersLength - 1)];
            
            if (ctype_digit($char)) {
                $hasDigit = true;
            } elseif (ctype_lower($char)) {
                $hasLowercase = true;
            } elseif (ctype_upper($char)) {
                $hasUppercase = true;
            } elseif (!ctype_alnum($char)) {
                $hasSpecial = true;
            }

            $randomString .= $char;
        }

        if (!$hasDigit || !$hasSpecial || !$hasLowercase || !$hasUppercase) {
            return generateRandomString($length);
        }

        return $randomString;
    }
}

/*
 Method Name:    getCommonList
 Purpose:        get the common listing 
 Params:         [type]
 */  
if (!function_exists('getCommonList')) {
    function getCommonList($type,$id = null)
    {
        $data  = [];
        switch($type){
            case 'variant':
                $ids = ServiceVariant::where('service_id',$id)->pluck('variant_id')->toArray();
                $data = Variant::whereNotIn('id',$ids)->where('status',1)->orderBy('id','desc')->pluck('name','id')->toArray();
            break;
            case 'vehicle':
                $data = Vehicle::where('status',1)->orderBy('id','desc')->pluck('name','id')->toArray();
            break;
            case 'service':
                $data = Service::where('status',1)->orderBy('id','desc')->pluck('name','id')->toArray();
            break;
            case 'country':
                $data = Country::orderBy('nicename','asc')->pluck('nicename','id')->toArray();
            break;
            case 'driver':
                $role = Role::where('name' , config('constants.ROLES.DRIVER'))->first();
                $data = User::where('role_id',$role->id)->where('status',1)->orderBy('id','desc')->get();
            break;
        }
       return $data;
    }
}

/*
 Method Name:    orderHistory
 Purpose:        get the common listing 
 Params:         [order_id,status]
 */  
if (!function_exists('orderHistory')) {
    function orderHistory($order_id,$status)
    {
        Order::where('id',$order_id)->update(['status' => $status]);
        OrderHistory::create(['order_id'=> $order_id,'status' => $status]);
        return true;
    }
}

/*
 Method Name:    orderStatusCheck
 Purpose:        check the order status is passed from status
 Params:         [order_id,status]
 */  
if (!function_exists('orderStatusCheck')) {
    function orderStatusCheck($order_id,$status)
    {
        $order_history = OrderHistory::where('order_id', $order_id)->where('status' , $status)->first();
        if($order_history){
            return true;
        }
        return false;
    }
}


if (!function_exists('haversineGreatCircleDistance')) {
    function haversineGreatCircleDistance($latFrom, $lonFrom, $latTo, $lonTo, $earthRadius = 6371)
    {
       // Convert from degrees to radians
       $latFrom = deg2rad($latFrom);
       $lonFrom = deg2rad($lonFrom);
       $latTo = deg2rad($latTo);
       $lonTo = deg2rad($lonTo);
    
       $latDelta = $latTo - $latFrom;
       $lonDelta = $lonTo - $lonFrom;
    
       $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
           cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
       
       return number_format(($angle * $earthRadius),2);
    }
}


/*
 Method Name:    getAdmimId
 Purpose:        get the admin id
 Params:         [type]
*/  
if (!function_exists('getAdmimId')) {
    function getAdmimId()
    {
        $role = Role::where('name' , config('constants.ROLES.ADMIN'))->first();
        $admin = User::where('role_id',$role->id)->first();
        return  $admin->id;
    }
}

/*
 Method Name:    ConfigDetail
 Purpose:        get the config setting details
 Params:         [type]
*/  
if (!function_exists('ConfigDetail')) {
    function ConfigDetail($type,$key)
    {
        $setting = ConfigSetting::where('key' , $key)->where('type',$type)->first();
        return  $setting ? $setting->value : null;
    }
}
// end method ConfigDetail

/*
 Method Name:    calculateRating
 Purpose:        to calculate the rating
 Params:         [driver_id]
*/  
if (!function_exists('calculateRating')) {
    function calculateRating($id)
    {
        $ratings = RatingReview::where('driver_id', $id)->pluck('rating')->toArray();
        
        if (count($ratings) == 0) {
            return 5; 
        }
        return array_sum($ratings) / count($ratings); 
    }
}

// end method ConfigDetail