<?php

declare(strict_types=1);

namespace App\Services;

use App\Foundation\Contracts\ServiceInterface;
use App\Foundation\Services\CommonService;
use App\Repositories\ProductFullReductionRepository;

class ProductFullReductionService extends CommonService implements ServiceInterface
{
    public function getRepository(): ProductFullReductionRepository
    {
        return ProductFullReductionRepository::getInstance();
    }
}
