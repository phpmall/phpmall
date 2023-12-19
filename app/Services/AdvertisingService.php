<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\AdvertisingRepository;
use Juling\Generator\Contracts\ServiceInterface;
use Juling\Generator\Services\CommonService;

class AdvertisingService extends CommonService implements ServiceInterface
{
    public function getRepository(): AdvertisingRepository
    {
        return AdvertisingRepository::getInstance();
    }
}
