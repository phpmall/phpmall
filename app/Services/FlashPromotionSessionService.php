<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\FlashPromotionSessionRepository;
use App\Contracts\ServiceInterface;
use App\Services\CommonService;

class FlashPromotionSessionService extends CommonService implements ServiceInterface
{
    public function getRepository(): FlashPromotionSessionRepository
    {
        return FlashPromotionSessionRepository::getInstance();
    }
}
