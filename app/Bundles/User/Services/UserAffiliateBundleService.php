<?php

declare(strict_types=1);

namespace App\Bundles\User\Services;

use App\Bundles\User\Repositories\UserAffiliateRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class UserAffiliateBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): UserAffiliateRepository
    {
        return UserAffiliateRepository::getInstance();
    }
}
