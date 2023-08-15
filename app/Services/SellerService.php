<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ServiceInterface;
use App\Repositories\SellerRepository;

class SellerService extends CommonService implements ServiceInterface
{
    public function getRepository(): SellerRepository
    {
        return SellerRepository::getInstance();
    }
}
