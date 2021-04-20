<?php

namespace app\controller\passport;

use think\response\Redirect;

/**
 * Class IndexController
 * @package app\controller\passport
 */
class IndexController
{
    /**
     * @return Redirect
     */
    public function index()
    {
        return redirect('/passport/login');
    }
}