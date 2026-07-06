<?php

declare(strict_types=1);

namespace App\Api\Shop\Controllers;

use App\Api\Shop\Requests\Search\SearchFiltersRequest;
use App\Api\Shop\Requests\Search\SearchHotKeywordsRequest;
use App\Api\Shop\Requests\Search\SearchProductsRequest;
use App\Api\Shop\Requests\Search\SearchRequest;
use App\Api\Shop\Requests\Search\SearchSuggestRequest;
use App\Api\Shop\Responses\Product\ProductResponse;
use App\Api\Shop\Responses\Search\SearchFiltersResponse;
use App\Api\Shop\Responses\Search\SearchHotKeywordsResponse;
use App\Api\Shop\Responses\Search\SearchProductsResponse;
use App\Api\Shop\Responses\Search\SearchResponse;
use App\Api\Shop\Responses\Search\SearchSuggestResponse;
use App\Modules\Product\Services\ProductSearchService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class SearchController extends BaseController
{
    public function __construct(
        private readonly ProductSearchService $searchService,
    ) {}

    #[OA\Get(path: '/search', summary: '搜索接口', security: [[]], tags: ['店铺'])]
    #[OA\Parameter(name: 'keyword', description: '搜索关键词', in: 'query', required: true)]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query')]
    #[OA\Parameter(name: 'per_page', description: '每页数量', in: 'query')]
    #[OA\Parameter(name: 'sort_by', description: '排序字段', in: 'query')]
    #[OA\Parameter(name: 'sort_direction', description: '排序方向: asc 或 desc', in: 'query')]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SearchResponse::class))]
    public function index(SearchRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/search/products', summary: '搜索商品', security: [[]], tags: ['店铺'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SearchProductsResponse::class))]
    public function products(SearchProductsRequest $request): JsonResponse
    {
        $result = $this->searchService->search($request->validated());

        $items = [];
        foreach ($result['data'] as $product) {
            $items[] = ProductResponse::from($product)->toArray();
        }

        $response = new SearchProductsResponse;
        $response->setItems($items);
        $response->setPagination([
            'total' => $result['total'] ?? 0,
            'per_page' => $result['per_page'] ?? (int) $request->input('per_page', 20),
            'current_page' => $result['current_page'] ?? 1,
            'last_page' => $result['last_page'] ?? 1,
        ]);

        return $this->success($response->toArray());
    }

    #[OA\Get(path: '/search/suggest', summary: '搜索建议', security: [[]], tags: ['店铺'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SearchSuggestResponse::class))]
    public function suggest(SearchSuggestRequest $request): JsonResponse
    {
        $suggestions = $this->searchService->suggest(
            (string) $request->input('keyword'),
            (int) $request->input('limit', 10)
        );

        $response = new SearchSuggestResponse;
        $response->setSuggestions($suggestions);

        return $this->success($response->toArray());
    }

    #[OA\Get(path: '/search/hot-keywords', summary: '热搜关键词', security: [[]], tags: ['店铺'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SearchHotKeywordsResponse::class))]
    public function hotKeywords(SearchHotKeywordsRequest $request): JsonResponse
    {
        $keywords = $this->searchService->hotKeywords(
            (int) $request->input('limit', 10)
        );

        $response = new SearchHotKeywordsResponse;
        $response->setKeywords($keywords);

        return $this->success($response->toArray());
    }

    #[OA\Get(path: '/search/filters', summary: '搜索筛选条件', security: [[]], tags: ['店铺'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SearchFiltersResponse::class))]
    public function filters(SearchFiltersRequest $request): JsonResponse
    {
        $filters = $this->searchService->filters($request->validated());

        $response = new SearchFiltersResponse;
        $response->setCategories($filters['categories']);
        $response->setPriceRanges($filters['priceRanges']);
        $response->setBrands($filters['brands']);
        $response->setAttributes($filters['attributes']);

        return $this->success($response->toArray());
    }
}
