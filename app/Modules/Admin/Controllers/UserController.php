<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\CommonHelper;
use App\Helpers\TimeHelper;
use App\Modules\Admin\Helpers\MainHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class UserController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        /**
         * 用户帐号列表
         */
        if ($action === 'list') {
            $this->admin_priv('users_manage');
            $rs = DB::table('user_rank')->orderBy('min_points')->select('rank_id', 'rank_name', 'min_points')->get();

            $ranks = [];
            foreach ($rs as $row) {
                $ranks[$row->rank_id] = $row->rank_name;
            }

            $this->assign('user_ranks', $ranks);
            $this->assign('ur_here', lang('03_users_list'));
            $this->assign('action_link', ['text' => lang('04_users_add'), 'href' => 'users.php?act=add']);

            $user_list = $this->user_list();

            $this->assign('user_list', $user_list['user_list']);
            $this->assign('filter', $user_list['filter']);
            $this->assign('record_count', $user_list['record_count']);
            $this->assign('page_count', $user_list['page_count']);
            $this->assign('full_page', 1);

            return $this->display('users_list');
        }

        /**
         * ajax返回用户列表
         */
        if ($action === 'query') {
            $user_list = $this->user_list();

            $this->assign('user_list', $user_list['user_list']);
            $this->assign('filter', $user_list['filter']);
            $this->assign('record_count', $user_list['record_count']);
            $this->assign('page_count', $user_list['page_count']);

            $sort_flag = MainHelper::sort_flag($user_list['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->make_json_result($this->fetch('users_list'), '', ['filter' => $user_list['filter'], 'page_count' => $user_list['page_count']]);
        }

        /**
         * 添加会员帐号
         */
        if ($action === 'add') {
            $this->admin_priv('users_manage');

            $user = [
                'rank_points' => cfg('register_points'),
                'pay_points' => cfg('register_points'),
                'sex' => 0,
                'credit_line' => 0,
            ];
            // 取出注册扩展字段
            $extend_info_list = DB::table('user_extend_fields')
                ->where('type', '<', 2)
                ->where('display', 1)
                ->where('id', '!=', 6)
                ->orderBy('dis_order')
                ->orderBy('id')
                ->get()
                ->map(fn ($r) => (array) $r)
                ->all();
            $this->assign('extend_info_list', $extend_info_list);

            $this->assign('ur_here', lang('04_users_add'));
            $this->assign('action_link', ['text' => lang('03_users_list'), 'href' => 'users.php?act=list']);
            $this->assign('form_action', 'insert');
            $this->assign('user', $user);
            $this->assign('special_ranks', MainHelper::get_rank_list(true));

            return $this->display('user_info');
        }

        /**
         * 添加会员帐号
         */
        if ($action === 'insert') {
            $this->admin_priv('users_manage');
            $username = empty($_POST['username']) ? '' : trim($_POST['username']);
            $password = empty($_POST['password']) ? '' : trim($_POST['password']);
            $email = empty($_POST['email']) ? '' : trim($_POST['email']);
            $sex = empty($_POST['sex']) ? 0 : intval($_POST['sex']);
            $sex = in_array($sex, [0, 1, 2]) ? $sex : 0;
            $birthday = $_POST['birthdayYear'].'-'.$_POST['birthdayMonth'].'-'.$_POST['birthdayDay'];
            $rank = empty($_POST['user_rank']) ? 0 : intval($_POST['user_rank']);
            $credit_line = empty($_POST['credit_line']) ? 0 : floatval($_POST['credit_line']);

            $users = CommonHelper::init_users();

            if (! $users->add_user($username, $password, $email)) {
                // 插入会员数据失败
                if ($users->error === ERR_INVALID_USERNAME) {
                    $msg = lang('username_invalid');
                } elseif ($users->error === ERR_USERNAME_NOT_ALLOW) {
                    $msg = lang('username_not_allow');
                } elseif ($users->error === ERR_USERNAME_EXISTS) {
                    $msg = lang('username_exists');
                } elseif ($users->error === ERR_INVALID_EMAIL) {
                    $msg = lang('email_invalid');
                } elseif ($users->error === ERR_EMAIL_NOT_ALLOW) {
                    $msg = lang('email_not_allow');
                } elseif ($users->error === ERR_EMAIL_EXISTS) {
                    $msg = lang('email_exists');
                } else {
                    // die('Error:'.$users->error_msg());
                }

                return $this->sys_msg($msg, 1);
            }

            // 注册送积分
            if (! empty(cfg('register_points'))) {
                CommonHelper::log_account_change(Session::get('user_id'), 0, 0, cfg('register_points'), cfg('register_points'), lang('register_points'));
            }

            // 把新注册用户的扩展信息插入数据库
            $fields_arr = DB::table('user_extend_fields')
                ->where('type', 0)
                ->where('display', 1)
                ->orderBy('dis_order')
                ->orderBy('id')
                ->select('id')
                ->get()
                ->map(fn ($r) => (array) $r)
                ->all();

            $extendFields = [];    // 生成扩展字段的内容数组
            $user_id_arr = $users->get_profile_by_name($username);
            foreach ($fields_arr as $val) {
                $extend_field_index = 'extend_field'.$val['id'];
                if (! empty($_POST[$extend_field_index])) {
                    $temp_field_content = strlen($_POST[$extend_field_index]) > 100 ? mb_substr($_POST[$extend_field_index], 0, 99) : $_POST[$extend_field_index];
                    $extendFields[] = [
                        'user_id' => $user_id_arr['user_id'],
                        'reg_field_id' => $val['id'],
                        'content' => $temp_field_content,
                    ];
                }
            }

            if (! empty($extendFields)) {      // 插入注册扩展数据
                DB::table('user_extend_info')->insert($extendFields);
            }

            // 更新会员的其它信息
            $other = [];
            $other['credit_line'] = $credit_line;
            $other['user_rank'] = $rank;
            $other['sex'] = $sex;
            $other['birthday'] = $birthday;
            $other['reg_time'] = TimeHelper::local_strtotime(TimeHelper::local_date('Y-m-d H:i:s'));

            $other['msn'] = isset($_POST['extend_field1']) ? htmlspecialchars(trim($_POST['extend_field1'])) : '';
            $other['qq'] = isset($_POST['extend_field2']) ? htmlspecialchars(trim($_POST['extend_field2'])) : '';
            $other['office_phone'] = isset($_POST['extend_field3']) ? htmlspecialchars(trim($_POST['extend_field3'])) : '';
            $other['home_phone'] = isset($_POST['extend_field4']) ? htmlspecialchars(trim($_POST['extend_field4'])) : '';
            $other['mobile_phone'] = isset($_POST['extend_field5']) ? htmlspecialchars(trim($_POST['extend_field5'])) : '';

            DB::table('user')->where('user_name', $username)->update($other);

            // 记录管理员操作
            $this->admin_log($_POST['username'], 'add', 'users');

            // 提示信息
            $link[] = ['text' => lang('go_back'), 'href' => 'users.php?act=list'];

            return $this->sys_msg(sprintf(lang('add_success'), htmlspecialchars(stripslashes($_POST['username']))), 0, $link);
        }

        /**
         * 编辑用户帐号
         */
        if ($action === 'edit') {
            $this->admin_priv('users_manage');

            $row = (array) DB::table('user AS u')
                ->leftJoin(ecs()->table('user').' AS u2', 'u.parent_id', '=', 'u2.user_id')
                ->where('u.user_id', $_GET['id'])
                ->select('u.user_name', 'u.sex', 'u.birthday', 'u.pay_points', 'u.rank_points', 'u.user_rank', 'u.user_money', 'u.frozen_money', 'u.credit_line', 'u.parent_id', 'u2.user_name as parent_username', 'u.qq', 'u.msn', 'u.office_phone', 'u.home_phone', 'u.mobile_phone')
                ->first();
            $row['user_name'] = addslashes($row['user_name']);
            $users = CommonHelper::init_users();
            $user = $users->get_user_info($row['user_name']);

            $row = (array) DB::table('user AS u')
                ->leftJoin(ecs()->table('user').' AS u2', 'u.parent_id', '=', 'u2.user_id')
                ->where('u.user_id', $_GET['id'])
                ->select('u.user_id', 'u.sex', 'u.birthday', 'u.pay_points', 'u.rank_points', 'u.user_rank', 'u.user_money', 'u.frozen_money', 'u.credit_line', 'u.parent_id', 'u2.user_name as parent_username', 'u.qq', 'u.msn', 'u.office_phone', 'u.home_phone', 'u.mobile_phone')
                ->first();

            if ($row) {
                $user['user_id'] = $row['user_id'];
                $user['sex'] = $row['sex'];
                $user['birthday'] = date($row['birthday']);
                $user['pay_points'] = $row['pay_points'];
                $user['rank_points'] = $row['rank_points'];
                $user['user_rank'] = $row['user_rank'];
                $user['user_money'] = $row['user_money'];
                $user['frozen_money'] = $row['frozen_money'];
                $user['credit_line'] = $row['credit_line'];
                $user['formated_user_money'] = CommonHelper::price_format($row['user_money']);
                $user['formated_frozen_money'] = CommonHelper::price_format($row['frozen_money']);
                $user['parent_id'] = $row['parent_id'];
                $user['parent_username'] = $row['parent_username'];
                $user['qq'] = $row['qq'];
                $user['msn'] = $row['msn'];
                $user['office_phone'] = $row['office_phone'];
                $user['home_phone'] = $row['home_phone'];
                $user['mobile_phone'] = $row['mobile_phone'];
            } else {
                $link[] = ['text' => lang('go_back'), 'href' => 'users.php?act=list'];

                return $this->sys_msg(lang('username_invalid'), 0, $link);
            }

            // 取出注册扩展字段
            $extend_info_list = DB::table('user_extend_fields')
                ->where('type', '<', 2)
                ->where('display', 1)
                ->where('id', '!=', 6)
                ->orderBy('dis_order')
                ->orderBy('id')
                ->get()
                ->map(fn ($r) => (array) $r)
                ->all();

            $extend_info_arr = DB::table('user_extend_info')
                ->where('user_id', $user['user_id'])
                ->select('reg_field_id', 'content')
                ->get()
                ->map(fn ($r) => (array) $r)
                ->all();

            $temp_arr = [];
            foreach ($extend_info_arr as $val) {
                $temp_arr[$val['reg_field_id']] = $val['content'];
            }

            foreach ($extend_info_list as $key => $val) {
                switch ($val['id']) {
                    case 1:
                        $extend_info_list[$key]['content'] = $user['msn'];
                        break;
                    case 2:
                        $extend_info_list[$key]['content'] = $user['qq'];
                        break;
                    case 3:
                        $extend_info_list[$key]['content'] = $user['office_phone'];
                        break;
                    case 4:
                        $extend_info_list[$key]['content'] = $user['home_phone'];
                        break;
                    case 5:
                        $extend_info_list[$key]['content'] = $user['mobile_phone'];
                        break;
                    default:
                        $extend_info_list[$key]['content'] = empty($temp_arr[$val['id']]) ? '' : $temp_arr[$val['id']];
                }
            }

            $this->assign('extend_info_list', $extend_info_list);

            // 当前会员推荐信息
            $affiliate = unserialize(cfg('affiliate'));
            $this->assign('affiliate', $affiliate);

            empty($affiliate) && $affiliate = [];

            if (empty($affiliate['config']['separate_by'])) {
                // 推荐注册分成
                $affdb = [];
                $num = count($affiliate['item']);
                $up_uid = "'$_GET[id]'";
                for ($i = 1; $i <= $num; $i++) {
                    $count = 0;
                    if ($up_uid) {
                        $query = DB::table('user')->whereIn('parent_id', explode(',', str_replace("'", '', $up_uid)))->select('user_id')->get();
                        $up_uid = '';
                        foreach ($query as $rt) {
                            $rt = (array) $rt;
                            $up_uid .= $up_uid ? ",'$rt[user_id]'" : "'$rt[user_id]'";
                            $count++;
                        }
                    }
                    $affdb[$i]['num'] = $count;
                }
                if ($affdb[1]['num'] > 0) {
                    $this->assign('affdb', $affdb);
                }
            }

            $this->assign('ur_here', lang('users_edit'));
            $this->assign('action_link', ['text' => lang('03_users_list'), 'href' => 'users.php?act=list&'.MainHelper::list_link_postfix()]);
            $this->assign('user', $user);
            $this->assign('form_action', 'update');
            $this->assign('special_ranks', MainHelper::get_rank_list(true));

            return $this->display('user_info');
        }

        /**
         * 更新用户帐号
         */
        if ($action === 'update') {
            $this->admin_priv('users_manage');
            $username = empty($_POST['username']) ? '' : trim($_POST['username']);
            $password = empty($_POST['password']) ? '' : trim($_POST['password']);
            $email = empty($_POST['email']) ? '' : trim($_POST['email']);
            $sex = empty($_POST['sex']) ? 0 : intval($_POST['sex']);
            $sex = in_array($sex, [0, 1, 2]) ? $sex : 0;
            $birthday = $_POST['birthdayYear'].'-'.$_POST['birthdayMonth'].'-'.$_POST['birthdayDay'];
            $rank = empty($_POST['user_rank']) ? 0 : intval($_POST['user_rank']);
            $credit_line = empty($_POST['credit_line']) ? 0 : floatval($_POST['credit_line']);

            $users = CommonHelper::init_users();

            if (! $users->edit_user(['username' => $username, 'password' => $password, 'email' => $email, 'gender' => $sex, 'bday' => $birthday], 1)) {
                if ($users->error === ERR_EMAIL_EXISTS) {
                    $msg = lang('email_exists');
                } else {
                    $msg = lang('edit_user_failed');
                }

                return $this->sys_msg($msg, 1);
            }
            if (! empty($password)) {
                DB::table('user')->where('user_name', $username)->update(['ec_salt' => '0']);
            }
            // 更新用户扩展字段的数据
            $fields_arr = DB::table('user_extend_fields')
                ->where('type', 0)
                ->where('display', 1)
                ->orderBy('dis_order')
                ->orderBy('id')
                ->select('id')
                ->get()
                ->map(fn ($r) => (array) $r)
                ->all();
            $user_id_arr = $users->get_profile_by_name($username);
            $user_id = $user_id_arr['user_id'];

            foreach ($fields_arr as $val) {       // 循环更新扩展用户信息
                $extend_field_index = 'extend_field'.$val['id'];
                if (isset($_POST[$extend_field_index])) {
                    $temp_field_content = strlen($_POST[$extend_field_index]) > 100 ? mb_substr($_POST[$extend_field_index], 0, 99) : $_POST[$extend_field_index];

                    $exists = DB::table('user_extend_info')
                        ->where('reg_field_id', $val['id'])
                        ->where('user_id', $user_id)
                        ->exists();
                    if ($exists) {
                        DB::table('user_extend_info')
                            ->where('reg_field_id', $val['id'])
                            ->where('user_id', $user_id)
                            ->update(['content' => $temp_field_content]);
                    } else {
                        DB::table('user_extend_info')->insert([
                            'user_id' => $user_id,
                            'reg_field_id' => $val['id'],
                            'content' => $temp_field_content,
                        ]);
                    }
                }
            }

            // 更新会员的其它信息
            $other = [];
            $other['credit_line'] = $credit_line;
            $other['user_rank'] = $rank;

            $other['msn'] = isset($_POST['extend_field1']) ? htmlspecialchars(trim($_POST['extend_field1'])) : '';
            $other['qq'] = isset($_POST['extend_field2']) ? htmlspecialchars(trim($_POST['extend_field2'])) : '';
            $other['office_phone'] = isset($_POST['extend_field3']) ? htmlspecialchars(trim($_POST['extend_field3'])) : '';
            $other['home_phone'] = isset($_POST['extend_field4']) ? htmlspecialchars(trim($_POST['extend_field4'])) : '';
            $other['mobile_phone'] = isset($_POST['extend_field5']) ? htmlspecialchars(trim($_POST['extend_field5'])) : '';

            DB::table('user')->where('user_name', $username)->update($other);

            // 记录管理员操作
            $this->admin_log($username, 'edit', 'users');

            // 提示信息
            $links[0]['text'] = lang('goto_list');
            $links[0]['href'] = 'users.php?act=list&'.MainHelper::list_link_postfix();
            $links[1]['text'] = lang('go_back');
            $links[1]['href'] = 'javascript:history.back()';

            return $this->sys_msg(lang('update_success'), 0, $links);
        }

        /**
         * 批量删除会员帐号
         */
        if ($action === 'batch_remove') {
            $this->admin_priv('users_drop');

            if (isset($_POST['checkboxes'])) {
                $col = DB::table('user')->whereIn('user_id', $_POST['checkboxes'])->pluck('user_name')->all();
                $usernames = implode(',', BaseHelper::addslashes_deep($col));
                $count = count($col);
                // 通过插件来删除用户
                $users = CommonHelper::init_users();
                $users->remove_user($col);

                $this->admin_log($usernames, 'batch_remove', 'users');

                $lnk[] = ['text' => lang('go_back'), 'href' => 'users.php?act=list'];

                return $this->sys_msg(sprintf(lang('batch_remove_success'), $count), 0, $lnk);
            } else {
                $lnk[] = ['text' => lang('go_back'), 'href' => 'users.php?act=list'];

                return $this->sys_msg(lang('no_select_user'), 0, $lnk);
            }
        }

        // 编辑用户名
        if ($action === 'edit_username') {
            $this->check_authz_json('users_manage');

            $username = empty($_REQUEST['val']) ? '' : BaseHelper::json_str_iconv(trim($_REQUEST['val']));
            $id = empty($_REQUEST['id']) ? 0 : intval($_REQUEST['id']);

            if ($id === 0) {
                return $this->make_json_error('NO USER ID');

                return;
            }

            if ($username === '') {
                return $this->make_json_error(lang('username_empty'));

                return;
            }

            $users = CommonHelper::init_users();

            if ($users->edit_user($id, $username)) {
                if (cfg('integrate_code') != 'phpmall') {
                    // 更新商城会员表
                    DB::table('user')->where('user_id', $id)->update(['user_name' => $username]);
                }

                $this->admin_log(addslashes($username), 'edit', 'users');

                return $this->make_json_result(stripcslashes($username));
            } else {
                $msg = ($users->error === ERR_USERNAME_EXISTS) ? lang('username_exists') : lang('edit_user_failed');

                return $this->make_json_error($msg);
            }
        }

        /**
         * 编辑email
         */
        if ($action === 'edit_email') {
            $this->check_authz_json('users_manage');

            $id = empty($_REQUEST['id']) ? 0 : intval($_REQUEST['id']);
            $email = empty($_REQUEST['val']) ? '' : BaseHelper::json_str_iconv(trim($_REQUEST['val']));

            $users = CommonHelper::init_users();

            $username = DB::table('user')->where('user_id', $id)->value('user_name');

            if (CommonHelper::is_email($email)) {
                if ($users->edit_user(['username' => $username, 'email' => $email])) {
                    $this->admin_log(addslashes($username), 'edit', 'users');

                    return $this->make_json_result(stripcslashes($email));
                } else {
                    $msg = ($users->error === ERR_EMAIL_EXISTS) ? lang('email_exists') : lang('edit_user_failed');

                    return $this->make_json_error($msg);
                }
            } else {
                return $this->make_json_error(lang('invalid_email'));
            }
        }

        /**
         * 删除会员帐号
         */
        if ($action === 'remove') {
            $this->admin_priv('users_drop');

            $username = DB::table('user')->where('user_id', $_GET['id'])->value('user_name');
            // 通过插件来删除用户
            $users = CommonHelper::init_users();
            $users->remove_user($username); // 已经删除用户所有数据

            // 记录管理员操作
            $this->admin_log(addslashes($username), 'remove', 'users');

            // 提示信息
            $link[] = ['text' => lang('go_back'), 'href' => 'users.php?act=list'];

            return $this->sys_msg(sprintf(lang('remove_success'), $username), 0, $link);
        }

        /**
         *  收货地址查看
         */
        if ($action === 'address_list') {
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            $address = DB::table('user_address as a')
                ->select('a.*', 'c.region_name as country_name', 'p.region_name as province', 'ct.region_name as city_name', 'd.region_name as district_name')
                ->leftJoin('shop_region as c', 'c.region_id', '=', 'a.country')
                ->leftJoin('shop_region as p', 'p.region_id', '=', 'a.province')
                ->leftJoin('shop_region as ct', 'ct.region_id', '=', 'a.city')
                ->leftJoin('shop_region as d', 'd.region_id', '=', 'a.district')
                ->where('a.user_id', $id)
                ->get()
                ->map(fn ($r) => (array) $r)
                ->toArray();
            $this->assign('address', $address);

            $this->assign('ur_here', lang('address_list'));
            $this->assign('action_link', ['text' => lang('03_users_list'), 'href' => 'users.php?act=list&'.MainHelper::list_link_postfix()]);

            return $this->display('user_address_list');
        }

        /**
         * 脱离推荐关系
         */
        if ($action === 'remove_parent') {
            $this->admin_priv('users_manage');

            DB::table('user')->where('user_id', $_GET['id'])->update(['parent_id' => 0]);

            // 记录管理员操作
            $username = DB::table('user')->where('user_id', $_GET['id'])->value('user_name');
            $this->admin_log(addslashes($username), 'edit', 'users');

            // 提示信息
            $link[] = ['text' => lang('go_back'), 'href' => 'users.php?act=list'];

            return $this->sys_msg(sprintf(lang('update_success'), $username), 0, $link);
        }

        /**
         * 查看用户推荐会员列表
         */
        if ($action === 'aff_list') {
            $this->admin_priv('users_manage');
            $this->assign('ur_here', lang('03_users_list'));

            $auid = $_GET['auid'];
            $user_list['user_list'] = [];

            $affiliate = unserialize(cfg('affiliate'));
            $this->assign('affiliate', $affiliate);

            empty($affiliate) && $affiliate = [];

            $num = count($affiliate['item']);
            $up_uid = "'$auid'";
            $all_count = 0;
            for ($i = 1; $i <= $num; $i++) {
                $count = 0;
                if ($up_uid) {
                    $query = DB::table('user')->whereIn('parent_id', explode(',', str_replace("'", '', $up_uid)))->select('user_id')->get();
                    $up_uid = '';
                    foreach ($query as $rt) {
                        $rt = (array) $rt;
                        $up_uid .= $up_uid ? ",'$rt[user_id]'" : "'$rt[user_id]'";
                        $count++;
                    }
                }
                $all_count += $count;

                if ($count) {
                    $sql = "SELECT user_id, user_name, '$i' AS level, email, is_validated, user_money, frozen_money, rank_points, pay_points, reg_time ".
                        ' FROM '.ecs()->table('user')." WHERE user_id IN($up_uid)".
                        ' ORDER by level, user_id';
                    $user_list['user_list'] = array_merge($user_list['user_list'], DB::select($sql));
                }
            }

            $temp_count = count($user_list['user_list']);
            for ($i = 0; $i < $temp_count; $i++) {
                $user_list['user_list'][$i] = (array) $user_list['user_list'][$i];
                $user_list['user_list'][$i]['reg_time'] = TimeHelper::local_date(cfg('date_format'), $user_list['user_list'][$i]['reg_time']);
            }

            $user_list['record_count'] = $all_count;

            $this->assign('user_list', $user_list['user_list']);
            $this->assign('record_count', $user_list['record_count']);
            $this->assign('full_page', 1);
            $this->assign('action_link', ['text' => lang('back_note'), 'href' => "users.php?act=edit&id=$auid"]);

            return $this->display('affiliate_list');
        }
    }

    /**
     *  返回用户列表数据
     */
    private function user_list(): array
    {
        $result = MainHelper::get_filter();
        if ($result === false) {
            // 过滤条件
            $filter['keywords'] = empty($_REQUEST['keywords']) ? '' : trim($_REQUEST['keywords']);
            if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] === 1) {
                $filter['keywords'] = BaseHelper::json_str_iconv($filter['keywords']);
            }
            $filter['rank'] = empty($_REQUEST['rank']) ? 0 : intval($_REQUEST['rank']);
            $filter['pay_points_gt'] = empty($_REQUEST['pay_points_gt']) ? 0 : intval($_REQUEST['pay_points_gt']);
            $filter['pay_points_lt'] = empty($_REQUEST['pay_points_lt']) ? 0 : intval($_REQUEST['pay_points_lt']);

            $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'user_id' : trim($_REQUEST['sort_by']);
            $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

            $ex_where = ' WHERE 1 ';
            if ($filter['keywords']) {
                $ex_where .= " AND user_name LIKE '%".BaseHelper::mysql_like_quote($filter['keywords'])."%'";
            }
            if ($filter['rank']) {
                $row = (array) DB::table('user_rank')->where('rank_id', $filter['rank'])->select('min_points', 'max_points', 'special_rank')->first();
                if ($row['special_rank'] > 0) {
                    // 特殊等级
                    $ex_where .= " AND user_rank = '$filter[rank]' ";
                } else {
                    $ex_where .= ' AND rank_points >= '.intval($row['min_points']).' AND rank_points < '.intval($row['max_points']);
                }
            }
            if ($filter['pay_points_gt']) {
                $ex_where .= " AND pay_points >= '$filter[pay_points_gt]' ";
            }
            if ($filter['pay_points_lt']) {
                $ex_where .= " AND pay_points < '$filter[pay_points_lt]' ";
            }

            // 构建查询
            $query = DB::table('user');

            if ($filter['keywords']) {
                $query->where('user_name', 'like', '%'.BaseHelper::mysql_like_quote($filter['keywords']).'%');
            }
            if ($filter['rank']) {
                $row = (array) DB::table('user_rank')->where('rank_id', $filter['rank'])->select('min_points', 'max_points', 'special_rank')->first();
                if ($row && $row['special_rank'] > 0) {
                    $query->where('user_rank', $filter['rank']);
                } else if ($row) {
                    $query->whereBetween('rank_points', [intval($row['min_points']), intval($row['max_points'])]);
                }
            }
            if ($filter['pay_points_gt']) {
                $query->where('pay_points', '>=', $filter['pay_points_gt']);
            }
            if ($filter['pay_points_lt']) {
                $query->where('pay_points', '<', $filter['pay_points_lt']);
            }

            $filter['record_count'] = $query->count();

            // 分页大小
            $filter = MainHelper::page_and_size($filter);

            $user_list = $query
                ->select('user_id', 'user_name', 'email', 'is_validated', 'user_money', 'frozen_money', 'rank_points', 'pay_points', 'reg_time')
                ->orderBy($filter['sort_by'], $filter['sort_order'])
                ->offset($filter['start'])
                ->limit($filter['page_size'])
                ->get()
                ->map(fn ($r) => (array) $r)
                ->toArray();

            $filter['keywords'] = stripslashes($filter['keywords']);
            MainHelper::set_filter($filter, '');
        } else {
            $sql = $result['sql'];
            $filter = $result['filter'];
            $user_list = DB::select($sql);
            $user_list = array_map(fn ($r) => (array) $r, $user_list);
        }

        $count = count($user_list);
        for ($i = 0; $i < $count; $i++) {
            $user_list[$i]['user_name'] = urldecode($user_list[$i]['user_name']);
            $user_list[$i]['reg_time'] = TimeHelper::local_date(cfg('date_format'), $user_list[$i]['reg_time']);
        }

        $arr = [
            'user_list' => $user_list,
            'filter' => $filter,
            'page_count' => $filter['page_count'],
            'record_count' => $filter['record_count'],
        ];

        return $arr;
    }
}
