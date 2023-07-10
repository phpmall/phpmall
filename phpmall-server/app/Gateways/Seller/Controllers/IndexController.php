<?php

declare(strict_types=1);

namespace App\Gateways\Seller\Controllers;

use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class IndexController extends BaseController
{
    #[OA\Get(path: '/', summary: '卖家', tags: ['seller'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(): JsonResponse
    {
        return $this->success(['seller::index']);
    }
}
