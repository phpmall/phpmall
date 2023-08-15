<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\UserAddressRepository;
use App\Contracts\ServiceInterface;
use App\Services\CommonService;

class UserAddressService extends CommonService implements ServiceInterface
{
    public function getRepository(): UserAddressRepository
    {
        return UserAddressRepository::getInstance();
    }
}
