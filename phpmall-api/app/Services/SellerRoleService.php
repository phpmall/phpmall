<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\SellerRoleRepository;
use Juling\Generator\Contracts\ServiceInterface;
use Juling\Generator\Services\CommonService;

class SellerRoleService extends CommonService implements ServiceInterface
{
    public function getRepository(): SellerRoleRepository
    {
        return SellerRoleRepository::getInstance();
    }
}
