<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\CodeHelper;
use App\Helpers\TimeHelper;
use App\Modules\Admin\Helpers\MainHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VirtualCardController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        /**
         * 补货处理
         */
        if ($action === 'replenish') {
            $this->admin_priv('virualcard');
            // 验证goods_id是否合法
            if (empty($_REQUEST['goods_id'])) {
                $link[] = ['text' => lang('go_back'), 'href' => 'virtual_card.php?act=list'];

                return $this->sys_msg(lang('replenish_no_goods_id'), 1, $link);
            } else {
                $goods_name = DB::table('goods')
                    ->where('goods_id', $_REQUEST['goods_id'])
                    ->where('is_real', 0)
                    ->where('extension_code', 'virtual_card')
                    ->value('goods_name');
                if (empty($goods_name)) {
                    $link[] = ['text' => lang('go_back'), 'href' => 'virtual_card.php?act=list'];

                    return $this->sys_msg(lang('replenish_no_get_goods_name'), 1, $link);
                }
            }

            $card = ['goods_id' => $_REQUEST['goods_id'], 'goods_name' => $goods_name, 'end_date' => date('Y-m-d', strtotime('+1 year'))];
            $this->assign('card', $card);

            $this->assign('ur_here', lang('replenish'));
            $this->assign('action_link', ['text' => lang('go_list'), 'href' => 'virtual_card.php?act=card&goods_id='.$card['goods_id']]);

            return $this->display('replenish_info');
        }

        /**
         * 编辑补货信息
         */
        if ($action === 'edit_replenish') {
            $this->admin_priv('virualcard');
            // 获取卡片信息
            $card = (array) DB::table('goods_virtual_card AS T1')
                ->join(ecs()->table('goods').' AS T2', 'T1.goods_id', '=', 'T2.goods_id')
                ->where('T1.card_id', $_REQUEST['card_id'])
                ->select('T1.card_id', 'T1.goods_id', 'T2.goods_name', 'T1.card_sn', 'T1.card_password', 'T1.end_date', 'T1.crc32')
                ->first();
            if ($card['crc32'] === 0 || $card['crc32'] === crc32(AUTH_KEY)) {
                $card['card_sn'] = CodeHelper::decrypt($card['card_sn']);
                $card['card_password'] = CodeHelper::decrypt($card['card_password']);
            } elseif ($card['crc32'] === crc32(OLD_AUTH_KEY)) {
                $card['card_sn'] = CodeHelper::decrypt($card['card_sn'], OLD_AUTH_KEY);
                $card['card_password'] = CodeHelper::decrypt($card['card_password'], OLD_AUTH_KEY);
            } else {
                $card['card_sn'] = '***';
                $card['card_password'] = '***';
            }

            $this->assign('ur_here', lang('replenish'));
            $this->assign('action_link', ['text' => lang('go_list'), 'href' => 'virtual_card.php?act=card&goods_id='.$card['goods_id']]);
            $this->assign('card', $card);

            return $this->display('replenish_info');
        }

        if ($action === 'action') {
            $this->admin_priv('virualcard');

            $_POST['card_sn'] = trim($_POST['card_sn']);

            // 加密后的
            $coded_card_sn = CodeHelper::encrypt($_POST['card_sn']);
            $coded_old_card_sn = CodeHelper::encrypt($_POST['old_card_sn']);
            $coded_card_password = CodeHelper::encrypt($_POST['card_password']);

            // 在前后两次card_sn不一致时，检查是否有重复记录,一致时直接更新数据
            if ($_POST['card_sn'] != $_POST['old_card_sn']) {
                if (DB::table('goods_virtual_card')->where('goods_id', $_POST['goods_id'])->where('card_sn', $coded_card_sn)->count() > 0) {
                    $link[] = ['text' => lang('go_back'), 'href' => 'virtual_card.php?act=replenish&goods_id='.$_POST['goods_id']];

                    return $this->sys_msg(sprintf(lang('card_sn_exist'), $_POST['card_sn']), 1, $link);
                }
            }

            // 如果old_card_sn不存在则新加一条记录
            if (empty($_POST['old_card_sn'])) {
                // 插入一条新记录
                $end_date = strtotime($_POST['end_dateYear'].'-'.$_POST['end_dateMonth'].'-'.$_POST['end_dateDay']);
                $add_date = TimeHelper::gmtime();
                DB::table('goods_virtual_card')->insert([
                    'goods_id' => $_POST['goods_id'],
                    'card_sn' => $coded_card_sn,
                    'card_password' => $coded_card_password,
                    'end_date' => $end_date,
                    'add_date' => $add_date,
                    'crc32' => crc32(AUTH_KEY),
                ]);

                // 如果添加成功且原卡号为空时商品库存加1
                if (empty($_POST['old_card_sn'])) {
                    DB::table('goods')->where('goods_id', $_POST['goods_id'])->increment('goods_number');
                }

                $link[] = ['text' => lang('go_list'), 'href' => 'virtual_card.php?act=card&goods_id='.$_POST['goods_id']];
                $link[] = ['text' => lang('continue_add'), 'href' => 'virtual_card.php?act=replenish&goods_id='.$_POST['goods_id']];

                return $this->sys_msg(lang('action_success'), 0, $link);
            } else {
                // 更新数据
                $end_date = strtotime($_POST['end_dateYear'].'-'.$_POST['end_dateMonth'].'-'.$_POST['end_dateDay']);
                DB::table('goods_virtual_card')->where('card_id', $_POST['card_id'])->update([
                    'card_sn' => $coded_card_sn,
                    'card_password' => $coded_card_password,
                    'end_date' => $end_date,
                ]);

                $link[] = ['text' => lang('go_list'), 'href' => 'virtual_card.php?act=card&goods_id='.$_POST['goods_id']];
                $link[] = ['text' => lang('continue_add'), 'href' => 'virtual_card.php?act=replenish&goods_id='.$_POST['goods_id']];

                return $this->sys_msg(lang('action_success'), 0, $link);
            }
        }
        /**
         * 补货列表
         */
        if ($action === 'card') {
            $this->admin_priv('virualcard');

            // 验证goods_id是否合法
            if (empty($_REQUEST['goods_id'])) {
                $link[] = ['text' => lang('go_back'), 'href' => 'virtual_card.php?act=list'];

                return $this->sys_msg(lang('replenish_no_goods_id'), 1, $link);
            } else {
                $goods_name = DB::table('goods')
                    ->where('goods_id', $_REQUEST['goods_id'])
                    ->where('is_real', 0)
                    ->where('extension_code', 'virtual_card')
                    ->value('goods_name');
                if (empty($goods_name)) {
                    $link[] = ['text' => lang('go_back'), 'href' => 'virtual_card.php?act=list'];

                    return $this->sys_msg(lang('replenish_no_get_goods_name'), 1, $link);
                }
            }

            if (empty($_REQUEST['order_sn'])) {
                $_REQUEST['order_sn'] = '';
            }

            $this->assign('goods_id', $_REQUEST['goods_id']);
            $this->assign('full_page', 1);

            $this->assign('ur_here', $goods_name);
            $this->assign('action_link', [
                'text' => lang('replenish'),
                'href' => 'virtual_card.php?act=replenish&goods_id='.$_REQUEST['goods_id'],
            ]);
            $this->assign('goods_id', $_REQUEST['goods_id']);

            $list = $this->get_replenish_list();

            $this->assign('card_list', $list['item']);
            $this->assign('filter', $list['filter']);
            $this->assign('record_count', $list['record_count']);
            $this->assign('page_count', $list['page_count']);

            $sort_flag = MainHelper::sort_flag($list['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->display('replenish_list');
        }

        /**
         * 虚拟卡列表，用于排序、翻页
         */
        if ($action === 'query_card') {
            $list = $this->get_replenish_list();

            $this->assign('card_list', $list['item']);
            $this->assign('filter', $list['filter']);
            $this->assign('record_count', $list['record_count']);
            $this->assign('page_count', $list['page_count']);

            $sort_flag = MainHelper::sort_flag($list['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->make_json_result(
                $this->fetch('replenish_list'),
                '',
                ['filter' => $list['filter'], 'page_count' => $list['page_count']]
            );
        }

        // 批量删除card
        if ($action === 'batch_drop_card') {
            $this->admin_priv('virualcard');

            $num = count($_POST['checkboxes']);
            $deleted = DB::table('goods_virtual_card')->whereIn('card_id', $_POST['checkboxes'])->delete();
            if ($deleted) {
                // 商品数量减$num
                $this->update_goods_number(intval($_REQUEST['goods_id']));
                $link[] = ['text' => lang('go_list'), 'href' => 'virtual_card.php?act=card&goods_id='.$_REQUEST['goods_id']];

                return $this->sys_msg(lang('action_success'), 0, $link);
            }
        }

        // 批量上传页面
        if ($action === 'batch_card_add') {
            $this->admin_priv('virualcard');

            $this->assign('ur_here', lang('batch_card_add'));
            $this->assign('action_link', ['text' => lang('virtual_card_list'), 'href' => 'goods.php?act=list&extension_code=virtual_card']);
            $this->assign('goods_id', $_REQUEST['goods_id']);

            return $this->display('batch_card_info');
        }

        if ($action === 'batch_confirm') {
            // 检查上传是否成功
            if ($_FILES['uploadfile']['tmp_name'] === '' || $_FILES['uploadfile']['tmp_name'] === 'none') {
                return $this->sys_msg(lang('uploadfile_fail'), 1);
            }

            $data = file($_FILES['uploadfile']['tmp_name']);
            $rec = []; // 数据数组
            $i = 0;
            $separator = trim($_POST['separator']);
            foreach ($data as $line) {
                $row = explode($separator, $line);
                switch (count($row)) {
                    case '3':
                        $rec[$i]['end_date'] = $row[2];
                        // no break
                    case '2':
                        $rec[$i]['card_password'] = $row[1];
                        // no break
                    case '1':
                        $rec[$i]['card_sn'] = $row[0];
                        break;
                    default:
                        $rec[$i]['card_sn'] = $row[0];
                        $rec[$i]['card_password'] = $row[1];
                        $rec[$i]['end_date'] = $row[2];
                        break;
                }
                $i++;
            }

            $this->assign('ur_here', lang('batch_card_add'));
            $this->assign('action_link', ['text' => lang('batch_card_add'), 'href' => 'virtual_card.php?act=batch_card_add&goods_id='.$_REQUEST['goods_id']]);
            $this->assign('list', $rec);

            return $this->display('batch_card_confirm');
        }

        // 批量上传处理
        if ($action === 'batch_insert') {
            $this->admin_priv('virualcard');

            $add_time = TimeHelper::gmtime();
            $i = 0;
            foreach ($_POST['checked'] as $key) {
                $rec['card_sn'] = CodeHelper::encrypt($_POST['card_sn'][$key]);
                $rec['card_password'] = CodeHelper::encrypt($_POST['card_password'][$key]);
                $rec['crc32'] = crc32(AUTH_KEY);
                $rec['end_date'] = empty($_POST['end_date'][$key]) ? 0 : strtotime($_POST['end_date'][$key]);
                $rec['goods_id'] = $_POST['goods_id'];
                $rec['add_date'] = $add_time;
                DB::table('goods_virtual_card')->insert($rec);
                $i++;
            }

            // 更新商品库存
            $this->update_goods_number(intval($_REQUEST['goods_id']));
            $link[] = ['text' => lang('card'), 'href' => 'virtual_card.php?act=card&goods_id='.$_POST['goods_id']];

            return $this->sys_msg(sprintf(lang('batch_card_add_ok'), $i), 0, $link);
        }

        /**
         * 更改加密串
         */
        if ($action === 'change') {
            $this->admin_priv('virualcard');

            $this->assign('ur_here', lang('virtual_card_change'));

            return $this->display('virtual_card_change');
        }

        /**
         * 提交更改
         */
        if ($action === 'submit_change') {
            $this->admin_priv('virualcard');

            if (isset($_POST['old_string']) && isset($_POST['new_string'])) {
                // 检查原加密串是否正确
                if ($_POST['old_string'] != OLD_AUTH_KEY) {
                    return $this->sys_msg(lang('invalid_old_string'), 1);
                }

                // 检查新加密串是否正确
                if ($_POST['new_string'] != AUTH_KEY) {
                    return $this->sys_msg(lang('invalid_new_string'), 1);
                }

                // 检查原加密串和新加密串是否相同
                if ($_POST['old_string'] === $_POST['new_string'] || crc32($_POST['old_string']) === crc32($_POST['new_string'])) {
                    return $this->sys_msg(lang('same_string'), 1);
                }

                // 重新加密卡号和密码
                $old_crc32 = crc32($_POST['old_string']);
                $new_crc32 = crc32($_POST['new_string']);
                $res = DB::table('goods_virtual_card')->where('crc32', $old_crc32)->get();
                foreach ($res as $row) {
                    $row = (array) $row;
                    $row['card_sn'] = CodeHelper::encrypt(CodeHelper::decrypt($row['card_sn'], $_POST['old_string']), $_POST['new_string']);
                    $row['card_password'] = CodeHelper::encrypt(CodeHelper::decrypt($row['card_password'], $_POST['old_string']), $_POST['new_string']);
                    DB::table('goods_virtual_card')->where('card_id', $row['card_id'])->update([
                        'card_sn' => $row['card_sn'],
                        'card_password' => $row['card_password'],
                        'crc32' => $new_crc32,
                    ]);
                }

                // 记录日志
                // admin_log();

                // 返回
                return $this->sys_msg(lang('change_key_ok'), 0, [['href' => 'virtual_card.php?act=list', 'text' => lang('virtual_card_list')]]);
            }
        }

        /**
         * 切换是否已出售状态
         */
        if ($action === 'toggle_sold') {
            $this->check_authz_json('virualcard');

            $id = intval($_POST['id']);
            $val = intval($_POST['val']);

            if (DB::table('goods_virtual_card')->where('card_id', $id)->update(['is_saled' => $val]) !== false) {
                // 修改商品库存
                $goods_id = DB::table('goods_virtual_card')->where('card_id', $id)->value('goods_id');

                $this->update_goods_number($goods_id);

                return $this->make_json_result($val);
            } else {
                return $this->make_json_error(lang('action_fail'));
            }
        }

        /**
         * 删除卡片
         */
        if ($action === 'remove_card') {
            $this->check_authz_json('virualcard');

            $id = intval($_GET['id']);

            $row = (array) DB::table('goods_virtual_card')->where('card_id', $id)->select('card_sn', 'goods_id')->first();

            $deleted = DB::table('goods_virtual_card')->where('card_id', $id)->delete();
            if ($deleted) {
                // 修改商品数量
                $this->update_goods_number($row['goods_id']);

                $url = 'virtual_card.php?act=query_card&'.str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

                return response()->redirectTo($url);
            } else {
                return $this->make_json_error(lang('delete_failed'));
            }
        }

        /**
         * 开始更改加密串：先检查原串和新串
         */
        if ($action === 'start_change') {
            $old_key = BaseHelper::json_str_iconv(trim($_GET['old_key']));
            $new_key = BaseHelper::json_str_iconv(trim($_GET['new_key']));
            // 检查原加密串和新加密串是否相同
            if ($old_key === $new_key || crc32($old_key) === crc32($new_key)) {
                return $this->make_json_error(lang('same_string'));
            }
            if ($old_key != AUTH_KEY) {
                return $this->make_json_error(lang('invalid_old_string'));
            } else {
                $f = ROOT_PATH.'data/config.php';
                file_put_contents($f, str_replace("'AUTH_KEY', '".AUTH_KEY."'", "'AUTH_KEY', '".$new_key."'", file_get_contents($f)));
                file_put_contents($f, str_replace("'OLD_AUTH_KEY', '".OLD_AUTH_KEY."'", "'OLD_AUTH_KEY', '".$old_key."'", file_get_contents($f)));
                @fclose($fp);
            }

            // 查询统计信息：总记录，使用原串的记录，使用新串的记录，使用未知串的记录
            $stat = ['all' => 0, 'new' => 0, 'old' => 0, 'unknown' => 0];
            $res = DB::table('goods_virtual_card')->select('crc32', DB::raw('count(*) AS cnt'))->groupBy('crc32')->get();
            foreach ($res as $row) {
                $row = (array) $row;
                $stat['all'] += $row['cnt'];
                if (crc32($new_key) === $row['crc32']) {
                    $stat['new'] += $row['cnt'];
                } elseif (crc32($old_key) === $row['crc32']) {
                    $stat['old'] += $row['cnt'];
                } else {
                    $stat['unknown'] += $row['cnt'];
                }
            }

            return $this->make_json_result(sprintf(lang('old_stat'), $stat['all'], $stat['new'], $stat['old'], $stat['unknown']));
        }

        /**
         * 更新加密串
         */
        if ($action === 'on_change') {
            // 重新加密卡号和密码
            $each_num = 1;
            $old_crc32 = crc32(OLD_AUTH_KEY);
            $new_crc32 = crc32(AUTH_KEY);
            $updated = intval($_GET['updated']);

            $res = DB::table('goods_virtual_card')
                ->where('crc32', $old_crc32)
                ->select('card_id', 'card_sn', 'card_password')
                ->limit($each_num)
                ->get();

            foreach ($res as $row) {
                $row = (array) $row;
                $row['card_sn'] = CodeHelper::encrypt(CodeHelper::decrypt($row['card_sn'], OLD_AUTH_KEY));
                $row['card_password'] = CodeHelper::encrypt(CodeHelper::decrypt($row['card_password'], OLD_AUTH_KEY));

                DB::table('goods_virtual_card')->where('card_id', $row['card_id'])->update([
                    'card_sn' => $row['card_sn'],
                    'card_password' => $row['card_password'],
                    'crc32' => $new_crc32,
                ]);

                $updated++;
            }

            // 查询是否还有未更新的
            $left_num = DB::table('goods_virtual_card')->where('crc32', $old_crc32)->count();

            if ($left_num > 0) {
                return $this->make_json_result($updated);
            } else {
                // 查询统计信息
                $stat = ['new' => 0, 'unknown' => 0];
                $res = DB::table('goods_virtual_card')->select('crc32', DB::raw('count(*) AS cnt'))->groupBy('crc32')->get();
                foreach ($res as $row) {
                    $row = (array) $row;
                    if ($new_crc32 === $row['crc32']) {
                        $stat['new'] += $row['cnt'];
                    } else {
                        $stat['unknown'] += $row['cnt'];
                    }
                }

                return $this->make_json_result($updated, sprintf(lang('new_stat'), $stat['new'], $stat['unknown']));
            }
        }
    }

    /**
     * 返回补货列表
     *
     * @return array
     */
    private function get_replenish_list()
    {
        // 查询条件
        $filter['goods_id'] = empty($_REQUEST['goods_id']) ? 0 : intval($_REQUEST['goods_id']);
        $filter['search_type'] = empty($_REQUEST['search_type']) ? 0 : trim($_REQUEST['search_type']);
        $filter['order_sn'] = empty($_REQUEST['order_sn']) ? 0 : trim($_REQUEST['order_sn']);
        $filter['keyword'] = empty($_REQUEST['keyword']) ? 0 : trim($_REQUEST['keyword']);
        if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] === 1) {
            $filter['keyword'] = BaseHelper::json_str_iconv($filter['keyword']);
        }
        $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'card_id' : trim($_REQUEST['sort_by']);
        $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

        $where = (! empty($filter['goods_id'])) ? " AND goods_id = '".$filter['goods_id']."' " : '';
        $where .= (! empty($filter['order_sn'])) ? " AND order_sn LIKE '%".BaseHelper::mysql_like_quote($filter['order_sn'])."%' " : '';

        if (! empty($filter['keyword'])) {
            if ($filter['search_type'] === 'card_sn') {
                $where .= " AND card_sn = '".CodeHelper::encrypt($filter['keyword'])."'";
            } else {
                $where .= " AND order_sn LIKE '%".BaseHelper::mysql_like_quote($filter['keyword'])."%' ";
            }
        }

        $filter['record_count'] = DB::table('goods_virtual_card')->whereRaw('1 '.$where)->count();

        // 分页大小
        $filter = MainHelper::page_and_size($filter);
        $start = ($filter['page'] - 1) * $filter['page_size'];

        // 查询
        $all = DB::table('goods_virtual_card')
            ->select('card_id', 'goods_id', 'card_sn', 'card_password', 'end_date', 'is_saled', 'order_sn', 'crc32')
            ->orderBy($filter['sort_by'], $filter['sort_order'])
            ->offset($start)
            ->limit($filter['page_size'])
            ->get()
            ->map(fn ($r) => (array) $r)
            ->toArray();

        $arr = [];
        foreach ($all as $key => $row) {
            if ($row['crc32'] === 0 || $row['crc32'] === crc32(AUTH_KEY)) {
                $row['card_sn'] = CodeHelper::decrypt($row['card_sn']);
                $row['card_password'] = CodeHelper::decrypt($row['card_password']);
            } elseif ($row['crc32'] === crc32(OLD_AUTH_KEY)) {
                $row['card_sn'] = CodeHelper::decrypt($row['card_sn'], OLD_AUTH_KEY);
                $row['card_password'] = CodeHelper::decrypt($row['card_password'], OLD_AUTH_KEY);
            } else {
                $row['card_sn'] = '***';
                $row['card_password'] = '***';
            }

            $row['end_date'] = $row['end_date'] === 0 ? '' : date(cfg('date_format'), $row['end_date']);

            $arr[] = $row;
        }

        return ['item' => $arr, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];
    }

    /**
     * 更新虚拟商品的商品数量
     *
     * @param  int  $goods_id
     * @return bool
     */
    private function update_goods_number($goods_id)
    {
        $goods_number = DB::table('goods_virtual_card')
            ->where('goods_id', $goods_id)
            ->where('is_saled', 0)
            ->count();

        return (bool) DB::table('goods')->where('goods_id', $goods_id)->where('extension_code', 'virtual_card')->update(['goods_number' => $goods_number]);
    }
}
