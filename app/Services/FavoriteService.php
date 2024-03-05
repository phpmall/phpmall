<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ServiceInterface;
use App\Services\CommonService;
use App\Repositories\FavoriteRepository;

class FavoriteService extends CommonService implements ServiceInterface
{
    public function getRepository(): FavoriteRepository
    {
        return FavoriteRepository::getInstance();
    }
}
