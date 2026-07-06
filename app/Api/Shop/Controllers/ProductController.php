<?php

declare(strict_types=1);

namespace App\Api\Shop\Controllers;

use App\Api\Shop\Requests\Product\ProductIndexRequest;
use App\Api\Shop\Responses\Product\ProductListResponse;
use App\Api\Shop\Responses\Product\ProductResponse;
use App\Api\Shop\Responses\Product\ProductSkuListResponse;
use App\Api\Shop\Responses\Product\ProductSkuResponse;
use App\Modules\Product\Services\ProductService;
use App\Modules\Product\Services\ProductSkuService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class ProductController extends BaseController
{
    public function __construct(
        private readonly ProductService $productService,
        private readonly ProductSkuService $productSkuService,
    ) {}

    #[OA\Get(path: '/products', summary: '商品列表', security: [[]], tags: ['店铺'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ProductListResponse::class))]
    public function index(ProductIndexRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/products/{id}', summary: '商品详情', security: [[]], tags: ['店铺'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ProductResponse::class))]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/products/{id}/skus', summary: '商品SKU列表', security: [[]], tags: ['店铺'])]
    #[OA\Parameter(name: 'id', description: '商品ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ProductSkuListResponse::class))]
    public function skus(int $id): JsonResponse
    {
        $product = $this->productService->getRepository()->findById($id);
        if (empty($product)) {
            return $this->error('商品不存在', 404);
        }

        $skuList = $this->productSkuService->getRepository()->findAll(['product_id' => $id]);

        $product['images'] = isset($product['images']) ? json_decode($product['images'], true) : [];
        $productResponse = ProductResponse::from($product);
        $skuResponses = [];
        foreach ($skuList as $sku) {
            $sku['attributes'] = isset($sku['sku_specs']) ? json_decode($sku['sku_specs'], true) : [];
            $skuResponses[] = ProductSkuResponse::from($sku);
        }

        $response = new ProductSkuListResponse;
        $response->setProduct($productResponse);
        $response->setSkus($skuResponses);

        return $this->success([
            'product' => $productResponse->toArray(),
            'skus' => array_map(fn (ProductSkuResponse $sku): array => $sku->toArray(), $skuResponses),
        ]);
    }
}
