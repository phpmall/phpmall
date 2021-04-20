<?php

namespace app\controller\console;

use app\controller\Controller;
use app\middleware\Authenticate;

/**
 * Class BaseController
 * @package app\controller\console
 */
abstract class BaseController extends Controller
{
    protected $middleware = [
        Authenticate::class,
    ];
}
