<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ProductFullReductionRepository;
use Focite\Builder\Contracts\ServiceInterface;
use Focite\Builder\Services\CommonService;

class ProductFullReductionService extends CommonService implements ServiceInterface
{
    public function getRepository(): ProductFullReductionRepository
    {
        return ProductFullReductionRepository::getInstance();
    }
}
