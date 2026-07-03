<?php

declare(strict_types=1);

namespace App\Api\Shop\Controllers;

use App\Api\Shop\Requests\Search\SearchFiltersRequest;
use App\Api\Shop\Requests\Search\SearchHotKeywordsRequest;
use App\Api\Shop\Requests\Search\SearchProductsRequest;
use App\Api\Shop\Requests\Search\SearchRequest;
use App\Api\Shop\Requests\Search\SearchSuggestRequest;
use App\Api\Shop\Responses\Search\SearchFiltersResponse;
use App\Api\Shop\Responses\Search\SearchHotKeywordsResponse;
use App\Api\Shop\Responses\Search\SearchProductsResponse;
use App\Api\Shop\Responses\Search\SearchResponse;
use App\Api\Shop\Responses\Search\SearchSuggestResponse;
use App\Exceptions\NotImplementedException;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class SearchController extends BaseController
{
    #[OA\Get(path: '/search', summary: '搜索接口', security: [[]], tags: ['店铺'])]
    #[OA\Parameter(name: 'keyword', description: '搜索关键词', in: 'query', required: true)]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query')]
    #[OA\Parameter(name: 'per_page', description: '每页数量', in: 'query')]
    #[OA\Parameter(name: 'sort_by', description: '排序字段', in: 'query')]
    #[OA\Parameter(name: 'sort_direction', description: '排序方向: asc 或 desc', in: 'query')]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SearchResponse::class))]
    public function index(SearchRequest $request): JsonResponse
    {
        throw new NotImplementedException('TODO: implement '.__CLASS__.'::'.__FUNCTION__);
    }

    #[OA\Get(path: '/search/products', summary: '搜索商品', security: [[]], tags: ['店铺'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SearchProductsResponse::class))]
    public function products(SearchProductsRequest $request): JsonResponse
    {
        throw new NotImplementedException('TODO: implement '.__CLASS__.'::'.__FUNCTION__);
    }

    #[OA\Get(path: '/search/suggest', summary: '搜索建议', security: [[]], tags: ['店铺'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SearchSuggestResponse::class))]
    public function suggest(SearchSuggestRequest $request): JsonResponse
    {
        throw new NotImplementedException('TODO: implement '.__CLASS__.'::'.__FUNCTION__);
    }

    #[OA\Get(path: '/search/hot-keywords', summary: '热搜关键词', security: [[]], tags: ['店铺'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SearchHotKeywordsResponse::class))]
    public function hotKeywords(SearchHotKeywordsRequest $request): JsonResponse
    {
        throw new NotImplementedException('TODO: implement '.__CLASS__.'::'.__FUNCTION__);
    }

    #[OA\Get(path: '/search/filters', summary: '搜索筛选条件', security: [[]], tags: ['店铺'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SearchFiltersResponse::class))]
    public function filters(SearchFiltersRequest $request): JsonResponse
    {
        throw new NotImplementedException('TODO: implement '.__CLASS__.'::'.__FUNCTION__);
    }
}
