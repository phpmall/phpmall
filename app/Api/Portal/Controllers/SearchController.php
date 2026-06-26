<?php

declare(strict_types=1);

namespace App\Api\Portal\Controllers;

use App\Api\Portal\Requests\Search\SearchFiltersRequest;
use App\Api\Portal\Requests\Search\SearchHotKeywordsRequest;
use App\Api\Portal\Requests\Search\SearchProductsRequest;
use App\Api\Portal\Requests\Search\SearchRequest;
use App\Api\Portal\Requests\Search\SearchSuggestRequest;
use App\Api\Portal\Responses\Search\SearchFiltersResponse;
use App\Api\Portal\Responses\Search\SearchHotKeywordsResponse;
use App\Api\Portal\Responses\Search\SearchProductsResponse;
use App\Api\Portal\Responses\Search\SearchResponse;
use App\Api\Portal\Responses\Search\SearchSuggestResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class SearchController extends BaseController
{
    #[OA\Get(path: '/search', summary: '搜索接口', security: [[]], tags: ['商城平台'])]
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

    #[OA\Get(path: '/search/products', summary: '商品搜索', security: [[]], tags: ['商城平台'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SearchProductsResponse::class))]
    public function products(SearchProductsRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/search/suggest', summary: '搜索建议', security: [[]], tags: ['商城平台'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SearchSuggestResponse::class))]
    public function suggest(SearchSuggestRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/search/hot-keywords', summary: '热搜关键词', security: [[]], tags: ['商城平台'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SearchHotKeywordsResponse::class))]
    public function hotKeywords(SearchHotKeywordsRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/search/filters', summary: '搜索筛选条件', security: [[]], tags: ['商城平台'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SearchFiltersResponse::class))]
    public function filters(SearchFiltersRequest $request): JsonResponse
    {
        return $this->success();
    }
}
