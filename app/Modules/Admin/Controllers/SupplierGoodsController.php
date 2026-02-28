<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\CommonHelper;
use App\Helpers\TimeHelper;
use App\Libraries\Image;
use App\Modules\Admin\Helpers\GoodsHelper;
use App\Modules\Admin\Helpers\MainHelper;
use App\Modules\Admin\Libraries\Exchange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SupplierGoodsController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        $image = new Image(cfg('bgcolor'));
        $exc = new Exchange(ecs()->table('goods'), DB::connection()->getPdo(), 'goods_id', 'goods_name');

        /**
         * 商品列表，商品回收站
         */
        if ($action === 'list' || $action === 'trash') {
            $this->admin_priv('goods_manage');

            $cat_id = empty($_REQUEST['cat_id']) ? 0 : intval($_REQUEST['cat_id']);
            $code = empty($_REQUEST['extension_code']) ? '' : trim($_REQUEST['extension_code']);

            $handler_list = [];
            $handler_list['virtual_card'][] = ['url' => 'virtual_card.php?act=card', 'title' => lang('card'), 'img' => 'icon_send_bonus.gif'];
            $handler_list['virtual_card'][] = ['url' => 'virtual_card.php?act=replenish', 'title' => lang('replenish'), 'img' => 'icon_add.gif'];
            $handler_list['virtual_card'][] = ['url' => 'virtual_card.php?act=batch_card_add', 'title' => lang('batch_card_add'), 'img' => 'icon_output.gif'];

            if ($action === 'list' && isset($handler_list[$code])) {
                $this->assign('add_handler', $handler_list[$code]);
            }

            $goods_ur = ['' => lang('01_goods_list'), 'virtual_card' => lang('50_virtual_card_list')];
            $ur_here = ($action === 'list') ? $goods_ur[$code] : lang('11_goods_trash');
            $this->assign('ur_here', $ur_here);

            $action_link = ($action === 'list') ? $this->add_link($code) : ['href' => 'goods.php?act=list', 'text' => lang('01_goods_list')];
            $this->assign('action_link', $action_link);
            $this->assign('code', $code);
            $this->assign('cat_list', CommonHelper::cat_list(0, $cat_id));
            $this->assign('brand_list', CommonHelper::get_brand_list());
            $this->assign('intro_list', GoodsHelper::get_intro_list());

            $this->assign('list_type', $action === 'list' ? 'goods' : 'trash');
            $this->assign('use_storage', empty(cfg('use_storage')) ? 0 : 1);

            $goods_list = GoodsHelper::goods_list($action === 'list' ? 0 : 1, ($action === 'list') ? (($code === '') ? 1 : 0) : -1);
            $this->assign('goods_list', $goods_list['goods']);
            $this->assign('filter', $goods_list['filter']);
            $this->assign('record_count', $goods_list['record_count']);
            $this->assign('page_count', $goods_list['page_count']);
            $this->assign('full_page', 1);

            // 排序标记
            $sort_flag = MainHelper::sort_flag($goods_list['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            $htm_file = ($action === 'list') ?
                'goods_list.htm' : (($action === 'trash') ? 'goods_trash.htm' : 'group_list');

            return $this->display($htm_file);
        }

        /**
         * 添加新商品 编辑商品
         */
        if ($action === 'add' || $action === 'edit' || $action === 'copy') {
            // include_once ROOT_PATH.'includes/fckeditor/fckeditor.php'; // 包含 html editor 类文件

            $is_add = $action === 'add'; // 添加还是编辑的标识
            $is_copy = $action === 'copy'; // 是否复制
            $code = empty($_REQUEST['extension_code']) ? '' : trim($_REQUEST['extension_code']);
            if ($code === 'virual_card') {
                $this->admin_priv('virualcard'); // 检查权限
            } else {
                $this->admin_priv('goods_manage'); // 检查权限
            }

            // 如果是安全模式，检查目录是否存在
            if (ini_get('safe_mode') === 1 && (! file_exists('../'.IMAGE_DIR.'/'.date('Ym')) || ! is_dir('../'.IMAGE_DIR.'/'.date('Ym')))) {
                if (@! mkdir('../'.IMAGE_DIR.'/'.date('Ym'), 0777)) {
                    $warning = sprintf(lang('safe_mode_warning'), '../'.IMAGE_DIR.'/'.date('Ym'));
                    $this->assign('warning', $warning);
                }
            } // 如果目录存在但不可写，提示用户
            elseif (file_exists('../'.IMAGE_DIR.'/'.date('Ym')) && file_mode_info('../'.IMAGE_DIR.'/'.date('Ym')) < 2) {
                $warning = sprintf(lang('not_writable_warning'), '../'.IMAGE_DIR.'/'.date('Ym'));
                $this->assign('warning', $warning);
            }

            // 取得商品信息
            if ($is_add) {
                // 默认值
                $last_choose = [0, 0];
                $ecscpCookie = Cookie::get('ECSCP');
                $lastChoose = is_array($ecscpCookie) ? ($ecscpCookie['last_choose'] ?? '') : '';
                if (! empty($lastChoose)) {
                    $last_choose = explode('|', $lastChoose);
                }
                $goods = [
                    'goods_id' => 0,
                    'goods_desc' => '',
                    'cat_id' => $last_choose[0],
                    'brand_id' => $last_choose[1],
                    'is_on_sale' => '1',
                    'is_alone_sale' => '1',
                    'other_cat' => [], // 扩展分类
                    'goods_type' => 0,       // 商品类型
                    'shop_price' => 0,
                    'promote_price' => 0,
                    'market_price' => 0,
                    'integral' => 0,
                    'goods_number' => cfg('default_storage'),
                    'warn_number' => 1,
                    'promote_start_date' => TimeHelper::local_date('Y-m-d'),
                    'promote_end_date' => TimeHelper::local_date('Y-m-d', TimeHelper::local_strtotime('+1 month')),
                    'goods_weight' => 0,
                    'give_integral' => -1,
                    'rank_integral' => -1,
                ];

                if ($code != '') {
                    $goods['goods_number'] = 0;
                }

                // 关联商品
                $link_goods_list = [];
                DB::table('goods_link_goods')
                    ->where(function ($q) {
                        $q->where('goods_id', 0)->orWhere('link_goods_id', 0);
                    })
                    ->where('admin_id', Session::get('admin_id'))
                    ->delete();

                // 组合商品
                $group_goods_list = [];
                DB::table('activity_group')->where('parent_id', 0)->where('admin_id', Session::get('admin_id'))->delete();

                // 关联文章
                $goods_article_list = [];
                DB::table('goods_article')->where('goods_id', 0)->where('admin_id', Session::get('admin_id'))->delete();

                // 属性
                DB::table('goods_attr')->where('goods_id', 0)->delete();

                // 图片列表
                $img_list = [];
            } else {
                // 商品信息
                $goods = (array) DB::table('goods')->where('goods_id', $_REQUEST['goods_id'])->first();

                // 虚拟卡商品复制时, 将其库存置为0
                if ($is_copy && $code != '') {
                    $goods['goods_number'] = 0;
                }

                if (empty($goods) === true) {
                    // 默认值
                    $goods = [
                        'goods_id' => 0,
                        'goods_desc' => '',
                        'cat_id' => 0,
                        'is_on_sale' => '1',
                        'is_alone_sale' => '1',
                        'other_cat' => [], // 扩展分类
                        'goods_type' => 0,       // 商品类型
                        'shop_price' => 0,
                        'promote_price' => 0,
                        'market_price' => 0,
                        'integral' => 0,
                        'goods_number' => 1,
                        'warn_number' => 1,
                        'promote_start_date' => TimeHelper::local_date('Y-m-d'),
                        'promote_end_date' => TimeHelper::local_date('Y-m-d', gmstr2tome('+1 month')),
                        'goods_weight' => 0,
                        'give_integral' => -1,
                        'rank_integral' => -1,
                    ];
                }

                // 根据商品重量的单位重新计算
                if ($goods['goods_weight'] > 0) {
                    $goods['goods_weight_by_unit'] = ($goods['goods_weight'] >= 1) ? $goods['goods_weight'] : ($goods['goods_weight'] / 0.001);
                }

                if (! empty($goods['goods_brief'])) {
                    $goods['goods_brief'] = $goods['goods_brief'];
                }
                if (! empty($goods['keywords'])) {
                    $goods['keywords'] = $goods['keywords'];
                }

                // 如果不是促销，处理促销日期
                if (isset($goods['is_promote']) && $goods['is_promote'] === '0') {
                    unset($goods['promote_start_date']);
                    unset($goods['promote_end_date']);
                } else {
                    $goods['promote_start_date'] = TimeHelper::local_date('Y-m-d', $goods['promote_start_date']);
                    $goods['promote_end_date'] = TimeHelper::local_date('Y-m-d', $goods['promote_end_date']);
                }

                // 如果是复制商品，处理
                if ($action === 'copy') {
                    // 商品信息
                    $goods['goods_id'] = 0;
                    $goods['goods_sn'] = '';
                    $goods['goods_name'] = '';
                    $goods['goods_img'] = '';
                    $goods['goods_thumb'] = '';
                    $goods['original_img'] = '';

                    // 扩展分类不变

                    // 关联商品
                    DB::table('goods_link_goods')
                        ->where(function ($q) {
                            $q->where('goods_id', 0)->orWhere('link_goods_id', 0);
                        })
                        ->where('admin_id', Session::get('admin_id'))
                        ->delete();

                    $res = DB::table('goods_link_goods')
                        ->where('goods_id', $_REQUEST['goods_id'])
                        ->select(DB::raw("'0' AS goods_id"), 'link_goods_id', 'is_double', DB::raw("'".Session::get('admin_id')."' AS admin_id"))
                        ->get();
                    foreach ($res as $row) {
                        DB::table('goods_link_goods')->insert((array) $row);
                    }

                    $res = DB::table('goods_link_goods')
                        ->where('link_goods_id', $_REQUEST['goods_id'])
                        ->select('goods_id', DB::raw("'0' AS link_goods_id"), 'is_double', DB::raw("'".Session::get('admin_id')."' AS admin_id"))
                        ->get();
                    foreach ($res as $row) {
                        DB::table('goods_link_goods')->insert((array) $row);
                    }

                    // 配件
                    DB::table('activity_group')->where('parent_id', 0)->where('admin_id', Session::get('admin_id'))->delete();

                    $res = DB::table('activity_group')
                        ->where('parent_id', $_REQUEST['goods_id'])
                        ->select(DB::raw('0 AS parent_id'), 'goods_id', 'goods_price', DB::raw("'".Session::get('admin_id')."' AS admin_id"))
                        ->get();
                    foreach ($res as $row) {
                        DB::table('activity_group')->insert((array) $row);
                    }

                    // 关联文章
                    DB::table('goods_article')->where('goods_id', 0)->where('admin_id', Session::get('admin_id'))->delete();

                    $res = DB::table('goods_article')
                        ->where('goods_id', $_REQUEST['goods_id'])
                        ->select(DB::raw('0 AS goods_id'), 'article_id', DB::raw("'".Session::get('admin_id')."' AS admin_id"))
                        ->get();
                    foreach ($res as $row) {
                        DB::table('goods_article')->insert((array) $row);
                    }

                    // 图片不变

                    // 商品属性
                    DB::table('goods_attr')->where('goods_id', 0)->delete();

                    $res = DB::table('goods_attr')
                        ->where('goods_id', $_REQUEST['goods_id'])
                        ->select(DB::raw('0 AS goods_id'), 'attr_id', 'attr_value', 'attr_price')
                        ->get();
                    foreach ($res as $row) {
                        DB::table('goods_attr')->insert(BaseHelper::addslashes_deep((array) $row));
                    }
                }

                // 扩展分类
                $other_cat_list = [];
                $goods['other_cat'] = DB::table('goods_cat')->where('goods_id', $_REQUEST['goods_id'])->pluck('cat_id')->all();
                foreach ($goods['other_cat'] as $cat_id) {
                    $other_cat_list[$cat_id] = CommonHelper::cat_list(0, $cat_id);
                }
                $this->assign('other_cat_list', $other_cat_list);

                $link_goods_list = GoodsHelper::get_linked_goods($goods['goods_id']); // 关联商品
                $group_goods_list = GoodsHelper::get_group_goods($goods['goods_id']); // 配件
                $goods_article_list = GoodsHelper::get_goods_articles($goods['goods_id']);   // 关联文章

                // 商品图片路径
                if (isset($GLOBALS['shop_id']) && ($GLOBALS['shop_id'] > 10) && ! empty($goods['original_img'])) {
                    $goods['goods_img'] = CommonHelper::get_image_path($goods['goods_img']);
                    $goods['goods_thumb'] = CommonHelper::get_image_path($goods['goods_thumb']);
                }

                // 图片列表
                $img_list = DB::table('goods_gallery')->where('goods_id', $goods['goods_id'])->get()->map(fn ($r) => (array) $r)->all();

                // 格式化相册图片路径
                if (isset($GLOBALS['shop_id']) && ($GLOBALS['shop_id'] > 0)) {
                    foreach ($img_list as $key => $gallery_img) {
                        $gallery_img[$key]['img_url'] = CommonHelper::get_image_path($gallery_img['img_original']);
                        $gallery_img[$key]['thumb_url'] = CommonHelper::get_image_path($gallery_img['img_original']);
                    }
                } else {
                    foreach ($img_list as $key => $gallery_img) {
                        $gallery_img[$key]['thumb_url'] = '../'.(empty($gallery_img['thumb_url']) ? $gallery_img['img_url'] : $gallery_img['thumb_url']);
                    }
                }
            }

            // 拆分商品名称样式
            $goods_name_style = explode('+', empty($goods['goods_name_style']) ? '+' : $goods['goods_name_style']);

            // 创建 html editor
            MainHelper::create_html_editor('goods_desc', $goods['goods_desc']);

            $this->assign('code', $code);
            $this->assign('ur_here', $is_add ? (empty($code) ? lang('02_goods_add') : lang('51_virtual_card_add')) : ($action === 'edit' ? lang('edit_goods') : lang('copy_goods')));
            $this->assign('action_link', $this->list_link($is_add, $code));
            $this->assign('goods', $goods);
            $this->assign('goods_name_color', $goods_name_style[0]);
            $this->assign('goods_name_style', $goods_name_style[1]);
            $this->assign('cat_list', CommonHelper::cat_list(0, $goods['cat_id']));
            $this->assign('brand_list', CommonHelper::get_brand_list());
            $this->assign('unit_list', GoodsHelper::get_unit_list());
            $this->assign('user_rank_list', GoodsHelper::get_user_rank_list());
            $this->assign('weight_unit', $is_add ? '1' : ($goods['goods_weight'] >= 1 ? '1' : '0.001'));
            $this->assign('cfg', cfg());
            $this->assign('form_act', $is_add ? 'insert' : ($action === 'edit' ? 'update' : 'insert'));
            if ($_GET['act'] === 'add' || $_GET['act'] === 'edit') {
                $this->assign('is_add', true);
            }
            if (! $is_add) {
                $this->assign('member_price_list', GoodsHelper::get_member_price_list($_REQUEST['goods_id']));
            }
            $this->assign('link_goods_list', $link_goods_list);
            $this->assign('group_goods_list', $group_goods_list);
            $this->assign('goods_article_list', $goods_article_list);
            $this->assign('img_list', $img_list);
            $this->assign('goods_type_list', MainHelper::goods_type_list($goods['goods_type']));
            $this->assign('gd', BaseHelper::gd_version());
            $this->assign('thumb_width', cfg('thumb_width'));
            $this->assign('thumb_height', cfg('thumb_height'));
            $this->assign('goods_attr_html', GoodsHelper::build_attr_html($goods['goods_type'], $goods['goods_id']));
            $volume_price_list = '';
            if (isset($_REQUEST['goods_id'])) {
                $volume_price_list = CommonHelper::get_volume_price_list($_REQUEST['goods_id']);
            }
            if (empty($volume_price_list)) {
                $volume_price_list = ['0' => ['number' => '', 'price' => '']];
            }
            $this->assign('volume_price_list', $volume_price_list);

            return $this->display('goods_info');
        }

        /**
         * 插入商品 更新商品
         */
        if ($action === 'insert' || $action === 'update') {
            $code = empty($_REQUEST['extension_code']) ? '' : trim($_REQUEST['extension_code']);

            // 是否处理缩略图
            $proc_thumb = (isset($GLOBALS['shop_id']) && $GLOBALS['shop_id'] > 0) ? false : true;
            if ($code === 'virtual_card') {
                $this->admin_priv('virualcard'); // 检查权限
            } else {
                $this->admin_priv('goods_manage'); // 检查权限
            }

            // 检查货号是否重复
            if ($_POST['goods_sn']) {
                if (DB::table('goods')->where('goods_sn', $_POST['goods_sn'])->where('is_delete', 0)->where('goods_id', '!=', $_POST['goods_id'])->count() > 0) {
                    return $this->sys_msg(lang('goods_sn_exists'), 1, [], false);
                }
            }

            // 检查图片：如果有错误，检查尺寸是否超过最大值；否则，检查文件类型
            if (isset($_FILES['goods_img']['error'])) { // php 4.2 版本才支持 error
                // 最大上传文件大小
                $php_maxsize = ini_get('upload_max_filesize');
                $htm_maxsize = '2M';

                // 商品图片
                if ($_FILES['goods_img']['error'] === 0) {
                    if (! $image->check_img_type($_FILES['goods_img']['type'])) {
                        return $this->sys_msg(lang('invalid_goods_img'), 1, [], false);
                    }
                } elseif ($_FILES['goods_img']['error'] === 1) {
                    return $this->sys_msg(sprintf(lang('goods_img_too_big'), $php_maxsize), 1, [], false);
                } elseif ($_FILES['goods_img']['error'] === 2) {
                    return $this->sys_msg(sprintf(lang('goods_img_too_big'), $htm_maxsize), 1, [], false);
                }

                // 商品缩略图
                if (isset($_FILES['goods_thumb'])) {
                    if ($_FILES['goods_thumb']['error'] === 0) {
                        if (! $image->check_img_type($_FILES['goods_thumb']['type'])) {
                            return $this->sys_msg(lang('invalid_goods_thumb'), 1, [], false);
                        }
                    } elseif ($_FILES['goods_thumb']['error'] === 1) {
                        return $this->sys_msg(sprintf(lang('goods_thumb_too_big'), $php_maxsize), 1, [], false);
                    } elseif ($_FILES['goods_thumb']['error'] === 2) {
                        return $this->sys_msg(sprintf(lang('goods_thumb_too_big'), $htm_maxsize), 1, [], false);
                    }
                }

                // 相册图片
                foreach ($_FILES['img_url']['error'] as $key => $value) {
                    if ($value === 0) {
                        if (! $image->check_img_type($_FILES['img_url']['type'][$key])) {
                            return $this->sys_msg(sprintf(lang('invalid_img_url'), $key + 1), 1, [], false);
                        }
                    } elseif ($value === 1) {
                        return $this->sys_msg(sprintf(lang('img_url_too_big'), $key + 1, $php_maxsize), 1, [], false);
                    } elseif ($_FILES['img_url']['error'] === 2) {
                        return $this->sys_msg(sprintf(lang('img_url_too_big'), $key + 1, $htm_maxsize), 1, [], false);
                    }
                }
            }

            // 插入还是更新的标识
            $is_insert = $action === 'insert';

            // 处理商品图片
            $goods_img = '';  // 初始化商品图片
            $goods_thumb = '';  // 初始化商品缩略图
            $original_img = '';  // 初始化原始图片
            $old_original_img = '';  // 初始化原始图片旧图

            // 如果上传了商品图片，相应处理
            if ($_FILES['goods_img']['tmp_name'] != '' && $_FILES['goods_img']['tmp_name'] != 'none') {
                if ($_REQUEST['goods_id'] > 0) {
                    // 删除原来的图片文件
                    $row = (array) DB::table('goods')->where('goods_id', $_REQUEST['goods_id'])->select('goods_thumb', 'goods_img', 'original_img')->first();
                    if ($row['goods_thumb'] != '' && is_file('../'.$row['goods_thumb'])) {
                        @unlink('../'.$row['goods_thumb']);
                    }
                    if ($row['goods_img'] != '' && is_file('../'.$row['goods_img'])) {
                        @unlink('../'.$row['goods_img']);
                    }
                }

                $original_img = $image->upload_image($_FILES['goods_img']); // 原始图片
                if ($original_img === false) {
                    return $this->sys_msg($image->error_msg(), 1, [], false);
                }
                $goods_img = $original_img;   // 商品图片

                // 复制一份相册图片
                // 添加判断是否自动生成相册图片
                if (cfg('auto_generate_gallery')) {
                    $img = $original_img;   // 相册图片
                    $pos = strpos(basename($img), '.');
                    $newname = dirname($img).'/'.$image->random_filename().substr(basename($img), $pos);
                    if (! copy('../'.$img, '../'.$newname)) {
                        return $this->sys_msg('fail to copy file: '.realpath('../'.$img), 1, [], false);
                    }
                    $img = $newname;

                    $gallery_img = $img;
                    $gallery_thumb = $img;
                }

                // 如果系统支持GD，缩放商品图片，且给商品图片和相册图片加水印
                if ($proc_thumb && $image->gd_version() > 0 && $image->check_img_function($_FILES['goods_img']['type'])) {
                    // 如果设置大小不为0，缩放图片
                    if (cfg('image_width') != 0 || cfg('image_height') != 0) {
                        $goods_img = $image->make_thumb('../'.$goods_img, cfg('image_width'), cfg('image_height'));
                        if ($goods_img === false) {
                            return $this->sys_msg($image->error_msg(), 1, [], false);
                        }
                    }

                    // 添加判断是否自动生成相册图片
                    if (cfg('auto_generate_gallery')) {
                        $newname = dirname($img).'/'.$image->random_filename().substr(basename($img), $pos);
                        if (! copy('../'.$img, '../'.$newname)) {
                            return $this->sys_msg('fail to copy file: '.realpath('../'.$img), 1, [], false);
                        }
                        $gallery_img = $newname;
                    }

                    // 加水印
                    if (intval(cfg('watermark_place')) > 0 && ! empty(cfg('watermark'))) {
                        if ($image->add_watermark('../'.$goods_img, '', cfg('watermark'), cfg('watermark_place'), cfg('watermark_alpha')) === false) {
                            return $this->sys_msg($image->error_msg(), 1, [], false);
                        }
                        // 添加判断是否自动生成相册图片
                        if (cfg('auto_generate_gallery')) {
                            if ($image->add_watermark('../'.$gallery_img, '', cfg('watermark'), cfg('watermark_place'), cfg('watermark_alpha')) === false) {
                                return $this->sys_msg($image->error_msg(), 1, [], false);
                            }
                        }
                    }

                    // 相册缩略图
                    // 添加判断是否自动生成相册图片
                    if (cfg('auto_generate_gallery')) {
                        if (cfg('thumb_width') != 0 || cfg('thumb_height') != 0) {
                            $gallery_thumb = $image->make_thumb('../'.$img, cfg('thumb_width'), cfg('thumb_height'));
                            if ($gallery_thumb === false) {
                                return $this->sys_msg($image->error_msg(), 1, [], false);
                            }
                        }
                    }
                }
            }

            // 是否上传商品缩略图
            if (
                isset($_FILES['goods_thumb']) && $_FILES['goods_thumb']['tmp_name'] != '' &&
                isset($_FILES['goods_thumb']['tmp_name']) && $_FILES['goods_thumb']['tmp_name'] != 'none'
            ) {
                // 上传了，直接使用，原始大小
                $goods_thumb = $image->upload_image($_FILES['goods_thumb']);
                if ($goods_thumb === false) {
                    return $this->sys_msg($image->error_msg(), 1, [], false);
                }
            } else {
                // 未上传，如果自动选择生成，且上传了商品图片，生成所略图
                if ($proc_thumb && isset($_POST['auto_thumb']) && ! empty($original_img)) {
                    // 如果设置缩略图大小不为0，生成缩略图
                    if (cfg('thumb_width') != 0 || cfg('thumb_height') != 0) {
                        $goods_thumb = $image->make_thumb('../'.$original_img, cfg('thumb_width'), cfg('thumb_height'));
                        if ($goods_thumb === false) {
                            return $this->sys_msg($image->error_msg(), 1, [], false);
                        }
                    } else {
                        $goods_thumb = $original_img;
                    }
                }
            }

            // 如果没有输入商品货号则自动生成一个商品货号
            if (empty($_POST['goods_sn'])) {
                $max_id = $is_insert ? DB::table('goods')->max('goods_id') + 1 : $_REQUEST['goods_id'];
                $goods_sn = GoodsHelper::generate_goods_sn($max_id);
            } else {
                $goods_sn = $_POST['goods_sn'];
            }

            // 处理商品数据
            $shop_price = ! empty($_POST['shop_price']) ? $_POST['shop_price'] : 0;
            $market_price = ! empty($_POST['market_price']) ? $_POST['market_price'] : 0;
            $promote_price = ! empty($_POST['promote_price']) ? floatval($_POST['promote_price']) : 0;
            $is_promote = empty($promote_price) ? 0 : 1;
            $promote_start_date = ($is_promote && ! empty($_POST['promote_start_date'])) ? TimeHelper::local_strtotime($_POST['promote_start_date']) : 0;
            $promote_end_date = ($is_promote && ! empty($_POST['promote_end_date'])) ? TimeHelper::local_strtotime($_POST['promote_end_date']) : 0;
            $goods_weight = ! empty($_POST['goods_weight']) ? $_POST['goods_weight'] * $_POST['weight_unit'] : 0;
            $is_best = isset($_POST['is_best']) ? 1 : 0;
            $is_new = isset($_POST['is_new']) ? 1 : 0;
            $is_hot = isset($_POST['is_hot']) ? 1 : 0;
            $is_on_sale = isset($_POST['is_on_sale']) ? 1 : 0;
            $is_alone_sale = isset($_POST['is_alone_sale']) ? 1 : 0;
            $goods_number = isset($_POST['goods_number']) ? $_POST['goods_number'] : 0;
            $warn_number = isset($_POST['warn_number']) ? $_POST['warn_number'] : 0;
            $goods_type = isset($_POST['goods_type']) ? $_POST['goods_type'] : 0;
            $give_integral = isset($_POST['give_integral']) ? intval($_POST['give_integral']) : '-1';
            $rank_integral = isset($_POST['rank_integral']) ? intval($_POST['rank_integral']) : '-1';

            $goods_name_style = $_POST['goods_name_color'].'+'.$_POST['goods_name_style'];

            $catgory_id = empty($_POST['cat_id']) ? '' : intval($_POST['cat_id']);
            $brand_id = empty($_POST['brand_id']) ? '' : intval($_POST['brand_id']);

            $goods_img = (empty($goods_img) && ! empty($_POST['goods_img_url']) && $this->goods_parse_url($_POST['goods_img_url'])) ? htmlspecialchars(trim($_POST['goods_img_url'])) : $goods_img;
            $goods_thumb = (empty($goods_thumb) && ! empty($_POST['goods_thumb_url']) && $this->goods_parse_url($_POST['goods_thumb_url'])) ? htmlspecialchars(trim($_POST['goods_thumb_url'])) : $goods_thumb;
            $goods_thumb = (empty($goods_thumb) && isset($_POST['auto_thumb'])) ? $goods_img : $goods_thumb;

            // 入库
            if ($is_insert) {
                $goodsData = [
                    'goods_name' => $_POST['goods_name'],
                    'goods_name_style' => $goods_name_style,
                    'goods_sn' => $goods_sn,
                    'cat_id' => $catgory_id,
                    'brand_id' => $brand_id,
                    'shop_price' => $shop_price,
                    'market_price' => $market_price,
                    'is_promote' => $is_promote,
                    'promote_price' => $promote_price,
                    'promote_start_date' => $promote_start_date,
                    'promote_end_date' => $promote_end_date,
                    'goods_img' => $goods_img,
                    'goods_thumb' => $goods_thumb,
                    'original_img' => $original_img,
                    'keywords' => $_POST['keywords'] ?? '',
                    'goods_brief' => $_POST['goods_brief'] ?? '',
                    'seller_note' => $_POST['seller_note'] ?? '',
                    'goods_weight' => $goods_weight,
                    'goods_number' => $goods_number,
                    'warn_number' => $warn_number,
                    'integral' => $_POST['integral'] ?? 0,
                    'give_integral' => $give_integral,
                    'is_best' => $is_best,
                    'is_new' => $is_new,
                    'is_hot' => $is_hot,
                    'is_on_sale' => $is_on_sale,
                    'is_alone_sale' => $is_alone_sale,
                    'goods_desc' => $_POST['goods_desc'] ?? '',
                    'add_time' => TimeHelper::gmtime(),
                    'last_update' => TimeHelper::gmtime(),
                    'goods_type' => $goods_type,
                    'rank_integral' => $rank_integral,
                ];

                if ($code !== '') {
                    $goodsData['extension_code'] = $code;
                    $goodsData['is_real'] = 0;
                }

                DB::table('goods')->insert($goodsData);
            } else {
                // 如果有上传图片，删除原来的商品图
                $row = (array) DB::table('goods')->where('goods_id', $_REQUEST['goods_id'])->select('goods_thumb', 'goods_img', 'original_img')->first();
                if ($proc_thumb && $goods_img && $row['goods_img'] && ! $this->goods_parse_url($row['goods_img'])) {
                    @unlink(ROOT_PATH.$row['goods_img']);
                    @unlink(ROOT_PATH.$row['original_img']);
                }

                if ($proc_thumb && $goods_thumb && $row['goods_thumb'] && ! $this->goods_parse_url($row['goods_thumb'])) {
                    @unlink(ROOT_PATH.$row['goods_thumb']);
                }

                $goodsData = [
                    'goods_name' => $_POST['goods_name'],
                    'goods_name_style' => $goods_name_style,
                    'goods_sn' => $goods_sn,
                    'cat_id' => $catgory_id,
                    'brand_id' => $brand_id,
                    'shop_price' => $shop_price,
                    'market_price' => $market_price,
                    'is_promote' => $is_promote,
                    'promote_price' => $promote_price,
                    'promote_start_date' => $promote_start_date,
                    'promote_end_date' => $promote_end_date,
                    'keywords' => $_POST['keywords'] ?? '',
                    'goods_brief' => $_POST['goods_brief'] ?? '',
                    'seller_note' => $_POST['seller_note'] ?? '',
                    'goods_weight' => $goods_weight,
                    'goods_number' => $goods_number,
                    'warn_number' => $warn_number,
                    'integral' => $_POST['integral'] ?? 0,
                    'give_integral' => $give_integral,
                    'rank_integral' => $rank_integral,
                    'is_best' => $is_best,
                    'is_new' => $is_new,
                    'is_hot' => $is_hot,
                    'is_on_sale' => $is_on_sale,
                    'is_alone_sale' => $is_alone_sale,
                    'goods_desc' => $_POST['goods_desc'] ?? '',
                    'last_update' => TimeHelper::gmtime(),
                    'goods_type' => $goods_type,
                ];

                // 如果有上传图片，需要更新数据库
                if ($goods_img) {
                    $goodsData['goods_img'] = $goods_img;
                    $goodsData['original_img'] = $original_img;
                }
                if ($goods_thumb) {
                    $goodsData['goods_thumb'] = $goods_thumb;
                }
                if ($code != '') {
                    $goodsData['is_real'] = 0;
                    $goodsData['extension_code'] = $code;
                }

                DB::table('goods')
                    ->where('goods_id', $_REQUEST['goods_id'])
                    ->update($goodsData);
            }

            // 商品编号
            $goods_id = $is_insert ? DB::getPdo()->lastInsertId() : $_REQUEST['goods_id'];

            // 记录日志
            if ($is_insert) {
                $this->admin_log($_POST['goods_name'], 'add', 'goods');
            } else {
                $this->admin_log($_POST['goods_name'], 'edit', 'goods');
            }

            // 处理属性
            if ((isset($_POST['attr_id_list']) && isset($_POST['attr_value_list'])) || (empty($_POST['attr_id_list']) && empty($_POST['attr_value_list']))) {
                // 取得原有的属性值
                $goods_attr_list = [];

                $keywords_arr = explode(' ', $_POST['keywords']);

                $keywords_arr = array_flip($keywords_arr);
                if (isset($keywords_arr[''])) {
                    unset($keywords_arr['']);
                }

                $attr_res = DB::table('goods_type_attribute')->where('cat_id', $goods_type)->select('attr_id', 'attr_index')->get();

                $attr_list = [];

                foreach ($attr_res as $row) {
                    $row = (array) $row;
                    $attr_list[$row['attr_id']] = $row['attr_index'];
                }

                $res = DB::table('goods_attr')->where('goods_id', $goods_id)->get();

                foreach ($res as $row) {
                    $row = (array) $row;
                    $goods_attr_list[$row['attr_id']][$row['attr_value']] = ['sign' => 'delete', 'goods_attr_id' => $row['goods_attr_id']];
                }
                // 循环现有的，根据原有的做相应处理
                if (isset($_POST['attr_id_list'])) {
                    foreach ($_POST['attr_id_list'] as $key => $attr_id) {
                        $attr_value = $_POST['attr_value_list'][$key];
                        $attr_price = $_POST['attr_price_list'][$key];
                        if (! empty($attr_value)) {
                            if (isset($goods_attr_list[$attr_id][$attr_value])) {
                                // 如果原来有，标记为更新
                                $goods_attr_list[$attr_id][$attr_value]['sign'] = 'update';
                                $goods_attr_list[$attr_id][$attr_value]['attr_price'] = $attr_price;
                            } else {
                                // 如果原来没有，标记为新增
                                $goods_attr_list[$attr_id][$attr_value]['sign'] = 'insert';
                                $goods_attr_list[$attr_id][$attr_value]['attr_price'] = $attr_price;
                            }
                            $val_arr = explode(' ', $attr_value);
                            foreach ($val_arr as $k => $v) {
                                if (! isset($keywords_arr[$v]) && $attr_list[$attr_id] === '1') {
                                    $keywords_arr[$v] = $v;
                                }
                            }
                        }
                    }
                }
                $keywords = implode(' ', array_flip($keywords_arr));

                DB::table('goods')->where('goods_id', $goods_id)->update(['keywords' => $keywords]);

                // 插入、更新、删除数据
                foreach ($goods_attr_list as $attr_id => $attr_value_list) {
                    foreach ($attr_value_list as $attr_value => $info) {
                        if ($info['sign'] === 'insert') {
                            DB::table('goods_attr')->insert([
                                'attr_id' => $attr_id,
                                'goods_id' => $goods_id,
                                'attr_value' => $attr_value,
                                'attr_price' => $info['attr_price'],
                            ]);
                        } elseif ($info['sign'] === 'update') {
                            DB::table('goods_attr')
                                ->where('goods_attr_id', $info['goods_attr_id'])
                                ->update(['attr_price' => $info['attr_price']]);
                        } else {
                            DB::table('goods_attr')
                                ->where('goods_attr_id', $info['goods_attr_id'])
                                ->delete();
                        }
                    }
                }
            }

            // 处理会员价格
            if (isset($_POST['user_rank']) && isset($_POST['user_price'])) {
                GoodsHelper::handle_member_price($goods_id, $_POST['user_rank'], $_POST['user_price']);
            }

            // 处理优惠价格
            if (isset($_POST['volume_number']) && isset($_POST['volume_price'])) {
                $temp_num = array_count_values($_POST['volume_number']);
                foreach ($temp_num as $v) {
                    if ($v > 1) {
                        return $this->sys_msg(lang('volume_number_continuous'), 1, [], false);
                        break;
                    }
                }
                $this->handle_volume_price($goods_id, $_POST['volume_number'], $_POST['volume_price']);
            }

            // 处理扩展分类
            if (isset($_POST['other_cat'])) {
                GoodsHelper::handle_other_cat($goods_id, array_unique($_POST['other_cat']));
            }

            if ($is_insert) {
                // 处理关联商品
                GoodsHelper::handle_link_goods($goods_id);

                // 处理组合商品
                GoodsHelper::handle_group_goods($goods_id);

                // 处理关联文章
                GoodsHelper::handle_goods_article($goods_id);
            }

            // 重新格式化图片名称
            $original_img = GoodsHelper::reformat_image_name('goods', $goods_id, $original_img, 'source');
            $goods_img = GoodsHelper::reformat_image_name('goods', $goods_id, $goods_img, 'goods');
            $goods_thumb = GoodsHelper::reformat_image_name('goods_thumb', $goods_id, $goods_thumb, 'thumb');
            if ($goods_img !== false) {
                DB::table('goods')->where('goods_id', $goods_id)->update(['goods_img' => $goods_img]);
            }

            if ($original_img !== false) {
                DB::table('goods')->where('goods_id', $goods_id)->update(['original_img' => $original_img]);
            }

            if ($goods_thumb !== false) {
                DB::table('goods')->where('goods_id', $goods_id)->update(['goods_thumb' => $goods_thumb]);
            }

            // 如果有图片，把商品图片加入图片相册
            if (isset($img)) {
                // 重新格式化图片名称
                $img = GoodsHelper::reformat_image_name('gallery', $goods_id, $img, 'source');
                $gallery_img = GoodsHelper::reformat_image_name('gallery', $goods_id, $gallery_img, 'goods');
                $gallery_thumb = GoodsHelper::reformat_image_name('gallery_thumb', $goods_id, $gallery_thumb, 'thumb');
                DB::table('goods_gallery')->insert([
                    'goods_id' => $goods_id,
                    'img_url' => $gallery_img,
                    'img_desc' => '',
                    'thumb_url' => $gallery_thumb,
                    'img_original' => $img,
                ]);
            }

            // 处理相册图片
            GoodsHelper::handle_gallery_image($goods_id, $_FILES['img_url'], $_POST['img_desc']);

            // 编辑时处理相册图片描述
            if (! $is_insert && isset($_POST['old_img_desc'])) {
                foreach ($_POST['old_img_desc'] as $img_id => $img_desc) {
                    DB::table('goods_gallery')->where('img_id', $img_id)->update(['img_desc' => $img_desc]);
                }
            }

            // 不保留商品原图的时候删除原图
            if ($proc_thumb && ! cfg('retain_original_img') && ! empty($original_img)) {
                DB::table('goods')->where('goods_id', $goods_id)->update(['original_img' => '']);
                DB::table('goods_gallery')->where('goods_id', $goods_id)->update(['img_original' => '']);
                @unlink('../'.$original_img);
                @unlink('../'.$img);
            }

            // 记录上一次选择的分类和品牌
            Cookie::queue('ECSCP[last_choose]', $catgory_id.'|'.$brand_id, TimeHelper::gmtime() + 86400);
            // 清空缓存
            $this->clear_cache_files();

            // 提示页面
            $link = [];
            if ($code === 'virtual_card') {
                $link[] = ['href' => 'virtual_card.php?act=replenish&goods_id='.$goods_id, 'text' => lang('add_replenish')];
            }
            if ($is_insert) {
                $link[] = $this->add_link($code);
            }
            $link[] = $this->list_link($is_insert, $code);

            return $this->sys_msg($is_insert ? lang('add_goods_ok') : lang('edit_goods_ok'), 0, $link);
        }

        /**
         * 批量操作
         */
        if ($action === 'batch') {
            $code = empty($_REQUEST['extension_code']) ? '' : trim($_REQUEST['extension_code']);

            // 取得要操作的商品编号
            $goods_id = ! empty($_POST['checkboxes']) ? implode(',', $_POST['checkboxes']) : 0;

            if (isset($_POST['type'])) {
                // 放入回收站
                if ($_POST['type'] === 'trash') {
                    $this->admin_priv('remove_back');

                    GoodsHelper::update_goods($goods_id, 'is_delete', '1');

                    // 记录日志
                    $this->admin_log('', 'batch_trash', 'goods');
                } // 上架
                elseif ($_POST['type'] === 'on_sale') {
                    $this->admin_priv('goods_manage');
                    GoodsHelper::update_goods($goods_id, 'is_on_sale', '1');
                } // 下架
                elseif ($_POST['type'] === 'not_on_sale') {
                    $this->admin_priv('goods_manage');
                    GoodsHelper::update_goods($goods_id, 'is_on_sale', '0');
                } // 设为精品
                elseif ($_POST['type'] === 'best') {
                    $this->admin_priv('goods_manage');
                    GoodsHelper::update_goods($goods_id, 'is_best', '1');
                } // 取消精品
                elseif ($_POST['type'] === 'not_best') {
                    $this->admin_priv('goods_manage');
                    GoodsHelper::update_goods($goods_id, 'is_best', '0');
                } // 设为新品
                elseif ($_POST['type'] === 'new') {
                    $this->admin_priv('goods_manage');
                    GoodsHelper::update_goods($goods_id, 'is_new', '1');
                } // 取消新品
                elseif ($_POST['type'] === 'not_new') {
                    $this->admin_priv('goods_manage');
                    GoodsHelper::update_goods($goods_id, 'is_new', '0');
                } // 设为热销
                elseif ($_POST['type'] === 'hot') {
                    $this->admin_priv('goods_manage');
                    GoodsHelper::update_goods($goods_id, 'is_hot', '1');
                } // 取消热销
                elseif ($_POST['type'] === 'not_hot') {
                    $this->admin_priv('goods_manage');
                    GoodsHelper::update_goods($goods_id, 'is_hot', '0');
                } // 转移到分类
                elseif ($_POST['type'] === 'move_to') {
                    $this->admin_priv('goods_manage');
                    GoodsHelper::update_goods($goods_id, 'cat_id', $_POST['target_cat']);
                } // 还原
                elseif ($_POST['type'] === 'restore') {
                    $this->admin_priv('remove_back');

                    GoodsHelper::update_goods($goods_id, 'is_delete', '0');

                    // 记录日志
                    $this->admin_log('', 'batch_restore', 'goods');
                } // 删除
                elseif ($_POST['type'] === 'drop') {
                    $this->admin_priv('remove_back');

                    GoodsHelper::delete_goods($goods_id);

                    // 记录日志
                    $this->admin_log('', 'batch_remove', 'goods');
                }
            }

            // 清除缓存
            $this->clear_cache_files();

            if ($_POST['type'] === 'drop' || $_POST['type'] === 'restore') {
                $link[] = ['href' => 'goods.php?act=trash', 'text' => lang('11_goods_trash')];
            } else {
                $link[] = $this->list_link(true, $code);
            }

            return $this->sys_msg(lang('batch_handle_ok'), 0, $link);
        }

        /**
         * 显示图片
         */
        if ($action === 'show_image') {
            if (isset($GLOBALS['shop_id']) && $GLOBALS['shop_id'] > 0) {
                $img_url = $_GET['img_url'];
            } else {
                if (strpos($_GET['img_url'], 'http://') === 0) {
                    $img_url = $_GET['img_url'];
                } else {
                    $img_url = '../'.$_GET['img_url'];
                }
            }
            $this->assign('img_url', $img_url);

            return $this->display('goods_show_image');
        }

        /**
         * 修改商品名称
         */
        if ($action === 'edit_goods_name') {
            $this->check_authz_json('goods_manage');

            $goods_id = intval($_POST['id']);
            $goods_name = BaseHelper::json_str_iconv(trim($_POST['val']));

            if ($exc->edit("goods_name = '$goods_name', last_update=".TimeHelper::gmtime(), $goods_id)) {
                $this->clear_cache_files();

                return $this->make_json_result(stripslashes($goods_name));
            }
        }

        /**
         * 修改商品货号
         */
        if ($action === 'edit_goods_sn') {
            $this->check_authz_json('goods_manage');

            $goods_id = intval($_POST['id']);
            $goods_sn = BaseHelper::json_str_iconv(trim($_POST['val']));

            // 检查是否重复
            if (! $exc->is_only('goods_sn', $goods_sn, $goods_id)) {
                return $this->make_json_error(lang('goods_sn_exists'));
            }

            if ($exc->edit("goods_sn = '$goods_sn', last_update=".TimeHelper::gmtime(), $goods_id)) {
                $this->clear_cache_files();

                return $this->make_json_result(stripslashes($goods_sn));
            }
        }

        if ($action === 'check_goods_sn') {
            $this->check_authz_json('goods_manage');

            $goods_id = intval($_REQUEST['goods_id']);
            $goods_sn = BaseHelper::json_str_iconv(trim($_REQUEST['goods_sn']));

            // 检查是否重复
            if (! $exc->is_only('goods_sn', $goods_sn, $goods_id)) {
                return $this->make_json_error(lang('goods_sn_exists'));
            }

            return $this->make_json_result('');
        }
        /**
         * 修改商品价格
         */
        if ($action === 'edit_goods_price') {
            $this->check_authz_json('goods_manage');

            $goods_id = intval($_POST['id']);
            $goods_price = floatval($_POST['val']);
            $price_rate = floatval(cfg('market_price_rate') * $goods_price);

            if ($goods_price < 0 || $goods_price === 0 && $_POST['val'] != "$goods_price") {
                return $this->make_json_error(lang('shop_price_invalid'));
            } else {
                if ($exc->edit("shop_price = '$goods_price', market_price = '$price_rate', last_update=".TimeHelper::gmtime(), $goods_id)) {
                    $this->clear_cache_files();

                    return $this->make_json_result(number_format($goods_price, 2, '.', ''));
                }
            }
        }

        /**
         * 修改商品库存数量
         */
        if ($action === 'edit_goods_number') {
            $this->check_authz_json('goods_manage');

            $goods_id = intval($_POST['id']);
            $goods_num = intval($_POST['val']);

            if ($goods_num < 0 || $goods_num === 0 && $_POST['val'] != "$goods_num") {
                return $this->make_json_error(lang('goods_number_error'));
            }

            if ($exc->edit("goods_number = '$goods_num', last_update=".TimeHelper::gmtime(), $goods_id)) {
                $this->clear_cache_files();

                return $this->make_json_result($goods_num);
            }
        }

        /**
         * 修改上架状态
         */
        if ($action === 'toggle_on_sale') {
            $this->check_authz_json('goods_manage');

            $goods_id = intval($_POST['id']);
            $on_sale = intval($_POST['val']);

            if ($exc->edit("is_on_sale = '$on_sale', last_update=".TimeHelper::gmtime(), $goods_id)) {
                $this->clear_cache_files();

                return $this->make_json_result($on_sale);
            }
        }

        /**
         * 修改精品推荐状态
         */
        if ($action === 'toggle_best') {
            $this->check_authz_json('goods_manage');

            $goods_id = intval($_POST['id']);
            $is_best = intval($_POST['val']);

            if ($exc->edit("is_best = '$is_best', last_update=".TimeHelper::gmtime(), $goods_id)) {
                $this->clear_cache_files();

                return $this->make_json_result($is_best);
            }
        }

        /**
         * 修改新品推荐状态
         */
        if ($action === 'toggle_new') {
            $this->check_authz_json('goods_manage');

            $goods_id = intval($_POST['id']);
            $is_new = intval($_POST['val']);

            if ($exc->edit("is_new = '$is_new', last_update=".TimeHelper::gmtime(), $goods_id)) {
                $this->clear_cache_files();

                return $this->make_json_result($is_new);
            }
        }

        /**
         * 修改热销推荐状态
         */
        if ($action === 'toggle_hot') {
            $this->check_authz_json('goods_manage');

            $goods_id = intval($_POST['id']);
            $is_hot = intval($_POST['val']);

            if ($exc->edit("is_hot = '$is_hot', last_update=".TimeHelper::gmtime(), $goods_id)) {
                $this->clear_cache_files();

                return $this->make_json_result($is_hot);
            }
        }

        /**
         * 修改商品排序
         */
        if ($action === 'edit_sort_order') {
            $this->check_authz_json('goods_manage');

            $goods_id = intval($_POST['id']);
            $sort_order = intval($_POST['val']);

            if ($exc->edit("sort_order = '$sort_order', last_update=".TimeHelper::gmtime(), $goods_id)) {
                $this->clear_cache_files();

                return $this->make_json_result($sort_order);
            }
        }

        /**
         * 排序、分页、查询
         */
        if ($action === 'query') {
            $is_delete = empty($_REQUEST['is_delete']) ? 0 : intval($_REQUEST['is_delete']);
            $code = empty($_REQUEST['extension_code']) ? '' : trim($_REQUEST['extension_code']);
            $goods_list = GoodsHelper::goods_list($is_delete, ($code === '') ? 1 : 0);

            $handler_list = [];
            $handler_list['virtual_card'][] = ['url' => 'virtual_card.php?act=card', 'title' => lang('card'), 'img' => 'icon_send_bonus.gif'];
            $handler_list['virtual_card'][] = ['url' => 'virtual_card.php?act=replenish', 'title' => lang('replenish'), 'img' => 'icon_add.gif'];
            $handler_list['virtual_card'][] = ['url' => 'virtual_card.php?act=batch_card_add', 'title' => lang('batch_card_add'), 'img' => 'icon_output.gif'];

            if (isset($handler_list[$code])) {
                $this->assign('add_handler', $handler_list[$code]);
            }

            $this->assign('goods_list', $goods_list['goods']);
            $this->assign('filter', $goods_list['filter']);
            $this->assign('record_count', $goods_list['record_count']);
            $this->assign('page_count', $goods_list['page_count']);
            $this->assign('list_type', $is_delete ? 'trash' : 'goods');
            $this->assign('use_storage', empty(cfg('use_storage')) ? 0 : 1);

            // 排序标记
            $sort_flag = MainHelper::sort_flag($goods_list['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            $tpl = $is_delete ? 'goods_trash.htm' : 'goods_list.htm';

            return $this->make_json_result(
                $this->fetch($tpl),
                '',
                ['filter' => $goods_list['filter'], 'page_count' => $goods_list['page_count']]
            );
        }

        /**
         * 放入回收站
         */
        if ($action === 'remove') {
            $goods_id = intval($_REQUEST['id']);

            $this->check_authz_json('remove_back');

            if ($exc->edit('is_delete = 1', $goods_id)) {
                $this->clear_cache_files();
                $goods_name = $exc->get_name($goods_id);

                $this->admin_log(addslashes($goods_name), 'trash', 'goods'); // 记录日志

                $url = 'goods.php?act=query&'.str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

                return response()->redirectTo($url);
            }
        }

        /**
         * 还原回收站中的商品
         */
        if ($action === 'restore_goods') {
            $goods_id = intval($_REQUEST['id']);

            $this->check_authz_json('remove_back'); // 检查权限

            $exc->edit("is_delete = 0, add_time = '".TimeHelper::gmtime()."'", $goods_id);
            $this->clear_cache_files();

            $goods_name = $exc->get_name($goods_id);

            $this->admin_log(addslashes($goods_name), 'restore', 'goods'); // 记录日志

            $url = 'goods.php?act=query&'.str_replace('act=restore_goods', '', $_SERVER['QUERY_STRING']);

            return response()->redirectTo($url);
        }

        /**
         * 彻底删除商品
         */
        if ($action === 'drop_goods') {
            // 检查权限
            $this->check_authz_json('remove_back');

            // 取得参数
            $goods_id = intval($_REQUEST['id']);
            if ($goods_id <= 0) {
                return $this->make_json_error('invalid params');
            }

            // 取得商品信息
            $goods = (array) DB::table('goods')->where('goods_id', $goods_id)
                ->select('goods_id', 'goods_name', 'is_delete', 'is_real', 'goods_thumb', 'goods_img', 'original_img')
                ->first();
            if (empty($goods)) {
                return $this->make_json_error(lang('goods_not_exist'));
            }

            if ($goods['is_delete'] != 1) {
                return $this->make_json_error(lang('goods_not_in_recycle_bin'));
            }

            // 删除商品图片和轮播图片
            if (! empty($goods['goods_thumb'])) {
                @unlink('../'.$goods['goods_thumb']);
            }
            if (! empty($goods['goods_img'])) {
                @unlink('../'.$goods['goods_img']);
            }
            if (! empty($goods['original_img'])) {
                @unlink('../'.$goods['original_img']);
            }
            // 删除商品
            $exc->drop($goods_id);

            // 记录日志
            $this->admin_log(addslashes($goods['goods_name']), 'remove', 'goods');

            // 删除商品相册
            $res = DB::table('goods_gallery')->where('goods_id', $goods_id)->select('img_url', 'thumb_url', 'img_original')->get();
            foreach ($res as $row) {
                $row = (array) $row;
                if (! empty($row['img_url'])) {
                    @unlink('../'.$row['img_url']);
                }
                if (! empty($row['thumb_url'])) {
                    @unlink('../'.$row['thumb_url']);
                }
                if (! empty($row['img_original'])) {
                    @unlink('../'.$row['img_original']);
                }
            }

            DB::table('goods_gallery')->where('goods_id', $goods_id)->delete();

            // 删除相关表记录
            DB::table('user_collect')->where('goods_id', $goods_id)->delete();
            DB::table('goods_article')->where('goods_id', $goods_id)->delete();
            DB::table('goods_attr')->where('goods_id', $goods_id)->delete();
            DB::table('goods_cat')->where('goods_id', $goods_id)->delete();
            DB::table('goods_member_price')->where('goods_id', $goods_id)->delete();
            DB::table('activity_group')->where('parent_id', $goods_id)->delete();
            DB::table('activity_group')->where('goods_id', $goods_id)->delete();
            DB::table('goods_link_goods')->where('goods_id', $goods_id)->delete();
            DB::table('goods_link_goods')->where('link_goods_id', $goods_id)->delete();
            DB::table('user_tag')->where('goods_id', $goods_id)->delete();
            DB::table('comment')->where('comment_type', 0)->where('id_value', $goods_id)->delete();
            DB::table('user_collect')->where('goods_id', $goods_id)->delete();
            DB::table('user_booking')->where('goods_id', $goods_id)->delete();
            DB::table('goods_activity')->where('goods_id', $goods_id)->delete();

            // 如果不是实体商品，删除相应虚拟商品记录
            if ($goods['is_real'] != 1) {
                DB::table('goods_virtual_card')->where('goods_id', $goods_id)->delete();
            }

            $this->clear_cache_files();
            $url = 'goods.php?act=query&'.str_replace('act=drop_goods', '', $_SERVER['QUERY_STRING']);

            return response()->redirectTo($url);
        }

        /**
         * 切换商品类型
         */
        if ($action === 'get_attr') {
            $this->check_authz_json('goods_manage');

            $goods_id = empty($_GET['goods_id']) ? 0 : intval($_GET['goods_id']);
            $goods_type = empty($_GET['goods_type']) ? 0 : intval($_GET['goods_type']);

            $content = GoodsHelper::build_attr_html($goods_type, $goods_id);

            return $this->make_json_result($content);
        }

        /**
         * 删除图片
         */
        if ($action === 'drop_image') {
            $this->check_authz_json('goods_manage');

            $img_id = empty($_REQUEST['img_id']) ? 0 : intval($_REQUEST['img_id']);

            // 删除图片文件
            $row = (array) DB::table('goods_gallery')->where('img_id', $img_id)->select('img_url', 'thumb_url', 'img_original')->first();

            if ($row['img_url'] != '' && is_file('../'.$row['img_url'])) {
                @unlink('../'.$row['img_url']);
            }
            if ($row['thumb_url'] != '' && is_file('../'.$row['thumb_url'])) {
                @unlink('../'.$row['thumb_url']);
            }
            if ($row['img_original'] != '' && is_file('../'.$row['img_original'])) {
                @unlink('../'.$row['img_original']);
            }

            // 删除数据
            DB::table('goods_gallery')->where('img_id', $img_id)->delete();

            $this->clear_cache_files();

            return $this->make_json_result($img_id);
        }

        /**
         * 搜索商品，仅返回名称及ID
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
         * 把商品加入关联
         */
        if ($action === 'add_link_goods') {
            $this->check_authz_json('goods_manage');

            $linked_array = json_decode($_GET['add_ids']);
            $linked_goods = json_decode($_GET['JSON']);
            $goods_id = $linked_goods[0];
            $is_double = $linked_goods[1] === true ? 0 : 1;

            foreach ($linked_array as $val) {
                if ($is_double) {
                    // 双向关联
                    DB::table('goods_link_goods')->insert([
                        'goods_id' => $val,
                        'link_goods_id' => $goods_id,
                        'is_double' => $is_double,
                        'admin_id' => Session::get('admin_id'),
                    ]);
                }

                DB::table('goods_link_goods')->insert([
                    'goods_id' => $goods_id,
                    'link_goods_id' => $val,
                    'is_double' => $is_double,
                    'admin_id' => Session::get('admin_id'),
                ]);
            }

            $linked_goods = GoodsHelper::get_linked_goods($goods_id);
            $options = [];

            foreach ($linked_goods as $val) {
                $options[] = [
                    'value' => $val['goods_id'],
                    'text' => $val['goods_name'],
                    'data' => '',
                ];
            }

            $this->clear_cache_files();

            return $this->make_json_result($options);
        }

        /**
         * 删除关联商品
         */
        if ($action === 'drop_link_goods') {
            $this->check_authz_json('goods_manage');

            $drop_goods = json_decode($_GET['drop_ids']);
            $linked_goods = json_decode($_GET['JSON']);
            $goods_id = $linked_goods[0];
            $is_signle = $linked_goods[1];

            $query = DB::table('goods_link_goods')->whereIn('goods_id', $drop_goods);

            if ($goods_id === 0) {
                $query->where('admin_id', Session::get('admin_id'));
            }

            if (! $is_signle) {
                $query->where('link_goods_id', $goods_id)->delete();
            } else {
                $query->where('link_goods_id', $goods_id)->update(['is_double' => 0]);
            }

            $query2 = DB::table('goods_link_goods')->whereIn('link_goods_id', $drop_goods);
            if ($goods_id === 0) {
                $query2->where('admin_id', Session::get('admin_id'));
            }
            $query2->where('goods_id', $goods_id)->delete();

            $linked_goods = GoodsHelper::get_linked_goods($goods_id);
            $options = [];

            foreach ($linked_goods as $val) {
                $options[] = [
                    'value' => $val['goods_id'],
                    'text' => $val['goods_name'],
                    'data' => '',
                ];
            }

            $this->clear_cache_files();

            return $this->make_json_result($options);
        }

        /**
         * 增加一个配件
         */
        if ($action === 'add_group_goods') {
            $this->check_authz_json('goods_manage');

            $fittings = json_decode($_GET['add_ids']);
            $arguments = json_decode($_GET['JSON']);
            $goods_id = $arguments[0];
            $price = $arguments[1];

            foreach ($fittings as $val) {
                DB::table('activity_group')->insert([
                    'parent_id' => $goods_id,
                    'goods_id' => $val,
                    'goods_price' => $price,
                    'admin_id' => Session::get('admin_id'),
                ]);
            }

            $arr = GoodsHelper::get_group_goods($goods_id);
            $opt = [];

            foreach ($arr as $val) {
                $opt[] = [
                    'value' => $val['goods_id'],
                    'text' => $val['goods_name'],
                    'data' => '',
                ];
            }

            $this->clear_cache_files();

            return $this->make_json_result($opt);
        }

        /**
         * 删除一个配件
         */
        if ($action === 'drop_group_goods') {
            $this->check_authz_json('goods_manage');

            $fittings = json_decode($_GET['drop_ids']);
            $arguments = json_decode($_GET['JSON']);
            $goods_id = $arguments[0];
            $price = $arguments[1];

            DB::table('activity_group')->where('parent_id', $goods_id)->whereIn('goods_id', $fittings)->when($goods_id === 0, fn ($q) => $q->where('admin_id', Session::get('admin_id')))->delete();

            $arr = GoodsHelper::get_group_goods($goods_id);
            $opt = [];

            foreach ($arr as $val) {
                $opt[] = [
                    'value' => $val['goods_id'],
                    'text' => $val['goods_name'],
                    'data' => '',
                ];
            }

            $this->clear_cache_files();

            return $this->make_json_result($opt);
        }

        /**
         * 搜索文章
         */
        if ($action === 'get_article_list') {
            $filters = (array) json_decode(BaseHelper::json_str_iconv($_GET['JSON']));

            $where = ' WHERE cat_id > 0 ';
            if (! empty($filters['title'])) {
                $keyword = trim($filters['title']);
                $where .= " AND title LIKE '%".BaseHelper::mysql_like_quote($keyword)."%' ";
            }

            $res = DB::table('article')->whereRaw('cat_id > 0'.$where)->orderByDesc('article_id')->limit(50)->select('article_id', 'title')->get();
            $arr = [];

            foreach ($res as $row) {
                $row = (array) $row;
                $arr[] = ['value' => $row['article_id'], 'text' => $row['title'], 'data' => ''];
            }

            return $this->make_json_result($arr);
        }

        /**
         * 添加关联文章
         */
        if ($action === 'add_goods_article') {
            $this->check_authz_json('goods_manage');

            $articles = json_decode($_GET['add_ids']);
            $arguments = json_decode($_GET['JSON']);
            $goods_id = $arguments[0];

            foreach ($articles as $val) {
                DB::table('goods_article')->insert([
                    'goods_id' => $goods_id,
                    'article_id' => $val,
                    'admin_id' => Session::get('admin_id'),
                ]);
            }

            $arr = GoodsHelper::get_goods_articles($goods_id);
            $opt = [];

            foreach ($arr as $val) {
                $opt[] = [
                    'value' => $val['article_id'],
                    'text' => $val['title'],
                    'data' => '',
                ];
            }

            $this->clear_cache_files();

            return $this->make_json_result($opt);
        }

        /**
         * 删除关联文章
         */
        if ($action === 'drop_goods_article') {
            $this->check_authz_json('goods_manage');

            $articles = json_decode($_GET['drop_ids']);
            $arguments = json_decode($_GET['JSON']);
            $goods_id = $arguments[0];

            DB::table('goods_article')->whereIn('article_id', $articles)->delete();

            $arr = GoodsHelper::get_goods_articles($goods_id);
            $opt = [];

            foreach ($arr as $val) {
                $opt[] = [
                    'value' => $val['article_id'],
                    'text' => $val['title'],
                    'data' => '',
                ];
            }

            $this->clear_cache_files();

            return $this->make_json_result($opt);
        }
    }

    /**
     * 列表链接
     *
     * @param  bool  $is_add  是否添加（插入）
     * @param  string  $extension_code  虚拟商品扩展代码，实体商品为空
     * @return array('href' => $href, 'text' => $text)
     */
    private function list_link($is_add = true, $extension_code = '')
    {
        $href = 'goods.php?act=list';
        if (! empty($extension_code)) {
            $href .= '&extension_code='.$extension_code;
        }
        if (! $is_add) {
            $href .= '&'.MainHelper::list_link_postfix();
        }

        if ($extension_code === 'virtual_card') {
            $text = lang('50_virtual_card_list');
        } else {
            $text = lang('01_goods_list');
        }

        return ['href' => $href, 'text' => $text];
    }

    /**
     * 添加链接
     *
     * @param  string  $extension_code  虚拟商品扩展代码，实体商品为空
     * @return array('href' => $href, 'text' => $text)
     */
    private function add_link($extension_code = '')
    {
        $href = 'goods.php?act=add';
        if (! empty($extension_code)) {
            $href .= '&extension_code='.$extension_code;
        }

        if ($extension_code === 'virtual_card') {
            $text = lang('51_virtual_card_add');
        } else {
            $text = lang('02_goods_add');
        }

        return ['href' => $href, 'text' => $text];
    }

    /**
     * 检查图片网址是否合法
     *
     * @param  string  $url  网址
     * @return bool
     */
    private function goods_parse_url($url)
    {
        $parse_url = @parse_url($url);

        return ! empty($parse_url['scheme']) && ! empty($parse_url['host']);
    }

    /**
     * 保存某商品的优惠价格
     *
     * @param  int  $goods_id  商品编号
     * @param  array  $number_list  优惠数量列表
     * @param  array  $price_list  价格列表
     * @return void
     */
    private function handle_volume_price($goods_id, $number_list, $price_list)
    {
        DB::table('goods_volume_price')->where('price_type', 1)->where('goods_id', $goods_id)->delete();

        // 循环处理每个优惠价格
        foreach ($price_list as $key => $price) {
            // 价格对应的数量上下限
            $volume_number = $number_list[$key];

            if (! empty($price)) {
                DB::table('goods_volume_price')->insert([
                    'price_type' => 1,
                    'goods_id' => $goods_id,
                    'volume_number' => $volume_number,
                    'volume_price' => $price,
                ]);
            }
        }
    }
}
