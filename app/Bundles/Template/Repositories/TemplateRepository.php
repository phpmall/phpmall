<?php

declare(strict_types=1);

namespace App\Bundles\Template\Repositories;

use App\Bundles\Template\Entities\TemplateEntity;
use App\Bundles\Template\Models\Template;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class TemplateRepository extends CurdRepository implements RepositoryInterface
{
    private static ?TemplateRepository $instance = null;

    /**
     * 单例 TemplateRepository
     */
    public static function getInstance(): TemplateRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new TemplateRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 TemplateEntity
     */
    public function saveEntity(TemplateEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?TemplateEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new TemplateEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?TemplateEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new TemplateEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('template');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new Template;
    }
}
