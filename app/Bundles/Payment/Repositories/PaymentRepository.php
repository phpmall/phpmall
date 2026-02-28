<?php

declare(strict_types=1);

namespace App\Bundles\Payment\Repositories;

use App\Bundles\Payment\Entities\PaymentEntity;
use App\Bundles\Payment\Models\Payment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class PaymentRepository extends CurdRepository implements RepositoryInterface
{
    private static ?PaymentRepository $instance = null;

    /**
     * 单例 PaymentRepository
     */
    public static function getInstance(): PaymentRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new PaymentRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 PaymentEntity
     */
    public function saveEntity(PaymentEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?PaymentEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new PaymentEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?PaymentEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new PaymentEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('payment');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new Payment;
    }
}
