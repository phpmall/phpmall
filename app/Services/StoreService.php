<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\StoreRepository;
use Juling\Generator\Contracts\ServiceInterface;
use Juling\Generator\Services\CommonService;

class StoreService extends CommonService implements ServiceInterface
{
    public function getRepository(): StoreRepository
    {
        return StoreRepository::getInstance();
    }
}
