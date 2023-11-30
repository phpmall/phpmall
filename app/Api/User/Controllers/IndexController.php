<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class IndexController extends BaseController
{
    #[OA\Get(path: '/api/user', summary: '仪表台', security: [['bearerAuth' => []]], tags: ['用户中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(): JsonResponse
    {
        return $this->success(['test user token', $this->getUser()]);
    }
}
