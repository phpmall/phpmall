<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use App\Api\User\Requests\Complaint\ComplaintEvidenceRequest;
use App\Api\User\Requests\Complaint\ComplaintIndexRequest;
use App\Api\User\Requests\Complaint\ComplaintStoreRequest;
use App\Api\User\Responses\Complaint\ComplaintEvidenceResponse;
use App\Api\User\Responses\Complaint\ComplaintListResponse;
use App\Api\User\Responses\Complaint\ComplaintResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class ComplaintController extends BaseController
{
    #[OA\Get(path: '/complaints', security: [['bearerAuth' => []]], summary: 'Complaint Controller index', tags: ['会员中心'])]
    #[OA\Parameter(name: 'status', description: '投诉状态', in: 'query', required: false, schema: new OA\Schema(type: 'integer', nullable: true))]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: false, schema: new OA\Schema(type: 'integer', example: 1))]
    #[OA\Parameter(name: 'per_page', description: '每页数量', in: 'query', required: false, schema: new OA\Schema(type: 'integer', example: 20))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ComplaintListResponse::class))]
    public function index(ComplaintIndexRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/complaints', security: [['bearerAuth' => []]], summary: 'Complaint Controller store', tags: ['会员中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ComplaintStoreRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ComplaintResponse::class))]
    public function store(ComplaintStoreRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/complaints/{id}', security: [['bearerAuth' => []]], summary: 'Complaint Controller show', tags: ['会员中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ComplaintResponse::class))]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/complaints/{id}/evidence', security: [['bearerAuth' => []]], summary: 'Complaint Controller evidence', tags: ['会员中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ComplaintEvidenceRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ComplaintEvidenceResponse::class))]
    public function evidence(ComplaintEvidenceRequest $request): JsonResponse
    {
        return $this->success();
    }
}
