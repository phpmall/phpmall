<?php

declare(strict_types=1);

namespace App\Api\Portal\Controllers;

use App\Api\Portal\Responses\Index\IndexResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class IndexController extends BaseController
{
    #[OA\Get(path: '/', summary: '首页', security: [[]], tags: ['商城平台'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: IndexResponse::class))]
    public function index(): JsonResponse
    {
        return $this->success();
    }
}
