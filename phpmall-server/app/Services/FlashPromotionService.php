<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\FlashPromotionRepository;
use Focite\Builder\Contracts\ServiceInterface;
use Focite\Builder\Services\CommonService;

class FlashPromotionService extends CommonService implements ServiceInterface
{
    public function getRepository(): FlashPromotionRepository
    {
        return FlashPromotionRepository::getInstance();
    }
}
