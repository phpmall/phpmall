<?php

use think\migration\db\Column;
use think\migration\Migrator;

class CreateAuthRulesTable extends Migrator
{
    public function change()
    {
        $this->table('auth_rules')
            ->addColumn(Column::string('name')->setUnique()->setDefault('')->setComment('规则唯一标识'))
            ->addColumn(Column::string('title')->setDefault('')->setComment('规则中文名称'))
            ->addColumn(Column::tinyInteger('type')->setDefault(1)->setComment('验证类型'))
            ->addColumn(Column::tinyInteger('status')->setDefault(1)->setComment('状态：为1正常，为0禁用'))
            ->addColumn(Column::string('condition')->setDefault('')->setComment('规则表达式，为空表示存在就验证，不为空表示按照条件验证'))
            ->setEngine('InnoDB')
            ->setCollation('utf8mb4_general_ci')
            ->setComment('权限表')
            ->create();
    }
}
