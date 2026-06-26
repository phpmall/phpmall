<?php

declare(strict_types=1);

namespace App\Api\Shop\Controllers;

use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class FreightTemplateController extends BaseController
{
    #[OA\Post(path: '/freight-templates/calculate', summary: '运费计算', security: [[]], tags: ['店铺'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function calculate(int $id): JsonResponse
    {
        return $this->success();
    }
}
