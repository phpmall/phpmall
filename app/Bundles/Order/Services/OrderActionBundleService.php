<?php

declare(strict_types=1);

namespace App\Bundles\Order\Services;

use App\Bundles\Order\Repositories\OrderActionRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class OrderActionBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): OrderActionRepository
    {
        return OrderActionRepository::getInstance();
    }
}
