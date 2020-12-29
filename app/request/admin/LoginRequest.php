<?php

declare(strict_types=1);

namespace app\request\admin;

/**
 * @OA\Schema
 * Class LoginRequest
 * @package app\request\admin
 */
class LoginRequest
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
    public $password;
}
