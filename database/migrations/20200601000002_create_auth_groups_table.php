<?php

use think\migration\db\Column;
use think\migration\Migrator;

class CreateAuthGroupsTable extends Migrator
{
    public function change()
    {
        $this->table('auth_groups')
            ->addColumn(Column::string('title')->setDefault('')->setComment('用户组中文名称'))
            ->addColumn(Column::tinyInteger('status')->setDefault(1)->setComment('用户组拥有的规则id，多个规则","隔开'))
            ->addColumn(Column::string('rules')->setDefault('')->setComment('状态：为1正常，为0禁用'))
            ->addColumn(Column::unsignedInteger('created_at')->setComment('创建时间'))
            ->addColumn(Column::unsignedInteger('updated_at')->setNullable()->setComment('更新时间'))
            ->setEngine('InnoDB')
            ->setCollation('utf8mb4_general_ci')
            ->setComment('角色表')
            ->create();
    }
}
