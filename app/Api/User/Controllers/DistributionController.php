<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class DistributionController extends BaseController
{
    #[OA\Get(path: '/distribution/profile', security: [['bearerAuth' => []]], summary: 'Distribution Controller profile', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function profile(): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/distribution/stats', security: [['bearerAuth' => []]], summary: 'Distribution Controller stats', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function stats(): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/distribution/team', security: [['bearerAuth' => []]], summary: 'Distribution Controller team', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function team(): JsonResponse
    {
        return $this->success();
    }
}
