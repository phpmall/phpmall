<?php

declare(strict_types=1);

namespace App\Bundles\Email\Services;

use App\Bundles\Email\Repositories\EmailSubscriberRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class EmailSubscriberBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): EmailSubscriberRepository
    {
        return EmailSubscriberRepository::getInstance();
    }
}
