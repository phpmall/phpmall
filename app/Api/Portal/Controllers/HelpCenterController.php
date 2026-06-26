<?php

declare(strict_types=1);

namespace App\Api\Portal\Controllers;

use App\Api\Portal\Requests\HelpCenter\HelpCenterIndexRequest;
use App\Api\Portal\Requests\HelpCenter\HelpCenterSearchRequest;
use App\Api\Portal\Responses\HelpCenter\HelpCenterCategoryResponse;
use App\Api\Portal\Responses\HelpCenter\HelpCenterListResponse;
use App\Api\Portal\Responses\HelpCenter\HelpCenterResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class HelpCenterController extends BaseController
{
    #[OA\Get(path: '/help-center', summary: '帮助中心列表', security: [[]], tags: ['商城平台'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: HelpCenterListResponse::class))]
    public function index(HelpCenterIndexRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/help-center/{id}', summary: '帮助中心详情', security: [[]], tags: ['商城平台'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: HelpCenterResponse::class))]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/help-center/search', summary: '帮助中心搜索', security: [[]], tags: ['商城平台'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: HelpCenterListResponse::class))]
    public function search(HelpCenterSearchRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/help-center/categories', summary: '帮助中心分类', security: [[]], tags: ['商城平台'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: HelpCenterCategoryResponse::class))]
    public function categories(): JsonResponse
    {
        return $this->success();
    }
}
