<?php

declare(strict_types=1);

namespace App\Gateways\Portal\Controllers;

use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class CatalogController extends BaseController
{
    #[OA\Get(path: '/catalog', summary: '全部类目', tags: ['类目'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(): JsonResponse
    {
        return $this->response('catalog');
    }
}
