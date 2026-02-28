<?php

declare(strict_types=1);

namespace App\Bundles\Feedback\Services;

use App\Bundles\Feedback\Repositories\FeedbackRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class FeedbackBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): FeedbackRepository
    {
        return FeedbackRepository::getInstance();
    }
}
