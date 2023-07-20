<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\FlashPromotionLogRepository;
use Focite\Builder\Contracts\ServiceInterface;
use Focite\Builder\Services\CommonService;

class FlashPromotionLogService extends CommonService implements ServiceInterface
{
    public function getRepository(): FlashPromotionLogRepository
    {
        return FlashPromotionLogRepository::getInstance();
    }
}
