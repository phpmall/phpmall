<?php

declare(strict_types=1);

namespace App\Services;

use App\Foundation\Contracts\ServiceInterface;
use App\Foundation\Services\CommonService;
use App\Repositories\ContentTagRelationRepository;

class ContentTagRelationService extends CommonService implements ServiceInterface
{
    public function getRepository(): ContentTagRelationRepository
    {
        return ContentTagRelationRepository::getInstance();
    }
}
