<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SqlController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        $_POST['sql'] = ! empty($_POST['sql']) ? trim($_POST['sql']) : '';

        if (! $_POST['sql']) {
            $_REQUEST['act'] = 'main';
        }

        /**
         * 用户帐号列表
         */
        if ($action === 'main') {
            $this->admin_priv('sql_query');

            $this->assign('type', -1);
            $this->assign('ur_here', lang('04_sql_query'));

            return $this->display('sql');
        }

        if ($action === 'query') {
            $this->admin_priv('sql_query');
            if (! empty($_POST['sql'])) {
                preg_match_all('/(SELECT)/i', $_POST['sql'], $matches);
                if (isset($matches[1]) && count($matches[1]) > 1) {
                    return $this->sys_msg('this sql more than one SELECT ');
                }

                if (preg_match('/(UPDATE|DELETE|TRUNCATE|ALTER|DROP|FLUSH|INSERT|REPLACE|SET|CREATE|CONCAT)/i', $_POST['sql'])) {
                    return $this->sys_msg('this sql May contain UPDATE,DELETE,TRUNCATE,ALTER,DROP,FLUSH,INSERT,REPLACE,SET,CREATE,CONCAT ');
                }
            }

            $this->assign_sql($_POST['sql']);

            $this->assign('ur_here', lang('04_sql_query'));

            return $this->display('sql');
        }
    }

    /**
     * @return void
     */
    private function assign_sql($sql)
    {
        $sql = stripslashes($sql);
        $this->assign('sql', $sql);

        // 解析查询项
        $sql = str_replace("\r", '', $sql);
        $query_items = explode(";\n", $sql);
        foreach ($query_items as $key => $value) {
            if (empty($value)) {
                unset($query_items[$key]);
            }
        }
        // 如果是多条语句，拆开来执行
        if (count($query_items) > 1) {
            foreach ($query_items as $value) {
                try {
                    DB::statement($value);
                } catch (\Exception $e) {
                    $this->assign('type', 0);
                    $this->assign('error', $e->getMessage());

                    return;
                }
            }
            $this->assign('type', 1);

            return; // 退出函数
        }

        // 单独一条sql语句处理
        if (preg_match('/^(?:UPDATE|DELETE|TRUNCATE|ALTER|DROP|FLUSH|INSERT|REPLACE|SET|CREATE)\\s+/i', $sql)) {
            try {
                DB::statement($sql);
                $this->assign('type', 1);
            } catch (\Exception $e) {
                $this->assign('type', 0);
                $this->assign('error', $e->getMessage());
            }
        } else {
            try {
                $data = DB::select($sql);
                // DB::select returns an array of stdClass objects.
                $data = array_map(function ($item) {
                    return (array) $item;
                }, $data);

                if (empty($data)) {
                    $result = '<center><h3>'.lang('no_data').'</h3></center>';
                } else {
                    $result = "<table> \n <tr>";
                    $keys = array_keys($data[0]);
                    for ($i = 0, $num = count($keys); $i < $num; $i++) {
                        $result .= '<th>'.$keys[$i]."</th>\n";
                    }
                    $result .= "</tr> \n";
                    foreach ($data as $data1) {
                        $result .= "<tr>\n";
                        foreach ($data1 as $value) {
                            $result .= '<td>'.htmlspecialchars((string) $value).'</td>';
                        }
                        $result .= "</tr>\n";
                    }
                    $result .= "</table>\n";
                }

                $this->assign('type', 2);
                $this->assign('result', $result);
            } catch (\Exception $e) {
                $this->assign('type', 0);
                $this->assign('error', $e->getMessage());
            }
        }
    }
}
