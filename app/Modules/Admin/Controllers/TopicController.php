<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\CommonHelper;
use App\Helpers\TimeHelper;
use App\Modules\Admin\Helpers\MainHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TopicController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        // 配置风格颜色选项
        $topic_style_color = [
            '0' => '008080',
            '1' => '008000',
            '2' => 'ffa500',
            '3' => 'ff0000',
            '4' => 'ffff00',
            '5' => '9acd32',
            '6' => 'ffd700',
        ];
        $allow_suffix = ['gif', 'jpg', 'png', 'jpeg', 'bmp', 'swf'];

        /**
         * 专题列表页面
         */
        if ($action === 'list') {
            $this->admin_priv('topic_manage');

            $this->assign('ur_here', lang('09_topic'));

            $this->assign('full_page', 1);
            $list = $this->get_topic_list();

            $this->assign('topic_list', $list['item']);
            $this->assign('filter', $list['filter']);
            $this->assign('record_count', $list['record_count']);
            $this->assign('page_count', $list['page_count']);

            $sort_flag = MainHelper::sort_flag($list['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            $this->assign('action_link', ['text' => lang('topic_add'), 'href' => 'topic.php?act=add']);

            return $this->display('topic_list');
        }
        // 添加,编辑
        if ($action === 'add' || $action === 'edit') {
            $this->admin_priv('topic_manage');

            $isadd = $action === 'add';
            $this->assign('isadd', $isadd);
            $topic_id = empty($_REQUEST['topic_id']) ? 0 : intval($_REQUEST['topic_id']);

            // include_once ROOT_PATH.'includes/fckeditor/fckeditor.php'; // 包含 html editor 类文件

            $this->assign('ur_here', lang('09_topic'));
            $this->assign('action_link', $this->list_link($isadd));

            $this->assign('cat_list', CommonHelper::cat_list(0, 1));
            $this->assign('brand_list', CommonHelper::get_brand_list());
            $this->assign('cfg_lang', cfg('lang'));
            $this->assign('topic_style_color', $topic_style_color);

            $width_height = $this->get_toppic_width_height();
            if (isset($width_height['pic']['width']) && isset($width_height['pic']['height'])) {
                $this->assign('width_height', sprintf(lang('tips_width_height'), $width_height['pic']['width'], $width_height['pic']['height']));
            }
            if (isset($width_height['title_pic']['width']) && isset($width_height['title_pic']['height'])) {
                $this->assign('title_width_height', sprintf(lang('tips_title_width_height'), $width_height['title_pic']['width'], $width_height['title_pic']['height']));
            }

            if (! $isadd) {
                $topic = DB::table('activity_topic')
                    ->where('topic_id', $topic_id)
                    ->first();
                $topic = $topic ? (array) $topic : [];
                $topic['start_time'] = TimeHelper::local_date('Y-m-d', $topic['start_time']);
                $topic['end_time'] = TimeHelper::local_date('Y-m-d', $topic['end_time']);

                MainHelper::create_html_editor('topic_intro', $topic['intro']);

                $topic['data'] = addcslashes($topic['data'], "'");
                $topic['data'] = json_encode(@unserialize($topic['data']));
                $topic['data'] = addcslashes($topic['data'], "'");

                if (empty($topic['topic_img']) && empty($topic['htmls'])) {
                    $topic['topic_type'] = 0;
                } elseif ($topic['htmls'] != '') {
                    $topic['topic_type'] = 2;
                } elseif (preg_match('/.swf$/i', $topic['topic_img'])) {
                    $topic['topic_type'] = 1;
                } else {
                    $topic['topic_type'] = '';
                }

                $this->assign('topic', $topic);
                $this->assign('act', 'update');
            } else {
                $topic = ['title' => '', 'topic_type' => 0, 'url' => 'http://'];
                $this->assign('topic', $topic);

                MainHelper::create_html_editor('topic_intro');
                $this->assign('act', 'insert');
            }

            return $this->display('topic_edit');
        }

        if ($action === 'insert' || $action === 'update') {
            $this->admin_priv('topic_manage');

            $is_insert = $action === 'insert';
            $topic_id = empty($_POST['topic_id']) ? 0 : intval($_POST['topic_id']);
            $topic_type = empty($_POST['topic_type']) ? 0 : intval($_POST['topic_type']);

            switch ($topic_type) {
                case '0':
                case '1':

                    // 主图上传
                    if ($_FILES['topic_img']['name'] && $_FILES['topic_img']['size'] > 0) {
                        // 检查文件合法性
                        if (! BaseHelper::get_file_suffix($_FILES['topic_img']['name'], $allow_suffix)) {
                            return $this->sys_msg(lang('invalid_type'));
                        }

                        // 处理
                        $name = date('Ymd');
                        for ($i = 0; $i < 6; $i++) {
                            $name .= chr(mt_rand(97, 122));
                        }
                        $topic_img_name_arr = explode('.', $_FILES['topic_img']['name']);
                        $name .= '.'.end($topic_img_name_arr);
                        $target = ROOT_PATH.DATA_DIR.'/afficheimg/'.$name;

                        if (BaseHelper::move_upload_file($_FILES['topic_img']['tmp_name'], $target)) {
                            $topic_img = DATA_DIR.'/afficheimg/'.$name;
                        }
                    } elseif (! empty($_REQUEST['url'])) {
                        // 来自互联网图片 不可以是服务器地址
                        if (strstr($_REQUEST['url'], 'http') && ! strstr($_REQUEST['url'], $_SERVER['SERVER_NAME'])) {
                            // 取互联网图片至本地
                            $topic_img = $this->get_url_image($_REQUEST['url']);
                        } else {
                            return $this->sys_msg(lang('web_url_no'));
                        }
                    }
                    unset($name, $target);

                    $topic_img = empty($topic_img) ? $_POST['img_url'] : $topic_img;
                    $htmls = '';

                    break;

                case '2':

                    $htmls = $_POST['htmls'];

                    $topic_img = '';

                    break;
            }

            // 标题图上传
            if ($_FILES['title_pic']['name'] && $_FILES['title_pic']['size'] > 0) {
                // 检查文件合法性
                if (! BaseHelper::get_file_suffix($_FILES['title_pic']['name'], $allow_suffix)) {
                    return $this->sys_msg(lang('invalid_type'));
                }

                // 处理
                $name = date('Ymd');
                for ($i = 0; $i < 6; $i++) {
                    $name .= chr(mt_rand(97, 122));
                }
                $title_pic_name_arr = explode('.', $_FILES['title_pic']['name']);
                $name .= '.'.end($title_pic_name_arr);
                $target = ROOT_PATH.DATA_DIR.'/afficheimg/'.$name;

                if (BaseHelper::move_upload_file($_FILES['title_pic']['tmp_name'], $target)) {
                    $title_pic = DATA_DIR.'/afficheimg/'.$name;
                }
            } elseif (! empty($_REQUEST['title_url'])) {
                // 来自互联网图片 不可以是服务器地址
                if (strstr($_REQUEST['title_url'], 'http') && ! strstr($_REQUEST['title_url'], $_SERVER['SERVER_NAME'])) {
                    // 取互联网图片至本地
                    $title_pic = $this->get_url_image($_REQUEST['title_url']);
                } else {
                    return $this->sys_msg(lang('web_url_no'));
                }
            }
            unset($name, $target);

            $title_pic = empty($title_pic) ? $_POST['title_img_url'] : $title_pic;

            $start_time = TimeHelper::local_strtotime($_POST['start_time']);
            $end_time = TimeHelper::local_strtotime($_POST['end_time']);

            $tmp_data = json_decode($_POST['topic_data']);
            $data = serialize($tmp_data);
            $base_style = $_POST['base_style'];
            $keywords = $_POST['keywords'];
            $description = $_POST['description'];

            $data_params = [
                'title' => $_POST['topic_name'],
                'start_time' => $start_time,
                'end_time' => $end_time,
                'data' => $data,
                'intro' => $_POST['topic_intro'],
                'template' => $_POST['topic_template_file'],
                'css' => $_POST['topic_css'],
                'topic_img' => $topic_img,
                'title_pic' => $title_pic,
                'base_style' => $base_style,
                'htmls' => $htmls,
                'keywords' => $keywords,
                'description' => $description,
            ];

            if ($is_insert) {
                DB::table('activity_topic')->insert($data_params);
            } else {
                DB::table('activity_topic')
                    ->where('topic_id', $topic_id)
                    ->update($data_params);
            }

            $this->clear_cache_files();

            $links[] = ['href' => 'topic.php', 'text' => lang('back_list')];

            return $this->sys_msg(lang('succed'), 0, $links);
        }

        if ($action === 'get_goods_list') {
            $filters = json_decode($_GET['JSON']);

            $arr = MainHelper::get_goods_list($filters);
            $opt = [];

            foreach ($arr as $key => $val) {
                $opt[] = [
                    'value' => $val['goods_id'],
                    'text' => $val['goods_name'],
                ];
            }

            return $this->make_json_result('', '', $opt);
        }

        if ($action === 'delete') {
            $this->admin_priv('topic_manage');

            if (! empty($_POST['checkboxs'])) {
                DB::table('activity_topic')
                    ->whereIn('topic_id', $_POST['checkboxs'])
                    ->delete();
            } elseif (! empty($_GET['id'])) {
                $_GET['id'] = intval($_GET['id']);
                DB::table('activity_topic')
                    ->where('topic_id', $_GET['id'])
                    ->delete();
            } else {
                exit;
            }

            $this->clear_cache_files();

            if (! empty($_REQUEST['is_ajax'])) {
                $url = 'topic.php?act=query&'.str_replace('act=delete', '', $_SERVER['QUERY_STRING']);

                return response()->redirectTo($url);
            }

            $links[] = ['href' => 'topic.php', 'text' => lang('back_list')];

            return $this->sys_msg(lang('succed'), 0, $links);
        }

        if ($action === 'query') {
            $topic_list = $this->get_topic_list();
            $this->assign('topic_list', $topic_list['item']);
            $this->assign('filter', $topic_list['filter']);
            $this->assign('record_count', $topic_list['record_count']);
            $this->assign('page_count', $topic_list['page_count']);
            $this->assign('use_storage', empty(cfg('use_storage')) ? 0 : 1);

            // 排序标记
            $sort_flag = MainHelper::sort_flag($topic_list['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            $tpl = 'topic_list.htm';

            return $this->make_json_result($this->fetch($tpl), '', ['filter' => $topic_list['filter'], 'page_count' => $topic_list['page_count']]);
        }
    }

    /**
     * 获取专题列表
     */
    private function get_topic_list(): array
    {
        $result = MainHelper::get_filter();
        if ($result === false) {
            // 查询条件
            $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'topic_id' : trim($_REQUEST['sort_by']);
            $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

            $filter['record_count'] = DB::table('activity_topic')->count();

            // 分页大小
            $filter = MainHelper::page_and_size($filter);

            MainHelper::set_filter($filter, '');
        } else {
            $sql = $result['sql'];
            $filter = $result['filter'];
        }

        $res_data = DB::table('activity_topic')
            ->orderBy($filter['sort_by'], $filter['sort_order'])
            ->offset($filter['start'])
            ->limit($filter['page_size'])
            ->get();

        $res = [];

        foreach ($res_data as $topic) {
            $topic = (array) $topic;
            $topic['start_time'] = TimeHelper::local_date('Y-m-d', $topic['start_time']);
            $topic['end_time'] = TimeHelper::local_date('Y-m-d', $topic['end_time']);
            $topic['url'] = ecs()->url().'topic.php?topic_id='.$topic['topic_id'];
            $res[] = $topic;
        }

        return ['item' => $res, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];
    }

    /**
     * 列表链接
     *
     * @param  bool  $is_add  是否添加（插入）
     * @param  string  $text  文字
     * @return array('href' => $href, 'text' => $text)
     */
    private function list_link($is_add = true, $text = '')
    {
        $href = 'topic.php?act=list';
        if (! $is_add) {
            $href .= '&'.MainHelper::list_link_postfix();
        }
        if ($text === '') {
            $text = lang('topic_list');
        }

        return ['href' => $href, 'text' => $text];
    }

    private function get_toppic_width_height()
    {
        $width_height = [];

        $file_path = ROOT_PATH.'themes/'.cfg('template').'/topic';
        if (! file_exists($file_path) || ! is_readable($file_path)) {
            return $width_height;
        }

        $string = file_get_contents($file_path);

        $pattern_width = '/var\s*topic_width\s*=\s*"(\d+)";/';
        $pattern_height = '/var\s*topic_height\s*=\s*"(\d+)";/';
        preg_match($pattern_width, $string, $width);
        preg_match($pattern_height, $string, $height);
        if (isset($width[1])) {
            $width_height['pic']['width'] = $width[1];
        }
        if (isset($height[1])) {
            $width_height['pic']['height'] = $height[1];
        }
        unset($width, $height);

        $pattern_width = '/TitlePicWidth:\s{1}(\d+)/';
        $pattern_height = '/TitlePicHeight:\s{1}(\d+)/';
        preg_match($pattern_width, $string, $width);
        preg_match($pattern_height, $string, $height);
        if (isset($width[1])) {
            $width_height['title_pic']['width'] = $width[1];
        }
        if (isset($height[1])) {
            $width_height['title_pic']['height'] = $height[1];
        }

        return $width_height;
    }

    private function get_url_image($url)
    {
        $url_arr = explode('.', $url);
        $ext = strtolower(end($url_arr));
        if ($ext != 'gif' && $ext != 'jpg' && $ext != 'png' && $ext != 'bmp' && $ext != 'jpeg') {
            return $url;
        }

        $name = date('Ymd');
        for ($i = 0; $i < 6; $i++) {
            $name .= chr(mt_rand(97, 122));
        }
        $name .= '.'.$ext;
        $target = ROOT_PATH.DATA_DIR.'/afficheimg/'.$name;

        $tmp_file = DATA_DIR.'/afficheimg/'.$name;
        $filename = ROOT_PATH.$tmp_file;

        $img = file_get_contents($url);

        $fp = @fopen($filename, 'a');
        fwrite($fp, $img);
        fclose($fp);

        return $tmp_file;
    }
}
