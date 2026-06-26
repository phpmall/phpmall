<?php

declare(strict_types=1);

namespace App\Api\Common\Controllers;

use App\Api\Common\Requests\Dictionary\IndexRequest;
use App\Api\Common\Responses\Dictionary\DictionaryListResponse;
use App\Api\Common\Responses\Dictionary\DictionaryResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class DictionaryController extends BaseController
{
    #[OA\Get(path: '/dictionaries', summary: '字典列表', security: [[]], tags: ['公共工具'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: DictionaryListResponse::class))]
    public function index(IndexRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/dictionaries/{id}', summary: '字典详情', security: [[]], tags: ['公共工具'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: DictionaryResponse::class))]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }
}
