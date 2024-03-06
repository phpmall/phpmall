<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e): JsonResponse
    {
        $response = parent::render($request, $e);
        if ($response instanceof JsonResponse) {
            return $response;
        }

        // 根据不同的异常类型，设置不同的状态码和错误信息
        if ($e instanceof ValidationException) {
            return $this->json($e->status, $e);
        } elseif ($e instanceof AuthenticationException) {
            return $this->json(401, $e);
        } else {
            return $this->json($response->getStatusCode() ?? 500, $e);
        }
    }

    private function json(int $code, Throwable $e): JsonResponse
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
}
