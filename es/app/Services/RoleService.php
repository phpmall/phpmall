<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\RoleRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class RoleService extends CommonService implements ServiceInterface
{
    public function getRepository(): RoleRepository
    {
        return RoleRepository::getInstance();
    }
}
