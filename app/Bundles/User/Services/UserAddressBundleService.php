<?php

declare(strict_types=1);

namespace App\Bundles\User\Services;

use App\Bundles\User\Repositories\UserAddressRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class UserAddressBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): UserAddressRepository
    {
        return UserAddressRepository::getInstance();
    }
}
