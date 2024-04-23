<?php

declare(strict_types=1);

namespace Juling\Foundation\Support;

class CategoryHelper
{
    /**
     * 原始数据
     */
    private array $rawList = [];

    /**
     * 格式化数据
     */
    private array $formatList = [];

    /**
     * 分类样式
     */
    private array $icon = ['│', '├', '└'];

    /**
     * 映射字段
     */
    private array $field = [];

    /**
     * 构建函数
     */
    public function __construct(array $field = [])
    {
        $this->field['id'] = $field['id'] ?? 'id';
        $this->field['pid'] = $field['pid'] ?? 'pid';
        $this->field['title'] = $field['title'] ?? 'title';
        $this->field['full_title'] = $field['full_title'] ?? 'full_title';
    }

    /**
     * 获取同级分类
     */
    public function getChild(int $pid, array $data = []): array
    {
        $childList = [];
        if (empty($data)) {
            $data = $this->rawList;
        }
        foreach ($data as $category) {
            if ($category[$this->field['pid']] === $pid) {
                $childList[] = $category;
            }
        }

        return $childList;
    }

    /**
     * 获取树形分类
     */
    public function getTree(array $data, int $id = 0): array
    {
        //数据为空，则返回
        if (empty($data)) {
            return [];
        }

        $this->formatList = [];
        $this->rawList = $data;
        $this->searchList($id);

        return $this->formatList;
    }

    /**
     * 获取树形分类2
     */
    public function getTree2(array $data, int $parentId = 0): array
    {
        $tree = [];

        foreach ($data as $node) {
            if ($node[$this->field['pid']] === $parentId) {
                $node['child'] = $this->getTree2($data, $node[$this->field['id']]);
                $tree[] = $node;
            }
        }

        return $tree;
    }

    /**
     * 获取分类路径
     */
    public function getPath(array $data, int $id): array
    {
        $this->rawList = $data;
        while (1) {
            $id = $this->getPid($id);
            if ($id == 0) {
                break;
            }
        }

        return array_reverse($this->formatList);
    }

    /**
     * 递归分类
     */
    private function searchList(int $id = 0, string $space = ''): void
    {
        //下级分类的数组
        $childList = $this->getChild($id);
        //如果没下级分类，结束递归
        if (! ($n = count($childList))) {
            return;
        }
        $cnt = 1;
        //循环所有的下级分类
        for ($i = 0; $i < $n; $i++) {
            $pre = '';
            $pad = '';
            if ($n == $cnt) {
                $pre = $this->icon[2];
            } else {
                $pre = $this->icon[1];
                $pad = $space ? $this->icon[0] : '';
            }
            $childList[$i][$this->field['full_title']] = ($space ? $space.$pre : '').$childList[$i][$this->field['title']];
            $this->formatList[] = $childList[$i];
            //递归下一级分类
            $this->searchList($childList[$i][$this->field['id']], $space.$pad.'&nbsp;&nbsp;');
            $cnt++;
        }
    }

    /**
     * 获取PID
     */
    private function getPid(int $id): int
    {
        foreach ($this->rawList as $key => $value) {
            if ($this->rawList[$key][$this->field['id']] === $id) {
                $this->formatList[] = $this->rawList[$key];

                return $this->rawList[$key][$this->field['pid']];
            }
        }

        return 0;
    }
}
