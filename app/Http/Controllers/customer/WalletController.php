<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use App\Models\{ConfigSetting,Payment,User,Wallet,WalletHistory,Order};
use App\Notifications\{WalletNotification,PaymentNotification};
use App\Traits\SendResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Stripe\{Customer,PaymentIntent,PaymentMethod,Stripe};

class WalletController extends Controller
{
    use SendResponseTrait;
    /**
     * functionName : getSavedCards
     * createdDate  : 21-11-2024
     * purpose      : get the saved cards for the payment
     */
    public function getSavedCards()
    {
        try {
            $setting = ConfigSetting::where('type', 'stripe')->pluck('value', 'key');
            
            Stripe::setApiKey($setting['STRIPE_SECRET']);

            $user = User::find(authId());
            
            if (!$user || !$user->customer_id) {
                return $this->apiResponse('error', 404, __('messages.User or customer ID not found'));
            }

            $paymentMethods = PaymentMethod::all([
                'customer' => $user->customer_id,
                'type' => 'card',
            ]);
            if($paymentMethods != []){
                $paymentMethods = $paymentMethods->data;
            }

            $message = trans('messages.SUCCESS.FETCH_DONE');
            return $this->apiResponse('success', 200, __('messages.Saved card detail') .' '. $message, $paymentMethods);
        } catch (\Exception $e) {
            return $this->apiResponse('error',  400, $e->getMessage());
        }
    }
    /* end method getSavedCards */


    /**
     * functionName : createPaymentIntent
     * createdDate  : 21-11-2024
     * purpose      : create the payment intent
     */
    
     public function createPaymentIntent(Request $request)
     {

        $validator = Validator::make($request->all(), [
            // 'amount'            => 'required|numeric|min:0',
            'payment_intent'     => 'required'
        ]);

        if ($validator->fails()) {
            return $this->apiResponse('error',422,$validator->errors()->first());
        }

        try 
        {
            $wallet = Wallet::firstWhere('user_id',authId());
            if(is_null($wallet)){
              $wallet = Wallet::create([
                   'user_id' => authId(),
                   'amount'  => 0
               ]);
            }

             /**
              * Create customer Start
             **/
             $user = User::find(authId());
            //  if( !$user->customer_id){
                 
            //      $customer = Customer::create([
            //         'email'         => $user->email,
            //         'name'          => $request->card_holder_name ? $request->card_holder_name : $user->full_name,
            //         'description'   => 'Customer for ' . $user->email,
            //      ]);
            //      $paymentMethod = PaymentMethod::retrieve($request->payment_method_id);

            //      $paymentMethod->attach(['customer' => $customer->id]);
                 
            //      $user->customer_id = $customer->id;
            //      $user->save();
            //  }
             /**
              * Create customer end
              */
              
            //  $paymentIntent = PaymentIntent::create([
            //      'customer' => $user->customer_id,
            //      'amount'   => $request->amount * 100,
            //      'currency' => 'USD',
            //      'payment_method' => $request->payment_method,
            //      'off_session' => true,
            //      'confirm' => true,
            //  ]);s
             if( $request->payment_intent['status'] != 'Succeeded'){
                WalletHistory::create([
                    'wallet_id'     => $wallet->id,
                    'message'       => 'Payment Failed',
                    'amount'        => isset($request->payment_intent['amount']) ? $request->payment_intent['amount']/100 : 0,
                    'payment_method'=> 'stripe',
                    'payment_status'=> 'failed'
                ]);

                User::find(getAdmimId())->notify(new WalletNotification($user->full_name.' failed to add $'.$request->amount.' in wallet'));

                $message = trans('messages.ERROR.SOMETHING_WRONG');
                return $this->apiResponse('error',401, $message);
             }
 
             $payment = Payment::create([
                'user_id'       => authId(),
                'payment_id'    => isset($request->payment_intent['id']) ? $request->payment_intent['id']: null,
                'amount'        => isset($request->payment_intent['amount']) ? $request->payment_intent['amount'] / 100 : null,
                'client_secret' => isset($request->payment_intent['clientSecret']) ? $request->payment_intent['clientSecret'] : null,
                // 'latest_charge' => isset($paymentIntent['latest_charge']) ? $paymentIntent['latest_charge'] : null,
                'status'        => isset($request->payment_intent['status']) ? $request->payment_intent['status'] : null,
                'order_id'      => isset($order->id) ? $order->id : null, 
                'payment_type'  => 'stripe'
             ]);

              
             $wallet->amount += $payment->amount ;
             $wallet->save();

             $message = 'Added $'. $payment->amount . ' in wallet';
             WalletHistory::create([
                'wallet_id'     => $wallet->id,
                'message'       => $message,
                'amount'        => $payment->amount,
                'payment_method'=> 'stripe',
                'payment_status'=> 'add'
            ]);
             
            User::find(getAdmimId())->notify(new WalletNotification($user->full_name.' '.$message));


            $message = trans('messages.SUCCESS.DONE');
             return $this->apiResponse('success',200, __('messages.Payment') .' '. $message);
            
         } catch (\Exception $e) {
            WalletHistory::create([
                'wallet_id'     => $wallet->id,
                'message'       => $e->getMessage(),
                'amount'        => $request->amount,
                'payment_method'=> 'stripe',
                'payment_status'=> 'failed'
            ]);

            // User::find(getAdmimId())->notify(new WalletNotification($user->full_name.' failed to add $'.$request->amount.' in wallet'));

            return $this->apiResponse('error',400,$e->getMessage());
         }
     }
     /**End method createPaymentIntent**/

