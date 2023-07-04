<?php

declare(strict_types=1);

namespace App\Enums;

enum ArticleStatus: int
{
    /**
     * 草稿
     */
    const Draft = 0;

    /**
     * 已发布
     */
    const Published = 1;

    /**
     * 无效
     */
    const Invalid = 2;
}
