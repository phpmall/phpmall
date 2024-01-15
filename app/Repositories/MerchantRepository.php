<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\MerchantEntity;
use App\Models\Merchant;
use Juling\Generator\Contracts\RepositoryInterface;
use Juling\Generator\Repositories\CurdRepository;

class MerchantRepository extends CurdRepository implements RepositoryInterface
{
    private static ?MerchantRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): MerchantRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new MerchantRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(MerchantEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?MerchantEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new MerchantEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?MerchantEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new MerchantEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): Merchant
    {
        return new Merchant();
    }
}
