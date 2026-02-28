<?php

declare(strict_types=1);

namespace App\Bundles\Vote\Services;

use App\Bundles\Vote\Repositories\VoteOptionRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class VoteOptionBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): VoteOptionRepository
    {
        return VoteOptionRepository::getInstance();
    }
}
