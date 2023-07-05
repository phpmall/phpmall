<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\FlashPromotionProductRepository;
use Focite\Builder\Contracts\ServiceInterface;
use Focite\Builder\Services\CommonService;

class FlashPromotionProductService extends CommonService implements ServiceInterface
{
    public function getRepository(): FlashPromotionProductRepository
    {
        return FlashPromotionProductRepository::getInstance();
    }
}
