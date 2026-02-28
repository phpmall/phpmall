<?php

declare(strict_types=1);

namespace App\Bundles\User\Services;

use App\Bundles\User\Repositories\UserCartRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class UserCartBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): UserCartRepository
    {
        return UserCartRepository::getInstance();
    }
}
