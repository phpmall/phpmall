<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Services;

use App\Bundles\Goods\Repositories\GoodsCatRecommendRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class GoodsCatRecommendBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): GoodsCatRecommendRepository
    {
        return GoodsCatRecommendRepository::getInstance();
    }
}
