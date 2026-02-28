<?php

declare(strict_types=1);

namespace App\Bundles\User\Services;

use App\Bundles\User\Repositories\UserExtendFieldsRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class UserExtendFieldsBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): UserExtendFieldsRepository
    {
        return UserExtendFieldsRepository::getInstance();
    }
}
