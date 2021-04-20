<?php

namespace app\validate;

use think\Validate;

/**
 * Class Login
 * @package app\validate
 */
class LoginValidator extends Validate
{
    protected $rule = [
        'username' => 'require|max:32',
        'password' => 'require',
    ];

    protected $message = [
        'username.require' => '用户名称必须',
        'username.max' => '用户名称最多不能超过32个字符',
        'password' => '用户登录密码必须',
    ];
}
