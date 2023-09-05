<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ServiceInterface;
use App\Repositories\CartRepository;

class CartService extends CommonService implements ServiceInterface
{
    public function getRepository(): CartRepository
    {
        return CartRepository::getInstance();
    }
}
