<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ServiceInterface;
use App\Services\CommonService;
use App\Repositories\AdvertisingRepository;

class AdvertisingService extends CommonService implements ServiceInterface
{
    public function getRepository(): AdvertisingRepository
    {
        return AdvertisingRepository::getInstance();
    }
}
