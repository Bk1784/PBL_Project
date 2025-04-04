<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => App\Http\Middleware\Admin::class,
            'admin.guest' => App\Http\Middleware\RedirectIfAuthenticatedAdmin::class, // middleware yang baru kita buat
            'client' => App\Http\Middleware\Client::class,
            'status' => App\Http\Middleware\ChangeStatus::class,
            'client.guest' => App\Http\Middleware\RedirectIfAuthenticatedClient::class, 
            'customer' => App\Http\Middleware\Customer::class,
            'customer.guest' => App\Http\Middleware\RedirectIfAuthenticatedCustomer::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
