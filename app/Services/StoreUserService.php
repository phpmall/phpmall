<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\StoreUserRepository;
use Juling\Generator\Contracts\ServiceInterface;
use Juling\Generator\Services\CommonService;

class StoreUserService extends CommonService implements ServiceInterface
{
    public function getRepository(): StoreUserRepository
    {
        return StoreUserRepository::getInstance();
    }
}
