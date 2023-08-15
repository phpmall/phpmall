<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\StoreEmployeeRepository;
use App\Contracts\ServiceInterface;
use App\Services\CommonService;

class StoreEmployeeService extends CommonService implements ServiceInterface
{
    public function getRepository(): StoreEmployeeRepository
    {
        return StoreEmployeeRepository::getInstance();
    }
}
