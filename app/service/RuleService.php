<?php

namespace app\service;

use app\model\AuthRule;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/**
 * Class RuleService
 * @package app\service
 */
class RuleService
{
    /**
     * 获取管理资源链接
     * @param int $menu 是否仅显示菜单
     * @param int $status 显示状态
     * @return array|int
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getRule($menu = 1, $status = 1)
    {
        $collection = AuthRule::where('status', $status)
            ->where('menu', $menu)
            ->order('sort', 'asc')
            ->order('id', 'asc')
            ->select();

        $menu = [];
        foreach ($collection->toArray() as $item) {
            if ($item['parent'] === 0) {
                $filtered = $collection->filter(function ($v) use ($item) {
                    return $v['parent'] == $item['id'];
                });

                $sub = [];
                foreach ($filtered->all() as $v) {
                    $sub[] = [
                        'name' => $v['title'],
                        'url' => url('admin/' . str_replace('/', '.', $v['name'])),
                        'icon' => $v['icon'],
                    ];
                }

                $menu[] = [
                    'name' => $item['title'],
                    'url' => 'javascript:void(0);',
                    'icon' => $item['icon'],
                    'sub' => $sub,
                ];
            }
        }

        return $menu;
    }
}