    /**
     * functionName : detail
     * createdDate  : 21-11-2024
     * purpose      : get the wallet detail
     */
    public function detail()
    {
        try {

            $wallet = Wallet::with('walletHistory')->where('user_id',authId())->first();
           
            $message = trans('messages.SUCCESS.FETCH_DONE');
            return $this->apiResponse('success', 200, __('messages.Wallet detail') .' '. $message, $wallet);
        } catch (\Exception $e) {
            return $this->apiResponse('error',  400, $e->getMessage());
        }
    }
    /* end method getSavedCards */

    /**
     * functionName : createPayment
     * createdDate  : 12-03-2025
     * purpose      : Made the payment from the wallet
     */
    public function createPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount'            => 'required|numeric|min:0',
            'order_id'          => 'required|exists:orders,order_id'
        ]);

        if ($validator->fails()) {
            return $this->apiResponse('error',422,$validator->errors()->first());
        }

        try 
        {

            $wallet = Wallet::firstWhere('user_id',authId());
            if(is_null($wallet)){
              $wallet = Wallet::create([
                   'user_id' => authId(),
                   'amount'  => 0
               ]);
            }

            if($wallet->amount < $request->amount){
                return $this->apiResponse('error',422, __('messages.Insufficent balance in wallet'));
            }           

            $order = Order::firstWhere('order_id',$request->order_id);

            $wallet->amount -= $request->amount ;
            $wallet->save();

             $message = 'Paid $'. $request->amount. ' from wallet';
             WalletHistory::create([
                'wallet_id'     => $wallet->id,
                'message'       => $message,
                'amount'        => $request->amount,
                'payment_method'=> 'wallet',
                'payment_status'=> 'sub'
            ]);

            $payment = Payment::create([
                'user_id'       => authId(),
                'payment_id'    => $wallet->wallet_id,
                'amount'        => $request->amount,
                'status'        => 'Succeeded',
                'order_id'      => isset($order->id) ? $order->id : null, 
                'payment_type'  => 'wallet'
            ]);
            
            Order::where('order_id',$request->order_id)->update(['status'=>Order::ORDER_PAID]);
            orderHistory($order->id,Order::ORDER_PAID);
            User::find(getAdmimId())->notify(new PaymentNotification(userNameById(authId()),$payment->amount,$request->order_id));

            $message = trans('messages.SUCCESS.DONE');
            return $this->apiResponse('success',200, __('messages.Payment') .' '. $message);
           
        } catch (\Exception $e) {
            return $this->apiResponse('error',400,$e->getMessage());
        }
    }
 /* end method createPayment */

}
