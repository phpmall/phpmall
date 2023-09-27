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

    public function render($request, Throwable $e): JsonResponse
    {
        $response = parent::render($request, $e);

        if ($response instanceof JsonResponse) {
            return $response;
        }

        return $this->response($response->getStatusCode(), $e);
    }

    protected function unauthenticated($request, AuthenticationException $exception): JsonResponse
    {
        return $this->response(401, $exception);
    }

    protected function convertValidationExceptionToResponse(ValidationException $e, $request): Response
    {
        if ($e->response) {
            return $e->response;
        }

        return $this->response($e->status, $e);
    }

    private function response(int $code, Throwable $e): JsonResponse
    {
        return response()->json([
            'code' => $code,
            'message' => $e->getMessage(),
            'data' => config('app.debug') ? $e->getTrace() : null,
        ], $code);
    }
}
