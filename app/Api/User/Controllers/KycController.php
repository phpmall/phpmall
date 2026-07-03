<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use App\Api\User\Requests\Kyc\KycResubmitRequest;
use App\Api\User\Requests\Kyc\KycSubmitRequest;
use App\Api\User\Responses\Kyc\KycStatusResponse;
use App\Exceptions\NotImplementedException;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class KycController extends BaseController
{
    #[OA\Post(path: '/kyc/submit', security: [['bearerAuth' => []]], summary: 'Kyc Controller submit', tags: ['会员中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: KycSubmitRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: KycStatusResponse::class))]
    public function submit(KycSubmitRequest $request): JsonResponse
    {
        throw new NotImplementedException('TODO: implement '.__CLASS__.'::'.__FUNCTION__);
    }

    #[OA\Get(path: '/kyc/status', security: [['bearerAuth' => []]], summary: 'Kyc Controller status', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: KycStatusResponse::class))]
    public function status(int $id): JsonResponse
    {
        throw new NotImplementedException('TODO: implement '.__CLASS__.'::'.__FUNCTION__);
    }

    #[OA\Post(path: '/kyc/resubmit', security: [['bearerAuth' => []]], summary: 'Kyc Controller resubmit', tags: ['会员中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: KycResubmitRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: KycStatusResponse::class))]
    public function resubmit(KycResubmitRequest $request): JsonResponse
    {
        throw new NotImplementedException('TODO: implement '.__CLASS__.'::'.__FUNCTION__);
    }
}
