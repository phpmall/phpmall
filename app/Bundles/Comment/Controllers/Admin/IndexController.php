<?php

declare(strict_types=1);

namespace App\Bundles\Comment\Controllers\Admin;

use App\Gateways\Admin\Controllers\BaseController;
use Illuminate\Http\JsonResponse;

class IndexController extends BaseController
{
    public function index(): JsonResponse
    {
        return $this->success([]);
    }
}
