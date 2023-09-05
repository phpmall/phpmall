<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ServiceInterface;
use App\Repositories\ShopEmployeeRepository;

class ShopEmployeeService extends CommonService implements ServiceInterface
{
    public function getRepository(): ShopEmployeeRepository
    {
        return ShopEmployeeRepository::getInstance();
    }
}
