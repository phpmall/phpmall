<?php

declare(strict_types=1);

namespace App\Gateways\Passport\Controllers;

use App\Gateways\Passport\Requests\Reset\ResetRequest;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class ResetController extends BaseController
{
    public function showResetForm(): JsonResponse|Renderable
    {
        return $this->response('auth::password.reset');
    }

    #[OA\Post(path: '/password/reset', summary: '通过验证码重新设置新密码', tags: ['重设密码'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ResetRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function reset(): JsonResponse
    {
        return $this->success(['reset']);
    }
}
