<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\CommonHelper;
use App\Helpers\TimeHelper;
use App\Modules\Admin\Helpers\MainHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TemplateController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        /**
         * 模版列表
         */
        if ($action === 'list') {
            $this->admin_priv('template_select');

            // 获得当前的模版的信息
            $curr_template = cfg('template');
            $curr_style = cfg('stylename');

            // 获得可用的模版
            $available_templates = [];
            $template_dir = @opendir(ROOT_PATH.'themes/');
            while ($file = readdir($template_dir)) {
                if ($file != '.' && $file != '..' && is_dir(ROOT_PATH.'themes/'.$file) && $file != '.svn' && $file != 'index') {
                    $available_templates[] = get_template_info($file);
                }
            }
            @closedir($template_dir);

            // 获得可用的模版的可选风格数组
            $templates_style = [];
            if (count($available_templates) > 0) {
                foreach ($available_templates as $value) {
                    $templates_style[$value['code']] = $this->read_tpl_style($value['code'], 2);
                }
            }

            // 清除不需要的模板设置
            $available_code = [];
            $query = DB::table('template');
            foreach ($available_templates as $tmp) {
                $query->where('theme', '<>', $tmp['code']);
                $available_code[] = $tmp['code'];
            }
            $tmp_bak_dir = @opendir(ROOT_PATH.'temp/backup/library/');
            while ($file = readdir($tmp_bak_dir)) {
                if ($file != '.' && $file != '..' && $file != '.svn' && $file != 'index.htm' && is_file(ROOT_PATH.'temp/backup/library/'.$file) === true) {
                    $code = substr($file, 0, strpos($file, '-'));
                    if (! in_array($code, $available_code)) {
                        @unlink(ROOT_PATH.'temp/backup/library/'.$file);
                    }
                }
            }

            $query->delete();

            $this->assign('ur_here', lang('template_manage'));
            $this->assign('curr_tpl_style', $curr_style);
            $this->assign('template_style', $templates_style);
            $this->assign('curr_template', get_template_info($curr_template, $curr_style));
            $this->assign('available_templates', $available_templates);

            return $this->display('templates_list');
        }

        /**
         * 设置模板的内容
         */
        if ($action === 'setup') {
            $this->admin_priv('template_setup');

            $template_theme = cfg('template');
            $curr_template = empty($_REQUEST['template_file']) ? 'index' : $_REQUEST['template_file'];

            $temp_options = [];
            $temp_regions = get_template_region($template_theme, $curr_template.'', false);
            $temp_libs = get_template_region($template_theme, $curr_template.'', true);

            $editable_libs = get_editable_libs($curr_template, $page_libs[$curr_template]);

            if (empty($editable_libs)) {
                // 获取数据库中数据，并跟模板中数据核对,并设置动态内容
                // 固定内容
                foreach ($page_libs[$curr_template] as $val => $number_enabled) {
                    $lib = basename(strtolower(substr($val, 0, strpos($val, '.'))));
                    if (! in_array($lib, $GLOBALS['dyna_libs'])) {
                        // 先排除动态内容
                        $temp_options[$lib] = get_setted($val, $temp_libs);
                        $temp_options[$lib]['desc'] = lang('template_libs')[$lib];
                        $temp_options[$lib]['library'] = $val;
                        $temp_options[$lib]['number_enabled'] = $number_enabled > 0 ? 1 : 0;
                        $temp_options[$lib]['number'] = $number_enabled;
                    }
                }
            } else {
                // 获取数据库中数据，并跟模板中数据核对,并设置动态内容
                // 固定内容
                foreach ($page_libs[$curr_template] as $val => $number_enabled) {
                    $lib = basename(strtolower(substr($val, 0, strpos($val, '.'))));
                    if (! in_array($lib, $GLOBALS['dyna_libs'])) {
                        // 先排除动态内容
                        $temp_options[$lib] = get_setted($val, $temp_libs);
                        $temp_options[$lib]['desc'] = lang('template_libs')[$lib];
                        $temp_options[$lib]['library'] = $val;
                        $temp_options[$lib]['number_enabled'] = $number_enabled > 0 ? 1 : 0;
                        $temp_options[$lib]['number'] = $number_enabled;

                        if (! in_array($lib, $editable_libs)) {
                            $temp_options[$lib]['editable'] = 1;
                        }
                    }
                }
            }

            // 动态内容
            $cate_goods = [];
            $brand_goods = [];
            $cat_articles = [];
            $ad_positions = [];

            $rc = DB::table('template')
                ->select('region', 'library', 'sort_order', 'id', 'number', 'type')
                ->where('theme', $template_theme)
                ->where('filename', $curr_template)
                ->where('remarks', '')
                ->orderBy('region')
                ->orderBy('sort_order')
                ->get();
            $db_dyna_libs = [];
            foreach ($rc as $row) {
                $row = (array) $row;
                if ($row['type'] > 0) {
                    // 动态内容
                    $db_dyna_libs[$row['region']][$row['library']][] = ['id' => $row['id'], 'number' => $row['number'], 'type' => $row['type']];
                } else {
                    // 固定内容
                    $lib = basename(strtolower(substr($row['library'], 0, strpos($row['library'], '.'))));
                    if (isset($lib)) {
                        $temp_options[$lib]['number'] = $row['number'];
                    }
                }
            }

            foreach ($temp_libs as $val) {
                // 对动态内容赋值
                if ($val['lib'] === 'cat_goods') {
                    // 分类下的商品
                    if (isset($db_dyna_libs[$val['region']][$val['library']]) && ($row = array_shift($db_dyna_libs[$val['region']][$val['library']]))) {
                        $cate_goods[] = ['region' => $val['region'], 'sort_order' => $val['sort_order'], 'number' => $row['number'], 'cats' => CommonHelper::cat_list(0, $row['id'])];
                    } else {
                        $cate_goods[] = ['region' => $val['region'], 'sort_order' => $val['sort_order'], 'number' => 0, 'cats' => CommonHelper::cat_list(0)];
                    }
                } elseif ($val['lib'] === 'brand_goods') {
                    // 品牌下的商品
                    if (isset($db_dyna_libs[$val['region']][$val['library']]) && ($row = array_shift($db_dyna_libs[$val['region']][$val['library']]))) {
                        $brand_goods[] = ['region' => $val['region'], 'sort_order' => $val['sort_order'], 'number' => $row['number'], 'brand' => $row['id']];
                    } else {
                        $brand_goods[] = ['region' => $val['region'], 'sort_order' => $val['sort_order'], 'number' => 0, 'brand' => 0];
                    }
                } // 文章列表
                elseif ($val['lib'] === 'cat_articles') {
                    if (isset($db_dyna_libs[$val['region']][$val['library']]) && ($row = array_shift($db_dyna_libs[$val['region']][$val['library']]))) {
                        $cat_articles[] = ['region' => $val['region'], 'sort_order' => $val['sort_order'], 'number' => $row['number'], 'cat' => article_cat_list(0, $row['id'])];
                    } else {
                        $cat_articles[] = ['region' => $val['region'], 'sort_order' => $val['sort_order'], 'number' => 0, 'cat' => article_cat_list(0)];
                    }
                } // 广告位
                elseif ($val['lib'] === 'ad_position') {
                    if (isset($db_dyna_libs[$val['region']][$val['library']]) && ($row = array_shift($db_dyna_libs[$val['region']][$val['library']]))) {
                        $ad_positions[] = ['region' => $val['region'], 'sort_order' => $val['sort_order'], 'number' => $row['number'], 'ad_pos' => $row['id']];
                    } else {
                        $ad_positions[] = ['region' => $val['region'], 'sort_order' => $val['sort_order'], 'number' => 0, 'ad_pos' => 0];
                    }
                }
            }

            $this->assign('ur_here', lang('03_template_setup'));
            $this->assign('curr_template_file', $curr_template);
            $this->assign('temp_options', $temp_options);
            $this->assign('temp_regions', $temp_regions);
            $this->assign('cate_goods', $cate_goods);
            $this->assign('brand_goods', $brand_goods);
            $this->assign('cat_articles', $cat_articles);
            $this->assign('ad_positions', $ad_positions);
            $this->assign('arr_cates', CommonHelper::cat_list(0, 0, true));
            $this->assign('arr_brands', CommonHelper::get_brand_list());
            $this->assign('arr_article_cats', article_cat_list(0, 0, true));
            $this->assign('arr_ad_positions', MainHelper::get_position_list());

            return $this->display('template_setup');
        }

        /**
         * 提交模板内容设置
         */
        if ($action === 'setting') {
            $this->admin_priv('template_setup');

            $curr_template = cfg('template');
            DB::table('template')
                ->where('remarks', '')
                ->where('filename', $_POST['template_file'])
                ->where('theme', $curr_template)
                ->delete();

            // 先处理固定内容
            foreach ($_POST['regions'] as $key => $val) {
                $number = isset($_POST['number'][$key]) ? intval($_POST['number'][$key]) : 0;
                if (! in_array($key, $GLOBALS['dyna_libs']) and (isset($_POST['display'][$key]) and $_POST['display'][$key] === 1 or $number > 0)) {
                    DB::table('template')->insert([
                        'theme' => $curr_template,
                        'filename' => $_POST['template_file'],
                        'region' => $val,
                        'library' => $_POST['map'][$key],
                        'sort_order' => $_POST['sort_order'][$key] ?? 0,
                        'number' => $number,
                    ]);
                }
            }

            // 分类的商品
            if (isset($_POST['regions']['cat_goods'])) {
                foreach ($_POST['regions']['cat_goods'] as $key => $val) {
                    if ($_POST['categories']['cat_goods'][$key] != '' && intval($_POST['categories']['cat_goods'][$key]) > 0) {
                        DB::table('template')->insert([
                            'theme' => $curr_template,
                            'filename' => $_POST['template_file'],
                            'region' => $val,
                            'library' => '/library/cat_goods',
                            'sort_order' => $_POST['sort_order']['cat_goods'][$key] ?? 0,
                            'type' => 1,
                            'id' => $_POST['categories']['cat_goods'][$key],
                            'number' => $_POST['number']['cat_goods'][$key] ?? 0,
                        ]);
                    }
                }
            }

            // 品牌的商品
            if (isset($_POST['regions']['brand_goods'])) {
                foreach ($_POST['regions']['brand_goods'] as $key => $val) {
                    if ($_POST['brands']['brand_goods'][$key] != '' && intval($_POST['brands']['brand_goods'][$key]) > 0) {
                        DB::table('template')->insert([
                            'theme' => $curr_template,
                            'filename' => $_POST['template_file'],
                            'region' => $val,
                            'library' => '/library/brand_goods',
                            'sort_order' => $_POST['sort_order']['brand_goods'][$key] ?? 0,
                            'type' => 2,
                            'id' => $_POST['brands']['brand_goods'][$key],
                            'number' => $_POST['number']['brand_goods'][$key] ?? 0,
                        ]);
                    }
                }
            }

            // 文章列表
            if (isset($_POST['regions']['cat_articles'])) {
                foreach ($_POST['regions']['cat_articles'] as $key => $val) {
                    if ($_POST['article_cat']['cat_articles'][$key] != '' && intval($_POST['article_cat']['cat_articles'][$key]) > 0) {
                        DB::table('template')->insert([
                            'theme' => $curr_template,
                            'filename' => $_POST['template_file'],
                            'region' => $val,
                            'library' => '/library/cat_articles',
                            'sort_order' => $_POST['sort_order']['cat_articles'][$key] ?? 0,
                            'type' => 3,
                            'id' => $_POST['article_cat']['cat_articles'][$key],
                            'number' => $_POST['number']['cat_articles'][$key] ?? 0,
                        ]);
                    }
                }
            }

            // 广告位
            if (isset($_POST['regions']['ad_position'])) {
                foreach ($_POST['regions']['ad_position'] as $key => $val) {
                    if ($_POST['ad_position'][$key] != '' && intval($_POST['ad_position'][$key]) > 0) {
                        DB::table('template')->insert([
                            'theme' => $curr_template,
                            'filename' => $_POST['template_file'],
                            'region' => $val,
                            'library' => '/library/ad_position',
                            'sort_order' => $_POST['sort_order']['ad_position'][$key] ?? 0,
                            'type' => 4,
                            'id' => $_POST['ad_position'][$key],
                            'number' => $_POST['number']['ad_position'][$key] ?? 0,
                        ]);
                    }
                }
            }

            // 对提交内容进行处理
            $post_regions = [];
            foreach ($_POST['regions'] as $key => $val) {
                switch ($key) {
                    case 'cat_goods':
                        foreach ($val as $k => $v) {
                            if (intval($_POST['categories']['cat_goods'][$k]) > 0) {
                                $post_regions[] = [
                                    'region' => $v,
                                    'type' => 1,
                                    'number' => $_POST['number']['cat_goods'][$k],
                                    'library' => '/library/'.$key.'',
                                    'sort_order' => $_POST['sort_order']['cat_goods'][$k],
                                    'id' => $_POST['categories']['cat_goods'][$k],
                                ];
                            }
                        }
                        break;
                    case 'brand_goods':
                        foreach ($val as $k => $v) {
                            if (intval($_POST['brands']['brand_goods'][$k]) > 0) {
                                $post_regions[] = [
                                    'region' => $v,
                                    'type' => 2,
                                    'number' => $_POST['number']['brand_goods'][$k],
                                    'library' => '/library/'.$key.'',
                                    'sort_order' => $_POST['sort_order']['brand_goods'][$k],
                                    'id' => $_POST['brands']['brand_goods'][$k],
                                ];
                            }
                        }
                        break;
                    case 'cat_articles':
                        foreach ($val as $k => $v) {
                            if (intval($_POST['article_cat']['cat_articles'][$k]) > 0) {
                                $post_regions[] = [
                                    'region' => $v,
                                    'type' => 3,
                                    'number' => $_POST['number']['cat_articles'][$k],
                                    'library' => '/library/'.$key.'',
                                    'sort_order' => $_POST['sort_order']['cat_articles'][$k],
                                    'id' => $_POST['article_cat']['cat_articles'][$k],
                                ];
                            }
                        }
                        break;
                    case 'ad_position':
                        foreach ($val as $k => $v) {
                            if (intval($_POST['ad_position'][$k]) > 0) {
                                $post_regions[] = [
                                    'region' => $v,
                                    'type' => 4,
                                    'number' => $_POST['number']['ad_position'][$k],
                                    'library' => '/library/'.$key.'',
                                    'sort_order' => $_POST['sort_order']['ad_position'][$k],
                                    'id' => $_POST['ad_position'][$k],
                                ];
                            }
                        }
                        break;
                    default:
                        if (! empty($_POST['display'][$key])) {
                            $post_regions[] = [
                                'region' => $val,
                                'type' => 0,
                                'number' => 0,
                                'library' => $_POST['map'][$key],
                                'sort_order' => $_POST['sort_order'][$key],
                                'id' => 0,
                            ];
                        }

                }
            }

            // 排序
            usort($post_regions, 'array_sort');

            // 修改模板文件
            $template_file = '../themes/'.$curr_template.'/'.$_POST['template_file'].'';
            $template_content = file_get_contents($template_file);
            $template_content = str_replace("\xEF\xBB\xBF", '', $template_content);
            $org_regions = get_template_region($curr_template, $_POST['template_file'].'', false);

            $region_content = '';
            $pattern = '/(<!--\\s*TemplateBeginEditable\\sname="%s"\\s*-->)(.*?)(<!--\\s*TemplateEndEditable\\s*-->)/s';
            $replacement = "\\1\n%s\\3";
            $lib_template = "<!-- #BeginLibraryItem \"%s\" -->\n%s\n <!-- #EndLibraryItem -->\n";

            foreach ($org_regions as $region) {
                $region_content = ''; // 获取当前区域内容
                foreach ($post_regions as $lib) {
                    if ($lib['region'] === $region) {
                        if (! file_exists('../themes/'.$curr_template.$lib['library'])) {
                            continue;
                        }
                        $lib_content = file_get_contents('../themes/'.$curr_template.$lib['library']);
                        $lib_content = preg_replace('/<meta\\shttp-equiv=["|\']Content-Type["|\']\\scontent=["|\']text\/html;\\scharset=.*["|\']>/i', '', $lib_content);
                        $lib_content = str_replace("\xEF\xBB\xBF", '', $lib_content);
                        $region_content .= sprintf($lib_template, $lib['library'], $lib_content);
                    }
                }

                // 替换原来区域内容
                $template_content = preg_replace(sprintf($pattern, $region), sprintf($replacement, $region_content), $template_content);
            }

            if (file_put_contents($template_file, $template_content)) {
                $this->clear_cache_files(); // 清除对应的编译文件
                $lnk[] = ['text' => lang('go_back'), 'href' => 'template.php?act=setup&template_file='.$_POST['template_file']];

                return $this->sys_msg(lang('setup_success'), 0, $lnk);
            } else {
                return $this->sys_msg(sprintf(lang('modify_dwt_failed'), 'themes/'.$curr_template.'/'.$_POST['template_file'].''), 1, null, false);
            }
        }

        /**
         * 管理库项目
         */
        if ($action === 'library') {
            $this->admin_priv('library_manage');

            $curr_template = cfg('template');
            $arr_library = [];
            $library_path = '../themes/'.$curr_template.'/library';
            $library_dir = @opendir($library_path);
            $curr_library = '';

            while ($file = @readdir($library_dir)) {
                if (substr($file, -3) === 'lbi') {
                    $filename = substr($file, 0, -4);
                    $arr_library[$filename] = $file.' - '.@lang('template_libs')[$filename];

                    if ($curr_library === '') {
                        $curr_library = $filename;
                    }
                }
            }

            ksort($arr_library);

            @closedir($library_dir);

            $lib = $this->load_library($curr_template, $curr_library);

            $this->assign('ur_here', lang('04_template_library'));
            $this->assign('curr_library', $curr_library);
            $this->assign('libraries', $arr_library);
            $this->assign('library_html', $lib['html']);

            return $this->display('template_library');
        }

        /**
         * 安装模版
         */
        if ($action === 'install') {
            $this->check_authz_json('backup_setting');

            $tpl_name = trim($_GET['tpl_name']);
            $tpl_fg = 0;
            $tpl_fg = trim($_GET['tpl_fg']);

            $step_one = DB::table('shop_config')->where('code', 'template')->update(['value' => $tpl_name]);
            $step_two = DB::table('shop_config')->where('code', 'stylename')->update(['value' => $tpl_fg]);

            if ($step_one && $step_two) {
                CommonHelper::clear_all_files(); // 清除模板编译文件

                return $this->make_json_result(read_style_and_tpl($tpl_name, $tpl_fg), lang('install_template_success'));
            } else {
                return $this->make_json_error(lang('install_template_failed'));
            }
        }

        /**
         * 备份模版
         */
        if ($action === 'backup') {
            $this->check_authz_json('backup_setting');
            $tpl = cfg('template');
            // $tpl = trim($_REQUEST['tpl_name']);

            $filename = '../temp/backup/'.$tpl.'_'.date('Ymd').'.zip';

            $zip = new PHPZip;
            $done = $zip->zip('../themes/'.$tpl.'/', $filename);

            if ($done) {
                return $this->make_json_result($filename);
            } else {
                return $this->make_json_error(lang('backup_failed'));
            }
        }

        /**
         * 载入指定库项目的内容
         */
        if ($action === 'load_library') {
            $library = $this->load_library(cfg('template'), trim($_GET['lib']));
            $message = ($library['mark'] & 7) ? '' : lang('library_not_written');

            return $this->make_json_result($library['html'], $message);
        }

        /**
         * 更新库项目内容
         */
        if ($action === 'update_library') {
            $this->check_authz_json('library_manage');

            $html = stripslashes(BaseHelper::json_str_iconv($_POST['html']));
            $lib_file = '../themes/'.cfg('template').'/library/'.$_POST['lib'].'';
            $lib_file = str_replace('0xa', '', $lib_file); // 过滤 0xa 非法字符

            $org_html = str_replace("\xEF\xBB\xBF", '', file_get_contents($lib_file));

            if (@file_exists($lib_file) === true && @file_put_contents($lib_file, $html)) {
                @file_put_contents('../temp/backup/library/'.cfg('template').'-'.$_POST['lib'].'', $org_html);

                return $this->make_json_result('', lang('update_lib_success'));
            } else {
                return $this->make_json_error(sprintf(lang('update_lib_failed'), 'themes/'.cfg('template').'/library'));
            }
        }

        /**
         * 还原库项目
         */
        if ($action === 'restore_library') {
            $this->admin_priv('backup_setting');
            $lib_name = trim($_GET['lib']);
            $lib_file = '../themes/'.cfg('template').'/library/'.$lib_name.'';
            $lib_file = str_replace('0xa', '', $lib_file); // 过滤 0xa 非法字符
            $lib_backup = '../temp/backup/library/'.cfg('template').'-'.$lib_name.'';
            $lib_backup = str_replace('0xa', '', $lib_backup); // 过滤 0xa 非法字符

            if (file_exists($lib_backup) && filemtime($lib_backup) >= filemtime($lib_file)) {
                return $this->make_json_result(str_replace("\xEF\xBB\xBF", '', file_get_contents($lib_backup)));
            } else {
                return $this->make_json_result(str_replace("\xEF\xBB\xBF", '', file_get_contents($lib_file)));
            }
        }

        /**
         * 布局备份
         */
        if ($action === 'backup_setting') {
            $this->admin_priv('backup_setting');

            $col = DB::table('template')
                ->where('theme', cfg('template'))
                ->where('remarks', '>', '')
                ->distinct()
                ->pluck('remarks')
                ->all();
            $remarks = [];
            foreach ($col as $val) {
                $remarks[] = ['content' => $val, 'url' => urlencode($val)];
            }

            $col = DB::table('template')
                ->where('theme', cfg('template'))
                ->where('remarks', '')
                ->distinct()
                ->pluck('filename')
                ->all();
            $files = [];
            foreach ($col as $val) {
                $files[$val] = lang('template_files')[$val];
            }

            $this->assign('ur_here', lang('backup_setting'));
            $this->assign('list', $remarks);
            $this->assign('files', $files);

            return $this->display('templates_backup');
        }

        if ($action === 'act_backup_setting') {
            $remarks = empty($_POST['remarks']) ? TimeHelper::local_date(cfg('time_format')) : trim($_POST['remarks']);

            if (empty($_POST['files'])) {
                $files = [];
            } else {
                $files = $_POST['files'];
            }

            if (DB::table('template')->where('remarks', $remarks)->where('theme', cfg('template'))->count() > 0) {
                return $this->sys_msg(sprintf(lang('remarks_exist'), $remarks), 1);
            }

            // INSERT INTO ... SELECT
            $templates = DB::table('template')
                ->select('filename', 'region', 'library', 'sort_order', 'id', 'number', 'type', 'theme')
                ->where('remarks', '')
                ->where('theme', cfg('template'))
                ->whereIn('filename', $files)
                ->get();

            foreach ($templates as $template) {
                $templateData = (array) $template;
                $templateData['remarks'] = $remarks;
                DB::table('template')->insert($templateData);
            }

            return $this->sys_msg(lang('backup_template_ok'), 0, [['text' => lang('backup_setting'), 'href' => 'template.php?act=backup_setting']]);
        }

        if ($action === 'del_backup') {
            $remarks = empty($_GET['remarks']) ? '' : trim($_GET['remarks']);
            if ($remarks) {
                DB::table('template')
                    ->where('remarks', $remarks)
                    ->where('theme', cfg('template'))
                    ->delete();
            }

            return $this->sys_msg(lang('del_backup_ok'), 0, [['text' => lang('backup_setting'), 'href' => 'template.php?act=backup_setting']]);
        }

        if ($action === 'restore_backup') {
            $remarks = empty($_GET['remarks']) ? '' : trim($_GET['remarks']);
            if ($remarks) {
                $arr = DB::table('template')
                    ->select('filename', 'region', 'library', 'sort_order')
                    ->where('remarks', $remarks)
                    ->where('theme', cfg('template'))
                    ->orderBy('filename')
                    ->orderBy('region')
                    ->orderBy('sort_order')
                    ->get()
                    ->map(fn ($r) => (array) $r)
                    ->toArray();
                if ($arr) {
                    $data = [];
                    foreach ($arr as $val) {
                        $lib_content = file_get_contents(ROOT_PATH.'themes/'.cfg('template').$val['library']);
                        // 去除lib头部
                        $lib_content = preg_replace('/<meta\\shttp-equiv=["|\']Content-Type["|\']\\scontent=["|\']text\/html;\\scharset=utf-8"|\']>/i', '', $lib_content);
                        // 去除utf bom
                        $lib_content = str_replace("\xEF\xBB\xBF", '', $lib_content);
                        // 加入dw 标识
                        $lib_content = '<!-- #BeginLibraryItem "'.$val['library']."\" -->\r\n".$lib_content."\r\n".'<!-- #EndLibraryItem -->';
                        if (isset($data[$val['filename']][$val['region']])) {
                            $data[$val['filename']][$val['region']] .= $lib_content;
                        } else {
                            $data[$val['filename']][$val['region']] = $lib_content;
                        }
                    }

                    foreach ($data as $file => $regions) {
                        $pattern = '/(?:<!--\\s*TemplateBeginEditable\\sname="('.implode('|', array_keys($regions)).')"\\s*-->)(?:.*?)(?:<!--\\s*TemplateEndEditable\\s*-->)/s';
                        $temple_file = ROOT_PATH.'themes/'.cfg('template').'/'.$file.'';
                        $template_content = file_get_contents($temple_file);
                        $match = [];
                        $template_content = preg_replace_callback($pattern, function ($r) use ($regions) {
                            return '<!-- TemplateBeginEditable name="'.$r[1]."\" -->\r\n".$regions[$r[1]]."\r\n<!-- TemplateEndEditable -->";
                        }, $template_content);
                        file_put_contents($temple_file, $template_content);
                    }

                    // 文件修改成功后，恢复数据库
                    DB::table('template')
                        ->where('remarks', '')
                        ->where('theme', cfg('template'))
                        ->whereIn('filename', array_keys($data))
                        ->delete();

                    // 从备份恢复数据
                    $backupTemplates = DB::table('template')
                        ->where('remarks', $remarks)
                        ->where('theme', cfg('template'))
                        ->get();

                    foreach ($backupTemplates as $template) {
                        $templateData = (array) $template;
                        $templateData['remarks'] = '';
                        unset($templateData['id']); // 让数据库自动生成新ID
                        DB::table('template')->insert($templateData);
                    }
                }
            }

            return $this->sys_msg(lang('restore_backup_ok'), 0, [['text' => lang('backup_setting'), 'href' => 'template.php?act=backup_setting']]);
        }
    }

    private function array_sort($a, $b)
    {
        $cmp = strcmp($a['region'], $b['region']);

        if ($cmp === 0) {
            return ($a['sort_order'] < $b['sort_order']) ? -1 : 1;
        } else {
            return ($cmp > 0) ? -1 : 1;
        }
    }

    /**
     * 载入库项目内容
     *
     * @param  string  $curr_template  模版名称
     * @param  string  $lib_name  库项目名称
     * @return array
     */
    private function load_library($curr_template, $lib_name)
    {
        $lib_name = str_replace('0xa', '', $lib_name); // 过滤 0xa 非法字符

        $lib_file = '../themes/'.$curr_template.'/library/'.$lib_name.'';
        $arr['mark'] = file_mode_info($lib_file);
        $arr['html'] = str_replace("\xEF\xBB\xBF", '', file_get_contents($lib_file));

        return $arr;
    }

    /**
     * 读取模板风格列表
     *
     * @param  string  $tpl_name  模版名称
     * @param  int  $flag  1，AJAX数据；2，Array
     */
    private function read_tpl_style($tpl_name, $flag = 1)
    {
        if (empty($tpl_name) && $flag === 1) {
            return 0;
        }

        // 获得可用的模版
        $temp = '';
        $start = 0;
        $available_templates = [];
        $dir = ROOT_PATH.'themes/'.$tpl_name.'/';
        $tpl_style_dir = @opendir($dir);
        while ($file = readdir($tpl_style_dir)) {
            if ($file != '.' && $file != '..' && is_file($dir.$file) && $file != '.svn' && $file != 'index') {
                if (preg_match('/^(style|style_)(.*)*/i', $file)) { // 取模板风格缩略图
                    $start = strpos($file, '.');
                    $temp = substr($file, 0, $start);
                    $temp = explode('_', $temp);
                    if (count($temp) === 2) {
                        $available_templates[] = $temp[1];
                    }
                }
            }
        }
        @closedir($tpl_style_dir);

        if ($flag === 1) {
            $ec = '<table border="0" width="100%" cellpadding="0" cellspacing="0" class="colortable" onMouseOver="javascript:onSOver(0, this);" onMouseOut="onSOut(this);" onclick="javascript:setupTemplateFG(0);"  bgcolor="#FFFFFF"><tr><td>&nbsp;</td></tr></table>';
            if (count($available_templates) > 0) {
                foreach ($available_templates as $value) {
                    $tpl_info = get_template_info($tpl_name, $value);

                    $ec .= '<table border="0" width="100%" cellpadding="0" cellspacing="0" class="colortable" onMouseOver="javascript:onSOver(\''.$value.'\', this);" onMouseOut="onSOut(this);" onclick="javascript:setupTemplateFG(\''.$value.'\');"  bgcolor="'.$tpl_info['type'].'"><tr><td>&nbsp;</td></tr></table>';

                    unset($tpl_info);
                }
            } else {
                $ec = '0';
            }

            return $ec;
        } elseif ($flag === 2) {
            $templates_temp = [''];
            if (count($available_templates) > 0) {
                foreach ($available_templates as $value) {
                    $templates_temp[] = $value;
                }
            }

            return $templates_temp;
        }
    }

    /**
     * 读取当前风格信息与当前模板风格列表
     *
     * @param  string  $tpl_name  模版名称
     * @param  string  $tpl_style  模版风格名
     */
    private function read_style_and_tpl($tpl_name, $tpl_style)
    {
        $style_info = [];
        $style_info = get_template_info($tpl_name, $tpl_style);

        $tpl_style_info = [];
        $tpl_style_info = $this->read_tpl_style($tpl_name, 2);
        $tpl_style_list = '';
        if (count($tpl_style_info) > 1) {
            foreach ($tpl_style_info as $value) {
                $tpl_style_list .= '<span style="cursor:pointer;" onMouseOver="javascript:onSOver(\'screenshot\', \''.$value.'\', this);" onMouseOut="onSOut(\'screenshot\', this, \''.$style_info['screenshot'].'\');" onclick="javascript:setupTemplateFG(\''.$tpl_name.'\', \''.$value.'\', \'\');" id="templateType_'.$value.'"><img src="../themes/'.$tpl_name.'/images/type'.$value.'_';

                if ($value === $tpl_style) {
                    $tpl_style_list .= '1';
                } else {
                    $tpl_style_list .= '0';
                }
                $tpl_style_list .= '.gif" border="0"></span>&nbsp;';
            }
        }
        $style_info['tpl_style'] = $tpl_style_list;

        return $style_info;
    }
}
