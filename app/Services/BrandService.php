<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\BrandRepository;
use App\Contracts\ServiceInterface;
use App\Services\CommonService;

class BrandService extends CommonService implements ServiceInterface
{
    public function getRepository(): BrandRepository
    {
        return BrandRepository::getInstance();
    }
}
