<?php

declare(strict_types=1);

namespace App\Gateways\User\Controllers;

use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class IndexController extends BaseController
{
    #[OA\Get(path: '/user', summary: '用户概要信息', tags: ['用户首页'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(): JsonResponse
    {
        return $this->success(['user::index']);
    }
}
