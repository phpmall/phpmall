<?php

use think\migration\db\Column;
use think\migration\Migrator;

class CreateUsersTable extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $this->table('users', ['engine' => 'InnoDB', 'comment' => '用户表'])
            ->addColumn(Column::string('username', 32)->setComment('用户名'))
            ->addColumn(Column::string('password')->setComment('登录密码'))
            ->addColumn(Column::string('password_salt', 8)->setComment('密码盐值'))
            ->addColumn(Column::string('name', 32)->setComment('昵称'))
            ->addColumn(Column::string('avatar')->setComment('头像'))
            ->addColumn(Column::string('email')->setComment('电子邮箱'))
            ->addColumn(Column::string('mobile', 16)->setComment('手机号码'))
            ->addTimestamps()
            ->addSoftDelete()
            ->create();
    }
}
