<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ServiceInterface;
use App\Services\CommonService;
use App\Repositories\HomeBrandRepository;

class HomeBrandService extends CommonService implements ServiceInterface
{
    public function getRepository(): HomeBrandRepository
    {
        return HomeBrandRepository::getInstance();
    }
}
