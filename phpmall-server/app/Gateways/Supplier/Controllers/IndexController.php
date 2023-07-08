<?php

declare(strict_types=1);

namespace App\Gateways\Supplier\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class IndexController extends BaseController
{
    #[OA\Get(path: '/', summary: 'supplier', tags: ['supplier'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(): Renderable|JsonResponse
    {
        return $this->response('supplier::index');
    }
}
