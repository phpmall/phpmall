<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\HomeBrandRepository;
use Juling\Generator\Contracts\ServiceInterface;
use Juling\Generator\Services\CommonService;

class HomeBrandService extends CommonService implements ServiceInterface
{
    public function getRepository(): HomeBrandRepository
    {
        return HomeBrandRepository::getInstance();
    }
}
