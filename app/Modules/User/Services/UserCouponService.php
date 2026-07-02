<?php

declare(strict_types=1);

namespace App\Modules\User\Services;

use App\Modules\User\Repositories\UserCouponRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class UserCouponService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly UserCouponRepository $repository,
    ) {}

    public function getRepository(): UserCouponRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
