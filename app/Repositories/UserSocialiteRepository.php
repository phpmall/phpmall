<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\UserSocialite;
use App\Models\UserSocialiteModel;
use Focite\Builder\Contracts\RepositoryInterface;
use Focite\Builder\Repositories\CurdRepository;

class UserSocialiteRepository extends CurdRepository implements RepositoryInterface
{
    private static ?UserSocialiteRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): UserSocialiteRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new UserSocialiteRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveUserSocialite(UserSocialite $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneByIdReturnUserSocialite(int $id): ?UserSocialite
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $output = new UserSocialite();
        $output->setData($data);

        return $output;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOneByWhereReturnUserSocialite(array $condition): ?UserSocialite
    {
        $data = $this->findByWhere($condition);
        if (empty($data)) {
            return null;
        }

        $output = new UserSocialite();
        $output->setData($data);

        return $output;
    }

    /**
     * 查询列表
     */
    public function findAllReturnUserSocialite(array $condition = [], string $order = 'id', string $sort = 'asc'): array
    {
        $result = $this->findAll($condition, $order, $sort);
        if (empty($result)) {
            return [];
        }

        foreach ($result as $key => $item) {
            $output = new UserSocialite();
            $output->setData($item);
            $result[$key] = $output;
        }

        return $result;
    }

    /**
     * 分页查询
     */
    public function pageReturnUserSocialite(array $condition, int $page, int $pageSize): array
    {
        $result = $this->page($condition, $page, $pageSize);

        foreach ($result['data'] as $key => $item) {
            $output = new UserSocialite();
            $output->setData($item);
            $result['data'][$key] = $output;
        }

        return $result;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): UserSocialiteModel
    {
        return new UserSocialiteModel();
    }
}
