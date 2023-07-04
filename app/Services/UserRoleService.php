<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\UserRoleRepository;
use App\Services\Input\UserRoleInput;
use App\Services\Output\UserRoleOutput;
use Focite\Builder\Contracts\ServiceInterface;
use Focite\Builder\Services\CommonService;

class UserRoleService extends CommonService implements ServiceInterface
{
    public function getRepository(): UserRoleRepository
    {
        return UserRoleRepository::getInstance();
    }
}
