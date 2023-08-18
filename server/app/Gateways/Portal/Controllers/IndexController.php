<?php

declare(strict_types=1);

namespace App\Gateways\Portal\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class IndexController extends BaseController
{
    #[OA\Get(path: '/portal', summary: '商城首页', tags: ['首页'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(): JsonResponse|Renderable
    {
        return $this->response('index');
    }
}
