<?php

declare(strict_types=1);

namespace App\Modules\Web\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\CommonHelper;
use App\Helpers\GoodsHelper;
use App\Helpers\MainHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

abstract class BaseController extends Controller
{
    private function init()
    {
        error_reporting(E_ALL);

        // 取得当前phpmall所在的根目录
        define('ROOT_PATH', str_replace('\\', '/', dirname(__DIR__).'/'));

        date_default_timezone_set($timezone);

        $php_self = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
        if (substr($php_self, -1) === '/') {
            $php_self .= 'index.php';
        }
        define('PHP_SELF', $php_self);

        // 对用户传入的变量进行转义操作。
        if (! empty($_GET)) {
            $_GET = BaseHelper::addslashes_deep($_GET);
        }
        if (! empty($_POST)) {
            $_POST = BaseHelper::addslashes_deep($_POST);
        }

        $_COOKIE = BaseHelper::addslashes_deep($_COOKIE);
        $_REQUEST = BaseHelper::addslashes_deep($_REQUEST);

        // 创建 PHPMall 对象
        $ecs = new ECS($db_name, $prefix);
        define('DATA_DIR', ecs()->data_dir());
        define('IMAGE_DIR', ecs()->image_dir());

        // 初始化数据库类
        $db = new cls_mysql($db_host, $db_user, $db_pass, $db_name);
        // db()->set_disable_cache_tables() — not needed with Laravel DB facade
        $db_host = $db_user = $db_pass = $db_name = null;

        // 创建错误处理对象
        $err = new ecs_error('message');

        // 载入系统参数
        $_CFG = CommonHelper::load_config();

        // 载入语言文件
        require ROOT_PATH.'languages/'.cfg('lang').'/common.php';

        if (cfg('shop_closed') === 1) {
            // 商店关闭了，输出关闭的消息
            header('Content-type: text/html; charset='.EC_CHARSET);

            exit('<div style="margin: 150px; text-align: center; font-size: 14px"><p>'.lang('shop_closed').'</p><p>'.cfg('close_comment').'</p></div>');
        }

        if (MainHelper::is_spider()) {
            // 如果是蜘蛛的访问，那么默认为访客方式，并且不记录到日志中
            if (! defined('INIT_NO_USERS')) {
                define('INIT_NO_USERS', true);
                // 整合UC后，如果是蜘蛛访问，初始化UC需要的常量
                if (cfg('integrate_code') === 'ucenter') {
                    $user = CommonHelper::init_users();
                }
            }
            Session::forget(['user_id', 'user_name', 'email', 'user_rank', 'discount']);
            Session::put('user_id', 0);
            Session::put('user_name', '');
            Session::put('email', '');
            Session::put('user_rank', 0);
            Session::put('discount', 1.00);
        }

        if (! defined('INIT_NO_USERS')) {
            define('SESS_ID', Session::getId());
        }
        if (isset($_SERVER['PHP_SELF'])) {
            $_SERVER['PHP_SELF'] = htmlspecialchars($_SERVER['PHP_SELF']);
        }
        if (! defined('INIT_NO_SMARTY')) {
            header('Cache-control: private');
            header('Content-type: text/html; charset='.EC_CHARSET);

            $this->assign('lang', lang());
            $this->assign('ecs_charset', EC_CHARSET);
            if (! empty(cfg('stylename'))) {
                $this->assign('ecs_css_path', 'themes/'.cfg('template').'/style_'.cfg('stylename').'.css');
            } else {
                $this->assign('ecs_css_path', 'themes/'.cfg('template').'/style.css');
            }
        }

        if (! defined('INIT_NO_USERS')) {
            // 会员信息
            $user = CommonHelper::init_users();

            if (! Session::has('user_id')) {
                // 获取投放站点的名称
                $site_name = isset($_GET['from']) ? htmlspecialchars($_GET['from']) : addslashes(lang('self_site'));
                $from_ad = ! empty($_GET['ad_id']) ? intval($_GET['ad_id']) : 0;

                Session::put('from_ad', $from_ad); // 用户点击的广告ID
                Session::put('referer', stripslashes($site_name)); // 用户来源

                unset($site_name);

                if (! defined('INGORE_VISIT_STATS')) {
                    MainHelper::visit_stats();
                }
            }

            if (empty(Session::get('user_id'))) {
                if ($user->get_cookie()) {
                    // 如果会员已经登录并且还没有获得会员的帐户余额、积分以及优惠券
                    if (Session::get('user_id') > 0) {
                        MainHelper::update_user_info();
                    }
                } else {
                    Session::put('user_id', 0);
                    Session::put('user_name', '');
                    Session::put('email', '');
                    Session::put('user_rank', 0);
                    Session::put('discount', 1.00);
                    if (! Session::has('login_fail')) {
                        Session::put('login_fail', 0);
                    }
                }
            }

            // 设置推荐会员
            if (isset($_GET['u'])) {
                MainHelper::set_affiliate();
            }

            if (isset($smarty)) {
                $this->assign('ecs_session', Session::all());
            }
        }
    }

