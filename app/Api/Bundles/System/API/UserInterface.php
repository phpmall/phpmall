<?php

declare(strict_types=1);

namespace App\Bundles\System\API;

interface UserInterface
{
    /**
     * 根据ID获取用户详情
     */
    public function getUserById(int $id);
}
