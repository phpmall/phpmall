<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use App\Api\User\Requests\Privacy\PrivacyCorrectRequest;
use App\Api\User\Requests\Privacy\PrivacyExportRequest;
use App\Api\User\Responses\Privacy\PrivacyStatusResponse;
use App\Exceptions\NotImplementedException;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class PrivacyController extends BaseController
{
    #[OA\Get(path: '/privacy/status', security: [['bearerAuth' => []]], summary: 'Privacy Controller status', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: PrivacyStatusResponse::class))]
    public function status(int $id): JsonResponse
    {
        throw new NotImplementedException('TODO: implement '.__CLASS__.'::'.__FUNCTION__);
    }

    #[OA\Post(path: '/privacy/export', security: [['bearerAuth' => []]], summary: 'Privacy Controller export', tags: ['会员中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: PrivacyExportRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function export(PrivacyExportRequest $request): JsonResponse
    {
        throw new NotImplementedException('TODO: implement '.__CLASS__.'::'.__FUNCTION__);
    }

    #[OA\Delete(path: '/privacy/{id}', security: [['bearerAuth' => []]], summary: 'Privacy Controller delete', tags: ['会员中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function delete(int $id): JsonResponse
    {
        throw new NotImplementedException('TODO: implement '.__CLASS__.'::'.__FUNCTION__);
    }

    #[OA\Post(path: '/privacy/correct', security: [['bearerAuth' => []]], summary: 'Privacy Controller correct', tags: ['会员中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: PrivacyCorrectRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function correct(PrivacyCorrectRequest $request): JsonResponse
    {
        throw new NotImplementedException('TODO: implement '.__CLASS__.'::'.__FUNCTION__);
    }
}
