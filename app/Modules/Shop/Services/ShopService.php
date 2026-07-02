<?php

declare(strict_types=1);

namespace App\Modules\Shop\Services;

use App\Modules\Shop\Repositories\ShopRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ShopService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly ShopRepository $repository,
    ) {}

    public function getRepository(): ShopRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
