<?php

declare(strict_types=1);

namespace App\Bundles\User\Services;

use App\Bundles\User\Repositories\UserBookingRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class UserBookingBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): UserBookingRepository
    {
        return UserBookingRepository::getInstance();
    }
}
