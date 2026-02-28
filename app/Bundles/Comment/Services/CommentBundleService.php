<?php

declare(strict_types=1);

namespace App\Bundles\Comment\Services;

use App\Bundles\Comment\Repositories\CommentRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class CommentBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): CommentRepository
    {
        return CommentRepository::getInstance();
    }
}
