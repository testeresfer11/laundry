<?php

use App\Http\Middleware\Admin;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\SetUserLanguage;
use App\Http\Middleware\Customer;
use App\Http\Middleware\Driver;
use App\Http\Middleware\User;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        apiPrefix: 'api'
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin'             => Admin::class,
            'customer'          => Customer::class,
            'driver'            => Driver::class,
            'authenticate'      => Authenticate::class,
            'setUserLanguage'   => SetUserLanguage::class,
            'previous_history'    => \App\Http\Middleware\PreventBackHistory::class,
            'SetLocaleFromHeader' =>  \App\Http\Middleware\SetLocaleFromHeader::class,
            'log.api'             => \App\Http\Middleware\LogApiRequest::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
