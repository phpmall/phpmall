<?php

declare(strict_types=1);

namespace App\Modules\Admin\Libraries;

use Illuminate\Support\Facades\DB;

class Exchange
{
    public $table;

    public $id;

    public $name;

    public $error_msg;

    /**
     * 构造函数
     *
     * @param  string  $table  数据库表名
     * @param  mixed  $db  (deprecated)
     * @param  string  $id  数据表主键字段名
     * @param  string  $name  数据表重要段名
     * @return void
     */
    public function __construct($table, $db, $id, $name)
    {
        $this->table = str_replace(config('database.connections.mysql.prefix'), '', $table);
        $this->id = $id;
        $this->name = $name;
        $this->error_msg = '';
    }

    /**
     * 判断表中某字段是否重复，若重复则中止程序，并给出错误信息
     *
     * @param  string  $col  字段名
     * @param  string  $name  字段值
     * @param  mixed  $id
     * @param  string  $where
     */
    public function is_only($col, $name, $id = 0, $where = ''): bool
    {
        $query = DB::table($this->table)->where($col, $name);
        if (! empty($id)) {
            $query->where($this->id, '<>', $id);
        }
        if (! empty($where)) {
            $query->whereRaw($where);
        }

        return $query->count() === 0;
    }

    /**
     * 返回指定名称记录再数据表中记录个数
     *
     * @param  string  $col  字段名
     * @param  string  $name  字段内容
     * @param  mixed  $id
     * @return int 记录个数
     */
    public function num($col, $name, $id = 0): int
    {
        $query = DB::table($this->table)->where($col, $name);
        if (! empty($id)) {
            $query->where($this->id, '!=', $id);
        }

        return (int) $query->count();
    }

    /**
     * 编辑某个字段
     *
     * @param  string|array  $set  要更新集合
     * @param  mixed  $id  要更新的记录编号
     * @return bool 成功或失败
     */
    public function edit($set, $id): bool
    {
        if (is_string($set)) {
            return DB::update('UPDATE '.$this->table." SET $set WHERE ".$this->id.' = ?', [$id]) >= 0;
        }

        return DB::table($this->table)->where($this->id, $id)->update($set) >= 0;
    }

    /**
     * 取得某个字段的值
     *
     * @param  mixed  $id  记录编号
     * @param  string  $name  字段名
     * @return string|null 取出的数据
     */
    public function get_name($id, $name = ''): ?string
    {
        if (empty($name)) {
            $name = $this->name;
        }

        return (string) DB::table($this->table)->where($this->id, $id)->value($name);
    }

    /**
     * 删除条记录
     *
     * @param  mixed  $id  记录编号
     */
    public function drop($id): bool
    {
        return DB::table($this->table)->where($this->id, $id)->delete() > 0;
    }
}
