<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\FlashPromotionSessionRepository;
use Focite\Builder\Contracts\ServiceInterface;
use Focite\Builder\Services\CommonService;

class FlashPromotionSessionService extends CommonService implements ServiceInterface
{
    public function getRepository(): FlashPromotionSessionRepository
    {
        return FlashPromotionSessionRepository::getInstance();
    }
}
