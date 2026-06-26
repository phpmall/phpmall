<?php

declare(strict_types=1);

namespace App\Api\Common\Controllers;

use App\Api\Common\Requests\Config\IndexRequest;
use App\Api\Common\Responses\Config\ConfigListResponse;
use App\Api\Common\Responses\Config\ConfigResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class ConfigController extends BaseController
{
    #[OA\Get(path: '/configs', summary: '配置列表', security: [[]], tags: ['公共工具'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ConfigListResponse::class))]
    public function index(IndexRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/configs/{id}', summary: '配置详情', security: [[]], tags: ['公共工具'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ConfigResponse::class))]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }
}
