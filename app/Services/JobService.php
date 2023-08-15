<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\JobRepository;
use App\Contracts\ServiceInterface;
use App\Services\CommonService;

class JobService extends CommonService implements ServiceInterface
{
    public function getRepository(): JobRepository
    {
        return JobRepository::getInstance();
    }
}
