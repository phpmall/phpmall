<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use App\Api\User\Requests\MemberLevel\MemberLevelIndexRequest;
use App\Api\User\Responses\MemberLevel\MemberLevelBenefitsResponse;
use App\Api\User\Responses\MemberLevel\MemberLevelListResponse;
use App\Api\User\Responses\MemberLevel\MemberLevelResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class MemberLevelController extends BaseController
{
    #[OA\Get(path: '/member-levels', security: [['bearerAuth' => []]], summary: 'Member Level Controller index', tags: ['会员中心'])]
    #[OA\Parameter(name: 'page', in: 'query', description: '页码', schema: new OA\Schema(type: 'integer', example: 1))]
    #[OA\Parameter(name: 'per_page', in: 'query', description: '每页数量', schema: new OA\Schema(type: 'integer', example: 20))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: MemberLevelListResponse::class))]
    public function index(MemberLevelIndexRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/member-levels/{id}', security: [['bearerAuth' => []]], summary: 'Member Level Controller show', tags: ['会员中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: MemberLevelResponse::class))]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/member-levels/{id}/benefits', security: [['bearerAuth' => []]], summary: 'Member Level Controller benefits', tags: ['会员中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: MemberLevelBenefitsResponse::class))]
    public function benefits(int $id): JsonResponse
    {
        return $this->success();
    }
}
