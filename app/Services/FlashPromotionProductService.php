<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ServiceInterface;
use App\Repositories\FlashPromotionProductRepository;

class FlashPromotionProductService extends CommonService implements ServiceInterface
{
    public function getRepository(): FlashPromotionProductRepository
    {
        return FlashPromotionProductRepository::getInstance();
    }
}
