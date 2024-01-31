<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\HomeAdvertisementRepository;
use Juling\Generator\Contracts\ServiceInterface;
use Juling\Generator\Services\CommonService;

class HomeAdvertisementService extends CommonService implements ServiceInterface
{
    public function getRepository(): HomeAdvertisementRepository
    {
        return HomeAdvertisementRepository::getInstance();
    }
}
