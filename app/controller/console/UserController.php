<?php

namespace app\controller\console;

/**
 * Class UserController
 * @package app\controller\console
 */
class UserController extends BaseController
{
    public function index()
    {
        return 'admin user page';
    }

    public function detail()
    {
        return 'admin user detail page';
    }

    public function detailAction()
    {
        return 'admin user detailAction';
    }
}