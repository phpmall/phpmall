<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use App\Enums\RoleEnum;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

class ProfileController extends BaseController
{
    #[OA\Get(path: '/profile', summary: '获取会员资料', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index()
    {
        Auth::guard(RoleEnum::User->getValue())->user();
    }

    #[OA\Put(path: '/profile', summary: '更新会员资料', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function update() {}
}
