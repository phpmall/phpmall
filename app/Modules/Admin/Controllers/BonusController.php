<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\CommonHelper;
use App\Helpers\OrderHelper;
use App\Helpers\TimeHelper;
use App\Modules\Admin\Helpers\MainHelper;
use App\Modules\Admin\Libraries\Exchange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BonusController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        // 初始化$exc对象
        $exc = new Exchange(ecs()->table('activity_bonus'), db(), 'type_id', 'type_name');

        /**
         * 红包类型列表页面
         */
        if ($action === 'list') {
            $this->assign('ur_here', lang('04_bonustype_list'));
            $this->assign('action_link', ['text' => lang('bonustype_add'), 'href' => 'bonus.php?act=add']);
            $this->assign('full_page', 1);

            $list = $this->get_type_list();

            $this->assign('type_list', $list['item']);
            $this->assign('filter', $list['filter']);
            $this->assign('record_count', $list['record_count']);
            $this->assign('page_count', $list['page_count']);

            $sort_flag = MainHelper::sort_flag($list['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->display('bonus_type');
        }

        /**
         * 翻页、排序
         */
        if ($action === 'query') {
            $list = $this->get_type_list();

            $this->assign('type_list', $list['item']);
            $this->assign('filter', $list['filter']);
            $this->assign('record_count', $list['record_count']);
            $this->assign('page_count', $list['page_count']);

            $sort_flag = MainHelper::sort_flag($list['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->make_json_result(
                $this->fetch('bonus_type'),
                '',
                ['filter' => $list['filter'], 'page_count' => $list['page_count']]
            );
        }

        /**
         * 编辑红包类型名称
         */
        if ($action === 'edit_type_name') {
            $this->check_authz_json('bonus_manage');

            $id = intval($_POST['id']);
            $val = BaseHelper::json_str_iconv(trim($_POST['val']));

            // 检查红包类型名称是否重复
            if (DB::table('activity_bonus')->where('type_name', $val)->where('type_id', '!=', $id)->exists()) {
                return $this->make_json_error(lang('type_name_exist'));
            }

            DB::table('activity_bonus')->where('type_id', $id)->update(['type_name' => $val]);

            return $this->make_json_result(stripslashes($val));
        }

        /**
         * 编辑红包金额
         */
        if ($action === 'edit_type_money') {
            $this->check_authz_json('bonus_manage');

            $id = intval($_POST['id']);
            $val = floatval($_POST['val']);

            if ($val <= 0) {
                return $this->make_json_error(lang('type_money_error'));
            }

            DB::table('activity_bonus')->where('type_id', $id)->update(['type_money' => $val]);

            return $this->make_json_result(number_format($val, 2));
        }

        /**
         * 编辑订单下限
         */
        if ($action === 'edit_min_amount') {
            $this->check_authz_json('bonus_manage');

            $id = intval($_POST['id']);
            $val = floatval($_POST['val']);

            if ($val < 0) {
                return $this->make_json_error(lang('min_amount_empty'));
            }

            DB::table('activity_bonus')->where('type_id', $id)->update(['min_amount' => $val]);

            return $this->make_json_result(number_format($val, 2));
        }

        /**
         * 删除红包类型
         */
        if ($action === 'remove') {
            $this->check_authz_json('bonus_manage');

            $id = intval($_GET['id']);

            DB::table('activity_bonus')->where('type_id', $id)->delete();

            // 更新商品信息
            DB::table('goods')->where('bonus_type_id', $id)->update(['bonus_type_id' => 0]);

            // 删除用户的红包
            DB::table('user_bonus')->where('bonus_type_id', $id)->delete();

            $url = 'bonus.php?act=query&'.str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

            return response()->redirectTo($url);
        }

        /**
         * 红包类型添加页面
         */
        if ($action === 'add') {
            $this->admin_priv('bonus_manage');

            $this->assign('ur_here', lang('bonustype_add'));
            $this->assign('action_link', ['href' => 'bonus.php?act=list', 'text' => lang('04_bonustype_list')]);
            $this->assign('action', 'add');

            $this->assign('form_act', 'insert');
            $this->assign('cfg_lang', cfg('lang'));

            $next_month = TimeHelper::local_strtotime('+1 months');
            $bonus_arr['send_start_date'] = TimeHelper::local_date('Y-m-d');
            $bonus_arr['use_start_date'] = TimeHelper::local_date('Y-m-d');
            $bonus_arr['send_end_date'] = TimeHelper::local_date('Y-m-d', $next_month);
            $bonus_arr['use_end_date'] = TimeHelper::local_date('Y-m-d', $next_month);

            $this->assign('bonus_arr', $bonus_arr);

            return $this->display('bonus_type_info');
        }

        /**
         * 红包类型添加的处理
         */
        if ($action === 'insert') {
            // 去掉红包类型名称前后的空格
            $type_name = ! empty($_POST['type_name']) ? trim($_POST['type_name']) : '';

            // 初始化变量
            $type_id = ! empty($_POST['type_id']) ? intval($_POST['type_id']) : 0;
            $min_amount = ! empty($_POST['min_amount']) ? intval($_POST['min_amount']) : 0;

            // 检查类型是否有重复
            if (DB::table('activity_bonus')->where('type_name', $type_name)->exists()) {
                $link[] = ['text' => lang('go_back'), 'href' => 'javascript:history.back(-1)'];

                return $this->sys_msg(lang('type_name_exist'), 0, $link);
            }

            // 获得日期信息
            $send_startdate = TimeHelper::local_strtotime($_POST['send_start_date']);
            $send_enddate = TimeHelper::local_strtotime($_POST['send_end_date']);
            $use_startdate = TimeHelper::local_strtotime($_POST['use_start_date']);
            $use_enddate = TimeHelper::local_strtotime($_POST['use_end_date']);

            // 插入数据库
            DB::table('activity_bonus')->insert([
                'type_name' => $type_name,
                'type_money' => $_POST['type_money'],
                'send_start_date' => $send_startdate,
                'send_end_date' => $send_enddate,
                'use_start_date' => $use_startdate,
                'use_end_date' => $use_enddate,
                'send_type' => $_POST['send_type'],
                'min_amount' => $min_amount,
                'min_goods_amount' => floatval($_POST['min_goods_amount']),
            ]);
            // 记录管理员操作
            $this->admin_log($_POST['type_name'], 'add', 'bonustype');

            // 清除缓存
            $this->clear_cache_files();

            // 提示信息
            $link[0]['text'] = lang('continus_add');
            $link[0]['href'] = 'bonus.php?act=add';

            $link[1]['text'] = lang('back_list');
            $link[1]['href'] = 'bonus.php?act=list';

            return $this->sys_msg(lang('add').'&nbsp;'.$_POST['type_name'].'&nbsp;'.lang('attradd_succed'), 0, $link);
        }

        /**
         * 红包类型编辑页面
         */
        if ($action === 'edit') {
            $this->admin_priv('bonus_manage');

            // 获取红包类型数据
            $type_id = ! empty($_GET['type_id']) ? intval($_GET['type_id']) : 0;
            $bonus_arr = DB::table('activity_bonus')->where('type_id', $type_id)->first();
            $bonus_arr = $bonus_arr ? (array) $bonus_arr : [];

            $bonus_arr['send_start_date'] = TimeHelper::local_date('Y-m-d', $bonus_arr['send_start_date']);
            $bonus_arr['send_end_date'] = TimeHelper::local_date('Y-m-d', $bonus_arr['send_end_date']);
            $bonus_arr['use_start_date'] = TimeHelper::local_date('Y-m-d', $bonus_arr['use_start_date']);
            $bonus_arr['use_end_date'] = TimeHelper::local_date('Y-m-d', $bonus_arr['use_end_date']);

            $this->assign('ur_here', lang('bonustype_edit'));
            $this->assign('action_link', ['href' => 'bonus.php?act=list&'.MainHelper::list_link_postfix(), 'text' => lang('04_bonustype_list')]);
            $this->assign('form_act', 'update');
            $this->assign('bonus_arr', $bonus_arr);

            return $this->display('bonus_type_info');
        }

        /**
         * 红包类型编辑的处理
         */
        if ($action === 'update') {
            // 获得日期信息
            $send_startdate = TimeHelper::local_strtotime($_POST['send_start_date']);
            $send_enddate = TimeHelper::local_strtotime($_POST['send_end_date']);
            $use_startdate = TimeHelper::local_strtotime($_POST['use_start_date']);
            $use_enddate = TimeHelper::local_strtotime($_POST['use_end_date']);

            // 对数据的处理
            $type_name = ! empty($_POST['type_name']) ? trim($_POST['type_name']) : '';
            $type_id = ! empty($_POST['type_id']) ? intval($_POST['type_id']) : 0;
            $min_amount = ! empty($_POST['min_amount']) ? intval($_POST['min_amount']) : 0;

            DB::table('activity_bonus')->where('type_id', $type_id)->update([
                'type_name' => $type_name,
                'type_money' => $_POST['type_money'],
                'send_start_date' => $send_startdate,
                'send_end_date' => $send_enddate,
                'use_start_date' => $use_startdate,
                'use_end_date' => $use_enddate,
                'send_type' => $_POST['send_type'],
                'min_amount' => $min_amount,
                'min_goods_amount' => floatval($_POST['min_goods_amount']),
            ]);
            // 记录管理员操作
            $this->admin_log($_POST['type_name'], 'edit', 'bonustype');

            // 清除缓存
            $this->clear_cache_files();

            // 提示信息
            $link[] = ['text' => lang('back_list'), 'href' => 'bonus.php?act=list&'.MainHelper::list_link_postfix()];

            return $this->sys_msg(lang('edit').' '.$_POST['type_name'].' '.lang('attradd_succed'), 0, $link);
        }

        /**
         * 红包发送页面
         */
        if ($action === 'send') {
            $this->admin_priv('bonus_manage');

            // 取得参数
            $id = ! empty($_REQUEST['id']) ? intval($_REQUEST['id']) : '';

            $this->assign('ur_here', lang('send_bonus'));
            $this->assign('action_link', ['href' => 'bonus.php?act=list', 'text' => lang('04_bonustype_list')]);

            if ($_REQUEST['send_by'] === SEND_BY_USER) {
                $this->assign('id', $id);
                $this->assign('ranklist', MainHelper::get_rank_list());

                return $this->display('bonus_by_user');
            } elseif ($_REQUEST['send_by'] === SEND_BY_GOODS) {
                // 查询此红包类型信息
                $bonus_type = DB::table('activity_bonus')
                    ->select('type_id', 'type_name')
                    ->where('type_id', $_REQUEST['id'])
                    ->first();
                $bonus_type = $bonus_type ? (array) $bonus_type : [];

                // 查询红包类型的商品列表
                $goods_list = $this->get_bonus_goods($_REQUEST['id']);

                // 查询其他红包类型的商品
                $other_goods_list = DB::table('goods')
                    ->where('bonus_type_id', '>', 0)
                    ->where('bonus_type_id', '<>', $_REQUEST['id'])
                    ->pluck('goods_id')
                    ->toArray();
                $this->assign('other_goods', implode(',', $other_goods_list));

                $this->assign('cat_list', CommonHelper::cat_list());
                $this->assign('brand_list', CommonHelper::get_brand_list());

                $this->assign('bonus_type', $bonus_type);
                $this->assign('goods_list', $goods_list);

                return $this->display('bonus_by_goods');
            } elseif ($_REQUEST['send_by'] === SEND_BY_PRINT) {
                $this->assign('type_list', MainHelper::get_bonus_type());

                return $this->display('bonus_by_print');
            }
        }

        /**
         * 处理红包的发送页面
         */
        if ($action === 'send_by_user') {
            $user_list = [];
            $start = empty($_REQUEST['start']) ? 0 : intval($_REQUEST['start']);
            $limit = empty($_REQUEST['limit']) ? 10 : intval($_REQUEST['limit']);
            $validated_email = empty($_REQUEST['validated_email']) ? 0 : intval($_REQUEST['validated_email']);
            $send_count = 0;

            if (isset($_REQUEST['send_rank'])) {
                // 按会员等级来发放红包
                $rank_id = intval($_REQUEST['rank_id']);

                if ($rank_id > 0) {
                    $row = DB::table('user_rank')
                        ->select('min_points', 'max_points', 'special_rank')
                        ->where('rank_id', $rank_id)
                        ->first();
                    $row = $row ? (array) $row : [];
                    if ($row['special_rank']) {
                        // 特殊会员组处理
                        $send_count = DB::table('user')->where('user_rank', $rank_id)->count();
                        if ($validated_email) {
                            $sql_query = DB::table('user')
                                ->select('user_id', 'email', 'user_name')
                                ->where('user_rank', $rank_id)
                                ->where('is_validated', 1)
                                ->offset($start)
                                ->limit($limit);
                        } else {
                            $sql_query = DB::table('user')
                                ->select('user_id', 'email', 'user_name')
                                ->where('user_rank', $rank_id)
                                ->offset($start)
                                ->limit($limit);
                        }
                    } else {
                        $send_count = DB::table('user')
                            ->where('rank_points', '>=', intval($row['min_points']))
                            ->where('rank_points', '<', intval($row['max_points']))
                            ->count();

                        if ($validated_email) {
                            $sql_query = DB::table('user')
                                ->select('user_id', 'email', 'user_name')
                                ->where('rank_points', '>=', intval($row['min_points']))
                                ->where('rank_points', '<', intval($row['max_points']))
                                ->where('is_validated', 1)
                                ->offset($start)
                                ->limit($limit);
                        } else {
                            $sql_query = DB::table('user')
                                ->select('user_id', 'email', 'user_name')
                                ->where('rank_points', '>=', intval($row['min_points']))
                                ->where('rank_points', '<', intval($row['max_points']))
                                ->offset($start)
                                ->limit($limit);
                        }
                    }

                    $user_list = $sql_query->get()->map(function ($item) {
                        return (array) $item;
                    })->toArray();
                    $count = count($user_list);
                }
            } elseif (isset($_REQUEST['send_user'])) {
                // 按会员列表发放红包
                // 如果是空数组，直接返回
                if (empty($_REQUEST['user'])) {
                    return $this->sys_msg(lang('send_user_empty'), 1);
                }

                $user_array = (is_array($_REQUEST['user'])) ? $_REQUEST['user'] : explode(',', $_REQUEST['user']);
                $send_count = count($user_array);

                $id_array = array_slice($user_array, $start, $limit);

                // 根据会员ID取得用户名和邮件地址
                $user_list = DB::table('user')
                    ->select('user_id', 'email', 'user_name')
                    ->whereIn('user_id', $id_array)
                    ->get()
                    ->map(function ($item) {
                        return (array) $item;
                    })->toArray();
                $count = count($user_list);
            }

            // 发送红包
            $loop = 0;
            $bonus_type = $this->bonus_type_info($_REQUEST['id']);

            $tpl = CommonHelper::get_mail_template('send_bonus');
            $today = TimeHelper::local_date(cfg('date_format'));

            foreach ($user_list as $key => $val) {
                // 发送邮件通知
                $this->assign('user_name', $val['user_name']);
                $this->assign('shop_name', cfg('shop_name'));
                $this->assign('send_date', $today);
                $this->assign('sent_date', $today);
                $this->assign('count', 1);
                $this->assign('money', CommonHelper::price_format($bonus_type['type_money']));

                $content = $this->fetch('str:'.$tpl['template_content']);

                if (add_to_maillist($val['user_name'], $val['email'], $tpl['template_subject'], $content, $tpl['is_html'])) {
                    // 向会员红包表录入数据
                    DB::table('user_bonus')->insert([
                        'bonus_type_id' => $_REQUEST['id'],
                        'bonus_sn' => 0,
                        'user_id' => $val['user_id'],
                        'used_time' => 0,
                        'order_id' => 0,
                        'emailed' => BONUS_MAIL_SUCCEED,
                    ]);
                } else {
                    // 邮件发送失败，更新数据库
                    DB::table('user_bonus')->insert([
                        'bonus_type_id' => $_REQUEST['id'],
                        'bonus_sn' => 0,
                        'user_id' => $val['user_id'],
                        'used_time' => 0,
                        'order_id' => 0,
                        'emailed' => BONUS_MAIL_FAIL,
                    ]);
                }

                if ($loop >= $limit) {
                    break;
                } else {
                    $loop++;
                }
            }
            if ($send_count > ($start + $limit)) {
                $href = 'bonus.php?act=send_by_user&start='.($start + $limit)."&limit=$limit&id=$_REQUEST[id]&";

                if (isset($_REQUEST['send_rank'])) {
                    $href .= "send_rank=1&rank_id=$rank_id";
                }

                if (isset($_REQUEST['send_user'])) {
                    $href .= 'send_user=1&user='.implode(',', $user_array);
                }

                $link[] = ['text' => lang('send_continue'), 'href' => $href];
            }

            $link[] = ['text' => lang('back_list'), 'href' => 'bonus.php?act=list'];

            return $this->sys_msg(sprintf(lang('sendbonus_count'), $count), 0, $link);
        }

        /**
         * 发送邮件
         */
        if ($action === 'send_mail') {
            // 取得参数：红包id
            $bonus_id = intval($_REQUEST['bonus_id']);
            if ($bonus_id <= 0) {
                exit('invalid params');
            }

            // 取得红包信息
            $bonus = OrderHelper::bonus_info($bonus_id);
            if (empty($bonus)) {
                return $this->sys_msg(lang('bonus_not_exist'));
            }

            // 发邮件
            $count = $this->send_bonus_mail($bonus['bonus_type_id'], [$bonus_id]);

            $link[0]['text'] = lang('back_bonus_list');
            $link[0]['href'] = 'bonus.php?act=bonus_list&bonus_type='.$bonus['bonus_type_id'];

            return $this->sys_msg(sprintf(lang('success_send_mail'), $count), 0, $link);
        }

        /**
         * 按印刷品发放红包
         */
        if ($action === 'send_by_print') {
            @set_time_limit(0);

            // 红下红包的类型ID和生成的数量的处理
            $bonus_typeid = ! empty($_POST['bonus_type_id']) ? $_POST['bonus_type_id'] : 0;
            $bonus_sum = ! empty($_POST['bonus_sum']) ? $_POST['bonus_sum'] : 1;

            // 生成红包序列号
            $num = DB::table('user_bonus')->max('bonus_sn');
            $num = $num ? floor($num / 10000) : 100000;

            for ($i = 0, $j = 0; $i < $bonus_sum; $i++) {
                $bonus_sn = ($num + $i).str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
                DB::table('user_bonus')->insert([
                    'bonus_type_id' => $bonus_typeid,
                    'bonus_sn' => $bonus_sn,
                ]);

                $j++;
            }

            // 记录管理员操作
            $this->admin_log($bonus_sn, 'add', 'userbonus');

            // 清除缓存
            $this->clear_cache_files();

            // 提示信息
            $link[0]['text'] = lang('back_bonus_list');
            $link[0]['href'] = 'bonus.php?act=bonus_list&bonus_type='.$bonus_typeid;

            return $this->sys_msg(lang('creat_bonus').$j.lang('creat_bonus_num'), 0, $link);
        }

        /**
         * 导出线下发放的信息
         */
        if ($action === 'gen_excel') {
            @set_time_limit(0);

            // 获得此线下红包类型的ID
            $tid = ! empty($_GET['tid']) ? intval($_GET['tid']) : 0;
            $type_name = DB::table('activity_bonus')->where('type_id', $tid)->value('type_name');

            // 文件名称
            $bonus_filename = $type_name.'_bonus_list';
            if (EC_CHARSET != 'gbk') {
                $bonus_filename = BaseHelper::ecs_iconv('UTF8', 'GB2312', $bonus_filename);
            }

            header('Content-type: application/vnd.ms-excel; charset=utf-8');
            header("Content-Disposition: attachment; filename=$bonus_filename.xls");

            // 文件标题
            if (EC_CHARSET != 'gbk') {
                echo BaseHelper::ecs_iconv('UTF8', 'GB2312', lang('bonus_excel_file'))."\t\n";
                // 红包序列号, 红包金额, 类型名称(红包名称), 使用结束日期
                echo BaseHelper::ecs_iconv('UTF8', 'GB2312', lang('bonus_sn'))."\t";
                echo BaseHelper::ecs_iconv('UTF8', 'GB2312', lang('type_money'))."\t";
                echo BaseHelper::ecs_iconv('UTF8', 'GB2312', lang('type_name'))."\t";
                echo BaseHelper::ecs_iconv('UTF8', 'GB2312', lang('use_enddate'))."\t\n";
            } else {
                echo lang('bonus_excel_file')."\t\n";
                // 红包序列号, 红包金额, 类型名称(红包名称), 使用结束日期
                echo lang('bonus_sn')."\t";
                echo lang('type_money')."\t";
                echo lang('type_name')."\t";
                echo lang('use_enddate')."\t\n";
            }

            $val = [];
            $res = DB::table('user_bonus as ub')
                ->join('activity_bonus as bt', 'bt.type_id', '=', 'ub.bonus_type_id')
                ->select('ub.bonus_id', 'ub.bonus_type_id', 'ub.bonus_sn', 'bt.type_name', 'bt.type_money', 'bt.use_end_date')
                ->where('ub.bonus_type_id', $tid)
                ->orderByDesc('ub.bonus_id')
                ->get();

            $code_table = [];
            foreach ($res as $val) {
                echo $val['bonus_sn']."\t";
                echo $val['type_money']."\t";
                if (! isset($code_table[$val['type_name']])) {
                    if (EC_CHARSET != 'gbk') {
                        $code_table[$val['type_name']] = BaseHelper::ecs_iconv('UTF8', 'GB2312', $val['type_name']);
                    } else {
                        $code_table[$val['type_name']] = $val['type_name'];
                    }
                }
                echo $code_table[$val['type_name']]."\t";
                echo TimeHelper::local_date('Y-m-d', $val['use_end_date']);
                echo "\t\n";
            }
        }

        /**
         * 搜索商品
         */
        if ($action === 'get_goods_list') {
            $filters = json_decode($_GET['JSON']);

            $arr = MainHelper::get_goods_list($filters);
            $opt = [];

            foreach ($arr as $key => $val) {
                $opt[] = [
                    'value' => $val['goods_id'],
                    'text' => $val['goods_name'],
                    'data' => $val['shop_price'],
                ];
            }

            return $this->make_json_result($opt);
        }

        /**
         * 添加发放红包的商品
         */
        if ($action === 'add_bonus_goods') {
            $this->check_authz_json('bonus_manage');

            $add_ids = json_decode($_GET['add_ids']);
            $args = json_decode($_GET['JSON']);
            $type_id = $args[0];

            foreach ($add_ids as $key => $val) {
                DB::table('goods')
                    ->where('goods_id', $val)
                    ->update(['bonus_type_id' => $type_id]);
            }

            // 重新载入
            $arr = $this->get_bonus_goods($type_id);
            $opt = [];

            foreach ($arr as $key => $val) {
                $opt[] = [
                    'value' => $val['goods_id'],
                    'text' => $val['goods_name'],
                    'data' => '',
                ];
            }

            return $this->make_json_result($opt);
        }

        /**
         * 删除发放红包的商品
         */
        if ($action === 'drop_bonus_goods') {
            $this->check_authz_json('bonus_manage');

            $drop_goods = json_decode($_GET['drop_ids']);
            $drop_goods_ids = db_create_in($drop_goods);
            $arguments = json_decode($_GET['JSON']);
            $type_id = $arguments[0];

            DB::table('goods')
                ->where('bonus_type_id', $type_id)
                ->whereRaw('goods_id '.$drop_goods_ids)
                ->update(['bonus_type_id' => 0]);

            // 重新载入
            $arr = $this->get_bonus_goods($type_id);
            $opt = [];

            foreach ($arr as $key => $val) {
                $opt[] = [
                    'value' => $val['goods_id'],
                    'text' => $val['goods_name'],
                    'data' => '',
                ];
            }

            return $this->make_json_result($opt);
        }

        /**
         * 搜索用户
         */
        if ($action === 'search_users') {
            $keywords = BaseHelper::json_str_iconv(trim($_GET['keywords']));

            $row = DB::table('user')
                ->select('user_id', 'user_name')
                ->where(function ($query) use ($keywords) {
                    $query->where('user_name', 'like', '%'.$keywords.'%')
                        ->orWhere('user_id', 'like', '%'.$keywords.'%');
                })
                ->get()
                ->map(function ($item) {
                    return (array) $item;
                })
                ->toArray();

            return $this->make_json_result($row);
        }

        /**
         * 红包列表
         */
        if ($action === 'bonus_list') {
            $this->assign('full_page', 1);
            $this->assign('ur_here', lang('bonus_list'));
            $this->assign('action_link', ['href' => 'bonus.php?act=list', 'text' => lang('04_bonustype_list')]);

            $list = $this->get_bonus_list();

            // 赋值是否显示红包序列号
            $bonus_type = $this->bonus_type_info(intval($_REQUEST['bonus_type']));
            if ($bonus_type['send_type'] === SEND_BY_PRINT) {
                $this->assign('show_bonus_sn', 1);
            } // 赋值是否显示发邮件操作和是否发过邮件
            elseif ($bonus_type['send_type'] === SEND_BY_USER) {
                $this->assign('show_mail', 1);
            }

            $this->assign('bonus_list', $list['item']);
            $this->assign('filter', $list['filter']);
            $this->assign('record_count', $list['record_count']);
            $this->assign('page_count', $list['page_count']);

            $sort_flag = MainHelper::sort_flag($list['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->display('bonus_list');
        }

        /**
         * 红包列表翻页、排序
         */
        if ($action === 'query_bonus') {
            $list = $this->get_bonus_list();

            // 赋值是否显示红包序列号
            $bonus_type = $this->bonus_type_info(intval($_REQUEST['bonus_type']));
            if ($bonus_type['send_type'] === SEND_BY_PRINT) {
                $this->assign('show_bonus_sn', 1);
            } // 赋值是否显示发邮件操作和是否发过邮件
            elseif ($bonus_type['send_type'] === SEND_BY_USER) {
                $this->assign('show_mail', 1);
            }

            $this->assign('bonus_list', $list['item']);
            $this->assign('filter', $list['filter']);
            $this->assign('record_count', $list['record_count']);
            $this->assign('page_count', $list['page_count']);

            $sort_flag = MainHelper::sort_flag($list['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->make_json_result(
                $this->fetch('bonus_list'),
                '',
                ['filter' => $list['filter'], 'page_count' => $list['page_count']]
            );
        }

        /**
         * 删除红包
         */
        if ($action === 'remove_bonus') {
            $this->check_authz_json('bonus_manage');

            $id = intval($_GET['id']);

            DB::table('user_bonus')->where('bonus_id', $id)->delete();

            $url = 'bonus.php?act=query_bonus&'.str_replace('act=remove_bonus', '', $_SERVER['QUERY_STRING']);

            return response()->redirectTo($url);
        }

        /**
         * 批量操作
         */
        if ($action === 'batch') {
            $this->admin_priv('bonus_manage');

            // 去掉参数：红包类型
            $bonus_type_id = intval($_REQUEST['bonus_type']);

            // 取得选中的红包id
            if (isset($_POST['checkboxes'])) {
                $bonus_id_list = $_POST['checkboxes'];

                // 删除红包
                if (isset($_POST['drop'])) {
                    DB::table('user_bonus')->whereIn('bonus_id', $bonus_id_list)->delete();

                    $this->admin_log(count($bonus_id_list), 'remove', 'userbonus');

                    $this->clear_cache_files();

                    $link[] = [
                        'text' => lang('back_bonus_list'),
                        'href' => 'bonus.php?act=bonus_list&bonus_type='.$bonus_type_id,
                    ];

                    return $this->sys_msg(sprintf(lang('batch_drop_success'), count($bonus_id_list)), 0, $link);
                } // 发邮件
                elseif (isset($_POST['mail'])) {
                    $count = $this->send_bonus_mail($bonus_type_id, $bonus_id_list);
                    $link[] = [
                        'text' => lang('back_bonus_list'),
                        'href' => 'bonus.php?act=bonus_list&bonus_type='.$bonus_type_id,
                    ];

                    return $this->sys_msg(sprintf(lang('success_send_mail'), $count), 0, $link);
                }
            } else {
                return $this->sys_msg(lang('no_select_bonus'), 1);
            }
        }
    }

    /**
     * 获取红包类型列表
     *
     * @return void
     */
    private function get_type_list()
    {
        // 获得所有红包类型的发放数量
        // 获得所有红包类型的发放数量
        $res = DB::table('user_bonus')
            ->selectRaw('bonus_type_id, COUNT(*) AS sent_count')
            ->groupBy('bonus_type_id')
            ->get();

        $sent_arr = [];
        foreach ($res as $row) {
            $sent_arr[$row['bonus_type_id']] = $row['sent_count'];
        }

        // 获得所有红包类型的发放数量
        // 获得所有红包类型的发放数量
        $res = DB::table('user_bonus')
            ->selectRaw('bonus_type_id, COUNT(*) AS used_count')
            ->where('used_time', '>', 0)
            ->groupBy('bonus_type_id')
            ->get();

        $used_arr = [];
        foreach ($res as $row) {
            $used_arr[$row['bonus_type_id']] = $row['used_count'];
        }

        $result = MainHelper::get_filter();
        if ($result === false) {
            // 查询条件
            $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'type_id' : trim($_REQUEST['sort_by']);
            $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

            $filter['record_count'] = DB::table('activity_bonus')->count();

            // 分页大小
            $filter = MainHelper::page_and_size($filter);

            MainHelper::set_filter($filter, '');
        } else {
            $sql = $result['sql'];
            $filter = $result['filter'];
        }
        $arr = [];
        $res = DB::table('activity_bonus')
            ->orderBy($filter['sort_by'], $filter['sort_order'])
            ->offset($filter['start'])
            ->limit($filter['page_size'])
            ->get();

        foreach ($res as $row) {
            $row['send_by'] = lang('send_by')[$row['send_type']];
            $row['send_count'] = isset($sent_arr[$row['type_id']]) ? $sent_arr[$row['type_id']] : 0;
            $row['use_count'] = isset($used_arr[$row['type_id']]) ? $used_arr[$row['type_id']] : 0;

            $arr[] = $row;
        }

        $arr = ['item' => $arr, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];

        return $arr;
    }

    /**
     * 查询红包类型的商品列表
     *
     * @param  int  $type_id
     * @return array
     */
    private function get_bonus_goods($type_id)
    {
        return DB::table('goods')
            ->select('goods_id', 'goods_name')
            ->where('bonus_type_id', $type_id)
            ->get()
            ->map(function ($item) {
                return (array) $item;
            })
            ->toArray();
    }

    /**
     * 获取用户红包列表
     *
     * @param  $page_param
     * @return void
     */
    private function get_bonus_list()
    {
        // 查询条件
        $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'bonus_type_id' : trim($_REQUEST['sort_by']);
        $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);
        $filter['bonus_type'] = empty($_REQUEST['bonus_type']) ? 0 : intval($_REQUEST['bonus_type']);

        $where = empty($filter['bonus_type']) ? '' : " WHERE bonus_type_id='$filter[bonus_type]'";

        $filter['record_count'] = DB::table('user_bonus')
            ->when(! empty($filter['bonus_type']), function ($query) use ($filter) {
                return $query->where('bonus_type_id', $filter['bonus_type']);
            })->count();

        // 分页大小
        $filter = MainHelper::page_and_size($filter);

        $row = DB::table('user_bonus as ub')
            ->leftJoin('activity_bonus as bt', 'bt.type_id', '=', 'ub.bonus_type_id')
            ->leftJoin('user as u', 'u.user_id', '=', 'ub.user_id')
            ->leftJoin('order_info as o', 'o.order_id', '=', 'ub.order_id')
            ->select('ub.*', 'u.user_name', 'u.email', 'o.order_sn', 'bt.type_name')
            ->when(! empty($filter['bonus_type']), function ($query) use ($filter) {
                return $query->where('ub.bonus_type_id', $filter['bonus_type']);
            })
            ->orderBy($filter['sort_by'], $filter['sort_order'])
            ->offset($filter['start'])
            ->limit($filter['page_size'])
            ->get()
            ->map(function ($item) {
                return (array) $item;
            })
            ->toArray();

        foreach ($row as $key => $val) {
            $row[$key]['used_time'] = $val['used_time'] === 0 ?
                lang('no_use') : TimeHelper::local_date(cfg('date_format'), $val['used_time']);
            $row[$key]['emailed'] = lang('mail_status')[$row[$key]['emailed']];
        }

        $arr = ['item' => $row, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];

        return $arr;
    }

    /**
     * 取得红包类型信息
     *
     * @param  int  $bonus_type_id  红包类型id
     * @return array
     */
    private function bonus_type_info($bonus_type_id)
    {
        $bonus_type = DB::table('activity_bonus')
            ->where('type_id', $bonus_type_id)
            ->first();

        return $bonus_type ? (array) $bonus_type : [];
    }

    /**
     * 发送红包邮件
     *
     * @param  int  $bonus_type_id  红包类型id
     * @param  array  $bonus_id_list  红包id数组
     * @return int 成功发送数量
     */
    private function send_bonus_mail($bonus_type_id, $bonus_id_list)
    {
        // 取得红包类型信息
        $bonus_type = $this->bonus_type_info($bonus_type_id);
        if ($bonus_type['send_type'] != SEND_BY_USER) {
            return 0;
        }

        // 取得属于该类型的红包信息
        // 取得属于该类型的红包信息
        $bonus_list = DB::table('user_bonus as b')
            ->join('user as u', 'u.user_id', '=', 'b.user_id')
            ->select('b.bonus_id', 'u.user_name', 'u.email')
            ->whereIn('b.bonus_id', $bonus_id_list)
            ->where('b.order_id', 0)
            ->where('u.email', '<>', '')
            ->get()
            ->map(function ($item) {
                return (array) $item;
            })
            ->toArray();
        if (empty($bonus_list)) {
            return 0;
        }

        // 初始化成功发送数量
        $send_count = 0;

        // 发送邮件
        $tpl = CommonHelper::get_mail_template('send_bonus');
        $today = TimeHelper::local_date(cfg('date_format'));
        foreach ($bonus_list as $bonus) {
            $this->assign('user_name', $bonus['user_name']);
            $this->assign('shop_name', cfg('shop_name'));
            $this->assign('send_date', $today);
            $this->assign('sent_date', $today);
            $this->assign('count', 1);
            $this->assign('money', CommonHelper::price_format($bonus_type['type_money']));

            $content = $this->fetch('str:'.$tpl['template_content']);
            if (add_to_maillist($bonus['user_name'], $bonus['email'], $tpl['template_subject'], $content, $tpl['is_html'], false)) {
                DB::table('user_bonus')
                    ->where('bonus_id', $bonus['bonus_id'])
                    ->update(['emailed' => BONUS_MAIL_SUCCEED]);
                $send_count++;
            } else {
                DB::table('user_bonus')
                    ->where('bonus_id', $bonus['bonus_id'])
                    ->update(['emailed' => BONUS_MAIL_FAIL]);
            }
        }

        return $send_count;
    }

    private function add_to_maillist($username, $email, $subject, $content, $is_html)
    {
        $time = time();
        $content = addslashes($content);
        $template_id = DB::table('email_template')
            ->where('template_code', 'send_bonus')
            ->value('template_id');

        DB::table('email_send')->insert([
            'email' => $email,
            'template_id' => $template_id,
            'email_content' => $content,
            'pri' => 1,
            'last_send' => $time,
        ]);

        return true;
    }
}
