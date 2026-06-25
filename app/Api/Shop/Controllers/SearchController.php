<?php

declare(strict_types=1);

namespace App\Api\Shop\Controllers;

use App\Api\Shop\Requests\Search\SearchRequest;
use App\Api\Shop\Responses\Search\SearchResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class SearchController extends BaseController
{
    #[OA\Get(path: '/search', summary: '搜索接口', tags: ['店铺'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SearchRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SearchResponse::class))]
    public function index(): JsonResponse
    {
        return $this->success();
    }
}
