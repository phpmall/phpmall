<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\CartRepository;
use App\Contracts\ServiceInterface;
use App\Services\CommonService;

class CartService extends CommonService implements ServiceInterface
{
    public function getRepository(): CartRepository
    {
        return CartRepository::getInstance();
    }
}
