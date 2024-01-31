<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\SellerRepository;
use Juling\Generator\Contracts\ServiceInterface;
use Juling\Generator\Services\CommonService;

class SellerService extends CommonService implements ServiceInterface
{
    public function getRepository(): SellerRepository
    {
        return SellerRepository::getInstance();
    }
}
