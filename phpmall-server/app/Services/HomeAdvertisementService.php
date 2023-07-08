<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\HomeAdvertisementRepository;
use Focite\Builder\Contracts\ServiceInterface;
use Focite\Builder\Services\CommonService;

class HomeAdvertisementService extends CommonService implements ServiceInterface
{
    public function getRepository(): HomeAdvertisementRepository
    {
        return HomeAdvertisementRepository::getInstance();
    }
}
