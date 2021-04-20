<?php

namespace app\controller\cms;

use app\controller\Controller;

/**
 * Class IndexController
 * @package app\controller\cms
 */
class IndexController extends Controller
{
    public function index(string $path = null)
    {
        return 'hello cms' . $path;
    }
}
