<?php

declare(strict_types=1);

use App\Exceptions\CustomException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        if (request()->is('api/*')) {
            // 认证异常
            $exceptions->renderable(function (AuthenticationException $e) {
                return response()->json([
                    'code' => Response::HTTP_UNAUTHORIZED,
                    'message' => $e->getMessage(),
                    'data' => null,
                ], Response::HTTP_UNAUTHORIZED);
            });

            // 验证异常
            $exceptions->renderable(function (ValidationException $e) {
                return response()->json([
                    'code' => $e->status,
                    'message' => $e->errors(),
                    'data' => null,
                ], $e->status);
            });

            // 自定义异常
            $exceptions->renderable(function (CustomException $e) {
                return response()->json([
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                    'data' => null,
                ], $e->getCode());
            });

            // NOTFOUND 异常
            $exceptions->renderable(function (NotFoundHttpException $e) {
                return response()->json([
                    'code' => Response::HTTP_NOT_FOUND,
                    'message' => $e->getMessage(),
                    'data' => null,
                ], Response::HTTP_NOT_FOUND);
            });

            // 统一错误处理
            $exceptions->renderable(function (Throwable $e) {
                return response()->json([
                    'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => $e->getMessage(),
                    'data' => null,
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            });
        }
    })->create();
