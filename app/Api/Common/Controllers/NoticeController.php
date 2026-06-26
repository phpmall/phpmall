<?php

declare(strict_types=1);

namespace App\Api\Common\Controllers;

use App\Api\Common\Requests\Notice\IndexRequest;
use App\Api\Common\Responses\Notice\NoticeListResponse;
use App\Api\Common\Responses\Notice\NoticeResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class NoticeController extends BaseController
{
    #[OA\Get(path: '/notices', summary: '公告列表', security: [[]], tags: ['公共工具'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: NoticeListResponse::class))]
    public function index(IndexRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/notices/{id}', summary: '公告详情', security: [[]], tags: ['公共工具'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: NoticeResponse::class))]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }
}
