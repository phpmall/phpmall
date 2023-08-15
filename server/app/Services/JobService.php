<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ServiceInterface;
use App\Repositories\JobRepository;

class JobService extends CommonService implements ServiceInterface
{
    public function getRepository(): JobRepository
    {
        return JobRepository::getInstance();
    }
}
