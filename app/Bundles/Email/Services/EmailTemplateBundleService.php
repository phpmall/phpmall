<?php

declare(strict_types=1);

namespace App\Bundles\Email\Services;

use App\Bundles\Email\Repositories\EmailTemplateRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class EmailTemplateBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): EmailTemplateRepository
    {
        return EmailTemplateRepository::getInstance();
    }
}
