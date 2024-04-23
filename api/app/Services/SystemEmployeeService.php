<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\SystemEmployeeRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class SystemEmployeeService extends CommonService implements ServiceInterface
{
    public function getRepository(): SystemEmployeeRepository
    {
        return SystemEmployeeRepository::getInstance();
    }
}
