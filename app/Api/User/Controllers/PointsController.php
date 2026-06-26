<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class PointsController extends BaseController
{
    #[OA\Get(path: '/points', summary: 'Points Controller index', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/points/history', security: [['bearerAuth' => []]], summary: 'Points Controller history', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function history(): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/points/exchange', security: [['bearerAuth' => []]], summary: 'Points Controller exchange', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function exchange(Request $request): JsonResponse
    {
        return $this->success();
    }
}
