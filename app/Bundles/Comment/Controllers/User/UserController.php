<?php

declare(strict_types=1);

namespace App\Bundles\Comment\Controllers\User;

use App\Http\Controllers\User\BaseController;
use Illuminate\Http\JsonResponse;

class UserController extends BaseController
{
    public function index(): JsonResponse
    {
        return $this->success([]);
    }
}
