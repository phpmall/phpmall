<?php

declare(strict_types=1);

namespace App\Bundles\System\API\Admin\Controllers;

use App\API\Admin\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ManagerController extends BaseController
{
    #[OA\Get(path: '/manager', summary: '管理员接口', security: [['bearerAuth' => []]], tags: ['管理员'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(Request $request): JsonResponse
    {
        return $this->success($request->path());
    }
}
