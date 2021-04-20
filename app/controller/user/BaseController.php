<?php

namespace app\controller\user;

use app\controller\Controller;

/**
 * Class BaseController
 * @package app\controller\user
 */
abstract class BaseController extends Controller
{
    protected $middleware = [];
}