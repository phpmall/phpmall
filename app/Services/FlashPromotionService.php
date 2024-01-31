<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\FlashPromotionRepository;
use Juling\Generator\Contracts\ServiceInterface;
use Juling\Generator\Services\CommonService;

class FlashPromotionService extends CommonService implements ServiceInterface
{
    public function getRepository(): FlashPromotionRepository
    {
        return FlashPromotionRepository::getInstance();
    }
}
