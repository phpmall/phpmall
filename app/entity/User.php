<?php

declare (strict_types=1);

namespace app\entity;

use think\Model;

/**
 * Class User
 * @package app\entity
 */
class User extends Model
{
    /**
     * @var string
     */
    protected $table = 'users';

    /**
     * @var string
     */
    protected $pk = 'id';
}
