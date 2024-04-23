<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\SystemRoleRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class SystemRoleService extends CommonService implements ServiceInterface
{
    public function getRepository(): SystemRoleRepository
    {
        return SystemRoleRepository::getInstance();
    }
}
