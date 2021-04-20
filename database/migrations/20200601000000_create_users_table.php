<?php

use think\migration\db\Column;
use think\migration\Migrator;

class CreateUsersTable extends Migrator
{
    public function change()
    {
        $this->table('users')
            ->addColumn(Column::string('username', 32)->setUnique()->setDefault('')->setComment('用户名'))
            ->addColumn(Column::string('password')->setDefault('')->setComment('密码'))
            ->addColumn(Column::string('name', 32)->setDefault('')->setComment('昵称'))
            ->addColumn(Column::string('avatar')->setDefault('')->setComment('头像'))
            ->addColumn(Column::string('mobile', 11)->setUnique()->setDefault('')->setComment('手机号码'))
            ->addColumn(Column::unsignedInteger('mobile_verified_at')->setNullable()->setComment('手机号码验证时间'))
            ->addColumn(Column::string('email', 32)->setUnique()->setDefault('')->setComment('电子邮箱'))
            ->addColumn(Column::unsignedInteger('email_verified_at')->setNullable()->setComment('电子邮箱验证时间'))
            ->addColumn(Column::string('remember_token')->setDefault('')->setComment('API Token'))
            ->addColumn(Column::string('reset_token')->setDefault('')->setComment('重置密码'))
            ->addColumn(Column::unsignedInteger('created_at')->setComment('创建时间'))
            ->addColumn(Column::unsignedInteger('updated_at')->setNullable()->setComment('更新时间'))
            ->setEngine('InnoDB')
            ->setCollation('utf8mb4_general_ci')
            ->setComment('用户表')
            ->create();
    }
}
