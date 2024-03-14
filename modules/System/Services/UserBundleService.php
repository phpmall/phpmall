<?php

declare(strict_types=1);

namespace App\Bundles\System\Services;

use App\Bundles\System\Services\UserService;
use App\Foundation\Exceptions\CustomException;
use App\Bundles\System\API\UserInterface;

class UserBundleService extends UserService implements UserInterface
{
    /**
     * @throws CustomException
     */
    public function getUserById(int $id): array
    {
        $user = $this->getOneById($id);
        if (empty($user)) {
            throw new CustomException('没有找到该用户');
        }

        $user['avatar'] = 'formatting'; // TODO implement

        return $user;
    }
}
