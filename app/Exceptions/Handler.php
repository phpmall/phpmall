<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
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

    public function render($request, Throwable $e): Response
    {
        $response = parent::render($request, $e);

        if ($request->is('api/*')) {
            if ($response instanceof JsonResponse) {
                return $response;
            }

            if ($e instanceof AuthenticationException) {
                return $this->errorResponse(401, $e);
            }

            if ($e instanceof ValidationException) {
                return $this->errorResponse($e->status, $e);
            }

            return $this->errorResponse($response->getStatusCode(), $e);
        }

        return $response;
    }

    private function errorResponse(int $code, Throwable $e): JsonResponse
    {
        return response()->json([
            'code' => $code,
            'message' => $e->getMessage(),
            'data' => config('app.debug') ? $e->getTrace() : null,
        ], $code);
    }
}
