<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ContentTagRelationRepository;
use Focite\Generator\Contracts\ServiceInterface;
use Focite\Generator\Services\CommonService;

class ContentTagRelationService extends CommonService implements ServiceInterface
{
    public function getRepository(): ContentTagRelationRepository
    {
        return ContentTagRelationRepository::getInstance();
    }
}
