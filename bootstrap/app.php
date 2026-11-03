<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\Auth\AuthenticationMiddleware;
use App\Http\Middleware\CustomerMiddleware;
use App\Http\Middleware\Locale\ResolveLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $authMiddleware = AuthenticationMiddleware::class;
        $adminMiddleware = AdminMiddleware::class;
        $customerMiddleware = CustomerMiddleware::class;
        $localizationMiddleware = ResolveLocale::class;


        // Authentication
        $middleware->appendToGroup('generalMiddlewares', [
            $localizationMiddleware,
        ]);

        // Authentication
        $middleware->appendToGroup('authenticationMiddlewares', [
            $authMiddleware,
        ]);

        // Admin
        $middleware->appendToGroup('adminMiddlewares', [
            $adminMiddleware,
        ]);
        // Member
        $middleware->appendToGroup('customerMiddlewares', [
            $customerMiddleware,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
