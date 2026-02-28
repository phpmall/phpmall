<?php

declare(strict_types=1);

namespace App\Bundles\User\Services;

use App\Bundles\User\Repositories\UserExtendInfoRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class UserExtendInfoBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): UserExtendInfoRepository
    {
        return UserExtendInfoRepository::getInstance();
    }
}
