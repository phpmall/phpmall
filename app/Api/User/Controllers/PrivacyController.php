<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class PrivacyController extends BaseController
{
    #[OA\Get(path: '/privacy/status', security: [['bearerAuth' => []]], summary: 'Privacy Controller status', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function status(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/privacy/export', security: [['bearerAuth' => []]], summary: 'Privacy Controller export', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function export(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Delete(path: '/privacy/{id}', security: [['bearerAuth' => []]], summary: 'Privacy Controller delete', tags: ['会员中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function delete(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/privacy/correct', security: [['bearerAuth' => []]], summary: 'Privacy Controller correct', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function correct(Request $request): JsonResponse
    {
        return $this->success();
    }
}
