<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\SettingRepository;
use Focite\Generator\Contracts\ServiceInterface;
use Focite\Generator\Services\CommonService;

class SettingService extends CommonService implements ServiceInterface
{
    public function getRepository(): SettingRepository
    {
        return SettingRepository::getInstance();
    }
}
