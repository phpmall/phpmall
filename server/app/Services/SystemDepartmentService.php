<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\SystemDepartmentRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class SystemDepartmentService extends CommonService implements ServiceInterface
{
    public function getRepository(): SystemDepartmentRepository
    {
        return SystemDepartmentRepository::getInstance();
    }
}
