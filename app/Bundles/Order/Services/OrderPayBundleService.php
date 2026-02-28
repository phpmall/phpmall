<?php

declare(strict_types=1);

namespace App\Bundles\Order\Services;

use App\Bundles\Order\Repositories\OrderPayRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class OrderPayBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): OrderPayRepository
    {
        return OrderPayRepository::getInstance();
    }
}
