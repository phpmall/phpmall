<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use App\Api\User\Requests\Consent\ConsentWithdrawRequest;
use App\Api\User\Responses\Consent\ConsentHistoryListResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class ConsentController extends BaseController
{
    #[OA\Get(path: '/consents/history', security: [['bearerAuth' => []]], summary: 'Consent Controller history', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ConsentHistoryListResponse::class))]
    public function history(): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/consents/withdraw', security: [['bearerAuth' => []]], summary: 'Consent Controller withdraw', tags: ['会员中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ConsentWithdrawRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function withdraw(ConsentWithdrawRequest $request): JsonResponse
    {
        return $this->success();
    }
}
