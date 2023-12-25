<?php

declare(strict_types=1);

namespace App\Portal\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use OpenApi\Attributes as OA;

class AuthController extends BaseController
{
    #[OA\Get(path: '/login', summary: '全部类目', tags: ['类目'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function login(): Renderable
    {
        return $this->display('user.auth.login');
    }
}
