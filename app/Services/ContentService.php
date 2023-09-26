<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ContentRepository;
use Focite\Generator\Contracts\ServiceInterface;
use Focite\Generator\Services\CommonService;

class ContentService extends CommonService implements ServiceInterface
{
    public function getRepository(): ContentRepository
    {
        return ContentRepository::getInstance();
    }
}
