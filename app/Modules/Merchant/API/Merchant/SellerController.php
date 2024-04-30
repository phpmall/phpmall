<?php

declare(strict_types=1);

namespace App\Modules\Merchant\API\Merchant;

use App\API\Merchant\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class SellerController extends BaseController
{
    #[OA\Get(path: '/seller', summary: '全部卖家', tags: ['卖家管理'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(): JsonResponse
    {
        return $this->success(['admin::user.index']);
    }
}
