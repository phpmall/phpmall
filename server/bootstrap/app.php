<?php

declare(strict_types=1);

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AuthenticationException $e) {
            return response()->json([
                'code' => 401,
                'message' => $e->getMessage(),
                'data' => null,
            ], 401);
        });

        $exceptions->render(function (ValidationException $e) {
            return response()->json([
                'code' => $e->status,
                'message' => $e->getMessage(),
                'data' => null,
            ], $e->status);
        });

        $exceptions->render(function (Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => $e->getMessage(),
                'data' => null,
            ], 500);
        });
    })->create();
