<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\SystemSettingRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class SystemSettingService extends CommonService implements ServiceInterface
{
    public function getRepository(): SystemSettingRepository
    {
        return SystemSettingRepository::getInstance();
    }
}
