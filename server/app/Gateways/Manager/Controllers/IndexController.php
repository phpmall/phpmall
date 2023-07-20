<?php

declare(strict_types=1);

namespace App\Gateways\Manager\Controllers;

use Illuminate\Http\JsonResponse;

class IndexController extends BaseController
{
    public function index(): JsonResponse
    {
        return $this->success(['admin::index']);
    }
}
