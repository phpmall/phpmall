<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\RoleRepository;
use App\Services\Input\RoleInput;
use App\Services\Output\RoleOutput;
use Focite\Builder\Contracts\ServiceInterface;
use Focite\Builder\Services\CommonService;

class RoleService extends CommonService implements ServiceInterface
{
    public function getRepository(): RoleRepository
    {
        return RoleRepository::getInstance();
    }
}
