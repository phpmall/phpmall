<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\FlashPromotionSessionRepository;
use Juling\Generator\Contracts\ServiceInterface;
use Juling\Generator\Services\CommonService;

class FlashPromotionSessionService extends CommonService implements ServiceInterface
{
    public function getRepository(): FlashPromotionSessionRepository
    {
        return FlashPromotionSessionRepository::getInstance();
    }
}
