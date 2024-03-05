<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ServiceInterface;
use App\Repositories\CommentRepository;

class CommentService extends CommonService implements ServiceInterface
{
    public function getRepository(): CommentRepository
    {
        return CommentRepository::getInstance();
    }
}
