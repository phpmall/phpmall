<?php

use think\migration\db\Column;
use think\migration\Migrator;

class CreateAuthGroupAccessTable extends Migrator
{
    public function change()
    {
        $this->table('auth_group_access')
            ->addColumn(Column::integer('user_id')->setComment('用户id'))
            ->addColumn(Column::integer('auth_group_id')->setComment('用户组id'))
            ->addIndex(['user_id', 'auth_group_id'], ['unique' => true])
            ->addIndex('user_id')
            ->addIndex('auth_group_id')
            ->setEngine('InnoDB')
            ->setCollation('utf8mb4_general_ci')
            ->setComment('角色授权表')
            ->create();
    }
}
