<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\CommonHelper;
use App\Helpers\TimeHelper;
use App\Http\Controllers\Controller;
use App\Libraries\Error;
use App\Modules\Admin\AdminServiceProvider;
use App\Modules\Admin\Helpers\MainHelper;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

define('ECS_ADMIN', true);

abstract class BaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    protected function display($template, array $vars = []): Renderable
    {
        $this->assign('lang', lang());

        return parent::display(AdminServiceProvider::NS.'::'.$template, $vars);
    }

    protected function getAdminId(): int
    {
        return (int) Session::get('admin_id');
    }

    /**
     * 创建一个JSON格式的数据
     */
    protected function make_json_response(string $content = '', int $error = 0, string $message = '', array $append = []): false|string
    {
        $res = ['error' => $error, 'message' => $message, 'content' => $content];

        if (! empty($append)) {
            foreach ($append as $key => $val) {
                $res[$key] = $val;
            }
        }

        return json_encode($res);
    }

    /**
     * 创建一个JSON格式的信息
     */
    protected function make_json_result(string $content, string $message = '', array $append = []): false|string
    {
        return $this->make_json_response($content, 0, $message, $append);
    }

    /**
     * 创建一个JSON格式的错误信息
     */
    protected function make_json_error(string $msg): false|string
    {
        return $this->make_json_response('', 1, $msg);
    }

    /**
     * 判断管理员对某一个操作是否有权限。
     *
     * 根据当前对应的action_code，然后再和用户session里面的action_list做匹配，以此来决定是否可以继续执行。
     *
     * @param  string  $priv_str  操作对应的priv_str
     * @param  string  $msg_type  返回的类型
     */
    protected function admin_priv(string $priv_str, string $msg_type = '', bool $msg_output = true)
    {
        return true; // TODO

        if (Session::get('action_list') === 'all') {
            return true;
        }

        if (! str_contains(','.Session::get('action_list').',', ','.$priv_str.',')) {
            $link[] = ['text' => lang('go_back'), 'href' => 'javascript:history.back(-1)'];
            if ($msg_output) {
                // return $this->sys_msg(lang('priv_error'), 0, $link);
                throw new \Exception(lang('priv_error'));
            }

            return false;
        } else {
            return true;
        }
    }

    /**
     * 检查管理员权限
     *
     * @param  string  $authz
     * @return bool
     */
    protected function check_authz($authz)
    {
        $action_list = Session::get('action_list');

        return preg_match('/,*'.$authz.',*/', $action_list) || $action_list === 'all';
    }

    /**
     * 检查管理员权限，返回JSON格式数剧
     *
     * @param  string  $authz
     * @return void
     */
    protected function check_authz_json($authz)
    {
        if (! $this->check_authz($authz)) {
            return $this->make_json_error(lang('priv_error'));
        }
    }

    /**
     * 系统提示信息：0消息，1错误，2询问
     */
    protected function sys_msg(string $msg_detail, int $msg_type = 0, array $links = [], bool $auto_redirect = true): Renderable
    {
        if (count($links) === 0) {
            $links[0]['text'] = lang('go_back');
            $links[0]['href'] = 'javascript:history.go(-1)';
        }

        $this->assign('ur_here', lang('system_message'));
        $this->assign('msg_detail', $msg_detail);
        $this->assign('msg_type', $msg_type);
        $this->assign('links', $links);
        $this->assign('default_url', $links[0]['href']);
        $this->assign('auto_redirect', $auto_redirect);

        return $this->display('message');
    }

    /**
     * 显示错误信息
     */
    protected function show(string $link, string $href, Error $error): Renderable
    {
        $message = [];

        $link = (empty($link)) ? lang('back_up_page') : $link;
        $href = (empty($href)) ? 'javascript:history.back();' : $href;
        $message['url_info'][$link] = $href;
        $message['back_url'] = $href;

        if ($error->error_no > 0) {
            foreach ($error->_message as $msg) {
                $message['content'] = '<div>'.htmlspecialchars($msg).'</div>';
            }
        }

        $this->assign('auto_redirect', true);
        $this->assign('message', $message);

        return $this->display('message');
    }

    /**
     * 记录管理员的操作内容
     *
     * @param  string  $sn  数据的唯一值
     * @param  string  $action  操作的类型
     * @param  string  $content  操作的内容
     * @return void
     */
    protected function admin_log($sn = '', $action = '', $content = '')
    {
        $log_info = lang('log_action')[$action].lang('log_action')[$content].': '.addslashes($sn);

        DB::table('admin_log')->insert([
            'log_time' => TimeHelper::gmtime(),
            'user_id' => Session::get('admin_id'),
            'log_info' => stripslashes($log_info),
            'ip_address' => BaseHelper::real_ip(),
        ]);
    }

    /**
     * 获得查询时间和次数，并赋值给smarty
     *
     * @return void
     */
    protected function assign_query_info()
    {
        // if (db()->queryTime === '') {
        //     $query_time = 0;
        // } else {
        //     $query_time = number_format(microtime(true) - db()->queryTime, 6);
        // }
        // $this->assign('query_info', sprintf(lang('query_info'), db()->queryCount, $query_time));

        // // 内存占用情况
        // if (lang('memory_info') && function_exists('memory_get_usage')) {
        //     $this->assign('memory_info', sprintf(lang('memory_info'), memory_get_usage() / 1048576));
        // }

        // // 是否启用了 gzip
        // $gzip_enabled = BaseHelper::gzip_enabled() ? lang('gzip_enabled') : lang('gzip_disabled');
        // $this->assign('gzip_enabled', $gzip_enabled);
    }
}
//
// $ecs = new ECS($db_name, $prefix);
// define('DATA_DIR', ecs()->data_dir());
// define('IMAGE_DIR', ecs()->image_dir());
//
// // 初始化数据库类
// $db = new cls_mysql($db_host, $db_user, $db_pass, $db_name);
// $db_host = $db_user = $db_pass = $db_name = null;
//
// // 创建错误处理对象
// $err = new ecs_error('message');
//
// // 初始化session
// $sess = new cls_session($db, ecs()->table('sessions'), ecs()->table('sessions_data'), 'ECSCP_ID');
//
// // 载入系统参数
// $_CFG = CommonHelper::load_config();
//
// // TODO : 登录部分准备拿出去做，到时候把以下操作一起挪过去
// if ($action === 'captcha') {
//
//    $img = new captcha('../data/captcha/');
//    @ob_end_clean(); // 清除之前出现的多余输入
//    $img->generate_image();
//
//    exit;
// }
//
// if (! file_exists('../temp/caches')) {
//    @mkdir('../temp/caches', 0777);
//    @chmod('../temp/caches', 0777);
// }
//
// if (! file_exists('../temp/compiled/admin')) {
//    @mkdir('../temp/compiled/admin', 0777);
//    @chmod('../temp/compiled/admin', 0777);
// }
//
// clearstatcache();
//
// if (preg_replace('/(?:\.|\s+)[a-z]*$/i', '', cfg('ecs_version')) != preg_replace('/(?:\.|\s+)[a-z]*$/i', '', VERSION)
//    && file_exists('../upgrade/index.php')) {
//    // 转到升级文件
//    return response()->redirectTo("../upgrade/index.php");
//
//    exit;
// }
//
// // 创建 Smarty 对象。
// $smarty = new cls_template;
//
// $smarty->template_dir = ROOT_PATH.ADMIN_PATH.'/templates';
// $smarty->compile_dir = ROOT_PATH.'temp/compiled/admin';
//
// if (DEBUG_MODE) {
//    $smarty->force_compile = true;
// }
//
//
// $this->assign('help_open', cfg('help_open'));
//
// if (cfg('enable_order_check')) {  // 为了从旧版本顺利升级到2.5.0
//    $this->assign('enable_order_check', cfg('enable_order_check'));
// } else {
//    $this->assign('enable_order_check', 0);
// }
//
// // 验证管理员身份
// if ((! isset($_SESSION['admin_id']) || intval($_SESSION['admin_id']) <= 0) &&
//    $_REQUEST['act'] != 'login' && $_REQUEST['act'] != 'signin' &&
//    $_REQUEST['act'] != 'forget_pwd' && $_REQUEST['act'] != 'reset_pwd' && $_REQUEST['act'] != 'check_order') {
//    // session 不存在，检查cookie
//    if (! empty($_COOKIE['ECSCP']['admin_id']) && ! empty($_COOKIE['ECSCP']['admin_pass'])) {
//        // 找到了cookie, 验证cookie信息
//        $sql = 'SELECT user_id, user_name, password, add_time, action_list, last_login '.
//            ' FROM '.ecs()->table('admin_user').
//            " WHERE user_id = '".intval($_COOKIE['ECSCP']['admin_id'])."'";
//        $row = db()->getRow($sql);
//
//        if (! $row) {
//            // 没有找到这个记录
//            setcookie($_COOKIE['ECSCP']['admin_id'], '', 1, '', '', false, true);
//            setcookie($_COOKIE['ECSCP']['admin_pass'], '', 1, '', '', false, true);
//
//            if (! empty($_REQUEST['is_ajax'])) {
//                return $this->make_json_error(lang('priv_error'));
//            } else {
//                return response()->redirectTo("privilege.php?act=login");
//            }
//
//            exit;
//        } else {
//            // 检查密码是否正确
//            if (md5($row['password'].cfg('hash_code').$row['add_time']) === $_COOKIE['ECSCP']['admin_pass']) {
//                ! isset($row['last_time']) && $row['last_time'] = '';
//                MainHelper::set_admin_session($row['user_id'], $row['user_name'], $row['action_list'], $row['last_time']);
//
//                // 更新最后登录时间和IP
//                db()->query('UPDATE '.ecs()->table('admin_user').
//                    " SET last_login = '".TimeHelper::gmtime()."', last_ip = '".BaseHelper::real_ip()."'".
//                    " WHERE user_id = '".$_SESSION['admin_id']."'");
//            } else {
//                setcookie($_COOKIE['ECSCP']['admin_id'], '', 1, '', '', false, true);
//                setcookie($_COOKIE['ECSCP']['admin_pass'], '', 1, '', '', false, true);
//
//                if (! empty($_REQUEST['is_ajax'])) {
//                    return $this->make_json_error(lang('priv_error'));
//                } else {
//                    return response()->redirectTo("privilege.php?act=login");
//                }
//
//                exit;
//            }
//        }
//    } else {
//        if (! empty($_REQUEST['is_ajax'])) {
//            return $this->make_json_error(lang('priv_error'));
//        } else {
//            return response()->redirectTo("privilege.php?act=login");
//        }
//
//        exit;
//    }
// }
//
// $this->assign('token', cfg('token'));
//
// if ($_REQUEST['act'] != 'login' && $_REQUEST['act'] != 'signin' &&
//    $_REQUEST['act'] != 'forget_pwd' && $_REQUEST['act'] != 'reset_pwd' && $_REQUEST['act'] != 'check_order') {
//    $admin_path = preg_replace('/:\d+/', '', ecs()->url()).ADMIN_PATH;
//    if (! empty($_SERVER['HTTP_REFERER']) &&
//        strpos(preg_replace('/:\d+/', '', $_SERVER['HTTP_REFERER']), $admin_path) === false) {
//        if (! empty($_REQUEST['is_ajax'])) {
//            return $this->make_json_error(lang('priv_error'));
//        } else {
//            return response()->redirectTo("privilege.php?act=login");
//        }
//
//        exit;
//    }
// }
//
// header('content-type: text/html; charset='.EC_CHARSET);
// header('Expires: Fri, 14 Mar 1980 20:53:00 GMT');
// header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
// header('Cache-Control: no-cache, must-revalidate');
// header('Pragma: no-cache');
//
// if (DEBUG_MODE) {
//    error_reporting(E_ALL);
// } else {
//    error_reporting(E_ALL ^ E_NOTICE);
// }
//
// // 判断是否支持gzip模式
// if (BaseHelper::gzip_enabled()) {
//    ob_start('ob_gzhandler');
// } else {
//    ob_start();
// }
