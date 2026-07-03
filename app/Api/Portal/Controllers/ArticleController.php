<?php

declare(strict_types=1);

namespace App\Api\Portal\Controllers;

use App\Api\Portal\Requests\Article\ArticleIndexRequest;
use App\Api\Portal\Responses\Article\ArticleListResponse;
use App\Api\Portal\Responses\Article\ArticleResponse;
use App\Exceptions\NotImplementedException;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class ArticleController extends BaseController
{
    #[OA\Get(path: '/articles', summary: '文章列表', security: [[]], tags: ['商城平台'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ArticleListResponse::class))]
    public function index(ArticleIndexRequest $request): JsonResponse
    {
        throw new NotImplementedException('TODO: implement '.__CLASS__.'::'.__FUNCTION__);
    }

    #[OA\Get(path: '/articles/{id}', summary: '文章详情', security: [[]], tags: ['商城平台'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ArticleResponse::class))]
    public function show(int $id): JsonResponse
    {
        throw new NotImplementedException('TODO: implement '.__CLASS__.'::'.__FUNCTION__);
    }
}
