<?php

declare(strict_types=1);

namespace App\Gateways\Passport\Services;

use App\Gateways\Passport\Services\Input\UserRegisterInput;
use App\Services\Input\UserInput;
use App\Services\UserService as BaseUserService;
use Focite\Builder\Exceptions\CustomException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Throwable;

class UserService extends BaseUserService
{
    /**
     * 新用户注册
     *
     * @throws CustomException
     */
    public function register(UserRegisterInput $input): bool
    {
        $userInput = new UserInput();
        $userInput->setName($input->getMobile());
        $userInput->setAvatar('');
        $userInput->setMobile($input->getMobile());
        $userInput->setMobileVerifiedAt(now()->toDateTimeString());
        $userInput->setPassword(Hash::make(Str::random()));
        $userInput->setStatus(1);

        try {
            return $this->save($userInput->toArray());
        } catch (Throwable $e) {
            throw new CustomException($e->getMessage());
        }
    }
}
