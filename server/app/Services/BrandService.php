<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ServiceInterface;
use App\Repositories\BrandRepository;

class BrandService extends CommonService implements ServiceInterface
{
    public function getRepository(): BrandRepository
    {
        return BrandRepository::getInstance();
    }
}
