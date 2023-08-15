<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ShopEmployeeRepository;
use App\Contracts\ServiceInterface;
use App\Services\CommonService;

class ShopEmployeeService extends CommonService implements ServiceInterface
{
    public function getRepository(): ShopEmployeeRepository
    {
        return ShopEmployeeRepository::getInstance();
    }
}
