<?php

declare(strict_types=1);

if (! function_exists('skin')) {
    /**
     * 主题文件链接
     */
    function skin(string $path = ''): string
    {
        return asset('themes/default/'.ltrim($path, '/'));
    }
}
