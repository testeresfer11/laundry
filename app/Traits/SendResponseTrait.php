<?php

namespace App\Traits;

use Kreait\Firebase\Factory;
use App\Models\{EmailTemplate,ConfigSetting,FirebaseNotification};
use Illuminate\Support\Facades\Mail;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Exception\Messaging\{InvalidArgument,NotFound};
use Illuminate\Support\Facades\Log;

trait SendResponseTrait
{
    /*
   Method Name:    apiResponse
   Purpose:        To send an api response
   Params:         [apiResponse,statusCode,message,data]
   */
   public function apiResponse($apiResponse, $statusCode = '404', $message = 'No records Found', $data = null) {
        $responseArray = [];
        if($apiResponse == 'success') {
            $responseArray['api_response'] = $apiResponse;
            $responseArray['status_code'] = $statusCode;
            $responseArray['message'] = $message;
            $responseArray['data'] = $data;
        } else {
            $responseArray['api_response'] = 'error';
            $responseArray['status_code'] = $statusCode;
            $responseArray['message'] = $message;
            $responseArray['data'] = $data;
        }

        return response()->json($responseArray, $statusCode);
   }
    /* End Method apiResponse*/

    /*
    Method Name:    getTemplateByName
    Purpose:        Get email template by name
    Params:         [name,id]
    */
    public function getTemplateByName($name, $id = 1) {
        $template = EmailTemplate::where('template_name', $name)->first(['id', 'template_name', 'subject', 'template']);
        return $template;
   }
   /* End Method getTemplateByName */
      /*
    Method Name:    mailData
    Purpose:        prepare email data
    Params:         [$to, $subject, $email_body, $templete_name, $templete_id, $logtoken , $remarks = null]
    */   
    public function mailData($to, $subject, $email_body, $templete_name, $templete_id, ){
        try{
            $stringToReplace = ['{{YEAR}}',  '{{$COMPANYNAME}}' ];
            $stringReplaceWith = [date("Y"), config('constants.COMPANYNAME') ]; 
            $email_body = str_replace( $stringToReplace , $stringReplaceWith , $email_body );
                    
            $data = [  
                'to'            => $to, 
                'subject'       => $subject,
                'html'          => $email_body, 
                'templete_name' => $templete_name,
                'templete_id'   => $templete_id,
            ]; 

            return $data;
        } catch ( \Exception $e ) {
            throw new \Exception( $e->getMessage( ) );
        }
    } 
    /* End Method mailData */

    /*
    Method Name:    mailSend
    Purpose:        Send email from node
    Params:         [data]
    */   
    public function mailSend( $data ){
        try{ 
            $emailConfig = ConfigSetting::where('type','smtp')->pluck('value','key');

            config([
                'mail.host' => $emailConfig['host'],
                'mail.port' => $emailConfig['port'],
                'mail.username' => $emailConfig['username'],
                'mail.password' => $emailConfig['password'],
                'mail.encryption' => $emailConfig['encryption'],
                'mail.from.address' => $emailConfig['from_email'],
                'mail.from.name' => $emailConfig['from_name'],
            ]);

            $body = array('body' => $data['html']);
            Mail::send('email.sendEmail', $body, function($message) use($data)
            {    
                $message->to([$data['to']])->subject($data['subject']);    
            });
            return true;
        } catch ( \Exception $e ) {
            throw new \Exception( $e->getMessage( ) );
        }
    }      
    /* End Method mailSend */


    public function sendPushNotification($user_id, $title, $body, $type=null,  $redirect_data =null,$imgName = null) {
        try {
            $topic = 'userId_'.$user_id;

            $firebase = (new Factory)
                ->withServiceAccount(public_path('firebase-service.json'));
            $messaging = $firebase->createMessaging();
            
            $data = [
                'type' => $type,
                'redirect_data' => $redirect_data
            ];

            $message = CloudMessage::fromArray([
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                    'image' => $imgName ? asset('images/'.$imgName) : null
                ],
                'topic' => $topic,
            ])->withData([
                'type' => $type,
                'redirect_data' => json_encode($redirect_data),
            ]);
            
            $response = $messaging->send($message);

            Log::info('Push notification sent successfully.', ['response' => $response]);
    
            FirebaseNotification::create([
                'user_id'   => $user_id,
                'title'     => $title,
                'body'      => $body,
                'data'      => json_encode($data),
            ]);
    
            return true;
        } catch (NotFound $e) {
            Log::warning('FCM token not found or invalid: ' . $e->getMessage());
            return false;
        } catch (InvalidArgument $e) {
            Log::warning('Invalid FCM token provided: ' . $e->getMessage());
            return false;
        } catch (\MessagingException $e) {
            Log::error('Messaging error occurred: ' . $e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }
    
}



