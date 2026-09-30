<?php

declare(strict_types=1);

namespace App\Api\Portal\Controllers;

use App\Api\Portal\Requests\Goods\GoodsSearchRequest;
use App\Api\Portal\Responses\Goods\CategoryTreeResponse;
use App\Api\Portal\Responses\Goods\GoodsDetailResponse;
use App\Api\Portal\Responses\Goods\GoodsItemResponse;
use App\Services\Goods\GoodsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;

#[OA\Tag(name: '前台-商品接口', description: 'PC 前台商城与移动多端统一商品检索、详情与分类接口')]
class GoodsController extends BaseController
{
    public function __construct(
        protected readonly GoodsService $goodsService,
    ) {}

    #[OA\Get(path: '/api/portal/goods', summary: '前台商品分页检索', tags: ['前台-商品接口'])]
    #[OA\Parameter(name: 'keyword', description: '搜索关键词', in: 'query', required: false, schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'category_id', description: '分类ID', in: 'query', required: false, schema: new OA\Schema(type: 'integer'))]
    #[OA\Parameter(name: 'min_price', description: '最低价格（分）', in: 'query', required: false, schema: new OA\Schema(type: 'integer'))]
    #[OA\Parameter(name: 'max_price', description: '最高价格（分）', in: 'query', required: false, schema: new OA\Schema(type: 'integer'))]
    #[OA\Parameter(name: 'sort_by', description: '排序：price|sales|created_at|sort_order', in: 'query', required: false, schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'sort_order', description: '排序方向：asc|desc', in: 'query', required: false, schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'is_hot', description: '是否热销：0-否，1-是', in: 'query', required: false, schema: new OA\Schema(type: 'integer'))]
    #[OA\Parameter(name: 'is_new', description: '是否新品：0-否，1-是', in: 'query', required: false, schema: new OA\Schema(type: 'integer'))]
    #[OA\Parameter(name: 'is_recommend', description: '是否推荐：0-否，1-是', in: 'query', required: false, schema: new OA\Schema(type: 'integer'))]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: false, schema: new OA\Schema(type: 'integer', default: 1))]
    #[OA\Parameter(name: 'pageSize', description: '每页条数', in: 'query', required: false, schema: new OA\Schema(type: 'integer', default: 20))]
    #[OA\Response(
        response: 200,
        description: '成功返回商品分页列表',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'code', type: 'integer', example: 0),
                new OA\Property(property: 'message', type: 'string', example: 'ok'),
                new OA\Property(
                    property: 'data',
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'total', type: 'integer', example: 100),
                        new OA\Property(property: 'current_page', type: 'integer', example: 1),
                        new OA\Property(property: 'last_page', type: 'integer', example: 5),
                        new OA\Property(property: 'per_page', type: 'integer', example: 20),
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: GoodsItemResponse::class)),
                    ]
                ),
            ]
        )
    )]
    public function search(GoodsSearchRequest $request): JsonResponse
    {
        $params = $request->validated();
        $page = (int) ($params['page'] ?? 1);
        $pageSize = (int) ($params['pageSize'] ?? 20);

        $paginator = $this->goodsService->search($params, $page, $pageSize);

        $transformed = collect($paginator->items())->map(function ($item) {
            return GoodsItemResponse::fromArray($item->toArray());
        })->all();

        return $this->success([
            'total' => $paginator->total(),
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'per_page' => $paginator->perPage(),
            'data' => $transformed,
        ]);
    }

    #[OA\Get(path: '/api/portal/goods/{id}', summary: '获取商品详情', tags: ['前台-商品接口'])]
    #[OA\Parameter(name: 'id', description: '商品ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer', example: 1001))]
    #[OA\Response(
        response: 200,
        description: '返回商品SPU、SKU规格价格列表与分类',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'code', type: 'integer', example: 0),
                new OA\Property(property: 'message', type: 'string', example: 'ok'),
                new OA\Property(property: 'data', ref: GoodsDetailResponse::class),
            ]
        )
    )]
    public function show(int $id): JsonResponse
    {
        $detail = $this->goodsService->getDetail($id);
        if (! $detail) {
            throw new BusinessException('商品不存在或已下架');
        }

        return $this->success(GoodsDetailResponse::fromDetail($detail));
    }

    #[OA\Get(path: '/api/portal/categories', summary: '获取全部分类树', tags: ['前台-商品接口'])]
    #[OA\Response(
        response: 200,
        description: '返回无限级商品分类树',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'code', type: 'integer', example: 0),
                new OA\Property(property: 'message', type: 'string', example: 'ok'),
                new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: CategoryTreeResponse::class)),
            ]
        )
    )]
    public function categories(): JsonResponse
    {
        $tree = $this->goodsService->getCategoryTree();

        return $this->success($tree);
    }

    #[OA\Get(path: '/api/portal/goods/featured', summary: '首页推荐/热销商品', tags: ['前台-商品接口'])]
    #[OA\Parameter(name: 'type', description: '类型：recommend|hot|new', in: 'query', required: false, schema: new OA\Schema(type: 'string', default: 'recommend'))]
    #[OA\Parameter(name: 'limit', description: '返回数量限制', in: 'query', required: false, schema: new OA\Schema(type: 'integer', default: 8))]
    #[OA\Response(
        response: 200,
        description: '返回指定类型的商品列表',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'code', type: 'integer', example: 0),
                new OA\Property(property: 'message', type: 'string', example: 'ok'),
                new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: GoodsItemResponse::class)),
            ]
        )
    )]
    public function featured(Request $request): JsonResponse
    {
        $type = (string) $request->query('type', 'recommend');
        $limit = (int) $request->query('limit', '8');

        $products = $this->goodsService->getFeaturedProducts($type, $limit);
        $data = $products->map(fn ($p) => GoodsItemResponse::fromArray($p->toArray()))->all();

        return $this->success($data);
    }
}
