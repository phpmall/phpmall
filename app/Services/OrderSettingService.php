<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ServiceInterface;
use App\Services\CommonService;
use App\Repositories\OrderSettingRepository;

class OrderSettingService extends CommonService implements ServiceInterface
{
    public function getRepository(): OrderSettingRepository
    {
        return OrderSettingRepository::getInstance();
    }
}
