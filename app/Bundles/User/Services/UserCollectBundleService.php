<?php

declare(strict_types=1);

namespace App\Bundles\User\Services;

use App\Bundles\User\Repositories\UserCollectRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class UserCollectBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): UserCollectRepository
    {
        return UserCollectRepository::getInstance();
    }
}
