<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use App\Api\Seller\Responses\Wallet\WalletBalanceResponse;
use App\Api\Seller\Responses\Wallet\WalletResponse;
use App\Api\Seller\Responses\Wallet\WalletTransactionListResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class WalletController extends BaseController
{
    #[OA\Get(path: '/wallet', summary: '获取钱包信息', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: WalletResponse::class))]
    public function index(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/wallet/balance', summary: '获取钱包余额', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: WalletBalanceResponse::class))]
    public function balance(): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/wallet/transactions', summary: '获取钱包交易记录', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: WalletTransactionListResponse::class))]
    public function transactions(): JsonResponse
    {
        return $this->success();
    }
}
