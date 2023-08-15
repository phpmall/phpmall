<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ServiceInterface;
use App\Repositories\FlashPromotionSessionRepository;

class FlashPromotionSessionService extends CommonService implements ServiceInterface
{
    public function getRepository(): FlashPromotionSessionRepository
    {
        return FlashPromotionSessionRepository::getInstance();
    }
}
