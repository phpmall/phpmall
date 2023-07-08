<?php

declare(strict_types=1);

namespace App\Gateways\Admin\Controllers;

use Illuminate\Http\JsonResponse;

class PermissionController extends BaseController
{
    public function index(): JsonResponse
    {
        return $this->success(['admin::user.index']);
    }
}
