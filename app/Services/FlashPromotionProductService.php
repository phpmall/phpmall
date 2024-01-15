<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\FlashPromotionProductRepository;
use Juling\Generator\Contracts\ServiceInterface;
use Juling\Generator\Services\CommonService;

class FlashPromotionProductService extends CommonService implements ServiceInterface
{
    public function getRepository(): FlashPromotionProductRepository
    {
        return FlashPromotionProductRepository::getInstance();
    }
}
