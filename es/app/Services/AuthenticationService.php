<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\AuthenticationRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class AuthenticationService extends CommonService implements ServiceInterface
{
    public function getRepository(): AuthenticationRepository
    {
        return AuthenticationRepository::getInstance();
    }
}
