<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class KycController extends BaseController
{
    #[OA\Post(path: '/kyc/submit', security: [['bearerAuth' => []]], summary: 'Kyc Controller submit', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function submit(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/kyc/status', security: [['bearerAuth' => []]], summary: 'Kyc Controller status', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function status(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/kyc/resubmit', security: [['bearerAuth' => []]], summary: 'Kyc Controller resubmit', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function resubmit(Request $request): JsonResponse
    {
        return $this->success();
    }
}
