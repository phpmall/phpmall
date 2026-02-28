<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Bundles\Admin\Entities\AdminUserEntity;
use App\Helpers\BaseHelper;
use App\Helpers\CommonHelper;
use App\Helpers\TimeHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class IndexController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        if ($action === 'list') {
            $this->assign('shop_url', urlencode(ecs()->url()));

            return $this->display('index');
        }

        // 顶部框架内容
        if ($action === 'top') {
            // 获得管理员设置的菜单
            $nav = DB::table('admin_user')->where([
                AdminUserEntity::getUserId => $this->getAdminId(),
            ])->value('nav_list');

            $nav_list = [];
            if (! empty($nav)) {
                $arr = explode(',', $nav);
                foreach ($arr as $val) {
                    $tmp = explode('|', $val);
                    $nav_list[$tmp[1]] = $tmp[0];
                }
            }

            // 获得管理员ID
            $this->assign('send_mail_on', cfg('send_mail_on'));
            $this->assign('nav_list', $nav_list);
            $this->assign('admin_id', $this->getAdminId());
            $this->assign('certi', cfg('certi')); // TODO deprecated

            return $this->display('top');
        }

        // 计算器
        if ($action === 'calculator') {
            return $this->display('calculator');
        }

        // 菜单框架
        if ($action === 'menu') {
            $modules = require dirname(__DIR__).'/Config/menu.php';
            $purview = require dirname(__DIR__).'/Config/priv.php';

            foreach ($modules as $key => $value) {
                ksort($modules[$key]);
            }
            ksort($modules);

            foreach ($modules as $key => $val) {
                $menus[$key]['label'] = lang($key);
                if (is_array($val)) {
                    foreach ($val as $k => $v) {
                        if (isset($purview[$k])) {
                            if (is_array($purview[$k])) {
                                $boole = false;
                                foreach ($purview[$k] as $action) {
                                    $boole = $boole || $this->admin_priv($action, '', false);
                                }
                                if (! $boole) {
                                    continue;
                                }
                            } else {
                                if (! $this->admin_priv($purview[$k], '', false)) {
                                    continue;
                                }
                            }
                        }
                        if ($k === 'ucenter_setup' && cfg('integrate_code') != 'ucenter') {
                            continue;
                        }
                        $menus[$key]['children'][$k]['label'] = lang($k);
                        $menus[$key]['children'][$k]['action'] = $v;
                    }
                } else {
                    $menus[$key]['action'] = $val;
                }

                // 如果children的子元素长度为0则删除该组
                if (empty($menus[$key]['children'])) {
                    unset($menus[$key]);
                }
            }

            $this->assign('menus', $menus);
            $this->assign('no_help', lang('no_help'));
            $this->assign('help_lang', cfg('lang'));
            $this->assign('admin_id', Session::get('admin_id'));

            return $this->display('menu');
        }

        // 清除缓存
        if ($action === 'clear_cache') {
            CommonHelper::clear_all_files();

            return $this->sys_msg(lang('caches_cleared'));
        }

        // 主窗口，起始页
        if ($action === 'main') {
            // 检查文件目录属性
            $warning = [];

            if (cfg('shop_closed')) {
                $warning[] = lang('shop_closed_tips');
            }

            // if (file_exists('../install')) {
            //     $warning[] = lang('remove_install');
            // }

            // if (file_exists('../upgrade')) {
            //     $warning[] = lang('remove_upgrade');
            // }

            // if (file_exists('../demo')) {
            //     $warning[] = lang('remove_demo');
            // }

            // $open_basedir = ini_get('open_basedir');
            // if (! empty($open_basedir)) {
            //     // 如果 open_basedir 不为空，则检查是否包含了 upload_tmp_dir
            //     $open_basedir = str_replace(['\\', '\\\\'], ['/', '/'], $open_basedir);
            //     $upload_tmp_dir = ini_get('upload_tmp_dir');

            //     if (empty($upload_tmp_dir)) {
            //         if (stristr(PHP_OS, 'win')) {
            //             $upload_tmp_dir = getenv('TEMP') ? getenv('TEMP') : getenv('TMP');
            //             $upload_tmp_dir = str_replace(['\\', '\\\\'], ['/', '/'], $upload_tmp_dir);
            //         } else {
            //             $upload_tmp_dir = getenv('TMPDIR') === false ? '/tmp' : getenv('TMPDIR');
            //         }
            //     }

            //     if (! stristr($open_basedir, $upload_tmp_dir)) {
            //         $warning[] = sprintf(lang('temp_dir_cannt_read'), $upload_tmp_dir);
            //     }
            // }

            // $result = file_mode_info('../cert');
            // if ($result < 2) {
            //     $warning[] = sprintf(lang('not_writable'), 'cert', lang('cert_cannt_write'));
            // }

            // $result = file_mode_info('../'.DATA_DIR);
            // if ($result < 2) {
            //     $warning[] = sprintf(lang('not_writable'), 'data', lang('data_cannt_write'));
            // } else {
            //     $result = file_mode_info('../'.DATA_DIR.'/afficheimg');
            //     if ($result < 2) {
            //         $warning[] = sprintf(lang('not_writable'), DATA_DIR.'/afficheimg', lang('afficheimg_cannt_write'));
            //     }

            //     $result = file_mode_info('../'.DATA_DIR.'/brandlogo');
            //     if ($result < 2) {
            //         $warning[] = sprintf(lang('not_writable'), DATA_DIR.'/brandlogo', lang('brandlogo_cannt_write'));
            //     }

            //     $result = file_mode_info('../'.DATA_DIR.'/cardimg');
            //     if ($result < 2) {
            //         $warning[] = sprintf(lang('not_writable'), DATA_DIR.'/cardimg', lang('cardimg_cannt_write'));
            //     }

            //     $result = file_mode_info('../'.DATA_DIR.'/feedbackimg');
            //     if ($result < 2) {
            //         $warning[] = sprintf(lang('not_writable'), DATA_DIR.'/feedbackimg', lang('feedbackimg_cannt_write'));
            //     }

            //     $result = file_mode_info('../'.DATA_DIR.'/packimg');
            //     if ($result < 2) {
            //         $warning[] = sprintf(lang('not_writable'), DATA_DIR.'/packimg', lang('packimg_cannt_write'));
            //     }
            // }

            // $result = file_mode_info('../images');
            // if ($result < 2) {
            //     $warning[] = sprintf(lang('not_writable'), 'images', lang('images_cannt_write'));
            // }

            // $result = file_mode_info('../temp');
            // if ($result < 2) {
            //     $warning[] = sprintf(lang('not_writable'), 'images', lang('tpl_cannt_write'));
            // }

            // $result = file_mode_info('../temp/backup');
            // if ($result < 2) {
            //     $warning[] = sprintf(lang('not_writable'), 'images', lang('tpl_backup_cannt_write'));
            // }

            // if (! is_writable('../'.DATA_DIR.'/order_print.html')) {
            //     $warning[] = lang('order_print_canntwrite');
            // }
            // clearstatcache();

            $this->assign('warning_arr', $warning);

            // 管理员留言信息
            $admin_msg = DB::table('admin_message as a')
                ->join('admin_user as b', 'a.sender_id', '=', 'b.user_id')
                ->select('a.message_id', 'a.sender_id', 'a.receiver_id', 'a.sent_time', 'a.readed', 'a.deleted', 'a.title', 'a.message', 'b.user_name')
                ->where('a.receiver_id', $this->getAdminId())
                ->where('a.readed', 0)
                ->where('a.deleted', 0)
                ->orderBy('a.sent_time', 'desc')
                ->get()
                ->toArray();

            $this->assign('admin_msg', $admin_msg);

            // 已完成的订单
            $order['finished'] = DB::table('order_info')
                ->whereRaw('1 '.order_query_sql('finished'))
                ->count();
            $status['finished'] = CS_FINISHED;

            // 待发货的订单：
            $order['await_ship'] = DB::table('order_info')
                ->whereRaw('1 '.order_query_sql('await_ship'))
                ->count();
            $status['await_ship'] = CS_AWAIT_SHIP;

            // 待付款的订单：
            $order['await_pay'] = DB::table('order_info')
                ->whereRaw('1 '.order_query_sql('await_pay'))
                ->count();
            $status['await_pay'] = CS_AWAIT_PAY;

            // “未确认”的订单
            $order['unconfirmed'] = DB::table('order_info')
                ->whereRaw('1 '.order_query_sql('unconfirmed'))
                ->count();
            $status['unconfirmed'] = OS_UNCONFIRMED;

            // “部分发货”的订单
            $order['shipped_part'] = DB::table('order_info')
                ->where('shipping_status', SS_SHIPPED_PART)
                ->count();
            $status['shipped_part'] = OS_SHIPPED_PART;

            $order['stats'] = DB::table('order_info')
                ->select(DB::raw('COUNT(*) AS oCount'), DB::raw('IFNULL(SUM(order_amount), 0) AS oAmount'))
                ->first();
            $order['stats'] = (array) $order['stats'];

            $this->assign('order', $order);
            $this->assign('status', $status);

            // 商品信息
            $goods['total'] = DB::table('goods')
                ->where('is_delete', 0)
                ->where('is_alone_sale', 1)
                ->where('is_real', 1)
                ->count();
            $virtual_card['total'] = DB::table('goods')
                ->where('is_delete', 0)
                ->where('is_alone_sale', 1)
                ->where('is_real', 0)
                ->where('extension_code', 'virtual_card')
                ->count();

            $goods['new'] = DB::table('goods')
                ->where('is_delete', 0)
                ->where('is_new', 1)
                ->where('is_real', 1)
                ->count();
            $virtual_card['new'] = DB::table('goods')
                ->where('is_delete', 0)
                ->where('is_new', 1)
                ->where('is_real', 0)
                ->where('extension_code', 'virtual_card')
                ->count();

            $goods['best'] = DB::table('goods')
                ->where('is_delete', 0)
                ->where('is_best', 1)
                ->where('is_real', 1)
                ->count();
            $virtual_card['best'] = DB::table('goods')
                ->where('is_delete', 0)
                ->where('is_best', 1)
                ->where('is_real', 0)
                ->where('extension_code', 'virtual_card')
                ->count();

            $goods['hot'] = DB::table('goods')
                ->where('is_delete', 0)
                ->where('is_hot', 1)
                ->where('is_real', 1)
                ->count();
            $virtual_card['hot'] = DB::table('goods')
                ->where('is_delete', 0)
                ->where('is_hot', 1)
                ->where('is_real', 0)
                ->where('extension_code', 'virtual_card')
                ->count();

            $time = TimeHelper::gmtime();
            $goods['promote'] = DB::table('goods')
                ->where('is_delete', 0)
                ->where('promote_price', '>', 0)
                ->where('promote_start_date', '<=', $time)
                ->where('promote_end_date', '>=', $time)
                ->where('is_real', 1)
                ->count();
            $virtual_card['promote'] = DB::table('goods')
                ->where('is_delete', 0)
                ->where('promote_price', '>', 0)
                ->where('promote_start_date', '<=', $time)
                ->where('promote_end_date', '>=', $time)
                ->where('is_real', 0)
                ->where('extension_code', 'virtual_card')
                ->count();

            // 缺货商品
            if (cfg('use_storage')) {
                $goods['warn'] = DB::table('goods')
                    ->where('is_delete', 0)
                    ->whereRaw('goods_number <= warn_number')
                    ->where('is_real', 1)
                    ->count();
                $virtual_card['warn'] = DB::table('goods')
                    ->where('is_delete', 0)
                    ->whereRaw('goods_number <= warn_number')
                    ->where('is_real', 0)
                    ->where('extension_code', 'virtual_card')
                    ->count();
            } else {
                $goods['warn'] = 0;
                $virtual_card['warn'] = 0;
            }
            $this->assign('goods', $goods);
            $this->assign('virtual_card', $virtual_card);

            // 访问统计信息
            $today = TimeHelper::local_getdate();
            $today_visit = DB::table('shop_stats')
                ->where('access_time', '>', (mktime(0, 0, 0, $today['mon'], $today['mday'], $today['year']) - date('Z')))
                ->count();
            $this->assign('today_visit', $today_visit);

            // TODO $online_users = $sess->get_users_count();
            $this->assign('online_users', 0); // $online_users);

            // 最近反馈
            $feedback_number = DB::table('feedback as f')
                ->leftJoin('feedback as r', 'r.parent_id', '=', 'f.msg_id')
                ->where('f.parent_id', 0)
                ->whereNull('r.msg_id')
                ->count('f.msg_id');
            $this->assign('feedback_number', $feedback_number);

            // 未审核评论
            $comment_number = DB::table('comment')
                ->where('status', 0)
                ->where('parent_id', 0)
                ->count();
            $this->assign('comment_number', $comment_number);

            $mysql_ver = DB::getServerVersion();   // 获得 MySQL 版本

            // 系统信息
            $sys_info['os'] = PHP_OS;
            $sys_info['ip'] = $_SERVER['SERVER_ADDR'] ?? '';
            $sys_info['web_server'] = $_SERVER['SERVER_SOFTWARE'];
            $sys_info['php_ver'] = PHP_VERSION;
            $sys_info['mysql_ver'] = $mysql_ver;
            $sys_info['zlib'] = function_exists('gzclose') ? lang('yes') : lang('no');
            $sys_info['safe_mode'] = (bool) ini_get('safe_mode') ? lang('yes') : lang('no');
            $sys_info['safe_mode_gid'] = (bool) ini_get('safe_mode_gid') ? lang('yes') : lang('no');
            $sys_info['timezone'] = function_exists('date_default_timezone_get') ? date_default_timezone_get() : lang('no_timezone');
            $sys_info['socket'] = function_exists('fsockopen') ? lang('yes') : lang('no');

            $sys_info['gd'] = BaseHelper::gd_version();
            if (empty($sys_info['gd'])) {
                $sys_info['gd'] = 'N/A';
            }

            // IP库版本
            $sys_info['ip_version'] = ''; // TODO BaseHelper::ecs_geoip('255.255.255.0');

            // 允许上传的最大文件大小
            $sys_info['max_filesize'] = ini_get('upload_max_filesize');

            $this->assign('sys_info', $sys_info);

            // 缺货登记
            // 缺货登记
            $this->assign('booking_goods', DB::table('user_booking')->where('is_dispose', 0)->count());

            // 退款申请
            $this->assign('new_repay', DB::table('user_account')->where('process_type', SURPLUS_RETURN)->where('is_paid', 0)->count());

            $this->assign('ecs_version', VERSION);
            $this->assign('ecs_release', RELEASE);
            $this->assign('ecs_lang', cfg('lang'));
            $this->assign('ecs_charset', strtoupper(EC_CHARSET));
            $this->assign('install_date', ''); // TODO TimeHelper::local_date(cfg('date_format'), cfg('install_date')));

            return $this->display('start');
        }

        // 拖动的帧
        if ($action === 'drag') {
            return $this->display('drag');
        }

        // 检查订单
        if ($action === 'check_order') {
            if (empty(Session::get('last_check'))) {
                Session::put('last_check', TimeHelper::gmtime());

                return $this->make_json_result('', '', ['new_orders' => 0, 'new_paid' => 0]);
            }

            // 新订单
            $arr['new_orders'] = DB::table('order_info')
                ->where('add_time', '>=', Session::get('last_check'))
                ->count();

            // 新付款的订单
            $arr['new_paid'] = DB::table('order_info')
                ->where('pay_time', '>=', Session::get('last_check'))
                ->count();

            Session::put('last_check', TimeHelper::gmtime());

            return $this->make_json_result('', '', $arr);
        }

        // 保存Totolist
        if ($action === 'save_todolist') {
            $content = BaseHelper::json_str_iconv($_POST['content']);
            DB::table('admin_user')
                ->where('user_id', Session::get('admin_id'))
                ->update(['todolist' => $content]);
        }

        // 获取Totolist
        if ($action === 'get_todolist') {
            $content = DB::table('admin_user')
                ->where('user_id', Session::get('admin_id'))
                ->value('todolist');
            echo $content;
        }

        // 邮件群发处理
        if ($action === 'send_mail') {
            if (cfg('send_mail_on') === 'off') {
                return $this->make_json_result('', lang('send_mail_off'), 0);
            }

            $row = DB::table('email_send')
                ->orderByDesc('pri')
                ->orderBy('last_send')
                ->first();
            $row = $row ? (array) $row : [];

            // 发送列表为空
            if (empty($row['id'])) {
                return $this->make_json_result('', lang('mailsend_null'), 0);
            }

            // 发送列表不为空，邮件地址为空
            if (! empty($row['id']) && empty($row['email'])) {
                DB::table('email_send')->where('id', $row['id'])->delete();
                $count = DB::table('email_send')->count();

                return $this->make_json_result('', lang('mailsend_skip'), ['count' => $count, 'goon' => 1]);
            }

            // 查询相关模板
            $rt = DB::table('email_template')->where('template_id', $row['template_id'])->first();
            $rt = $rt ? (array) $rt : [];

            // 如果是模板，则将已存入email_sendlist的内容作为邮件内容
            // 否则即是杂质，将mail_templates调出的内容作为邮件内容
            if ($rt['type'] === 'template') {
                $rt['template_content'] = $row['email_content'];
            }

            if ($rt['template_id'] && $rt['template_content']) {
                if (BaseHelper::send_mail('', $row['email'], $rt['template_subject'], $rt['template_content'], $rt['is_html'])) {
                    // 发送成功

                    // 从列表中删除
                    DB::table('email_send')->where('id', $row['id'])->delete();

                    // 剩余列表数
                    $count = DB::table('email_send')->count();

                    if ($count > 0) {
                        $msg = sprintf(lang('mailsend_ok'), $row['email'], $count);
                    } else {
                        $msg = sprintf(lang('mailsend_finished'), $row['email']);
                    }

                    return $this->make_json_result('', $msg, ['count' => $count]);
                } else {
                    // 发送出错

                    if ($row['error'] < 3) {
                        DB::table('email_send')->where('id', $row['id'])->increment('error', 1, [
                            'pri' => 0,
                            'last_send' => time(),
                        ]);
                    } else {
                        // 将出错超次的纪录删除
                        DB::table('email_send')->where('id', $row['id'])->delete();
                    }

                    $count = DB::table('email_send')->count();

                    return $this->make_json_result('', sprintf(lang('mailsend_fail'), $row['email']), ['count' => $count]);
                }
            } else {
                // 无效的邮件队列
                DB::table('email_send')->where('id', $row['id'])->delete();
                $count = DB::table('email_send')->count();

                return $this->make_json_result('', sprintf(lang('mailsend_fail'), $row['email']), ['count' => $count]);
            }
        }
    }
}
