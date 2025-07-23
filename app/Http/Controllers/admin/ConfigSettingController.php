<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ConfigSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConfigSettingController extends Controller
{
    /**
     * functionName : smtpInformation
     * createdDate  : 23-07-2024
     * purpose      : update the smtp information
    */
    public function smtpInformation(Request $request){
        try{
            if($request->isMethod('get')){
                $smtpDetail = ConfigSetting::where('type','smtp')->pluck('value','key');
                return view("admin.config-setting.smtp",compact('smtpDetail'));
            }elseif( $request->isMethod('post') ){
                $validator = Validator::make($request->all(), [
                    'from_email'    => 'required|email:rfc,dns',
                    'host'          => 'required',
                    'port'          => 'required',
                    'username'      => 'required|email:rfc,dns',
                    'from_name'     => 'required',
                    'password'      => 'required',
                    'encryption'    => 'required|in:tls,ssl',
                ]);
                
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                
                ConfigSetting::updateOrCreate(['type' => 'smtp','key' => 'from_email'],['value' => $request->from_email]);
                ConfigSetting::updateOrCreate(['type' => 'smtp','key' => 'host'],['value' => $request->host]);
                ConfigSetting::updateOrCreate(['type' => 'smtp','key' => 'port'],['value' => $request->port]);
                ConfigSetting::updateOrCreate(['type' => 'smtp','key' => 'username'],['value' => $request->username]);
                ConfigSetting::updateOrCreate(['type' => 'smtp','key' => 'from_name'],['value' => $request->from_name]);
                ConfigSetting::updateOrCreate(['type' => 'smtp','key' => 'password'],['value' => $request->password]);
                ConfigSetting::updateOrCreate(['type' => 'smtp','key' => 'encryption'],['value' => $request->encryption]);
               
                return redirect()->back()->with('success','SMTP information '.config('constants.SUCCESS.ADD_DONE'));
            }
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method smtpInformation**/

    /**
     * functionName : stripeInformation
     * createdDate  : 23-07-2024
     * purpose      : update the stripe information
    */
    public function stripeInformation(Request $request){
        try{
            if($request->isMethod('get')){
                $stripeDetail = ConfigSetting::where('type','stripe')->pluck('value','key');
                return view("admin.config-setting.stripe",compact('stripeDetail'));
            }elseif( $request->isMethod('post') ){
                $validator = Validator::make($request->all(), [
                    'STRIPE_KEY'    => 'required',
                    'STRIPE_SECRET' => 'required',
                ]);
                
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                
                ConfigSetting::updateOrCreate(['type' => 'stripe','key' => 'STRIPE_KEY'],['value' => $request->STRIPE_KEY]);
                ConfigSetting::updateOrCreate(['type' => 'stripe','key' => 'STRIPE_SECRET'],['value' => $request->STRIPE_SECRET]);
                
                return redirect()->back()->with('success','Stripe information '.config('constants.SUCCESS.ADD_DONE'));
            }
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method stripeInformation**/

    /**
     * functionName : configInformation
     * createdDate  : 23-07-2024
     * purpose      : update the config information
    */
    public function configInformation(Request $request){
        try{
            if($request->isMethod('get')){
                $configDetail = ConfigSetting::where('type','config')->pluck('value','key');
                return view("admin.config-setting.config",compact('configDetail'));
            }elseif( $request->isMethod('post') ){
                $validator = Validator::make($request->all(), [
                    'CONFIG_MAX_WEIGHT'    => 'required',
                ]);
                
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                
                ConfigSetting::updateOrCreate(['type' => 'config','key' => 'CONFIG_MAX_WEIGHT'],['value' => $request->CONFIG_MAX_WEIGHT]);
                return redirect()->back()->with('success','config information '.config('constants.SUCCESS.ADD_DONE'));
            }
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method configInformation**/

    /**
     * functionName : DeliveryCostInformation
     * createdDate  : 29-07-2024
     * purpose      : update the Delivery Cost Information
    */
    public function DeliveryCostInformation(Request $request){
        try{
            if($request->isMethod('get')){
                $detail = ConfigSetting::where('type','delivery-cost')->pluck('value','key');
                return view("admin.config-setting.delivery-setting",compact('detail'));
            }elseif( $request->isMethod('post') ){
                $validator = Validator::make($request->all(), [
                    'DELIVERY_CHARGE'    => 'required',
                    'FREE_DELIVERY' => 'required',
                    'MINIMUM_ORDER_AMOUNT' => 'required',
                ]);
              
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                
                ConfigSetting::updateOrCreate(['type' => 'delivery-cost','key' => 'DELIVERY_CHARGE'],['value' => $request->DELIVERY_CHARGE]);
                ConfigSetting::updateOrCreate(['type' => 'delivery-cost','key' => 'FREE_DELIVERY'],['value' => $request->FREE_DELIVERY]);
                ConfigSetting::updateOrCreate(['type' => 'delivery-cost','key' => 'MINIMUM_ORDER_AMOUNT'],['value' => $request->MINIMUM_ORDER_AMOUNT]);
                
                return redirect()->back()->with('success','Delivery cost price '.config('constants.SUCCESS.ADD_DONE'));
            }
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method DeliveryCostInformation**/

    /**
     * functionName : generalInformation
     * createdDate  : 04-03-2024
     * purpose      : update the general Information
    */
    public function generalInformation(Request $request){
        try{
            if($request->isMethod('get')){
                $generalDetail = ConfigSetting::where('type','general-setting')->pluck('value','key');
                return view("admin.config-setting.general-setting",compact('generalDetail'));
            }elseif( $request->isMethod('post') ){
                $validator = Validator::make($request->all(), [
                    'received_point_per_order'              => 'required|integer',
                    'received_point_rate'                   => 'required',
                    'maximum_received_point_used_per_order' => 'required|integer',
                    'expiry_points'                         => 'required|integer',
                    'expiry_period'                         => 'required|integer',
                    // 'purchased_point'                     => 'required|integer',
                ]);
                
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }

                ConfigSetting::updateOrCreate(['type' => 'general-setting','key' => 'received_point_per_order'],['value' => $request->received_point_per_order]);
                ConfigSetting::updateOrCreate(['type' => 'general-setting','key' => 'received_point_rate'],['value' => $request->received_point_rate]);
                ConfigSetting::updateOrCreate(['type' => 'general-setting','key' => 'maximum_received_point_used_per_order'],['value' => $request->maximum_received_point_used_per_order]);
                ConfigSetting::updateOrCreate(['type' => 'general-setting','key' => 'expiry_points'],['value' => $request->expiry_points]);
                ConfigSetting::updateOrCreate(['type' => 'general-setting','key' => 'expiry_period'],['value' => $request->expiry_period]);
                //ConfigSetting::updateOrCreate(['type' => 'general-setting','key' => 'purchased_point'],['value' => $request->purchased_point]);
                
               
                return redirect()->back()->with('success','General Setting information '.config('constants.SUCCESS.ADD_DONE'));
            }
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method smtpInformation**/

}
