<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ServiceInterface;
use App\Services\CommonService;
use App\Repositories\CouponCategoryRepository;

class CouponCategoryService extends CommonService implements ServiceInterface
{
    public function getRepository(): CouponCategoryRepository
    {
        return CouponCategoryRepository::getInstance();
    }
}