    /**
     * 取得当前位置和页面标题
     *
     * @param  int  $cat  分类编号（只有商品及分类、文章及分类用到）
     * @param  string  $str  商品名、文章标题或其他附加的内容（无链接）
     * @return array
     */
    protected function assign_ur_here($cat = 0, $str = '')
    {
        // 判断是否重写，取得文件名
        $cur_url = basename(request()->path());
        if (intval(cfg('rewrite'))) {
            $filename = strpos($cur_url, '-') ? substr($cur_url, 0, strpos($cur_url, '-')) : substr($cur_url, 0, -4);
        } else {
            $filename = substr($cur_url, 0, -4);
        }

        // 初始化“页面标题”和“当前位置”
        $page_title = cfg('shop_title');
        $ur_here = '<a href=".">'.lang('home').'</a>';

        // 根据文件名分别处理中间的部分
        if ($filename != 'index') {
            // 处理有分类的
            if (in_array($filename, ['category', 'goods', 'article_cat', 'article', 'brand'])) {
                // 商品分类或商品
                if ($filename === 'category' || $filename === 'goods' || $filename === 'brand') {
                    if ($cat > 0) {
                        $cat_arr = MainHelper::get_parent_cats($cat);

                        $key = 'cid';
                        $type = 'category';
                    } else {
                        $cat_arr = [];
                    }
                } // 文章分类或文章
                elseif ($filename === 'article_cat' || $filename === 'article') {
                    if ($cat > 0) {
                        $cat_arr = MainHelper::get_article_parent_cats($cat);

                        $key = 'acid';
                        $type = 'article_cat';
                    } else {
                        $cat_arr = [];
                    }
                }

                // 循环分类
                if (! empty($cat_arr)) {
                    krsort($cat_arr);
                    foreach ($cat_arr as $val) {
                        $page_title = htmlspecialchars($val['cat_name']).'_'.$page_title;
                        $args = [$key => $val['cat_id']];
                        $ur_here .= ' <code>&gt;</code> <a href="'.build_uri($type, $args, $val['cat_name']).'">'.
                            htmlspecialchars($val['cat_name']).'</a>';
                    }
                }
            } // 处理无分类的
            else {
                // 团购
                if ($filename === 'group_buy') {
                    $page_title = lang('group_buy_goods').'_'.$page_title;
                    $args = ['gbid' => '0'];
                    $ur_here .= ' <code>&gt;</code> <a href="group_buy.php">'.
                        lang('group_buy_goods').'</a>';
                } // 拍卖
                elseif ($filename === 'auction') {
                    $page_title = lang('auction').'_'.$page_title;
                    $args = ['auid' => '0'];
                    $ur_here .= ' <code>&gt;</code> <a href="auction.php">'.
                        lang('auction').'</a>';
                } // 夺宝
                elseif ($filename === 'snatch') {
                    $page_title = lang('snatch').'_'.$page_title;
                    $args = ['id' => '0'];
                    $ur_here .= ' <code> &gt; </code><a href="snatch.php">'.lang('snatch_list').'</a>';
                } // 批发
                elseif ($filename === 'wholesale') {
                    $page_title = lang('wholesale').'_'.$page_title;
                    $args = ['wsid' => '0'];
                    $ur_here .= ' <code>&gt;</code> <a href="wholesale.php">'.
                        lang('wholesale').'</a>';
                } // 积分兑换
                elseif ($filename === 'exchange') {
                    $page_title = lang('exchange').'_'.$page_title;
                    $args = ['wsid' => '0'];
                    $ur_here .= ' <code>&gt;</code> <a href="exchange.php">'.
                        lang('exchange').'</a>';
                }
                // 其他的在这里补充
            }
        }

        // 处理最后一部分
        if (! empty($str)) {
            $page_title = $str.'_'.$page_title;
            $ur_here .= ' <code>&gt;</code> '.$str;
        }

        // 返回值
        return ['title' => $page_title, 'ur_here' => $ur_here];
    }

