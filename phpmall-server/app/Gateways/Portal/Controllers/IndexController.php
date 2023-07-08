<?php

declare(strict_types=1);

namespace App\Gateways\Portal\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class IndexController extends Controller
{
    #[OA\Get(path: '/', summary: '商城首页', tags: ['首页'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(): Renderable|JsonResponse
    {
        return $this->response('index');
    }
}
