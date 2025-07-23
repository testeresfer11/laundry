<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePaymentRequest;
use App\Models\{Transaction,ConfigSetting,Order,Payment, User};
use App\Notifications\PaymentNotification;
use App\Traits\SendResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Stripe\{PaymentIntent,PaymentMethod,Stripe};

class StripeController extends Controller
{
    use SendResponseTrait;

    /** 
    * functionName : Create Payment Intent 
    * createdDate  : 09-03-2025
    * purpose      : Create Payment Intent for stripe payment
    **/

    public function createPaymentIntent(CreatePaymentRequest $request)
    {
        try 
        {
            $order = Order::firstWhere('order_id',$request->order_id);
            $paymentIntent = $request->payment_intent;
            $payment = Payment::create([
                'user_id'       => authId(),
                'payment_id'    => isset($paymentIntent['id']) ? $paymentIntent['id'] : null,
                'amount'        => isset($paymentIntent['amount']) ? $paymentIntent['amount'] / 100 : null,
                'client_secret' => isset($paymentIntent['client_secret']) ? $paymentIntent['client_secret'] : null,
                'latest_charge' => isset($paymentIntent['latest_charge']) ? $paymentIntent['latest_charge'] : null,
                'status'        => isset($paymentIntent['status']) ? $paymentIntent['status'] : null,
                'order_id'      => isset($order->id) ? $order->id : null, 
                'payment_type'  => 'stripe'
            ]);
            
            Order::where('order_id',$request->order_id)->update(['status'=>Order::ORDER_PAID]);
            orderHistory($order->id,Order::ORDER_PAID);
            User::find(getAdmimId())->notify(new PaymentNotification(userNameById(authId()),$payment->amount,$request->order_id));
            
            //send mail to admin
            $admin = User::Where('role_id',1)->first();

            $template = $this->getTemplateByName('order_paid');
            if( $template ) { 
                $stringToReplace    = ['{{$name}}','{{$old_status}}','{{$new_status}}','{{$order_id}}','{{$amount}}','{{$services}}','{{$created_at}}'];
                $stringReplaceWith  = [$admin->full_name,$order->status,'paid',$order->order_id,$order->total_amount,implode(',',$order->service_name),$order->created_at];
                $newval             = str_replace($stringToReplace, $stringReplaceWith, $template->template);
                $newsubject         = str_replace($stringToReplace, $stringReplaceWith, $template->subject);
                $emailData          = $this->mailData($admin->email, $newsubject, $newval, 'order_paid', $template->id);
                $this->mailSend($emailData);
            }

            $message = trans('messages.SUCCESS.DONE');
            return $this->apiResponse('success',200, __('messages.Payment') .' '. $message);
           
        } catch (\Exception $e) {
            return $this->apiResponse('error',400,$e->getMessage());
        }
    }

    /** 
    * functionName : secretKey 
    * createdDate  : 09-03-2025
    * purpose      : Secret Key Fetching
    **/

    public function secretKey(Request $request){
        $setting = ConfigSetting::where('type','stripe')->pluck('value','key');
        
        Stripe::setApiKey($setting['STRIPE_SECRET']);
        
        if($setting){
            $message = trans('messages.SUCCESS.FETCH_DONE');
            return $this->apiResponse('success',200, __('messages.Stripe Key') .' '. $message, $setting);
        }else{
            return $this->apiResponse('error',400, __('messages.Stripe secret key not found'));
        }
    }

    
}
