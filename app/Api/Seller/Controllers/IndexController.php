<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use App\Api\Seller\Responses\Index\IndexResponse;
use App\Exceptions\NotImplementedException;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class IndexController extends BaseController
{
    #[OA\Get(path: '/', summary: '首页', security: [['bearerAuth' => []]], tags: ['卖家中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: IndexResponse::class))]
    public function index(): JsonResponse
    {
        throw new NotImplementedException('TODO: implement '.__CLASS__.'::'.__FUNCTION__);
    }
}
