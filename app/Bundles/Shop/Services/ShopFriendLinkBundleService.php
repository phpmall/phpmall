<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Services;

use App\Bundles\Shop\Repositories\ShopFriendLinkRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ShopFriendLinkBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): ShopFriendLinkRepository
    {
        return ShopFriendLinkRepository::getInstance();
    }
}
