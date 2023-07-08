<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\HomeBrandRepository;
use Focite\Builder\Contracts\ServiceInterface;
use Focite\Builder\Services\CommonService;

class HomeBrandService extends CommonService implements ServiceInterface
{
    public function getRepository(): HomeBrandRepository
    {
        return HomeBrandRepository::getInstance();
    }
}
