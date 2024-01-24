<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class IndexController extends BaseController
{
    #[OA\Get(path: '/dashboard', summary: '卖家', tags: ['seller'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function dashboard(Request $request): JsonResponse
    {
        return $this->success(['seller::index', $request->user()]);
    }
}
