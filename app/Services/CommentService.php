<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\CommentRepository;
use Focite\Generator\Contracts\ServiceInterface;
use Focite\Generator\Services\CommonService;

class CommentService extends CommonService implements ServiceInterface
{
    public function getRepository(): CommentRepository
    {
        return CommentRepository::getInstance();
    }
}
