<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\OrderSettingRepository;
use Focite\Builder\Contracts\ServiceInterface;
use Focite\Builder\Services\CommonService;

class OrderSettingService extends CommonService implements ServiceInterface
{
    public function getRepository(): OrderSettingRepository
    {
        return OrderSettingRepository::getInstance();
    }
}
