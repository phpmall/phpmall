<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\NavigationRepository;
use Focite\Generator\Contracts\ServiceInterface;
use Focite\Generator\Services\CommonService;

class NavigationService extends CommonService implements ServiceInterface
{
    public function getRepository(): NavigationRepository
    {
        return NavigationRepository::getInstance();
    }
}
