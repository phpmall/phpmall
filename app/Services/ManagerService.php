<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ServiceInterface;
use App\Repositories\ManagerRepository;

class ManagerService extends CommonService implements ServiceInterface
{
    public function getRepository(): ManagerRepository
    {
        return ManagerRepository::getInstance();
    }
}
