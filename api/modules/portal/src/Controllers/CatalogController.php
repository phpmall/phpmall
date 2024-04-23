<?php

declare(strict_types=1);

namespace Juling\Portal\Controllers;

use Illuminate\Contracts\Support\Renderable;
use OpenApi\Attributes as OA;

class CatalogController extends BaseController
{
    #[OA\Get(path: '/catalog', summary: '全部类目', tags: ['类目'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(): Renderable
    {
        return $this->display('catalog');
    }
}
