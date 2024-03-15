<?php

declare(strict_types=1);

namespace App\Bundles\Portal\Controllers;

use App\Bundles\Portal\Controllers\BaseController;
use Illuminate\Contracts\Support\Renderable;
use OpenApi\Attributes as OA;

class CatalogController extends BaseController
{
    #[OA\Get(path: '/portal/catalog', summary: '全部类目', tags: ['类目'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(): Renderable
    {
        return $this->display('catalog');
    }
}
