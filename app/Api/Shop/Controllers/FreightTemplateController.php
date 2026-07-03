<?php

declare(strict_types=1);

namespace App\Api\Shop\Controllers;

use App\Api\Shop\Requests\FreightTemplate\FreightTemplateCalculateRequest;
use App\Api\Shop\Responses\FreightTemplate\FreightTemplateCalculateResponse;
use App\Exceptions\NotImplementedException;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class FreightTemplateController extends BaseController
{
    #[OA\Post(path: '/freight-templates/calculate', summary: '运费计算', security: [[]], tags: ['店铺'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: FreightTemplateCalculateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: FreightTemplateCalculateResponse::class))]
    public function calculate(FreightTemplateCalculateRequest $request): JsonResponse
    {
        throw new NotImplementedException('TODO: implement '.__CLASS__.'::'.__FUNCTION__);
    }
}
