<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class CommissionController extends BaseController
{
    #[OA\Get(path: '/commissions', summary: 'Commission Controller index', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/commissions/stats', security: [['bearerAuth' => []]], summary: 'Commission Controller stats', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function stats(): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/commissions/withdraw', security: [['bearerAuth' => []]], summary: 'Commission Controller withdraw', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function withdraw(Request $request): JsonResponse
    {
        return $this->success();
    }
}
