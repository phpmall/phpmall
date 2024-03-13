<?php

declare(strict_types=1);

namespace App\Api\Member\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class IndexController extends BaseController
{
    #[OA\Get(path: '/dashboard', summary: '仪表台', security: [['bearerAuth' => []]], tags: ['用户中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function dashboard(Request $request): JsonResponse
    {
        return $this->success(['test user token', $request->user()]);
    }
}
