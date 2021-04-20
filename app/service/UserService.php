<?php

namespace app\service;

use app\model\User;

/**
 * Class UserService
 * @package app\service
 */
class UserService
{
    /**
     * @var User
     */
    private $user;

    /**
     * UserService constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findIdentity($id)
    {
        return $this->user->findOrEmpty($id);
    }

    /**
     * @param $token
     * @param null $type
     * @return null
     */
    public function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    /**
     * @param $username
     * @return mixed
     */
    public function findByUsername($username)
    {
        return $this->user->where('username', $username)->findOrEmpty();
    }

    /**
     * @param $email
     * @return mixed
     */
    public function findByEmail($email)
    {
        return $this->user->where('email', $email)->findOrEmpty();
    }

    /**
     * @param $mobile
     * @return mixed
     */
    public function findByMobile($mobile)
    {
        return $this->user->where('mobile', $mobile)->findOrEmpty();
    }
}
