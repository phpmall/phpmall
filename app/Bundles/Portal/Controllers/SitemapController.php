<?php

declare(strict_types=1);

namespace App\Bundles\Portal\Controllers;

use App\Bundles\Portal\Controllers\BaseController;
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
