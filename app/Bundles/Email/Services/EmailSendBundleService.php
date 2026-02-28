<?php

declare(strict_types=1);

namespace App\Bundles\Email\Services;

use App\Bundles\Email\Repositories\EmailSendRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class EmailSendBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): EmailSendRepository
    {
        return EmailSendRepository::getInstance();
    }
}
