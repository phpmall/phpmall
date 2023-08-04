<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\CouponCategoryRepository;
use Focite\Builder\Contracts\ServiceInterface;
use Focite\Builder\Services\CommonService;

class CouponCategoryService extends CommonService implements ServiceInterface
{
    public function getRepository(): CouponCategoryRepository
    {
        return CouponCategoryRepository::getInstance();
    }
}
