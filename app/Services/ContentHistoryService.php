<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ContentHistoryRepository;
use Focite\Generator\Contracts\ServiceInterface;
use Focite\Generator\Services\CommonService;

class ContentHistoryService extends CommonService implements ServiceInterface
{
    public function getRepository(): ContentHistoryRepository
    {
        return ContentHistoryRepository::getInstance();
    }
}
