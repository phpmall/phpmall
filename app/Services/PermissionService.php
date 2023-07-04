<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\PermissionRepository;
use App\Services\Input\PermissionInput;
use App\Services\Output\PermissionOutput;
use Focite\Builder\Contracts\ServiceInterface;
use Focite\Builder\Services\CommonService;

class PermissionService extends CommonService implements ServiceInterface
{
    public function getRepository(): PermissionRepository
    {
        return PermissionRepository::getInstance();
    }
}
