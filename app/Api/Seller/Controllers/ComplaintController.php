<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use App\Api\Seller\Requests\Complaint\ComplaintAppealRequest;
use App\Api\Seller\Requests\Complaint\ComplaintRespondRequest;
use App\Api\Seller\Responses\Complaint\ComplaintListResponse;
use App\Api\Seller\Responses\Complaint\ComplaintResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class ComplaintController extends BaseController
{
    #[OA\Get(path: '/complaints', summary: '获取投诉列表', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ComplaintListResponse::class))]
    public function index(): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/complaints/{id}', summary: '获取投诉详情', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '投诉ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ComplaintResponse::class))]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/complaints/{id}/respond', summary: '回应投诉', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '投诉ID', in: 'path', required: true)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ComplaintRespondRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function respond(ComplaintRespondRequest $request, int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/complaints/{id}/appeal', summary: '申诉投诉', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '投诉ID', in: 'path', required: true)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ComplaintAppealRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function appeal(ComplaintAppealRequest $request, int $id): JsonResponse
    {
        return $this->success();
    }
}
