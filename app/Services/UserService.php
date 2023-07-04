<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\UserRepository;
use App\Services\Input\UserInput;
use App\Services\Output\UserOutput;
use Focite\Builder\Contracts\ServiceInterface;
use Focite\Builder\Exceptions\CustomException;
use Focite\Builder\Services\CommonService;

class UserService extends CommonService implements ServiceInterface
{
    public function getRepository(): UserRepository
    {
        return UserRepository::getInstance();
    }

    /**
     * @throws CustomException
     */
    public function findOneByMobile(string $mobile): UserOutput
    {
        $result = $this->getOne(['mobile' => $mobile]);
        if (empty($result)) {
            throw new CustomException('没有查询到相关数据');
        }

        $userOutput = new UserOutput();
        $userOutput->setData($result);

        return $userOutput;
    }
}
