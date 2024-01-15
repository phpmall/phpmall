<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ShopEmployeeRepository;
use Juling\Generator\Contracts\ServiceInterface;
use Juling\Generator\Services\CommonService;

class ShopEmployeeService extends CommonService implements ServiceInterface
{
    public function getRepository(): ShopEmployeeRepository
    {
        return ShopEmployeeRepository::getInstance();
    }
}
