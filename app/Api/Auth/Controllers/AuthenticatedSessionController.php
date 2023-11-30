<?php

declare(strict_types=1);

namespace App\Api\Auth\Controllers;

use App\Api\Auth\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

class AuthenticatedSessionController extends BaseController
{
    #[OA\Post(path: '/admin', summary: '用户登录', security: [['bearerAuth' => []]], tags: ['认证'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function store(LoginRequest $request): Response
    {
        $request->authenticate();

        $request->session()->regenerate();

        return response()->noContent();
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    }
}
