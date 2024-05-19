<?php

declare(strict_types=1);

namespace App\Modules\Comment\API\Manager;

use App\API\Manager\Controllers\BaseController;
use Illuminate\Http\JsonResponse;

class IndexController extends BaseController
{
    public function index(): JsonResponse
    {
        return $this->success([]);
    }
}
