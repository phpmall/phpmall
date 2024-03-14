<?php

declare(strict_types=1);

namespace App\Bundles\UMS\Controllers\Member;

use App\Api\Member\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ProfileController extends BaseController
{
    #[OA\Get(path: '/profile', summary: '会员个人资料', security: [['bearerAuth' => []]], tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        return $this->success([
            'user' => $user->toArray(),
        ]);
    }
}
