<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Services;

use App\Bundles\Shop\Repositories\ShopCardRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ShopCardBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): ShopCardRepository
    {
        return ShopCardRepository::getInstance();
    }
}
