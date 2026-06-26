<?php

declare(strict_types=1);

namespace App\Api\Common\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class DictionaryController extends BaseController
{
    #[OA\Get(path: '/dictionaries', summary: '字典列表', security: [[]], tags: ['公共工具'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/dictionaries/{id}', summary: '字典详情', security: [[]], tags: ['公共工具'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }
}
