<?php

declare(strict_types=1);

namespace App\Bundles\Seller\Controllers\Seller;

use App\Api\Seller\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class DashboardController extends BaseController
{
    #[OA\Get(path: 'dashboard', summary: '卖家', tags: ['seller'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(Request $request): JsonResponse
    {
        return $this->success(['seller::index', $request->user()]);
    }
}
