<?php

namespace app\service;

/**
 * Class AuthService
 * @package app\service
 */
class AuthService
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var LogService
     */
    private $logService;

    /**
     * AuthService constructor.
     * @param UserService $userService
     * @param LogService $logService
     */
    public function __construct(
        UserService $userService,
        LogService $logService
    )
    {
        $this->userService = $userService;
        $this->logService = $logService;
    }

    /**
     * @param $username
     * @param $password
     * @return array|bool
     */
    public function login($username, $password)
    {
        if (is_email($username)) {
            $model = $this->userService->findByEmail($username);
        } elseif (is_mobile($username)) {
            $model = $this->userService->findByMobile($username);
        } else {
            $model = $this->userService->findByUsername($username);
        }

        if (is_null($model)) {
            return false;
        }

        if (!password_verify($password, $model->getAttr('password'))) {
            return false;
        }

        unset($model->id);
        unset($model->password);

        return $model->toArray();
    }

    /**
     * @param $username
     * @return bool
     */
    public function register($username)
    {
        return false;
    }
}
