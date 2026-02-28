<?php

declare(strict_types=1);

namespace App\Bundles\Admin\Services;

use App\Bundles\Admin\Repositories\AdminMessageRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class AdminMessageBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): AdminMessageRepository
    {
        return AdminMessageRepository::getInstance();
    }
}
