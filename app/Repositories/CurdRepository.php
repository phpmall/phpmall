<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\CurdRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * @method Model model() 实体模型
 */
abstract class CurdRepository implements CurdRepositoryInterface
{
    /**
     * 保存给定的实体数据
     */
    public function save(array $data): int
    {
        return $this->model()->query()->insertGetId($data);
    }

    /**
     * 保存给定的实体数据数组
     */
    public function saveAll(array $data): bool
    {
        return $this->model()->query()->insert($data);
    }

    /**
     * 根据实体的id检索实体
     */
    public function findById(int $id): array
    {
        $result = $this->model()->query()->find($id);
        if ($result->isEmpty()) {
            return [];
        }

        return collect($result)->toArray();
    }

    /**
     * 根据条件检索实体
     */
    public function findByWhere(array $condition, string $order = 'id', string $sort = 'desc'): array
    {
        $result = $this->model()->query()->where($condition)->orderBy($order, $sort)->first();
        if (is_null($result)) {
            return [];
        }

        return collect($result)->toArray();
    }

    /**
     * 查询某个字段的值
     */
    public function value(string $field, array $condition): mixed
    {
        return $this->model()->query()->where($condition)->value($field);
    }

    /**
     * 获取某一列的值
     */
    public function pluck(string $field, array $condition): array
    {
        $result = $this->model()->query()->where($condition)->pluck($field);

        return collect($result)->toArray();
    }

    /**
     * 返回具有给定id的实体是否存在
     */
    public function existsById(int $id): bool
    {
        return $this->model()->query()->find($id)->exists();
    }

    /**
     * 返回该类型的所有实例
     */
    public function findAll(array $condition, string $order = 'id', string $sort = 'desc'): array
    {
        $result = $this->model()->query()->where($condition)->orderBy($order, $sort)->get();
        if ($result->isEmpty()) {
            return [];
        }

        return $result->toArray();
    }

    /**
     * 返回具有给定id类型的所有实例
     */
    public function findAllById(array $ids, string $order = 'id', string $sort = 'desc'): array
    {
        $result = $this->model()->query()->whereIn('id', $ids)->orderBy($order, $sort)->get();
        if ($result->isEmpty()) {
            return [];
        }

        return $result->toArray();
    }

    /**
     * 返回可用实体的数量
     */
    public function count(array $condition): int
    {
        return $this->model()->query()->where($condition)->count();
    }

    /**
     * 删除具有给定id的实体
     */
    public function deleteById(int $id): bool
    {
        return $this->model()->query()->find($id)->delete();
    }

    /**
     * 删除给定条件的实体
     */
    public function delete(array $condition): bool
    {
        if (empty($condition)) {
            return false;
        }

        return $this->model()->query()->where($condition)->delete();
    }

    /**
     * 删除具有给定id类型的所有实例
     */
    public function deleteAllById(array $ids): bool
    {
        if (empty($ids)) {
            return false;
        }

        return $this->model()->query()->whereIn('id', $ids)->delete();
    }

    /**
     * 分页查询
     */
    public function page(array $condition, int $page = 1, int $perPage = 20, string $order = 'id', string $sort = 'desc'): array
    {
        $result = $this->model()->query()->where($condition)->orderBy($order, $sort)->paginate($perPage, ['*'], 'page', $page);
        if ($result->isEmpty()) {
            return [];
        }

        return $result->toArray();
    }

    /**
     * 按ID更新数据
     */
    public function updateById(array $data, int $id): int
    {
        return $this->model()->query()->where('id', $id)->update($data);
    }

    /**
     * 按条件更新数据
     */
    public function update(array $data, array $condition): int
    {
        if (empty($condition)) {
            return 0;
        }

        return $this->model()->query()->where($condition)->update($data);
    }
}
