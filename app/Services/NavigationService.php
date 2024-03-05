<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ServiceInterface;
use App\Services\CommonService;
use App\Repositories\NavigationRepository;

class NavigationService extends CommonService implements ServiceInterface
{
    public function getRepository(): NavigationRepository
    {
        return NavigationRepository::getInstance();
    }
}
