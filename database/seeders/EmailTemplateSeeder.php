<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EmailTemplate::updateOrCreate([
            'template_name' => 'Otp_Verification'
        ],[
            'template' => '<!doctype html>
                <html>
                <head>
                    <title>{{$companyName}}</title>
                    <meta charset="utf-8">
                    <meta name="viewport" content="width=device-width">
                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                    <meta name="x-apple-disable-message-reformatting">
                    <meta http-equiv="Content-Type" content="text/html charset=UTF-8" />
                    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,700;1,400;1,700&amp;display=swap" rel="stylesheet" />
                    <!--–[if mso]-->
                    <style type="text/css">body, td,p {
                        font-family: Helvetica, Arial, sans-serif !important;
                        }
                    </style>
                </head>
                <body>
                    <table style="margin: auto;background:#f5f5f5;" role="presentation" border="0" cellspacing="0" cellpadding="0" align="center" width="600">
                        <tbody>
                            <tr>
                                <td style="padding: 1.5em 2.5em 1.5em 2.5em; background-color:#79a1e1;" valign="top" align="center">
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="230">
                                        <tbody>
                                            <tr>
                                                
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td valign="top" align="center" style="background-color:#79a1e1;padding:0px 10px 0;">
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="540">
                                        <tbody>
                                            <tr>
                                                <td valign="top" style="padding: 0em 2em 1em;background:#ffffff;" valign="middle">
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <!-- end tr -->
                            <tr>
                                <td style="padding: 0em 2em 1.5em; background:#f5f5f5;" valign="middle">
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="540">
                                        <tbody>
                                            <tr>
                                                <td valign="top" style="padding: 0em 2.5em 2em;background:#ffffff;">
                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                                                        <tbody>
                                                            <tr>
                                                                <td valign="top" style="text-align: left; color: #000000; font-size: 18px; padding-top: 0; line-height: 1.4; font-weight: bold;font-family: Helvetica, Arial, sans-serif;">
                                                                    Hello {{$name}},
                                                                </td>
                                                            </tr>
                                                             <tr>
                                                                <td valign="top" style="text-align: left; color: #000000; font-size: 16px; padding:15px 0 15px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                                    Welcome to {{$COMPANYNAME}}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td valign="top" style="text-align: left; color: #000000; font-size: 16px; padding:15px 0 15px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                                    Please verify your email address via enter otp.
                                                                </td>
                                                            </tr>
                                                            
                                                            
                                                             <tr>
                                                                <td valign="top" style="text-align: left; color: #000000; font-size: 16px; padding-top: 0px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;word-break: break-all;">
                                                                    <p style="text-align: left;">Verification OTP:- {{$otp}}</p>
                                                                </td>
                                                            </tr>
                                  
                                                            <tr>
                                                                <td valign="top" style="text-align: left; color: #000000; font-size: 16px; padding-top: 30px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                                    Thanks<br><strong>{{$COMPANYNAME}} Team</strong>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>

                            <tr>
                                <td style="padding: 15px 20px 15px;background:#79a1e1;" align="center">
                                    <p style="margin: 0; font-size: 12px;font-family: Helvetica, Arial, sans-serif;">&copy; {{YEAR}} <a style="color: #141637;" >{{$COMPANYNAME}}</a>. All Rights Reserved</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </body>
            </html>',
            'subject' => 'Email Address Verification',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        
        EmailTemplate::updateOrCreate([
            'template_name' => 'Forget_password'
        ],[
            'template' => '<!doctype html>
                <html>
                <head>
                    <title>{{$companyName}}</title>
                    <meta charset="utf-8">
                    <meta name="viewport" content="width=device-width">
                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                    <meta name="x-apple-disable-message-reformatting">
                    <meta http-equiv="Content-Type" content="text/html charset=UTF-8" />
                    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,700;1,400;1,700&amp;display=swap" rel="stylesheet" />
                    <!--–[if mso]-->
                    <style type="text/css">body, td,p {
                        font-family: Helvetica, Arial, sans-serif !important;
                        }
                    </style>
                </head>
                <body>
                    <table style="margin: auto;background:#f5f5f5;" role="presentation" border="0" cellspacing="0" cellpadding="0" align="center" width="600">
                        <tbody>
                            <tr>
                                <td style="padding: 1.5em 2.5em 1.5em 2.5em; background-color:#79a1e1;" valign="top" align="center">
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="230">
                                        <tbody>
                                            <tr>
                                                
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td valign="top" align="center" style="background-color:#79a1e1;padding:0px 10px 0;">
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="540">
                                        <tbody>
                                            <tr>
                                                <td valign="top" style="padding: 0em 2em 1em;background:#ffffff;" valign="middle">
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <!-- end tr -->
                            <tr>
                                <td style="padding: 0em 2em 1.5em; background:#f5f5f5;" valign="middle">
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="540">
                                        <tbody>
                                            <tr>
                                                <td valign="top" style="padding: 0em 2.5em 2em;background:#ffffff;">
                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                                                        <tbody>
                                                            <tr>
                                                                <td valign="top" style="text-align: left; color: #000000; font-size: 18px; padding-top: 0; line-height: 1.4; font-weight: bold;font-family: Helvetica, Arial, sans-serif;">
                                                                    Hello {{$name}},
                                                                </td>
                                                            </tr>
                                                             <tr>
                                                                <td valign="top" style="text-align: left; color: #000000; font-size: 16px; padding:15px 0 15px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                                    Welcome to {{$COMPANYNAME}}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td valign="top" style="text-align: left; color: #000000; font-size: 16px; padding:15px 0 15px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                                    Please reset your password via enter otp .
                                                                </td>
                                                            </tr>
                                                            
                                                            
                                                             <tr>
                                                                <td valign="top" style="text-align: left; color: #000000; font-size: 16px; padding-top: 0px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;word-break: break-all;">
                                                                    <p style="text-align: left;">Verification OTP:- {{$otp}}</p>
                                                                </td>
                                                            </tr>
                                  
                                                            <tr>
                                                                <td valign="top" style="text-align: left; color: #000000; font-size: 16px; padding-top: 30px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                                    Thanks<br><strong>{{$COMPANYNAME}} Team</strong>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>

                            <tr>
                                <td style="padding: 15px 20px 15px;background:#79a1e1;" align="center">
                                    <p style="margin: 0; font-size: 12px;font-family: Helvetica, Arial, sans-serif;">&copy; {{YEAR}} <a style="color: #141637;" >{{$COMPANYNAME}}</a>. All Rights Reserved</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </body>
            </html>',
            'subject' => 'Reset New Password',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        EmailTemplate::updateOrCreate([
            'template_name' => 'Email_Verification'
        ],[
            'template' => '<!doctype html>
                <html>
                <head>
                    <title>{{$companyName}}</title>
                    <meta charset="utf-8">
                    <meta name="viewport" content="width=device-width">
                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                    <meta name="x-apple-disable-message-reformatting">
                    <meta http-equiv="Content-Type" content="text/html charset=UTF-8" />
                    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,700;1,400;1,700&amp;display=swap" rel="stylesheet" />
                    <!--–[if mso]-->
                    <style type="text/css">body, td,p {
                        font-family: Helvetica, Arial, sans-serif !important;
                        }
                    </style>
                </head>
                <body>
                    <table style="margin: auto;background:#f5f5f5;" role="presentation" border="0" cellspacing="0" cellpadding="0" align="center" width="600">
                        <tbody>
                            <tr>
                                <td style="padding: 1.5em 2.5em 1.5em 2.5em; background-color:#57632a;" valign="top" align="center">
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="230">
                                        <tbody>
                                            <tr>
                                                
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td valign="top" align="center" style="background-color:#57632a;padding:0px 10px 0;">
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="540">
                                        <tbody>
                                            <tr>
                                                <td valign="top" style="padding: 0em 2em 1em;background:#ffffff;" valign="middle">
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <!-- end tr -->
                            <tr>
                                <td style="padding: 0em 2em 1.5em; background:#f5f5f5;" valign="middle">
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="540">
                                        <tbody>
                                            <tr>
                                                <td valign="top" style="padding: 0em 2.5em 2em;background:#ffffff;">
                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                                                        <tbody>
                                                            <tr>
                                                                <td valign="top" style="text-align: left; color: #000000; font-size: 18px; padding-top: 0; line-height: 1.4; font-weight: bold;font-family: Helvetica, Arial, sans-serif;">
                                                                    Hello {{$name}},
                                                                </td>
                                                            </tr>
                                                             <tr>
                                                                <td valign="top" style="text-align: left; color: #000000; font-size: 16px; padding:15px 0 15px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                                    Welcome to {{$COMPANYNAME}}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td valign="top" style="text-align: left; color: #000000; font-size: 16px; padding:15px 0 15px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                                    Click here to Verify Email.
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                              <td>
                                                                    <button style="background-color: #57632a; color: #ffffff; padding: 10px 15px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin: 4px 2px; cursor: pointer; border: none; border-radius: 12px;"><a href="{{$token}}" target="_blank">Click Here</a></button>

                                                              </td>
                                                            </tr>
                                                            
                                                            <tr>
                                                                <td valign="top" style="text-align: left; color: #000000; font-size: 16px; padding-top: 0px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;word-break: break-all;">
                                                                    <p style="text-align: left;">Or copy the url for email verification</p>
                                                                </td>
                                                            </tr>
                                                            
                                                            <tr>
                                                              <td valign="top" style="text-align: left; color: #000000; font-size: 16px; padding-top: 0px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;word-break: break-all;">
                                                                  <p style="text-align: left;">Verification Url:- {{$token}}</p>
                                                              </td>
                                                            </tr>
                                  
                                                            <tr>
                                                                <td valign="top" style="text-align: left; color: #000000; font-size: 16px; padding-top: 30px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                                    Thanks<br><strong>{{$COMPANYNAME}} Team</strong>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>

                            <tr>
                                <td style="padding: 15px 20px 15px;background:#57632a;" align="center">
                                    <p style="margin: 0; font-size: 12px;font-family: Helvetica, Arial, sans-serif;">&copy; {{YEAR}} <a style="color: #141637;" >{{$COMPANYNAME}}</a>. All Rights Reserved</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </body>
            </html>',
            'subject' => 'Email Verification',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        
        EmailTemplate::updateOrCreate([
            'template_name' => 'Account_detail'
        ],[
            'template' => '<!doctype html>
                <html>
                <head>
                    <title>{{$companyName}}</title>
                    <meta charset="utf-8">
                    <meta name="viewport" content="width=device-width">
                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                    <meta name="x-apple-disable-message-reformatting">
                    <meta http-equiv="Content-Type" content="text/html charset=UTF-8" />
                    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,700;1,400;1,700&amp;display=swap" rel="stylesheet" />
                    <!--–[if mso]-->
                    <style type="text/css">body, td,p {
                        font-family: Helvetica, Arial, sans-serif !important;
                        }
                    </style>
                </head>
                <body>
                    <table style="margin: auto;background:#f5f5f5;" role="presentation" border="0" cellspacing="0" cellpadding="0" align="center" width="600">
                        <tbody>
                            <tr>
                                <td style="padding: 1.5em 2.5em 1.5em 2.5em; background-color:#79a1e1;" valign="top" align="center">
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="230">
                                        <tbody>
                                            <tr>
                                                
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td valign="top" align="center" style="background-color:#79a1e1;padding:0px 10px 0;">
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="540">
                                        <tbody>
                                            <tr>
                                                <td valign="top" style="padding: 0em 2em 1em;background:#ffffff;" valign="middle">
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <!-- end tr -->
                            <tr>
                                <td style="padding: 0em 2em 1.5em; background:#f5f5f5;" valign="middle">
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="540">
                                        <tbody>
                                            <tr>
                                                <td valign="top" style="padding: 0em 2.5em 2em;background:#ffffff;">
                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                                                        <tbody>
                                                            <tr>
                                                                <td valign="top" style="text-align: left; color: #000000; font-size: 18px; padding-top: 0; line-height: 1.4; font-weight: bold;font-family: Helvetica, Arial, sans-serif;">
                                                                    Hello {{$name}},
                                                                </td>
                                                            </tr>
                                                             <tr>
                                                                <td valign="top" style="text-align: left; color: #000000; font-size: 16px; padding:15px 0 15px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                                    Welcome to {{$COMPANYNAME}}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td valign="top" style="text-align: left; color: #000000; font-size: 16px; padding:15px 0 15px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                                    Your account has been created successfully. Now you can login with the following credentials:-
                                                                </td>
                                                            </tr>
                                                            
                                                            
                                                             <tr>
                                                                <td valign="top" style="text-align: left; color: #000000; font-size: 16px; padding-top: 0px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;word-break: break-all;">
                                                                    <p style="text-align: left;">Email:- {{$email}}</p>
                                                                </td>
                                                            </tr>
                                                            
                                                             <tr>
                                                                <td valign="top" style="text-align: left; color: #000000; font-size: 16px; padding-top: 0px; font-weight: 400;font-family: Helvetica, Arial, sans-serif;word-break: break-all;">
                                                                    <p style="text-align: left;">Password:- {{$password}}</p>
                                                                </td>
                                                            </tr>
                                  
                                                            <tr>
                                                                <td valign="top" style="text-align: left; color: #000000; font-size: 16px; padding-top: 30px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                                    Thanks<br><strong>{{$COMPANYNAME}} Team</strong>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>

                            <tr>
                                <td style="padding: 15px 20px 15px;background:#79a1e1;" align="center">
                                    <p style="margin: 0; font-size: 12px;font-family: Helvetica, Arial, sans-serif;">&copy; {{YEAR}} <a style="color: #141637;" >{{$COMPANYNAME}}</a>. All Rights Reserved</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </body>
            </html>',
            'subject' => 'User Account Detail',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        EmailTemplate::updateOrCreate([
            'template_name' => 'Web_Forget_password'
        ],[
            'template' => '<!doctype html>
                <html>
                <head>
                    <title>{{$companyName}}</title>
                    <meta charset="utf-8">
                    <meta name="viewport" content="width=device-width">
                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                    <meta name="x-apple-disable-message-reformatting">
                    <meta http-equiv="Content-Type" content="text/html charset=UTF-8" />
                    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,700;1,400;1,700&amp;display=swap" rel="stylesheet" />
                    <!--–[if mso]-->
                    <style type="text/css">body, td,p {
                        font-family: Helvetica, Arial, sans-serif !important;
                        }
                    </style>
                </head>
                <body>
                    <table style="margin: auto;background:#f5f5f5;" role="presentation" border="0" cellspacing="0" cellpadding="0" align="center" width="600">
                        <tbody>
                            <tr>
                                <td style="padding: 1.5em 2.5em 1.5em 2.5em; background-color:#79a1e1;" valign="top" align="center">
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="230">
                                        <tbody>
                                            <tr>
                                                
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td valign="top" align="center" style="background-color:#79a1e1;padding:0px 10px 0;">
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="540">
                                        <tbody>
                                            <tr>
                                                <td valign="top" style="padding: 0em 2em 1em;background:#ffffff;" valign="middle">
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <!-- end tr -->
                            <tr>
                                <td style="padding: 0em 2em 1.5em; background:#f5f5f5;" valign="middle">
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="540">
                                        <tbody>
                                            <tr>
                                                <td valign="top" style="padding: 0em 2.5em 2em;background:#ffffff;">
                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                                                        <tbody>
                                                            <tr>
                                                                <td valign="top" style="text-align: left; color: #000000; font-size: 18px; padding-top: 0; line-height: 1.4; font-weight: bold;font-family: Helvetica, Arial, sans-serif;">
                                                                    Hello {{$name}},
                                                                </td>
                                                            </tr>
                                                             <tr>
                                                                <td valign="top" style="text-align: left; color: #000000; font-size: 16px; padding:15px 0 15px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                                    Welcome to {{$COMPANYNAME}}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td valign="top" style="text-align: left; color: #000000; font-size: 16px; padding:15px 0 15px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                                    Click here to reset password.
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                              <td>
                                                                    <button style="background-color: #79a1e1; color: #ffffff; padding: 10px 15px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin: 4px 2px; cursor: pointer; border: none; border-radius: 12px;"><a href="{{$token}}" target="_blank">Click Here</a></button>

                                                              </td>
                                                            </tr>
                                                            
                                                            <tr>
                                                                <td valign="top" style="text-align: left; color: #000000; font-size: 16px; padding-top: 0px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;word-break: break-all;">
                                                                    <p style="text-align: left;">Or copy the url and paste on browser for reset password</p>
                                                                </td>
                                                            </tr>
                                                            
                                                            <tr>
                                                              <td valign="top" style="text-align: left; color: #000000; font-size: 16px; padding-top: 0px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;word-break: break-all;">
                                                                  <p style="text-align: left;">Reset Url:- {{$token}}</p>
                                                              </td>
                                                            </tr>
                                  
                                                            <tr>
                                                                <td valign="top" style="text-align: left; color: #000000; font-size: 16px; padding-top: 30px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                                    Thanks<br><strong>{{$COMPANYNAME}} Team</strong>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>

                            <tr>
                                <td style="padding: 15px 20px 15px;background:#79a1e1;" align="center">
                                    <p style="margin: 0; font-size: 12px;font-family: Helvetica, Arial, sans-serif;">&copy; {{YEAR}} <a style="color: #141637;" >{{$COMPANYNAME}}</a>. All Rights Reserved</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </body>
            </html>',
            'subject' => 'Reset New Password',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ]);


        EmailTemplate::updateOrCreate([
            'template_name' => 'password_change'
        ],[
            'template' => '<!doctype html>
            <html>
            <head>
                <title>{{$companyName}}</title>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="x-apple-disable-message-reformatting">
                <meta http-equiv="Content-Type" content="text/html charset=UTF-8" />
                <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,700;1,400;1,700&amp;display=swap" rel="stylesheet" />
                <!--–[if mso]-->
                <style type="text/css">
                    body, td, p {
                        font-family: Helvetica, Arial, sans-serif !important;
                    }
                </style>
            </head>
            <body>
                <table style="margin: auto;background:#f5f5f5;" role="presentation" border="0" cellspacing="0" cellpadding="0" align="center" width="600">
                    <tbody>
                        <tr>
                            <td style="padding: 1.5em 2.5em 1.5em 2.5em; background-color:#79a1e1;" valign="top" align="center">
                                <table align="center" border="0" cellpadding="0" cellspacing="0" width="230">
                                    <tbody>
                                        <tr>
                                            <!-- Logo Section -->
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" align="center" style="background-color:#79a1e1;padding:0px 10px 0;">
                                <table align="center" border="0" cellpadding="0" cellspacing="0" width="540">
                                    <tbody>
                                        <tr>
                                            <td valign="top" style="padding: 0em 2em 1em;background:#ffffff;" valign="middle">
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <!-- Main Content Section -->
                        <tr>
                            <td style="padding: 0em 2em 1.5em; background:#f5f5f5;" valign="middle">
                                <table align="center" border="0" cellpadding="0" cellspacing="0" width="540">
                                    <tbody>
                                        <tr>
                                            <td valign="top" style="padding: 0em 2.5em 2em;background:#ffffff;">
                                                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                                                    <tbody>
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 18px; padding-top: 0; line-height: 1.4; font-weight: bold;font-family: Helvetica, Arial, sans-serif;">
                                                                Hello {{$name}},
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 16px; padding:15px 0 15px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                                You can now login with the following credentials:
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 16px; padding-top: 0px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;word-break: break-all;">
                                                                <p style="text-align: left;">Email: {{$email}}</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 16px; padding-top: 0px; font-weight: 400;font-family: Helvetica, Arial, sans-serif;word-break: break-all;">
                                                                <p style="text-align: left;">Password: {{$password}}</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 16px; padding-top: 30px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                                For security reasons, please update your password after logging in.
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 16px; padding-top: 30px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                                Thanks,<br><strong>{{$COMPANYNAME}} Team</strong>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <!-- Footer Section -->
                        <tr>
                            <td style="padding: 15px 20px 15px;background:#79a1e1;" align="center">
                                <p style="margin: 0; font-size: 12px;font-family: Helvetica, Arial, sans-serif;">&copy; {{YEAR}} <a style="color: #141637;" >{{$COMPANYNAME}}</a>. All Rights Reserved</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </body>
            </html>',
            'subject' => 'User Account Detail',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        // email_verification_success
        EmailTemplate::updateOrCreate([
            'template_name' => 'email_verification_success'
        ], [
            'template' => '<!doctype html>
            <html>
            <head>
                <title>{{$companyName}}</title>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="x-apple-disable-message-reformatting">
                <meta http-equiv="Content-Type" content="text/html charset=UTF-8" />
                <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,700;1,400;1,700&amp;display=swap" rel="stylesheet" />
                <!--–[if mso]-->
                <style type="text/css">
                    body, td, p {
                        font-family: Helvetica, Arial, sans-serif !important;
                    }
                </style>
            </head>
            <body>
                <table style="margin: auto;background:#f5f5f5;" role="presentation" border="0" cellspacing="0" cellpadding="0" align="center" width="600">
                    <tbody>
                        <tr>
                            <td style="padding: 1.5em 2.5em 1.5em 2.5em; background-color:#79a1e1;" valign="top" align="center">
                                <table align="center" border="0" cellpadding="0" cellspacing="0" width="230">
                                    <tbody>
                                        <tr>
                                            <!-- Logo Section -->
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" align="center" style="background-color:#79a1e1;padding:0px 10px 0;">
                                <table align="center" border="0" cellpadding="0" cellspacing="0" width="540">
                                    <tbody>
                                        <tr>
                                            <td valign="top" style="padding: 0em 2em 1em;background:#ffffff;" valign="middle">
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <!-- Main Content Section -->
                        <tr>
                            <td style="padding: 0em 2em 1.5em; background:#f5f5f5;" valign="middle">
                                <table align="center" border="0" cellpadding="0" cellspacing="0" width="540">
                                    <tbody>
                                        <tr>
                                            <td valign="top" style="padding: 0em 2.5em 2em;background:#ffffff;">
                                                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                                                    <tbody>
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 18px; padding-top: 0; line-height: 1.4; font-weight: bold;font-family: Helvetica, Arial, sans-serif;">
                                                                Hello {{$name}},
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 16px; padding:15px 0 15px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                                Congratulations! Your email address has been successfully verified.
                                                            </td>
                                                        </tr>
                                                      
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 16px; padding-top: 10px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                                Thanks,<br><strong>{{$COMPANYNAME}} Team</strong>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <!-- Footer Section -->
                        <tr>
                            <td style="padding: 15px 20px 15px;background:#79a1e1;" align="center">
                                <p style="margin: 0; font-size: 12px;font-family: Helvetica, Arial, sans-serif;">&copy; {{YEAR}} <a style="color: #141637;" >{{$COMPANYNAME}}</a>. All Rights Reserved</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </body>
            </html>',
            'subject' => 'Email verification Successful',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        // status changed
        EmailTemplate::updateOrCreate([
            'template_name' => 'status_changed'
        ], [
            'template' => '<!doctype html>
            <html>
            <head>
                <title>{{$companyName}}</title>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="x-apple-disable-message-reformatting">
                <meta http-equiv="Content-Type" content="text/html charset=UTF-8" />
                <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet" />
                <!--–[if mso]-->
                <style type="text/css">
                    body, td, p {
                        font-family: Helvetica, Arial, sans-serif !important;
                    }
                </style>
            </head>
            <body>
                <table style="margin: auto;background:#f5f5f5;" role="presentation" border="0" cellspacing="0" cellpadding="0" align="center" width="600">
                    <tbody>
                        <tr>
                            <td style="padding: 1.5em 2.5em 1.5em 2.5em; background-color:#79a1e1;" valign="top" align="center">
                                <table align="center" border="0" cellpadding="0" cellspacing="0" width="230">
                                    <tbody>
                                        <tr>
                                            <!-- Logo Section -->
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" align="center" style="background-color:#79a1e1;padding:0px 10px 0;">
                                <table align="center" border="0" cellpadding="0" cellspacing="0" width="540">
                                    <tbody>
                                        <tr>
                                            <td valign="top" style="padding: 0em 2em 1em;background:#ffffff;" valign="middle">
                                                    
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <!-- Main Content Section -->
                        <tr>
                            <td style="padding: 0em 2em 1.5em; background:#f5f5f5;" valign="middle">
                                <table align="center" border="0" cellpadding="0" cellspacing="0" width="540">
                                    <tbody>
                                        <tr>
                                            <td valign="top" style="padding: 0em 2.5em 2em;background:#ffffff;">
                                                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                                                    <tbody>
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 18px; padding-top: 0; line-height: 1.4; font-weight: bold;font-family: Helvetica, Arial, sans-serif;">
                                                                Hello {{$name}},
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 16px; padding:10px 0 10px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                                Your Order has been {{$new_status}}.
                                                            </td>
                                                        </tr>
                                                        
                                                        
                                                        <tr>
                                                         <td style="text-align: left; color: #000000; font-size: 18px; padding:15px 0 15px; line-height: 1.4; font-weight: bold;font-family: Helvetica, Arial, sans-serif;">Order Details:-</td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 14px; padding:0px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                             <b>Order Id:-</b>
                                                             {{$order_id}}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 14px; padding:0px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                             <b>Amount:-</b>
                                                             {{$amount}}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 14px; padding:0px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                             <b>Services:- </b>
                                                             {{$services}}
                                                            </td>
                                                        </tr>
                                                         <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 14px; padding:0px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                             <b>Created On:- </b>
                                                             {{$created_at}}
                                                            </td>
                                                        </tr>
                                                        
                                                        
                                                        
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 16px; padding-top: 10px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                                Thanks,<br><strong>{{$COMPANYNAME}} Team</strong>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <!-- Footer Section -->
                        <tr>
                            <td style="padding: 15px 20px 15px;background:#79a1e1;" align="center">
                                <p style="margin: 0; font-size: 12px;font-family: Helvetica, Arial, sans-serif;">&copy; {{YEAR}} <a style="color: #141637;" >{{$COMPANYNAME}}</a>. All Rights Reserved</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </body>
            </html>',
            'subject' => 'Order Status changed Successfully.',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        // Order Accepted or Approved
        EmailTemplate::updateOrCreate([
            'template_name' => 'order_accept_approve'
        ], [
            'template' => '<!doctype html>
            <html>
            <head>
                <title>{{$companyName}}</title>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="x-apple-disable-message-reformatting">
                <meta http-equiv="Content-Type" content="text/html charset=UTF-8" />
                <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet" />
                <!--–[if mso]-->
                <style type="text/css">
                    body, td, p {
                        font-family: Helvetica, Arial, sans-serif !important;
                    }
                </style>
            </head>
            <body>
                <table style="margin: auto;background:#f5f5f5;" role="presentation" border="0" cellspacing="0" cellpadding="0" align="center" width="600">
                    <tbody>
                        <tr>
                            <td style="padding: 1.5em 2.5em 1.5em 2.5em; background-color:#79a1e1;" valign="top" align="center">
                                <table align="center" border="0" cellpadding="0" cellspacing="0" width="230">
                                    <tbody>
                                        <tr>
                                            <!-- Logo Section -->
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" align="center" style="background-color:#79a1e1;padding:0px 10px 0;">
                                <table align="center" border="0" cellpadding="0" cellspacing="0" width="540">
                                    <tbody>
                                        <tr>
                                            <td valign="top" style="padding: 0em 2em 1em;background:#ffffff;" valign="middle">
                                                    
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <!-- Main Content Section -->
                        <tr>
                            <td style="padding: 0em 2em 1.5em; background:#f5f5f5;" valign="middle">
                                <table align="center" border="0" cellpadding="0" cellspacing="0" width="540">
                                    <tbody>
                                        <tr>
                                            <td valign="top" style="padding: 0em 2.5em 2em;background:#ffffff;">
                                                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                                                    <tbody>
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 18px; padding-top: 0; line-height: 1.4; font-weight: bold;font-family: Helvetica, Arial, sans-serif;">
                                                                Hello {{$name}},
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 16px; padding:10px 0 10px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                                Order status has been changed from {{$old_status}} to {{$new_status}}.
                                                            </td>
                                                        </tr>
                                                        
                                                        
                                                        <tr>
                                                         <td style="text-align: left; color: #000000; font-size: 18px; padding:15px 0 15px; line-height: 1.4; font-weight: bold;font-family: Helvetica, Arial, sans-serif;">Order Details:-</td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 14px; padding:0px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                             <b>Order Id:-</b>
                                                             {{$order_id}}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 14px; padding:0px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                             <b>Amount:-</b>
                                                             {{$amount}}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 14px; padding:0px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                             <b>Services:- </b>
                                                             {{$services}}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 14px; padding:0px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                             <b>Created On:- </b>
                                                             {{$created_at}}
                                                            </td>
                                                        </tr>
                                                    
                                                        
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 16px; padding-top: 10px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                                Thanks,<br><strong>{{$COMPANYNAME}} Team</strong>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <!-- Footer Section -->
                        <tr>
                            <td style="padding: 15px 20px 15px;background:#79a1e1;" align="center">
                                <p style="margin: 0; font-size: 12px;font-family: Helvetica, Arial, sans-serif;">&copy; {{YEAR}} <a style="color: #141637;" >{{$COMPANYNAME}}</a>. All Rights Reserved</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </body>
            </html>',
            'subject' => 'Order {{$new_status}} Successfully.',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        // Order Rejected or Cancelled
        EmailTemplate::updateOrCreate([
            'template_name' => 'order_reject_cancel'
        ], [
            'template' => '<!doctype html>
            <html>
            <head>
                <title>{{$companyName}}</title>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="x-apple-disable-message-reformatting">
                <meta http-equiv="Content-Type" content="text/html charset=UTF-8" />
                <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet" />
                <!--–[if mso]-->
                <style type="text/css">
                    body, td, p {
                        font-family: Helvetica, Arial, sans-serif !important;
                    }
                </style>
            </head>
            <body>
                <table style="margin: auto;background:#f5f5f5;" role="presentation" border="0" cellspacing="0" cellpadding="0" align="center" width="600">
                    <tbody>
                        <tr>
                            <td style="padding: 1.5em 2.5em 1.5em 2.5em; background-color:#79a1e1;" valign="top" align="center">
                                <table align="center" border="0" cellpadding="0" cellspacing="0" width="230">
                                    <tbody>
                                        <tr>
                                            <!-- Logo Section -->
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" align="center" style="background-color:#79a1e1;padding:0px 10px 0;">
                                <table align="center" border="0" cellpadding="0" cellspacing="0" width="540">
                                    <tbody>
                                        <tr>
                                            <td valign="top" style="padding: 0em 2em 1em;background:#ffffff;" valign="middle">
                                                    
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <!-- Main Content Section -->
                        <tr>
                            <td style="padding: 0em 2em 1.5em; background:#f5f5f5;" valign="middle">
                                <table align="center" border="0" cellpadding="0" cellspacing="0" width="540">
                                    <tbody>
                                        <tr>
                                            <td valign="top" style="padding: 0em 2.5em 2em;background:#ffffff;">
                                                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                                                    <tbody>
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 18px; padding-top: 0; line-height: 1.4; font-weight: bold;font-family: Helvetica, Arial, sans-serif;">
                                                                Hello {{$name}},
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 16px; padding:10px 0 10px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                                Order status has been changed from {{$old_status}} to {{$new_status}}.
                                                            </td>
                                                        </tr>
                                                        
                                                        
                                                        <tr>
                                                         <td style="text-align: left; color: #000000; font-size: 18px; padding:15px 0 15px; line-height: 1.4; font-weight: bold;font-family: Helvetica, Arial, sans-serif;">Order Details:-</td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 14px; padding:0px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                             <b>Order Id:-</b>
                                                             {{$order_id}}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 14px; padding:0px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                             <b>Amount:-</b>
                                                             {{$amount}}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 14px; padding:0px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                             <b>Services:- </b>
                                                             {{$services}}
                                                            </td>
                                                        </tr>
                                                         <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 14px; padding:0px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                             <b>Created On:- </b>
                                                             {{$created_at}}
                                                            </td>
                                                        </tr>
                                                    
                                                        <!-- Add Reason here -->
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 16px; padding:15px 0px 15px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                            <b>Reason:-</b>
                                                            {{$reason}}
                                                            </td>
                                                        </tr>
                                                        
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 16px; padding-top: 10px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                                Thanks,<br><strong>{{$COMPANYNAME}} Team</strong>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <!-- Footer Section -->
                        <tr>
                            <td style="padding: 15px 20px 15px;background:#79a1e1;" align="center">
                                <p style="margin: 0; font-size: 12px;font-family: Helvetica, Arial, sans-serif;">&copy; {{YEAR}} <a style="color: #141637;" >{{$COMPANYNAME}}</a>. All Rights Reserved</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </body>
            </html>',
            'subject' => 'Order {{$new_status}} Successfully.',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        // Assigning Order to Driver
        EmailTemplate::updateOrCreate([
            'template_name' => 'assign_driver'
        ], [
            'template' => '<!doctype html>
            <html>
            <head>
                <title>{{$companyName}}</title>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="x-apple-disable-message-reformatting">
                <meta http-equiv="Content-Type" content="text/html charset=UTF-8" />
                <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet" />
                <!--–[if mso]-->
                <style type="text/css">
                    body, td, p {
                        font-family: Helvetica, Arial, sans-serif !important;
                    }
                </style>
            </head>
            <body>
                <table style="margin: auto;background:#f5f5f5;" role="presentation" border="0" cellspacing="0" cellpadding="0" align="center" width="600">
                    <tbody>
                        <tr>
                            <td style="padding: 1.5em 2.5em 1.5em 2.5em; background-color:#79a1e1;" valign="top" align="center">
                                <table align="center" border="0" cellpadding="0" cellspacing="0" width="230">
                                    <tbody>
                                        <tr>
                                            <!-- Logo Section -->
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" align="center" style="background-color:#79a1e1;padding:0px 10px 0;">
                                <table align="center" border="0" cellpadding="0" cellspacing="0" width="540">
                                    <tbody>
                                        <tr>
                                            <td valign="top" style="padding: 0em 2em 1em;background:#ffffff;" valign="middle">
                                                    
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <!-- Main Content Section -->
                        <tr>
                            <td style="padding: 0em 2em 1.5em; background:#f5f5f5;" valign="middle">
                                <table align="center" border="0" cellpadding="0" cellspacing="0" width="540">
                                    <tbody>
                                        <tr>
                                            <td valign="top" style="padding: 0em 2.5em 2em;background:#ffffff;">
                                                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                                                    <tbody>
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 18px; padding-top: 0; line-height: 1.4; font-weight: bold;font-family: Helvetica, Arial, sans-serif;">
                                                                Hello {{$name}},
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 16px; padding:10px 0 10px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                                {{$driver_name}} driver has been assigned for {{$new_status}} the order.
                                                            </td>
                                                        </tr>
                                                        
                                                        
                                                        <tr>
                                                         <td style="text-align: left; color: #000000; font-size: 18px; padding:15px 0 15px; line-height: 1.4; font-weight: bold;font-family: Helvetica, Arial, sans-serif;">Order Details:-</td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 14px; padding:0px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                             <b>Order Id:-</b>
                                                             {{$order_id}}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 14px; padding:0px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                             <b>Amount:-</b>
                                                             {{$amount}}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 14px; padding:0px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                             <b>Services:- </b>
                                                             {{$services}}
                                                            </td>
                                                        </tr>
                                                         <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 14px; padding:0px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                             <b>Created On:- </b>
                                                             {{$created_at}}
                                                            </td>
                                                        </tr>
                                                        
                                                        <tr>
                                                         <td style="text-align: left; color: #000000; font-size: 18px; padding:15px 0 15px; line-height: 1.4; font-weight: bold;font-family: Helvetica, Arial, sans-serif;">Driver Information:-</td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 14px; padding:0px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                             <b>Name:-</b>
                                                             {{$driver_name}}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 14px; padding:0px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                             <b>Email:-</b>
                                                             {{$driver_email}}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 14px; padding:0px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                             <b>Mobile No:- </b>
                                                             {{$driver_phone}}
                                                            </td>
                                                        </tr>
                                                        
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 16px; padding-top: 10px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                                Thanks,<br><strong>{{$COMPANYNAME}} Team</strong>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <!-- Footer Section -->
                        <tr>
                            <td style="padding: 15px 20px 15px;background:#79a1e1;" align="center">
                                <p style="margin: 0; font-size: 12px;font-family: Helvetica, Arial, sans-serif;">&copy; {{YEAR}} <a style="color: #141637;" >{{$COMPANYNAME}}</a>. All Rights Reserved</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </body>
            </html>',
            'subject' => 'Order {{$new_status}} Successfully.',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        // Order Progress
        EmailTemplate::updateOrCreate([
            'template_name' => 'order_in_progress'
        ], [
            'template' => '<!doctype html>
            <html>
            <head>
                <title>{{$companyName}}</title>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="x-apple-disable-message-reformatting">
                <meta http-equiv="Content-Type" content="text/html charset=UTF-8" />
                <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet" />
                <!--–[if mso]-->
                <style type="text/css">
                    body, td, p {
                        font-family: Helvetica, Arial, sans-serif !important;
                    }
                </style>
            </head>
            <body>
                <table style="margin: auto;background:#f5f5f5;" role="presentation" border="0" cellspacing="0" cellpadding="0" align="center" width="600">
                    <tbody>
                        <tr>
                            <td style="padding: 1.5em 2.5em 1.5em 2.5em; background-color:#79a1e1;" valign="top" align="center">
                                <table align="center" border="0" cellpadding="0" cellspacing="0" width="230">
                                    <tbody>
                                        <tr>
                                            <!-- Logo Section -->
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" align="center" style="background-color:#79a1e1;padding:0px 10px 0;">
                                <table align="center" border="0" cellpadding="0" cellspacing="0" width="540">
                                    <tbody>
                                        <tr>
                                            <td valign="top" style="padding: 0em 2em 1em;background:#ffffff;" valign="middle">
                                                    
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <!-- Main Content Section -->
                        <tr>
                            <td style="padding: 0em 2em 1.5em; background:#f5f5f5;" valign="middle">
                                <table align="center" border="0" cellpadding="0" cellspacing="0" width="540">
                                    <tbody>
                                        <tr>
                                            <td valign="top" style="padding: 0em 2.5em 2em;background:#ffffff;">
                                                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                                                    <tbody>
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 18px; padding-top: 0; line-height: 1.4; font-weight: bold;font-family: Helvetica, Arial, sans-serif;">
                                                                Hello {{$name}},
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 16px; padding:10px 0 10px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                                Your Order has been {{$new_status}} for the delivery.
                                                            </td>
                                                        </tr>
                                                        
                                                        
                                                        <tr>
                                                         <td style="text-align: left; color: #000000; font-size: 18px; padding:15px 0 15px; line-height: 1.4; font-weight: bold;font-family: Helvetica, Arial, sans-serif;">Order Details:-</td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 14px; padding:0px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                             <b>Order Id:-</b>
                                                             {{$order_id}}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 14px; padding:0px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                             <b>Amount:-</b>
                                                             {{$amount}}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 14px; padding:0px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                             <b>Services:- </b>
                                                             {{$services}}
                                                            </td>
                                                        </tr>
                                                         <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 14px; padding:0px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                             <b>Created On:- </b>
                                                             {{$created_at}}
                                                            </td>
                                                        </tr>
                                                        
                                                        
                                                        
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 16px; padding-top: 10px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                                Thanks,<br><strong>{{$COMPANYNAME}} Team</strong>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <!-- Footer Section -->
                        <tr>
                            <td style="padding: 15px 20px 15px;background:#79a1e1;" align="center">
                                <p style="margin: 0; font-size: 12px;font-family: Helvetica, Arial, sans-serif;">&copy; {{YEAR}} <a style="color: #141637;" >{{$COMPANYNAME}}</a>. All Rights Reserved</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </body>
            </html>',
            'subject' => 'Order is {{$new_status}} for delivery',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        // Order Accepted or Approved
        EmailTemplate::updateOrCreate([
            'template_name' => 'order_ready'
        ], [
            'template' => '<!doctype html>
            <html>
            <head>
                <title>{{$companyName}}</title>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="x-apple-disable-message-reformatting">
                <meta http-equiv="Content-Type" content="text/html charset=UTF-8" />
                <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet" />
                <!--–[if mso]-->
                <style type="text/css">
                    body, td, p {
                        font-family: Helvetica, Arial, sans-serif !important;
                    }
                </style>
            </head>
            <body>
                <table style="margin: auto;background:#f5f5f5;" role="presentation" border="0" cellspacing="0" cellpadding="0" align="center" width="600">
                    <tbody>
                        <tr>
                            <td style="padding: 1.5em 2.5em 1.5em 2.5em; background-color:#79a1e1;" valign="top" align="center">
                                <table align="center" border="0" cellpadding="0" cellspacing="0" width="230">
                                    <tbody>
                                        <tr>
                                            <!-- Logo Section -->
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" align="center" style="background-color:#79a1e1;padding:0px 10px 0;">
                                <table align="center" border="0" cellpadding="0" cellspacing="0" width="540">
                                    <tbody>
                                        <tr>
                                            <td valign="top" style="padding: 0em 2em 1em;background:#ffffff;" valign="middle">
                                                    
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <!-- Main Content Section -->
                        <tr>
                            <td style="padding: 0em 2em 1.5em; background:#f5f5f5;" valign="middle">
                                <table align="center" border="0" cellpadding="0" cellspacing="0" width="540">
                                    <tbody>
                                        <tr>
                                            <td valign="top" style="padding: 0em 2.5em 2em;background:#ffffff;">
                                                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                                                    <tbody>
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 18px; padding-top: 0; line-height: 1.4; font-weight: bold;font-family: Helvetica, Arial, sans-serif;">
                                                                Hello {{$name}},
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 16px; padding:10px 0 10px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                                Order status has been changed from {{$old_status}} to {{$new_status}}.
                                                            </td>
                                                        </tr>
                                                        
                                                        
                                                        <tr>
                                                         <td style="text-align: left; color: #000000; font-size: 18px; padding:15px 0 15px; line-height: 1.4; font-weight: bold;font-family: Helvetica, Arial, sans-serif;">Order Details:-</td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 14px; padding:0px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                             <b>Order Id:-</b>
                                                             {{$order_id}}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 14px; padding:0px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                             <b>Amount:-</b>
                                                             {{$amount}}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 14px; padding:0px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                             <b>Services:- </b>
                                                             {{$services}}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 14px; padding:0px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                             <b>Created On:- </b>
                                                             {{$created_at}}
                                                            </td>
                                                        </tr>
                                                    
                                                        
                                                        <tr>
                                                            <td valign="top" style="text-align: left; color: #000000; font-size: 16px; padding-top: 10px; line-height: 1.4; font-weight: 400;font-family: Helvetica, Arial, sans-serif;">
                                                                Thanks,<br><strong>{{$COMPANYNAME}} Team</strong>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <!-- Footer Section -->
                        <tr>
                            <td style="padding: 15px 20px 15px;background:#79a1e1;" align="center">
                                <p style="margin: 0; font-size: 12px;font-family: Helvetica, Arial, sans-serif;">&copy; {{YEAR}} <a style="color: #141637;" >{{$COMPANYNAME}}</a>. All Rights Reserved</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </body>
            </html>',
            'subject' => 'Your Order is {{$new_status}} for delivery.',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        // Order Paid
        EmailTemplate::updateOrCreate([
            'template_name' => 'order_paid'
        ], [
            'template' => '<!doctype html>
                <html>
                <head>
                    <title>{{$companyName}} - Order Paid Notification</title>
                    <meta charset="utf-8">
                    <meta name="viewport" content="width=device-width">
                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                    <meta name="x-apple-disable-message-reformatting">
                    <meta http-equiv="Content-Type" content="text/html charset=UTF-8" />
                    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet" />
                    <!--–[if mso]-->
                    <style type="text/css">
                        body, td, p {
                            font-family: Helvetica, Arial, sans-serif !important;
                        }
                    </style>
                </head>
                <body>
                    <table style="margin: auto; background:#f5f5f5;" role="presentation" border="0" cellspacing="0" cellpadding="0" align="center" width="600">
                        <tbody>
                            <tr>
                                <td style="padding: 1.5em 2.5em 1.5em 2.5em; background-color:#79a1e1;" valign="top" align="center">
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="230">
                                        <tbody>
                                            <tr>
                                                <!-- Logo Section -->
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td valign="top" align="center" style="background-color:#79a1e1; padding:0px 10px 0;">
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="540">
                                        <tbody>
                                            <tr>
                                                <td valign="top" style="padding: 0em 2em 1em; background:#ffffff;" valign="middle">
                                                    <!-- Content Section -->
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <!-- Main Content Section -->
                            <tr>
                                <td style="padding: 0em 2em 1.5em; background:#f5f5f5;" valign="middle">
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="540">
                                        <tbody>
                                            <tr>
                                                <td valign="top" style="padding: 0em 2.5em 2em; background:#ffffff;">
                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                                                        <tbody>
                                                            <tr>
                                                                <td valign="top" style="text-align: left; color: #000000; font-size: 18px; padding-top: 0; line-height: 1.4; font-weight: bold; font-family: Helvetica, Arial, sans-serif;">
                                                                    Order Payment Received
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td valign="top" style="text-align: left; color: #000000; font-size: 16px; padding:10px 0 10px; line-height: 1.4; font-weight: 400; font-family: Helvetica, Arial, sans-serif;">
                                                                    A customer has successfully paid for their order. Here are the details:
                                                                </td>
                                                            </tr>
                                                            
                                                            <tr>
                                                                <td style="text-align: left; color: #000000; font-size: 18px; padding:15px 0 15px; line-height: 1.4; font-weight: bold; font-family: Helvetica, Arial, sans-serif;">
                                                                    Order Details:
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td valign="top" style="text-align: left; color: #000000; font-size: 14px; padding:0px; line-height: 1.4; font-weight: 400; font-family: Helvetica, Arial, sans-serif;">
                                                                    <b>Order Id:</b> {{$order_id}}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td valign="top" style="text-align: left; color: #000000; font-size: 14px; padding:0px; line-height: 1.4; font-weight: 400; font-family: Helvetica, Arial, sans-serif;">
                                                                    <b>Amount Paid:</b> {{$amount}}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td valign="top" style="text-align: left; color: #000000; font-size: 14px; padding:0px; line-height: 1.4; font-weight: 400; font-family: Helvetica, Arial, sans-serif;">
                                                                    <b>Order Status:</b> Paid
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td valign="top" style="text-align: left; color: #000000; font-size: 14px; padding:0px; line-height: 1.4; font-weight: 400; font-family: Helvetica, Arial, sans-serif;">
                                                                    <b>Created On:</b> {{$created_at}}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td valign="top" style="text-align: left; color: #000000; font-size: 16px; padding-top: 10px; line-height: 1.4; font-weight: 400; font-family: Helvetica, Arial, sans-serif;">
                                                                    
                                                                    <br><strong>{{$COMPANYNAME}} Team</strong>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <!-- Footer Section -->
                            <tr>
                                <td style="padding: 15px 20px 15px; background:#79a1e1;" align="center">
                                    <p style="margin: 0; font-size: 12px; font-family: Helvetica, Arial, sans-serif;">&copy; {{YEAR}} <a style="color: #141637;" >{{$COMPANYNAME}}</a>. All Rights Reserved</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </body>
                </html>
                ',
            'subject' => 'Order {{$new_status}} Successfully.',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        // Order Delivered
        EmailTemplate::updateOrCreate([
            'template_name' => 'order_delivered'
        ], [
            'template' => '<!doctype html>
                <html>
                <head>
                    <title>{{$companyName}} - Order Delivered Notification</title>
                    <meta charset="utf-8">
                    <meta name="viewport" content="width=device-width">
                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                    <meta name="x-apple-disable-message-reformatting">
                    <meta http-equiv="Content-Type" content="text/html charset=UTF-8" />
                    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet" />
                    <!--–[if mso]-->
                    <style type="text/css">
                        body, td, p {
                            font-family: Helvetica, Arial, sans-serif !important;
                        }
                    </style>
                </head>
                <body>
                    <table style="margin: auto; background:#f5f5f5;" role="presentation" border="0" cellspacing="0" cellpadding="0" align="center" width="600">
                        <tbody>
                            <tr>
                                <td style="padding: 1.5em 2.5em 1.5em 2.5em; background-color:#79a1e1;" valign="top" align="center">
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="230">
                                        <tbody>
                                            <tr>
                                                <!-- Logo Section -->
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td valign="top" align="center" style="background-color:#79a1e1; padding:0px 10px 0;">
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="540">
                                        <tbody>
                                            <tr>
                                                <td valign="top" style="padding: 0em 2em 1em; background:#ffffff;" valign="middle">
                                                    <!-- Content Section -->
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <!-- Main Content Section -->
                            <tr>
                                <td style="padding: 0em 2em 1.5em; background:#f5f5f5;" valign="middle">
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="540">
                                        <tbody>
                                            <tr>
                                                <td valign="top" style="padding: 0em 2.5em 2em; background:#ffffff;">
                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                                                        <tbody>
                                                            <tr>
                                                                <td valign="top" style="text-align: left; color: #000000; font-size: 18px; padding-top: 0; line-height: 1.4; font-weight: bold; font-family: Helvetica, Arial, sans-serif;">
                                                                    Order Delivered
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td valign="top" style="text-align: left; color: #000000; font-size: 16px; padding:10px 0 10px; line-height: 1.4; font-weight: 400; font-family: Helvetica, Arial, sans-serif;">
                                                                    Your Order has been successfully delivered. Here are the details:
                                                                </td>
                                                            </tr>
                                                            
                                                            <tr>
                                                                <td style="text-align: left; color: #000000; font-size: 18px; padding:15px 0 15px; line-height: 1.4; font-weight: bold; font-family: Helvetica, Arial, sans-serif;">
                                                                    Order Details:
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td valign="top" style="text-align: left; color: #000000; font-size: 14px; padding:0px; line-height: 1.4; font-weight: 400; font-family: Helvetica, Arial, sans-serif;">
                                                                    <b>Order Id:</b> {{$order_id}}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td valign="top" style="text-align: left; color: #000000; font-size: 14px; padding:0px; line-height: 1.4; font-weight: 400; font-family: Helvetica, Arial, sans-serif;">
                                                                    <b>Amount Paid:</b> {{$amount}}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td valign="top" style="text-align: left; color: #000000; font-size: 14px; padding:0px; line-height: 1.4; font-weight: 400; font-family: Helvetica, Arial, sans-serif;">
                                                                    <b>Order Status:</b> Delivered
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td valign="top" style="text-align: left; color: #000000; font-size: 14px; padding:0px; line-height: 1.4; font-weight: 400; font-family: Helvetica, Arial, sans-serif;">
                                                                    <b>Created On:</b> {{$created_at}}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td valign="top" style="text-align: left; color: #000000; font-size: 16px; padding-top: 10px; line-height: 1.4; font-weight: 400; font-family: Helvetica, Arial, sans-serif;">
                                                                    
                                                                    <br><strong>{{$COMPANYNAME}} Team</strong>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <!-- Footer Section -->
                            <tr>
                                <td style="padding: 15px 20px 15px; background:#79a1e1;" align="center">
                                    <p style="margin: 0; font-size: 12px; font-family: Helvetica, Arial, sans-serif;">&copy; {{YEAR}} <a style="color: #141637;" >{{$COMPANYNAME}}</a>. All Rights Reserved</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </body>
                </html>
                ',
            'subject' => 'Order {{$new_status}} Successfully.',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
