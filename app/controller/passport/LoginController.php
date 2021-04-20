<?php

namespace app\controller\passport;

use app\component\Request;
use app\controller\Controller;
use app\middleware\RedirectIfAuthenticated;
use app\validate\LoginValidator;
use think\exception\ValidateException;
use think\response\Json;

/**
 * Class LoginController
 * @package app\controller\passport
 */
class LoginController extends Controller
{
    /**
     * @var array
     */
    protected $middleware = [
        RedirectIfAuthenticated::class,
    ];

    /**
     * @return string
     */
    public function index()
    {
        return view('index');
    }

    /**
     * @param Request $request
     * @return Json
     */
    public function loginAction(Request $request)
    {
        try {
            validate(LoginValidator::class)->check($request->post());
        } catch (ValidateException $e) {
            return $this->failed($e->getError());
        }


    }
}
