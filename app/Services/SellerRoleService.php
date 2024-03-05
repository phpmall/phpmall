<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ServiceInterface;
use App\Repositories\SellerRoleRepository;

class SellerRoleService extends CommonService implements ServiceInterface
{
    public function getRepository(): SellerRoleRepository
    {
        return SellerRoleRepository::getInstance();
    }
}
