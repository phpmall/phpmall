<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ContentAttachmentRepository;
use Juling\Generator\Contracts\ServiceInterface;
use Juling\Generator\Services\CommonService;

class ContentAttachmentService extends CommonService implements ServiceInterface
{
    public function getRepository(): ContentAttachmentRepository
    {
        return ContentAttachmentRepository::getInstance();
    }
}
