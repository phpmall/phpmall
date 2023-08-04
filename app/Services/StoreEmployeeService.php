<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\StoreEmployeeRepository;
use Focite\Builder\Contracts\ServiceInterface;
use Focite\Builder\Services\CommonService;

class StoreEmployeeService extends CommonService implements ServiceInterface
{
    public function getRepository(): StoreEmployeeRepository
    {
        return StoreEmployeeRepository::getInstance();
    }
}
