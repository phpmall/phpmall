<?php

declare(strict_types=1);

namespace App\Api\Common\Controllers;

use App\Api\Common\Requests\Agreement\LatestRequest;
use App\Api\Common\Responses\Agreement\AgreementResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class AgreementController extends BaseController
{
    #[OA\Get(path: '/agreements/{id}', summary: '协议详情', security: [[]], tags: ['公共工具'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AgreementResponse::class))]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/latest', summary: '最新协议', security: [[]], tags: ['公共工具'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AgreementResponse::class))]
    public function latest(LatestRequest $request): JsonResponse
    {
        return $this->success();
    }
}
