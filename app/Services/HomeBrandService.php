<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\HomeBrandRepository;
use App\Contracts\ServiceInterface;
use App\Services\CommonService;

class HomeBrandService extends CommonService implements ServiceInterface
{
    public function getRepository(): HomeBrandRepository
    {
        return HomeBrandRepository::getInstance();
    }
}
