<?php

declare(strict_types=1);

namespace App\Bundles\Auth\Services;

use App\Bundles\Auth\Services\Input\RegisterInput;
use App\Exceptions\CustomException;
use App\Services\UserService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SignupService extends UserService
{
    /**
     * 新用户注册
     *
     * @throws CustomException
     */
    public function handle(RegisterInput $input): bool
    {
        $userInput = new LoginInput();
        $userInput->setName($input->getMobile());
        $userInput->setAvatar('');
        $userInput->setMobile($input->getMobile());
        $userInput->setMobileVerifiedAt(now()->toDateTimeString());
        $userInput->setPassword(Hash::make(Str::random()));
        $userInput->setStatus(1);

        return $this->save($userInput->toArray());
    }
}
