<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ContentAttachment;
use App\Models\Entity\ContentAttachmentEntity;
use Juling\Generator\Contracts\RepositoryInterface;
use Juling\Generator\Repositories\CurdRepository;

class ContentAttachmentRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ContentAttachmentRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): ContentAttachmentRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ContentAttachmentRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(ContentAttachmentEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ContentAttachmentEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new ContentAttachmentEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ContentAttachmentEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new ContentAttachmentEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): ContentAttachment
    {
        return new ContentAttachment();
    }
}
