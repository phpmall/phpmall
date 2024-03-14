<?php

declare(strict_types=1);

namespace App\Bundles\CMS\Migrations\Content\Enums;

enum ContentStatusEnum: int
{
    /**
     * 草稿
     */
    case Draft = 0;

    /**
     * 已发布
     */
    case Published = 1;

    /**
     * 无效
     */
    case Invalid = 2;
}
