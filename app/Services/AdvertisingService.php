<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\AdvertisingRepository;
use Focite\Generator\Contracts\ServiceInterface;
use Focite\Generator\Services\CommonService;

class AdvertisingService extends CommonService implements ServiceInterface
{
    public function getRepository(): AdvertisingRepository
    {
        return AdvertisingRepository::getInstance();
    }
}
