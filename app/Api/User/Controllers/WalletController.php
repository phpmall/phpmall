<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class WalletController extends BaseController
{
    #[OA\Get(path: '/wallet', summary: 'Wallet Controller index', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/wallet/balance', security: [['bearerAuth' => []]], summary: 'Wallet Controller balance', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function balance(): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/wallet/transactions', security: [['bearerAuth' => []]], summary: 'Wallet Controller transactions', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function transactions(): JsonResponse
    {
        return $this->success();
    }
}
