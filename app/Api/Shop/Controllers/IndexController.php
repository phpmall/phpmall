<?php

declare(strict_types=1);

namespace App\Api\Shop\Controllers;

use App\Exceptions\NotImplementedException;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class IndexController extends BaseController
{
    #[OA\Get(path: '/', summary: '首页', security: [[]], tags: ['店铺'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(): JsonResponse
    {
        throw new NotImplementedException('TODO: implement '.__CLASS__.'::'.__FUNCTION__);
    }
}
