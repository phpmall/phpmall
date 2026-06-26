<?php

declare(strict_types=1);

namespace App\Api\Common\Controllers;

use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class LogisticsCallbackController extends BaseController
{
    #[OA\Post(path: '/kuaidi/notify', summary: '快递回调通知', security: [[]], tags: ['公共工具'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function kuaidiNotify(): JsonResponse
    {
        return $this->success();
    }
}
