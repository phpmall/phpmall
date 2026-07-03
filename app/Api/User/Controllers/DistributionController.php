<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use App\Api\User\Responses\Distribution\DistributionProfileResponse;
use App\Api\User\Responses\Distribution\DistributionStatsResponse;
use App\Api\User\Responses\Distribution\DistributionTeamListResponse;
use App\Exceptions\NotImplementedException;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class DistributionController extends BaseController
{
    #[OA\Get(path: '/distribution/profile', security: [['bearerAuth' => []]], summary: 'Distribution Controller profile', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: DistributionProfileResponse::class))]
    public function profile(): JsonResponse
    {
        throw new NotImplementedException('TODO: implement '.__CLASS__.'::'.__FUNCTION__);
    }

    #[OA\Get(path: '/distribution/stats', security: [['bearerAuth' => []]], summary: 'Distribution Controller stats', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: DistributionStatsResponse::class))]
    public function stats(): JsonResponse
    {
        throw new NotImplementedException('TODO: implement '.__CLASS__.'::'.__FUNCTION__);
    }

    #[OA\Get(path: '/distribution/team', security: [['bearerAuth' => []]], summary: 'Distribution Controller team', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: DistributionTeamListResponse::class))]
    public function team(): JsonResponse
    {
        throw new NotImplementedException('TODO: implement '.__CLASS__.'::'.__FUNCTION__);
    }
}
