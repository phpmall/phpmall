<?php

declare(strict_types=1);

namespace App\Modules\Notification\Services;

use App\Modules\Notification\Repositories\NotificationRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class NotificationService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly NotificationRepository $repository,
    ) {}

    public function getRepository(): NotificationRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
