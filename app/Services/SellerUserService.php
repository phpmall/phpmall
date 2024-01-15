<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\SellerUserRepository;
use Juling\Generator\Contracts\ServiceInterface;
use Juling\Generator\Services\CommonService;

class SellerUserService extends CommonService implements ServiceInterface
{
    public function getRepository(): SellerUserRepository
    {
        return SellerUserRepository::getInstance();
    }
}
