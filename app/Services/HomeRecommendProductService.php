<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\HomeRecommendProductRepository;
use Focite\Builder\Contracts\ServiceInterface;
use Focite\Builder\Services\CommonService;

class HomeRecommendProductService extends CommonService implements ServiceInterface
{
    public function getRepository(): HomeRecommendProductRepository
    {
        return HomeRecommendProductRepository::getInstance();
    }
}
