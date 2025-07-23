<?php

use App\Http\Controllers\admin\{AuthController,RoleController, ConfigSettingController, ContentPageController, CustomerAddressController, CustomerController, DashboardController, HelpDeskController, ManageFAQController, OrderManagementController, PromotionController, ServiceController, TimeSheduleController, TransactionController, VariantController, VehicleController,HolidayController,DriverController, TaxManagementController,InStoreOrderController, NotificationController, PointManagementController, WalletController};
use App\Http\Controllers\admin\IncomeReportController;
use App\Http\Controllers\admin\StaffController;
use Illuminate\Support\Facades\Route;

Route::get('/',function(){
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    if (auth()->check()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('login');
});

Route::controller(AuthController::class)->group(function () {
    Route::match(['get','post'],'login','login')->name('login')->middleware('previous_history','SetLocaleFromHeader');
    Route::match(['get','post'],'forget-password','forgetPassword')->name('forget-password')->middleware('previous_history');
    Route::match(['get','post'],'reset-password/{token}','resetPassword')->name('reset-password')->middleware('previous_history');
});

// Route::group(['prefix' =>'admin'],function () {
    Route::middleware(['auth','admin','previous_history','SetLocaleFromHeader'])->prefix('admin')->name('admin.')->group(function () {
            Route::controller(DashboardController::class)->group(function () {
                Route::get('/dashboard','index')->name('dashboard');
                Route::get('trashed/list','getTrashedList')->name('trashed.list');
                Route::get('/restore/{id}','restore')->name('restore');
            });
        
            // Manage auth routes
            Route::controller(AuthController::class)->group(function () {
               Route::match(['get', 'post'],'profile','profile')->name('profile');
               Route::match(['get', 'post'],'changePassword','changePassword')->name('changePassword');
               Route::get('logout','logout')->name('logout');
            });
            
            // Manage customer routes
            Route::group(['prefix' => 'customer', 'as' => 'customer.'], function () {
                Route::controller(CustomerController::class)->group(function () {
                    Route::get('list', 'getList')->name('list');
                    Route::match(['get', 'post'], 'add', 'add')->name('add');
                    Route::get('view/{id}', 'view')->name('view');
                    Route::match(['get', 'post'], 'edit/{id}', 'edit')->name('edit');
                    Route::get('delete/{id}', 'delete')->name('delete');
                    Route::get('changeStatus', 'changeStatus')->name('changeStatus');
                });

                // Manage customer address routes
                Route::group(['prefix' => 'address', 'as' => 'address.'], function () {
                    Route::controller(CustomerAddressController::class)->group(function () {
                        Route::get('list/{id}', 'getList')->name('list');
                        Route::match(['get', 'post'], 'add/{id}', 'add')->name('add');
                        Route::match(['get', 'post'], 'edit/{id}', 'edit')->name('edit');
                        Route::get('delete/{id}', 'delete')->name('delete');
                        Route::get('changeStatus', 'changeStatus')->name('changeStatus');
                    });
                });
            });


            // Manage driver routes
            Route::group(['prefix' =>'driver'],function () {
                Route::name('driver.')->controller(DriverController::class)->group(function () {
                    Route::get('list','getList')->name('list');
                    Route::match(['get', 'post'],'add','add')->name('add');
                    Route::get('view/{id}','view')->name('view');
                    Route::match(['get', 'post'],'edit/{id}','edit')->name('edit');
                    Route::get('delete/{id}','delete')->name('delete');
                    Route::get('changeStatus','changeStatus')->name('changeStatus');
                });
            });

            // Manage Staff routes
            Route::group(['prefix' =>'staff'],function () {
                Route::name('staff.')->controller(StaffController::class)->group(function () {
                    Route::get('list','getList')->name('list');
                    Route::match(['get', 'post'],'add','add')->name('add');
                    Route::get('view/{id}','view')->name('view');
                    Route::match(['get', 'post'],'edit/{id}','edit')->name('edit');
                    Route::get('delete/{id}','delete')->name('delete');
                    Route::get('changeStatus','changeStatus')->name('changeStatus');
                });
            });

            // Manage services
            Route::group(['prefix' =>'service'],function () {
                Route::name('service.')->controller(ServiceController::class)->group(function () {
                    Route::get('list','getList')->name('list');
                    Route::match(['get', 'post'],'add','add')->name('add');
                    Route::get('view/{id}','view')->name('view');
                    Route::match(['get', 'post'],'edit/{id}','edit')->name('edit');
                    Route::match(['get', 'post'],'variant/{id}','variant')->name('variant');
                    
                    Route::get('variantList/{id}','variantList');
                    Route::post('addVariant','addVariant')->name('addVariant');
                    Route::post('removeVariant/{id}','removeVariant');

                    Route::post('delete/{id}','delete')->name('delete');
                    Route::get('changeStatus','changeStatus')->name('changeStatus');
                });
            });

             // Manage tax route
             Route::group(['prefix' =>'tax'],function () {
                Route::name('tax.')->controller(TaxManagementController::class)->group(function () {
                    Route::get('list','getList')->name('list');
                    Route::match(['get', 'post'],'add','add')->name('add');
                    Route::match(['get', 'post'],'edit/{id}','edit')->name('edit');
                    Route::get('delete/{id}','delete')->name('delete');
                    Route::get('changeStatus','changeStatus')->name('changeStatus');
                });
            });


            // Manage variant routes
            Route::group(['prefix' =>'variant'],function () {
                Route::name('variant.')->controller(VariantController::class)->group(function () {
                    Route::get('list','getList')->name('list');
                    Route::post('add','add')->name('add');
                    Route::match(['get', 'post'],'edit/{id?}','edit')->name('edit');
                    Route::post('delete/{id}','delete')->name('delete');
                    Route::get('changeStatus','changeStatus')->name('changeStatus');
                });
            });

            // Manage promotion routes
            Route::group(['prefix' =>'promotion'],function () {
                Route::name('promotion.')->controller(PromotionController::class)->group(function () {
                    Route::get('list','getList')->name('list');
                    Route::match(['get', 'post'],'add','add')->name('add');
                    Route::get('view/{id}','view')->name('view');
                    Route::match(['get', 'post'],'edit/{id}','edit')->name('edit');
                    Route::post('delete/{id}','delete')->name('delete');
                    Route::get('changeStatus','changeStatus')->name('changeStatus');
                });
            });

             // Manage Points routes
             Route::group(['prefix' =>'points'],function () {
                Route::name('points.')->controller(PointManagementController::class)->group(function () {
                    Route::get('list','getList')->name('list');
                    Route::match(['get', 'post'],'add','add')->name('add');
                    Route::get('view/{id}','view')->name('view');
                    Route::match(['get', 'post'],'edit/{id}','edit')->name('edit');
                    Route::post('delete/{id}','delete')->name('delete');
                    Route::get('changeStatus','changeStatus')->name('changeStatus');
                });
            });

            // Manage Config setting routes
            Route::group(['prefix' =>'contentPages'],function () {
                Route::name('contentPages.')->controller(ContentPageController::class)->group(function () {
                    Route::match(['get', 'post'],'{slug}','contentPageDetail')->name('detail');
                });
            });


            // Manage Income report routes
            Route::group(['prefix' =>'income'],function () {
                Route::name('income.')->controller(IncomeReportController::class)->group(function () {
                    Route::get('list','getList')->name('list');
                    Route::get('export','export')->name('export');
                });
            });

            // Manage vehicle routes
            Route::group(['prefix' =>'vehicle'],function () {
                Route::name('vehicle.')->controller(VehicleController::class)->group(function () {
                    Route::get('list','getList')->name('list');
                    Route::post('add','add')->name('add');
                    Route::match(['get', 'post'],'edit/{id?}','edit')->name('edit');
                    Route::post('delete/{id}','delete')->name('delete');
                    Route::get('changeStatus','changeStatus')->name('changeStatus');
                });
            });
            
            /**Manage FAQ routes */
            Route::group(['prefix' =>'f-a-q'],function () {
                Route::name('f-a-q.')->controller(ManageFAQController::class)->group(function () {
                    Route::get('/','getList')->name('list');
                    Route::match(['get','post'],'add','add')->name('add');
                    Route::match(['get','post'],'edit/{id}','edit')->name('edit');
                    Route::get('delete/{id}','delete')->name('delete');
                    Route::get('changeStatus','changeStatus')->name('changeStatus');
                });
            });

            /**Manage Role routes */
            Route::group(['prefix' =>'role'],function () {
                Route::name('role.')->controller(RoleController::class)->group(function () {
                    Route::get('/','getList')->name('list');
                    Route::match(['get','post'],'add','add')->name('add');
                    Route::match(['get','post'],'edit/{id}','edit')->name('edit');
                    Route::get('delete/{id}','delete')->name('delete');
                    Route::get('changeStatus','changeStatus')->name('changeStatus');
                });
            });

            // Manage help desk routes
            Route::group(['prefix' =>'helpDesk'],function () {
                Route::name('helpDesk.')->controller(HelpDeskController::class)->group(function () {
                    Route::get('list/{type}','getList')->name('list');
                    Route::match(['get', 'post'],'add','add')->name('add');
                    Route::match(['get', 'post'],'response/{id}','response')->name('response');
                    Route::get('changeStatus','changeStatus')->name('changeStatus');
                });
            });

            // Manage Config setting routes
            Route::group(['prefix' =>'config-setting'],function () {
                Route::name('config-setting.')->controller(ConfigSettingController::class)->group(function () {
                    Route::match(['get', 'post'],'smtp','smtpInformation')->name('smtp');
                    Route::match(['get', 'post'],'stripe','stripeInformation')->name('stripe');
                    Route::match(['get', 'post'],'config','configInformation')->name('config');
                    Route::match(['get', 'post'],'delivery-cost','DeliveryCostInformation')->name('delivery-cost');
                    Route::match(['get', 'post'],'general-setting','generalInformation')->name('general-setting');
                
                });

                Route::name('config-setting.timeShedule.')->controller(TimeSheduleController::class)->group(function () {
                    Route::get('timeShedule/list','getList')->name('list');
                    Route::post('add','add')->name('add');
                    Route::match(['get', 'post'],'edit/{id?}','edit')->name('edit');
                    Route::get('changeStatus','changeStatus')->name('changeStatus');
                    Route::get('changeStatus','changeStatus')->name('changeStatus');
                    Route::post('timeSlot','timeSlot')->name('timeSlot');
                });

                Route::group(['prefix' =>'holiday'],function () {
                    Route::name('config-setting.holiday.')->controller(HolidayController::class)->group(function () {
                        Route::get('/','getList')->name('list');
                        Route::post('add','add')->name('add');
                        Route::get('delete/{id}', 'delete')->name('delete');
                        Route::match(['get', 'post'],'edit/{id?}','edit')->name('edit');
                        Route::get('changeStatus','changeStatus')->name('changeStatus');
                    });
                });
            });


            //Manage notification routes
            Route::group(['prefix' =>'notification'],function () {
                Route::name('notification.')->controller(NotificationController::class)->group(function () {
                    Route::get('/','getList')->name('list');
                    Route::get('read/{id}','notificationRead')->name('read');
                    Route::post('delete/{id}','delete')->name('delete');
                });
            });


            // Manage transactions routes
            Route::group(['prefix' =>'transaction'],function () {
                Route::name('transaction.')->controller(TransactionController::class)->group(function () {
                    Route::get('list','getList')->name('list');
                    Route::get('view/{id}','view')->name('view');
                });
            });

             // Manage In Store Order Management routes
             Route::group(['prefix' =>'storeOrder'],function () {
                Route::name('storeOrder.')->controller(InStoreOrderController::class)->group(function () {
                    Route::get('list','getList')->name('list');
                    Route::match(['get','post'],'create','create')->name('create');
                    Route::get('view/{id}','view')->name('view');
                    Route::get(uri: 'invoiceDownload/{id}',action: 'downloadInvoice')->name('invoice.download');
                    Route::post( uri: 'paid', action: 'paid')->name('paid');
                    Route::match(['get','post'],'edit/{id}','edit')->name('edit');
                });
            });

            // Manage order routes
            Route::group(['prefix' =>'order'],function () {
                Route::name('order.')->controller(OrderManagementController::class)->group(function () {
                    Route::get('list/{type}','getList')->name('list');
                    Route::match(['get','post'],'create','create')->name('create');
                    Route::match(['get','post'],'edit/{id}','edit')->name('edit');
                    Route::get('view/{id}', 'view')->name('view');
                    Route::get( 'change/status/{id}/{status}', 'changeStatus')->name('changeStatus');
                    Route::post(  'reject', 'reject')->name('reject');
                    Route::post( 'assignDriver', 'assignDriver')->name('assignDriver');
                    Route::group(['prefix' =>'service'],function () {
                        Route::name('service.')->group(function () {
                            Route::group(['prefix' =>'variant'],function () {
                                Route::name('variant.')->group(function () {
                                    Route::get('{serviceId}','getServiceVariant')->name('variant');
                                    Route::get('price/{serviceId}/{variantId}','getServiceVariantPrice');
                                });
                            });
                            Route::get('remove/{id}','removeService');
                            Route::get('update/{id}/{amount}/{qty}','updateServiceAmount');
                        });
                    });
                });
            });

            // Manage wallet routes
             Route::group(['prefix' =>'wallet'],function () {
                Route::name('wallet.')->controller(WalletController::class)->group(function () {
                    Route::get('list','getList')->name('list');
                    Route::get('view/{id}','view')->name('view');
                });
            });

    });
// });


// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');