<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\Requests\Reset\ResetRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ResetController extends BaseController
{
    #[OA\Post(path: '/reset', summary: '通过验证码重新设置新密码', tags: ['重设密码'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ResetRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function reset(Request $request): JsonResponse
    {
        return $this->success(['reset']);
    }
}
