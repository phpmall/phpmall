<?php

declare(strict_types=1);

namespace App\Domains\Cart\Services;

use App\Domains\Cart\Repositories\CartRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class CartService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly CartRepository $repository,
    ) {}

    public function getRepository(): CartRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
