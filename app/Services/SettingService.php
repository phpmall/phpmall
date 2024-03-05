<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ServiceInterface;
use App\Repositories\SettingRepository;

class SettingService extends CommonService implements ServiceInterface
{
    public function getRepository(): SettingRepository
    {
        return SettingRepository::getInstance();
    }
}
