<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\CartRepository;
use Focite\Builder\Contracts\ServiceInterface;
use Focite\Builder\Services\CommonService;

class CartService extends CommonService implements ServiceInterface
{
    public function getRepository(): CartRepository
    {
        return CartRepository::getInstance();
    }
}
