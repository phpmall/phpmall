<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\SellerBrandRepository;
use Focite\Builder\Contracts\ServiceInterface;
use Focite\Builder\Services\CommonService;

class SellerBrandService extends CommonService implements ServiceInterface
{
    public function getRepository(): SellerBrandRepository
    {
        return SellerBrandRepository::getInstance();
    }
}
