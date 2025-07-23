<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\RewardPointController;
use App\Http\Controllers\customer\{AuthController,CustomerAddressController,HelpDeskController,HomeController,NotificationController,OrderManagementController,RatingReviewController,RewardPointsController, RedeemPointController, ServiceController,WalletController,StripeController};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['log.api'])->group(function () {
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/location-update', [AuthController::class,'locationUpdate'])->middleware('authenticate','SetLocaleFromHeader');

Route::get('countries/{id?}',[AuthController::class,'countries'])->middleware('auth:sanctum','SetLocaleFromHeader');

Route::post('/chat/joinroom', [ChatController::class,'joinRoom'])->middleware('SetLocaleFromHeader');
Route::post('/chat/send', [ChatController::class, 'sendMessage'])->middleware('SetLocaleFromHeader');
Route::get('/chat/messages/{roomId}', [ChatController::class, 'getMessages'])->middleware('SetLocaleFromHeader');

Route::get('/cron/update-expired-points', [RewardPointController::class, 'updateExpiredPointsCron']);

Route::group(['prefix' =>'customer'],function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('register','register')->middleware('SetLocaleFromHeader');
        Route::post('login','login')->middleware('SetLocaleFromHeader');
        Route::post('forget-password','forgetPassword')->middleware('SetLocaleFromHeader');
        Route::post('verify-otp','verifyOtp')->middleware('SetLocaleFromHeader');
        Route::post('set-new-password','setNewPassword')->middleware('SetLocaleFromHeader');
        Route::post('restoration-request','restoration_request')->middleware('SetLocaleFromHeader');
        Route::post('delete','deleteUser')->middleware('auth:sanctum','SetLocaleFromHeader');
        Route::post('social-login','handleSocialLogin')->middleware('SetLocaleFromHeader');;
    });


    Route::controller(HomeController::class)->group(function (){
        Route::get('contentPages/{slug}', 'contentPages');
    });
    
    Route::middleware(['customer', 'SetLocaleFromHeader'])->group(function () {

        Route::group(['prefix' => 'promotions', 'as' => 'promotion.'], function () {
            Route::controller(HomeController::class)->group(function () {
                Route::get('/{id?}', 'getPromotions');
            });
        });

        Route::controller(AuthController::class)->group(function () {
            Route::get('logout','logOut');
            Route::post('change-password','changePassword');
            Route::match(['get','post'],'profile','Profile');
            Route::get('language','getLanguage');
            Route::post('update-language','updateLanguage');
        });

        // Manage Home routes
        Route::group(['prefix' => 'home', 'as' => 'home.'], function () {
            Route::controller(HomeController::class)->group(function () {
                Route::get('/', 'index')->name('index');
            });
        });
        

        // Manage Address routes
        Route::group(['prefix' => 'address', 'as' => 'address.'], function () {
            Route::controller(CustomerAddressController::class)->group(function () {
                Route::get('list', 'getList');
                Route::post('add', 'add')->name('add');
                Route::match(['get', 'post'], 'edit/{id?}', 'edit');
                Route::get('delete/{id}', 'delete')->name('delete');
            });
        });
        
        // Manage Service routes
        Route::group(['prefix' => 'service', 'as' => 'service.'], function () {
            Route::controller(ServiceController::class)->group(function () {
                Route::get('/', 'list')->name('list');
                Route::get('variant/{id}', 'variant')->name('variant');
            });
        });

        // Manage Order routes
        Route::group(['prefix' => 'order', 'as' => 'order.'], function () {
            Route::controller(OrderManagementController::class)->group(function () {
                Route::post('availability', 'availability')->name('availability');
                Route::post('tax', 'tax')->name('tax');
                Route::post('create', 'create')->name('create');
                Route::get('list', 'getList')->name('list');
                Route::get('view/{id}', 'view')->name('view');
                Route::post('delivery', 'delivery')->name('delivery');
            });
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
    });

    Route::middleware(['customer'])->group(function (){
        // Manage Notification Routes
        Route::controller(NotificationController::class)->group(function () {
            Route::prefix('notification')->group(function () {
                Route::get('/', 'list');
                Route::get('clear', 'clear');
            });
        });
    });

    Route::middleware(['customer', 'SetLocaleFromHeader'])->group(function () {
         // Manage Notification Routes
         Route::controller(RatingReviewController::class)->group(function () {
            Route::prefix('rating')->group(function () {
                Route::post('/', 'rating');
                Route::get('/view', 'viewDriverRating');
            });
        });

        Route::controller(RewardPointsController::class)->group(function () {
            Route::prefix('points')->group(function () {
                Route::get('/getPointsEarned', 'getPointsEarned')->middleware('auth:sanctum');
                Route::get('/history', 'history')->middleware('auth:sanctum');
                Route::get('/settings', 'getPointSettings')->middleware('auth:sanctum');
            });
        });

        Route::controller(RedeemPointController::class)->group(function () {
            Route::prefix('points')->group(function () {
                Route::post('/redeemedpoints', 'redeemedPoint')->middleware('auth:sanctum');
                Route::get('/getredeemedpoints', 'getRedeemedPoints')->middleware('auth:sanctum');
            });
        });

        // Walet routes
        Route::controller(WalletController::class)->group(function () {
            Route::prefix('wallet')->group(function () {
                Route::get('card', 'getSavedCards');
                Route::post('create', 'createPaymentIntent');
                Route::get('detail', 'detail');
                Route::post('payment', 'createPayment');
            });
        });

        Route::controller(StripeController::class)->group(function (){
            Route::prefix('stripe')->group(function () {
                Route::post('payment', 'createPaymentIntent');
                Route::get('secretkey', 'secretKey');
            });
        });

    });
});
require 'driver.php';
});
