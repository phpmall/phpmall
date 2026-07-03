<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use App\Api\Seller\Requests\ShopReview\ShopReviewIndexRequest;
use App\Api\Seller\Requests\ShopReview\ShopReviewReplyRequest;
use App\Api\Seller\Responses\ShopReview\ShopReviewListResponse;
use App\Exceptions\NotImplementedException;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class ShopReviewController extends BaseController
{
    #[OA\Get(path: '/shop-reviews', summary: '获取店铺评价列表', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: false, schema: new OA\Schema(type: 'integer', example: 1))]
    #[OA\Parameter(name: 'per_page', description: '每页数量', in: 'query', required: false, schema: new OA\Schema(type: 'integer', example: 20))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopReviewListResponse::class))]
    public function index(ShopReviewIndexRequest $request): JsonResponse
    {
        throw new NotImplementedException('TODO: implement '.__CLASS__.'::'.__FUNCTION__);
    }

    #[OA\Post(path: '/shop-reviews/{id}/reply', summary: '回复店铺评价', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '评价ID', in: 'path', required: true)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShopReviewReplyRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function reply(ShopReviewReplyRequest $request, int $id): JsonResponse
    {
        throw new NotImplementedException('TODO: implement '.__CLASS__.'::'.__FUNCTION__);
    }
}
