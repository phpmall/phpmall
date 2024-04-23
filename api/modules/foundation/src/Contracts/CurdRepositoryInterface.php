<?php

declare(strict_types=1);

namespace Juling\Foundation\Contracts;

interface CurdRepositoryInterface
{
    /**
     * 保存给定的实体数据
     */
    public function save(array $data): int;

    /**
     * 保存给定的实体数据数组
     */
    public function saveAll(array $data): bool;

    /**
     * 根据实体的id检索实体
     */
    public function findById(int $id): array;

    /**
     * 根据条件检索实体
     */
    public function find(array $condition, string $order, string $sort): array;

    /**
     * 查询某个字段的值
     */
    public function value(string $field, array $condition): mixed;

    /**
     * 获取某一列的值
     */
    public function pluck(string $field, array $condition): array;

    /**
     * 返回具有给定id的实体是否存在
     */
    public function existsById(int $id): bool;

    /**
     * 返回该类型的所有实例
     */
    public function findAll(array $condition, string $order, string $sort): array;

    /**
     * 返回具有给定id类型的所有实例
     */
    public function findAllByIds(array $ids, string $order, string $sort): array;

    /**
     * 返回可用实体的数量
     */
    public function count(array $condition): int;

    /**
     * 删除具有给定id的实体
     */
    public function deleteById(int $id): bool;

    /**
     * 删除给定条件的实体
     */
    public function delete(array $condition): bool;

    /**
     * 删除具有给定id类型的所有实例
     */
    public function deleteAllByIds(array $ids): bool;

    /**
     * 分页查询
     */
    public function page(array $condition, int $page, int $perPage, string $order, string $sort): array;

    /**
     * 按ID更新数据
     */
    public function updateById(array $data, int $id): int;

    /**
     * 按条件更新数据
     */
    public function update(array $data, array $condition): int;
}