    protected function assign_template($ctype = '', $catlist = [])
    {
        $this->assign('image_width', cfg('image_width'));
        $this->assign('image_height', cfg('image_height'));
        $this->assign('points_name', cfg('integral_name'));
        $this->assign('qq', explode(',', cfg('qq')));
        $this->assign('ww', explode(',', cfg('ww')));
        $this->assign('ym', explode(',', cfg('ym')));
        $this->assign('msn', explode(',', cfg('msn')));
        $this->assign('skype', explode(',', cfg('skype')));
        $this->assign('stats_code', cfg('stats_code'));
        $this->assign('copyright', sprintf(lang('copyright'), date('Y'), cfg('shop_name')));
        $this->assign('shop_name', cfg('shop_name'));
        $this->assign('service_email', cfg('service_email'));
        $this->assign('service_phone', cfg('service_phone'));
        $this->assign('shop_address', cfg('shop_address'));
        $this->assign('licensed', '');
        $this->assign('ecs_version', VERSION);
        $this->assign('icp_number', cfg('icp_number'));
        $this->assign('username', Session::get('user_name', ''));
        $this->assign('category_list', CommonHelper::cat_list(0, 0, true, 2, false));
        $this->assign('catalog_list', CommonHelper::cat_list(0, 0, false, 1, false));
        $this->assign('navigator_list', MainHelper::get_navigator($ctype, $catlist));  // 自定义导航栏

        if (! empty(cfg('search_keywords'))) {
            $searchkeywords = explode(',', trim(cfg('search_keywords')));
        } else {
            $searchkeywords = [];
        }
        $this->assign('searchkeywords', $searchkeywords);
    }

    /**
     * 获得指定页面的动态内容
     *
     * @param  string  $tmp  模板名称
     * @return void
     */
    protected function assign_dynamic($tmp)
    {
        $res = DB::table('template')
            ->where('filename', $tmp)
            ->where('type', '>', 0)
            ->where('remarks', '')
            ->where('theme', cfg('template'))
            ->select('id', 'number', 'type')
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        foreach ($res as $row) {
            switch ($row['type']) {
                case 1:
                    // 分类下的商品
                    $this->assign('goods_cat_'.$row['id'], GoodsHelper::assign_cat_goods($row['id'], $row['number']));
                    break;
                case 2:
                    // 品牌的商品
                    $brand_goods = GoodsHelper::assign_brand_goods($row['id'], $row['number']);

                    $this->assign('brand_goods_'.$row['id'], $brand_goods['goods']);
                    $this->assign('goods_brand_'.$row['id'], $brand_goods['brand']);
                    break;
                case 3:
                    // 文章列表
                    $cat_articles = MainHelper::assign_articles($row['id'], $row['number']);

                    $this->assign('articles_cat_'.$row['id'], $cat_articles['cat']);
                    $this->assign('articles_'.$row['id'], $cat_articles['arr']);
                    break;
            }
        }
    }

    /**
     * 显示一个提示信息
     *
     * @param  string  $content
     * @param  string  $link
     * @param  string  $href
     * @param  string  $type  信息类型：warning, error, info
     * @param  string  $auto_redirect  是否自动跳转
     * @return void
     */
    protected function show_message($content, $links = '', $hrefs = '', $type = 'info', $auto_redirect = true)
    {
        $this->assign_template();

        $msg['content'] = $content;
        if (is_array($links) && is_array($hrefs)) {
            if (! empty($links) && count($links) === count($hrefs)) {
                foreach ($links as $key => $val) {
                    $msg['url_info'][$val] = $hrefs[$key];
                }
                $msg['back_url'] = $hrefs['0'];
            }
        } else {
            $link = empty($links) ? lang('back_up_page') : $links;
            $href = empty($hrefs) ? 'javascript:history.back()' : $hrefs;
            $msg['url_info'][$link] = $href;
            $msg['back_url'] = $href;
        }

        $msg['type'] = $type;
        $position = $this->assign_ur_here(0, lang('sys_msg'));
        $this->assign('page_title', $position['title']);   // 页面标题
        $this->assign('ur_here', $position['ur_here']); // 当前位置

        if (is_null($this->get_template_vars('helps'))) {
            $this->assign('helps', MainHelper::get_shop_help()); // 网店帮助
        }

        $this->assign('auto_redirect', $auto_redirect);
        $this->assign('message', $msg);

        return $this->display('message');
    }
}
