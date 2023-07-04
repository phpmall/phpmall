<?php

declare(strict_types=1);

namespace App\Gateways\User\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class IndexController extends BaseController
{
    #[OA\Get(path: '/', summary: '用户概要信息', tags: ['用户首页'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(): Renderable|JsonResponse
    {
        return $this->response('user::index');
    }
}
