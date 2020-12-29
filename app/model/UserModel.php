<?php

declare(strict_types=1);

namespace app\model;

/**
 * @OA\Schema
 * Class UserModel
 * @package app\model
 */
class UserModel
{
    /**
     * @var string
     * @OA\Property
     */
    public $username;

    /**
     * @var string
     * @OA\Property
     */
    public $avatar;
}
