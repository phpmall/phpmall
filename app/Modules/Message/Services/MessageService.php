<?php

declare(strict_types=1);

namespace App\Modules\Message\Services;

use App\Modules\Message\Repositories\MessageRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class MessageService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly MessageRepository $repository,
    ) {}

    public function getRepository(): MessageRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
