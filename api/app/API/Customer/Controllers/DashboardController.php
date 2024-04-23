<?php

declare(strict_types=1);

namespace App\API\Customer\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class DashboardController extends BaseController
{
    #[OA\Get(path: '/dashboard', summary: '客户首页', security: [['bearerAuth' => []]], tags: ['客户中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(Request $request): JsonResponse
    {
        return $this->success(['test user token', $request->user()]);
    }
}
