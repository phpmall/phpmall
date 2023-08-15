<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\SellerRepository;
use App\Contracts\ServiceInterface;
use App\Services\CommonService;

class SellerService extends CommonService implements ServiceInterface
{
    public function getRepository(): SellerRepository
    {
        return SellerRepository::getInstance();
    }
}
