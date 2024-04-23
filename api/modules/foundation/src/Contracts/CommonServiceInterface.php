<?php

declare(strict_types=1);

namespace Juling\Foundation\Contracts;

interface CommonServiceInterface
{
    const DEFAULT_BATCH_SIZE = 1000;

    /**
     * 插入记录并返回 ID 值
     */
    public function insertGetId(array $entity): int;

    /**
     * 插入一条记录
     */
    public function save(array $entity): bool;

    /**
     * 插入（批量）
     */
    public function saveBatch(array $dataSet, int $batchSize = self::DEFAULT_BATCH_SIZE): bool;

    /**
     * 批量修改插入
     */
    public function saveOrUpdateBatch(array $dataSet, int $batchSize = self::DEFAULT_BATCH_SIZE): bool;

    /**
     * 根据 ID 删除
     */
    public function removeById(int $id): bool;

    /**
     * 根据条件，删除记录
     */
    public function remove(array $condition): bool;

    /**
     * 删除（根据ID 批量删除）
     */
    public function removeByIds(array $ids): bool;

    /**
     * 根据 ID 选择修改
     */
    public function updateById(array $entity, int $id): bool;

    /**
     * 根据条件，更新记录
     */
    public function update(array $entity, array $condition): bool;

    /**
     * 存在更新记录，否插入一条记录
     */
    public function saveOrUpdate(array $entity): bool;

    /**
     * 根据 ID 查询
     */
    public function getOneById(int $id): array;

    /**
     * 查询（根据ID 批量查询）
     */
    public function getListByIds(array $ids, string $order, string $sort): array;

    /**
     * 根据条件，查询一条记录
     */
    public function getOne(array $condition, string $order, string $sort): array;

    /**
     * 查询某个字段的值
     */
    public function value(string $field, array $condition): mixed;

    /**
     * 查询某一列的值
     */
    public function pluck(string $field, array $condition): array;

    /**
     * 根据条件，查询总记录数
     */
    public function count(array $condition): int;

    /**
     * 查询列表
     */
    public function getList(array $condition, string $order, string $sort): array;

    /**
     * 分页查询列表
     */
    public function page(array $condition, int $page, int $perPage, string $order, string $sort): array;
}
