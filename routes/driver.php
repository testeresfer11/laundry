<?php

use App\Http\Controllers\driver\{AuthController, HelpDeskController, HomeController, NotificationController, OrderManagementController};
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::group(['prefix' =>'driver'],function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('login','login')->middleware('SetLocaleFromHeader');
        Route::post('forget-password','forgetPassword')->middleware('SetLocaleFromHeader');
        Route::post('verify-otp','verifyOtp')->middleware('SetLocaleFromHeader');
        Route::post('set-new-password','setNewPassword')->middleware('SetLocaleFromHeader');
    });

    Route::controller(HomeController::class)->group(function (){
        Route::get('contentPages/{slug}', 'contentPages');
       
    });

    Route::middleware(['driver','SetLocaleFromHeader'])->group(function () {
        Route::controller(AuthController::class)->group(function () {
            Route::get('logout','logOut');
            Route::post('change-password','changePassword');
            Route::match(['get','post'],'profile','Profile');
            Route::get('language','getLanguage');
            Route::post('update-language','updateLanguage');
        });
        Route::controller(HomeController::class)->group(function (){
            Route::get('home', 'index');
        });
        
        // Manage Help desk Routes
        Route::controller(HelpDeskController::class)->group(function () {
            Route::prefix('helpdesk')->group(function () {
                Route::get('/', 'list');
                Route::post('add', 'add');
                Route::match(['get','post'],'response/{id}', 'response');
                Route::get('changestatus/{id}', 'changeStatus');
            });
        });

        // Manage Notification Routes
        Route::controller(NotificationController::class)->group(function () {
            Route::prefix('notification')->group(function () {
                Route::get('/', 'list');
                Route::get('clear', 'clear');
                Route::get('address', 'address');

            });
        });


        // Manage Order routes
        Route::group(['prefix' => 'order', 'as' => 'order.'], function () {
            Route::controller(OrderManagementController::class)->group(function () {
                Route::get('view/{id}', 'view');
                Route::get( 'change/status/{id}/{status}', 'changeStatus');
                Route::get('history', 'history');
                Route::post('verify-delivery-code', 'verify_delivery_code');
            });
        });

    });
});