<?php

declare(strict_types=1);

namespace App\Modules\Coupon\Services;

use App\Modules\Coupon\Repositories\CouponRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class CouponService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly CouponRepository $repository,
    ) {}

    public function getRepository(): CouponRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
