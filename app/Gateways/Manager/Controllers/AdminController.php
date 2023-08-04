<?php

declare(strict_types=1);

namespace App\Gateways\Manager\Controllers;

use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class AdminController extends BaseController
{
    #[OA\Get(path: '/manager/admin', summary: '运营员工管理', tags: ['员工管理'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(): JsonResponse
    {
        return $this->success(['admin::admin.index']);
    }
}
