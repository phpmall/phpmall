<?php

use think\migration\db\Column;
use think\migration\Migrator;

class CreateSettingsTable extends Migrator
{
    public function change()
    {
        $this->table('settings')
            ->addColumn(Column::integer('parent_id')->setDefault(0)->setComment('父节点id'))
            ->addColumn(Column::string('code')->setUnique()->setDefault('')->setComment('配置code'))
            ->addColumn(Column::string('type')->setDefault('')->setComment('配置类型：text、select、file、hidden等'))
            ->addColumn(Column::string('store_range')->setDefault('')->setComment('配置数组索引'))
            ->addColumn(Column::string('value')->setDefault('')->setComment('该项配置的值'))
            ->addColumn(Column::tinyInteger('sort_order')->setDefault(1)->setComment('排序'))
            ->addIndex('parent_id')
            ->setEngine('InnoDB')
            ->setCollation('utf8mb4_general_ci')
            ->setComment('配置表')
            ->create();
    }
}

