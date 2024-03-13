<?php

declare(strict_types=1);

namespace App\Bundles\CMS\Migrations\Comment\Controllers\Member;

use App\Api\Member\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class IndexController extends BaseController
{
    #[OA\Get(path: '/comment', summary: '评论列表', security: [['bearerAuth' => []]], tags: ['评论管理'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(): JsonResponse
    {
        return $this->success([]);
    }
}
