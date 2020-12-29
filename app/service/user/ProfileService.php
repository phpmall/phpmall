<?php

declare (strict_types=1);

namespace app\service\user;

use app\entity\User;

/**
 * Class ProfileService
 * @package app\service\user
 */
class ProfileService
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
}
