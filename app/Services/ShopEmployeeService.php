<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ShopEmployeeRepository;
use Focite\Builder\Contracts\ServiceInterface;
use Focite\Builder\Services\CommonService;

class ShopEmployeeService extends CommonService implements ServiceInterface
{
    public function getRepository(): ShopEmployeeRepository
    {
        return ShopEmployeeRepository::getInstance();
    }
}
