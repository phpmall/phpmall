<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\SettingRepository;
use Juling\Generator\Contracts\ServiceInterface;
use Juling\Generator\Services\CommonService;

class SettingService extends CommonService implements ServiceInterface
{
    public function getRepository(): SettingRepository
    {
        return SettingRepository::getInstance();
    }
}
