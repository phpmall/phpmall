<?php

declare(strict_types=1);

namespace App\Services;

use App\Foundation\Contracts\ServiceInterface;
use App\Foundation\Services\CommonService;
use App\Repositories\ManagerRepository;

class ManagerService extends CommonService implements ServiceInterface
{
    public function getRepository(): ManagerRepository
    {
        return ManagerRepository::getInstance();
    }
}
