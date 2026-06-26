<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ConsentController extends BaseController
{
    #[OA\Get(path: '/consents/history', security: [['bearerAuth' => []]], summary: 'Consent Controller history', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function history(): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/consents/withdraw', security: [['bearerAuth' => []]], summary: 'Consent Controller withdraw', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function withdraw(Request $request): JsonResponse
    {
        return $this->success();
    }
}
