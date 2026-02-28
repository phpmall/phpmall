<?php

declare(strict_types=1);

namespace App\Bundles\Vote\Services;

use App\Bundles\Vote\Repositories\VoteLogRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class VoteLogBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): VoteLogRepository
    {
        return VoteLogRepository::getInstance();
    }
}
