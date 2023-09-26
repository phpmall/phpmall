<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\AdminUserRepository;
use Focite\Generator\Contracts\ServiceInterface;
use Focite\Generator\Services\CommonService;

class AdminUserService extends CommonService implements ServiceInterface
{
    public function getRepository(): AdminUserRepository
    {
        return AdminUserRepository::getInstance();
    }
}
