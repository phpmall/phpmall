<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ContentRelationRepository;
use Focite\Generator\Contracts\ServiceInterface;
use Focite\Generator\Services\CommonService;

class ContentRelationService extends CommonService implements ServiceInterface
{
    public function getRepository(): ContentRelationRepository
    {
        return ContentRelationRepository::getInstance();
    }
}
