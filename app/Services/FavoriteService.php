<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\FavoriteRepository;
use Juling\Generator\Contracts\ServiceInterface;
use Juling\Generator\Services\CommonService;

class FavoriteService extends CommonService implements ServiceInterface
{
    public function getRepository(): FavoriteRepository
    {
        return FavoriteRepository::getInstance();
    }
}
