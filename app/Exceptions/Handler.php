<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
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

    /**
     * Convert an authentication exception into a response.
     */
    protected function unauthenticated($request, AuthenticationException $exception): JsonResponse|RedirectResponse
    {
        if ($this->shouldReturnJson($request, $exception)) {
            return response()->json([
                'code' => 401,
                'message' => $exception->getMessage(),
                'data' => config('app.debug') ? $exception->getTrace() : null,
            ], 401);
        } else {
            $parameters = ['callback' => $request->fullUrl()];

            return redirect()->guest(route('login', $parameters));
        }
    }
}
