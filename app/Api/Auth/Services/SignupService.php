<?php

declare(strict_types=1);

namespace App\Api\Auth\Services;

use App\Api\Auth\Services\Input\UserRegisterInput;
use App\Foundation\Exceptions\CustomException;
use App\Services\UserService as BaseUserService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Throwable;

class SignupService extends BaseUserService
{
    /**
     * 新用户注册
     *
     * @throws CustomException
     */
    public function mobile(UserRegisterInput $input): bool
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
