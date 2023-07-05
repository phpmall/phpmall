<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\JobRepository;
use Focite\Builder\Contracts\ServiceInterface;
use Focite\Builder\Services\CommonService;

class JobService extends CommonService implements ServiceInterface
{
    public function getRepository(): JobRepository
    {
        return JobRepository::getInstance();
    }
}
