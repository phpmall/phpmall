<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\FlashPromotionLogRepository;
use Juling\Generator\Contracts\ServiceInterface;
use Juling\Generator\Services\CommonService;

class FlashPromotionLogService extends CommonService implements ServiceInterface
{
    public function getRepository(): FlashPromotionLogRepository
    {
        return FlashPromotionLogRepository::getInstance();
    }
}
