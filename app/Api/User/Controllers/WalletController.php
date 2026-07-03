<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use App\Api\User\Requests\Wallet\WalletIndexRequest;
use App\Api\User\Responses\Wallet\WalletBalanceResponse;
use App\Api\User\Responses\Wallet\WalletTransactionListResponse;
use App\Exceptions\NotImplementedException;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class WalletController extends BaseController
{
    #[OA\Get(path: '/wallet', security: [['bearerAuth' => []]], summary: 'Wallet Controller index', tags: ['会员中心'])]
    #[OA\Parameter(name: 'page', in: 'query', description: '页码', schema: new OA\Schema(type: 'integer', example: 1))]
    #[OA\Parameter(name: 'per_page', in: 'query', description: '每页数量', schema: new OA\Schema(type: 'integer', example: 20))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: WalletBalanceResponse::class))]
    public function index(WalletIndexRequest $request): JsonResponse
    {
        throw new NotImplementedException('TODO: implement '.__CLASS__.'::'.__FUNCTION__);
    }

    #[OA\Get(path: '/wallet/balance', security: [['bearerAuth' => []]], summary: 'Wallet Controller balance', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: WalletBalanceResponse::class))]
    public function balance(): JsonResponse
    {
        throw new NotImplementedException('TODO: implement '.__CLASS__.'::'.__FUNCTION__);
    }

    #[OA\Get(path: '/wallet/transactions', security: [['bearerAuth' => []]], summary: 'Wallet Controller transactions', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: WalletTransactionListResponse::class))]
    public function transactions(): JsonResponse
    {
        throw new NotImplementedException('TODO: implement '.__CLASS__.'::'.__FUNCTION__);
    }
}
