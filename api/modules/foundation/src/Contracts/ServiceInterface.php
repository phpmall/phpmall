<?php

declare(strict_types=1);

namespace Juling\Foundation\Contracts;

interface ServiceInterface
{
    public function getRepository(): CurdRepositoryInterface;
}
