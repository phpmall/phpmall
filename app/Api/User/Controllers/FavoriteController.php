<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use App\Api\User\Requests\Favorite\FavoriteStoreRequest;
use App\Api\User\Responses\Favorite\FavoriteListResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class FavoriteController extends BaseController
{
    #[OA\Get(path: '/favorites', security: [['bearerAuth' => []]], summary: 'Favorite Controller index', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: FavoriteListResponse::class))]
    public function index(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/favorites', security: [['bearerAuth' => []]], summary: 'Favorite Controller store', tags: ['会员中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: FavoriteStoreRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function store(FavoriteStoreRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Delete(path: '/favorites/{id}', security: [['bearerAuth' => []]], summary: 'Favorite Controller destroy', tags: ['会员中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function destroy(int $id): JsonResponse
    {
        return $this->success();
    }
}
