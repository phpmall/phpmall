<?php

declare(strict_types=1);

namespace App\Domains\User\Services;

use App\Domains\User\Repositories\UserRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class UserService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly UserRepository $repository,
    ) {}

    public function getRepository(): UserRepository
    {
        return $this->repository;
    }

    // please fill in your code here
    
}
