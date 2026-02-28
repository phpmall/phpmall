<?php

declare(strict_types=1);

namespace App\Bundles\Order\Services;

use App\Bundles\Order\Repositories\OrderInfoRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class OrderInfoBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): OrderInfoRepository
    {
        return OrderInfoRepository::getInstance();
    }
}
