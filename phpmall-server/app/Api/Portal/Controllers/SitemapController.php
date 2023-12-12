<?php

declare(strict_types=1);

namespace App\Api\Portal\Controllers;

use Illuminate\Contracts\Support\Renderable;

class SitemapController extends BaseController
{
    /**
     * 网站地图
     */
    public function index(): Renderable
    {
        return $this->display('sitemap');
    }
}
