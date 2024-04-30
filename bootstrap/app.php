<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

function json(int $code, Throwable $e): JsonResponse
{
    $data = null;

    // 如果是开发环境，添加额外的错误信息
    if (config('app.debug')) {
        $data = [
            'exception' => get_class($e), // 异常类名
            'trace' => $e->getTraceAsString(), // 异常追踪信息
        ];
    }

    return response()->json([
        'code' => $code,
        'message' => $e->getMessage(),
        'data' => $data,
    ], $code);
}

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
            return json(401, $e);
        });

        $exceptions->render(function (ValidationException $e) {
            return json($e->status, $e);
        });

        $exceptions->render(function (Exception $e) {
            return json(500, $e);
        });
    })->create();
