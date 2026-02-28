<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\CommonHelper;
use App\Helpers\TimeHelper;
use App\Libraries\Image;
use App\Modules\Admin\Helpers\GoodsHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PictureBatchController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        $image = new Image(cfg('bgcolor'));

        // 权限检查
        $this->admin_priv('picture_batch');

        if (empty($_GET['is_ajax'])) {
            $this->assign('ur_here', lang('12_batch_pic'));
            $this->assign('cat_list', CommonHelper::cat_list(0, 0));
            $this->assign('brand_list', CommonHelper::get_brand_list());

            return $this->display('picture_batch');
        } elseif (! empty($_GET['get_goods'])) {
            $brand_id = intval($_GET['brand_id']);
            $cat_id = intval($_GET['cat_id']);
            $goods_where = '';

            if (! empty($cat_id)) {
                $goods_where .= ' AND '.CommonHelper::get_children($cat_id);
            }
            if (! empty($brand_id)) {
                $goods_where .= " AND g.`brand_id` = '$brand_id'";
            }

            $query = DB::table('goods as g')
                ->select('goods_id', 'goods_name')
                ->limit(50);

            return response()->json(
                $query->get()
            );
        } else {
            $proc_thumb = (isset($GLOBALS['shop_id']) && $GLOBALS['shop_id'] > 0);
            $do_album = empty($_GET['do_album']) ? 0 : 1;
            $do_icon = empty($_GET['do_icon']) ? 0 : 1;
            $goods_id = trim($_GET['goods_id']);
            $brand_id = intval($_GET['brand_id']);
            $cat_id = intval($_GET['cat_id']);
            $goods_where = '';
            $album_where = '';
            $module_no = 0;

            if ($do_album === 1 and $do_icon === 0) {
                $module_no = 1;
            }
            if (empty($goods_id)) {
                if (! empty($cat_id)) {
                    $goods_where .= ' AND '.CommonHelper::get_children($cat_id);
                }
                if (! empty($brand_id)) {
                    $goods_where .= " AND g.`brand_id` = '$brand_id'";
                }
            } else {
                $goods_where .= ' AND g.`goods_id` '.db_create_in($goods_id);
            }

            if (! empty($goods_where)) {
                $album_where = ', '.ecs()->table('goods')." AS g WHERE album.img_original > '' AND album.goods_id = g.goods_id ".$goods_where;
            } else {
                $album_where = " WHERE album.img_original > ''";
            }

            // 设置最长执行时间为5分钟
            @set_time_limit(300);

            if (isset($_GET['start'])) {
                $page_size = 50; // 默认50张/页
                $thumb = empty($_GET['thumb']) ? 0 : 1;
                $watermark = empty($_GET['watermark']) ? 0 : 1;
                $change = empty($_GET['change']) ? 0 : 1;
                $silent = empty($_GET['silent']) ? 0 : 1;

                // 检查GD
                if ($image->gd_version() < 1) {
                    return $this->make_json_error(lang('missing_gd'));
                }

                // 如果需要添加水印，检查水印文件
                if ((! empty(cfg('watermark'))) && (cfg('watermark_place') > 0) && $watermark && (! $image->validate_image(cfg('watermark')))) {
                    return $this->make_json_error($image->error_msg());
                }
                $title = '';

                if (isset($_GET['total_icon'])) {
                    $count = DB::selectOne('SELECT COUNT(*) AS cnt FROM '.ecs()->table('goods')." AS g WHERE g.original_img <> ''".$goods_where)->cnt;
                    $title = sprintf(lang('goods_format'), $count, $page_size);
                }

                if (isset($_GET['total_album'])) {
                    $count = DB::selectOne('SELECT COUNT(*) AS cnt FROM '.ecs()->table('goods_gallery').' AS album '.$album_where)->cnt;
                    $title = sprintf('&nbsp;'.lang('gallery_format'), $count, $page_size);
                    $module_no = 1;
                }
                $result = [
                    'error' => 0,
                    'message' => '',
                    'content' => '',
                    'module_no' => $module_no,
                    'done' => 1,
                    'title' => $title,
                    'page_size' => $page_size,
                    'page' => 1,
                    'thumb' => $thumb,
                    'watermark' => $watermark,
                    'total' => 1,
                    'change' => $change,
                    'silent' => $silent,
                    'do_album' => $do_album,
                    'do_icon' => $do_icon,
                    'goods_id' => $goods_id,
                    'brand_id' => $brand_id,
                    'cat_id' => $cat_id,
                    'row' => [
                        'new_page' => sprintf(lang('page_format'), 1),
                        'new_total' => sprintf(lang('total_format'), ceil($count / $page_size)),
                        'new_time' => lang('wait'),
                        'cur_id' => 'time_1',
                    ],
                ];

                return response()->json($result);
            } else {
                $result = ['error' => 0, 'message' => '', 'content' => '', 'done' => 2, 'do_album' => $do_album, 'do_icon' => $do_icon, 'goods_id' => $goods_id, 'brand_id' => $brand_id, 'cat_id' => $cat_id];
                $result['thumb'] = empty($_GET['thumb']) ? 0 : 1;
                $result['watermark'] = empty($_GET['watermark']) ? 0 : 1;
                $result['change'] = empty($_GET['change']) ? 0 : 1;
                $result['page_size'] = empty($_GET['page_size']) ? 100 : intval($_GET['page_size']);
                $result['module_no'] = empty($_GET['module_no']) ? 0 : intval($_GET['module_no']);
                $result['page'] = isset($_GET['page']) ? intval($_GET['page']) : 1;
                $result['total'] = isset($_GET['total']) ? intval($_GET['total']) : 1;
                $result['silent'] = empty($_GET['silent']) ? 0 : 1;

                if ($result['silent']) {
                    $err_msg = [];
                }

                // ------------------------------------------------------
                // -- 商品图片
                // ------------------------------------------------------
                if ($result['module_no'] === 0) {
                    $count = DB::selectOne('SELECT COUNT(*) AS cnt FROM '.ecs()->table('goods')." AS g WHERE g.original_img > ''".$goods_where)->cnt;
                    // 页数在许可范围内
                    if ($result['page'] <= ceil($count / $result['page_size'])) {
                        $start_time = TimeHelper::gmtime(); // 开始执行时间

                        // 开始处理
                        if ($proc_thumb) {
                            $this->process_image_ex($result['page'], $result['page_size'], $result['module_no'], $result['thumb'], $result['watermark'], $result['change'], $result['silent']);
                        } else {
                            $this->process_image($result['page'], $result['page_size'], $result['module_no'], $result['thumb'], $result['watermark'], $result['change'], $result['silent']);
                        }
                        $end_time = TimeHelper::gmtime();
                        $result['row']['pre_id'] = 'time_'.$result['total'];
                        $result['row']['pre_time'] = ($end_time > $start_time) ? $end_time - $start_time : 1;
                        $result['row']['pre_time'] = sprintf(lang('time_format'), $result['row']['pre_time']);
                        $result['row']['cur_id'] = 'time_'.($result['total'] + 1);
                        $result['page']++; // 新行
                        $result['row']['new_page'] = sprintf(lang('page_format'), $result['page']);
                        $result['row']['new_total'] = sprintf(lang('total_format'), ceil($count / $result['page_size']));
                        $result['row']['new_time'] = lang('wait');
                        $result['total']++;
                    } else {
                        $result['total']--;
                        $result['page']--;
                        $result['done'] = 0;
                        $result['message'] = ($do_album) ? '' : lang('done');
                        // 清除缓存
                        $this->clear_cache_files();

                        return response()->json($result);
                    }
                } elseif ($result['module_no'] === 1 && $result['do_album'] === 1) {
                    // 商品相册
                    $count = DB::selectOne('SELECT COUNT(*) AS cnt FROM '.ecs()->table('goods_gallery').' AS album '.$album_where)->cnt;

                    if ($result['page'] <= ceil($count / $result['page_size'])) {
                        $start_time = TimeHelper::gmtime(); // 开始执行时间
                        // 开始处理
                        if ($proc_thumb) {
                            $this->process_image_ex($result['page'], $result['page_size'], $result['module_no'], $result['thumb'], $result['watermark'], $result['change'], $result['silent']);
                        } else {
                            $this->process_image($result['page'], $result['page_size'], $result['module_no'], $result['thumb'], $result['watermark'], $result['change'], $result['silent']);
                        }
                        $end_time = TimeHelper::gmtime();

                        $result['row']['pre_id'] = 'time_'.$result['total'];
                        $result['row']['pre_time'] = ($end_time > $start_time) ? $end_time - $start_time : 1;
                        $result['row']['pre_time'] = sprintf(lang('time_format'), $result['row']['pre_time']);
                        $result['row']['cur_id'] = 'time_'.($result['total'] + 1);
                        $result['page']++;
                        $result['row']['new_page'] = sprintf(lang('page_format'), $result['page']);
                        $result['row']['new_total'] = sprintf(lang('total_format'), ceil($count / $result['page_size']));
                        $result['row']['new_time'] = lang('wait');

                        $result['total']++;
                    } else {
                        $result['row']['pre_id'] = 'time_'.$result['total'];
                        $result['row']['cur_id'] = 'time_'.($result['total'] + 1);
                        $result['row']['new_page'] = sprintf(lang('page_format'), $result['page']);
                        $result['row']['new_total'] = sprintf(lang('total_format'), ceil($count / $result['page_size']));
                        $result['row']['new_time'] = lang('wait');

                        // 执行结束
                        $result['done'] = 0;
                        $result['message'] = lang('done');
                        // 清除缓存
                        $this->clear_cache_files();
                    }
                }

                if ($result['silent'] && $err_msg) {
                    $result['content'] = implode('<br />', $err_msg);
                }

                return response()->json($result);
            }
        }
    }

    /**
     * 图片处理函数
     *
     * @param  int  $page
     * @param  int  $page_size
     * @param  int  $type
     * @param  bool  $thumb  是否生成缩略图
     * @param  bool  $watermark  是否生成水印图
     * @param  bool  $change  true 生成新图，删除旧图 false 用新图覆盖旧图
     * @param  bool  $silent  是否执行能忽略错误
     * @return void
     */
    private function process_image($page = 1, $page_size = 100, $type = 0, $thumb = true, $watermark = true, $change = false, $silent = true)
    {
        if ($type === 0) {
            $res = DB::table('goods as g')
                ->select('g.goods_id', 'g.original_img', 'g.goods_img', 'g.goods_thumb')
                ->where('g.original_img', '>', '')
                ->offset(($page - 1) * $page_size)
                ->limit($page_size)
                ->get();
            foreach ($res as $row) {
                $goods_thumb = '';
                $image = '';

                // 水印
                if ($watermark) {
                    // 获取加水印图片的目录
                    if (empty($row['goods_img'])) {
                        $dir = dirname(ROOT_PATH.$row['original_img']).'/';
                    } else {
                        $dir = dirname(ROOT_PATH.$row['goods_img']).'/';
                    }

                    $image = $GLOBALS['image']->make_thumb(ROOT_PATH.$row['original_img'], cfg('image_width'), cfg('image_height'), $dir); // 先生成缩略图

                    if (! $image) {
                        // 出错返回
                        $msg = sprintf(lang('error_pos'), $row['goods_id'])."\n".$GLOBALS['image']->error_msg();
                        if ($silent) {
                            $GLOBALS['err_msg'][] = $msg;

                            continue;
                        } else {
                            return $this->make_json_error($msg);
                        }
                    }

                    $image = $GLOBALS['image']->add_watermark(ROOT_PATH.$image, '', cfg('watermark'), cfg('watermark_place'), cfg('watermark_alpha'));

                    if (! $image) {
                        // 出错返回
                        $msg = sprintf(lang('error_pos'), $row['goods_id'])."\n".$GLOBALS['image']->error_msg();
                        if ($silent) {
                            $GLOBALS['err_msg'][] = $msg;

                            continue;
                        } else {
                            return $this->make_json_error($msg);
                        }
                    }

                    // 重新格式化图片名称
                    $image = GoodsHelper::reformat_image_name('goods', $row['goods_id'], $image, 'goods');
                    if ($change || empty($row['goods_img'])) {
                        // 要生成新链接的处理过程
                        if ($image != $row['goods_img']) {
                            DB::table('goods')->where('goods_id', $row['goods_id'])->update(['goods_img' => $image]);
                            // 防止原图被删除
                            if ($row['goods_img'] != $row['original_img']) {
                                @unlink(ROOT_PATH.$row['goods_img']);
                            }
                        }
                    } else {
                        $this->replace_image($image, $row['goods_img'], $row['goods_id'], $silent);
                    }
                }

                // 缩略图
                if ($thumb) {
                    if (empty($row['goods_thumb'])) {
                        $dir = dirname(ROOT_PATH.$row['original_img']).'/';
                    } else {
                        $dir = dirname(ROOT_PATH.$row['goods_thumb']).'/';
                    }

                    $goods_thumb = $GLOBALS['image']->make_thumb(ROOT_PATH.$row['original_img'], cfg('thumb_width'), cfg('thumb_height'), $dir);

                    // 出错处理
                    if (! $goods_thumb) {
                        $msg = sprintf(lang('error_pos'), $row['goods_id'])."\n".$GLOBALS['image']->error_msg();
                        if ($silent) {
                            $GLOBALS['err_msg'][] = $msg;

                            continue;
                        } else {
                            return $this->make_json_error($msg);
                        }
                    }
                    // 重新格式化图片名称
                    $goods_thumb = GoodsHelper::reformat_image_name('goods_thumb', $row['goods_id'], $goods_thumb, 'thumb');
                    if ($change || empty($row['goods_thumb'])) {
                        if ($row['goods_thumb'] != $goods_thumb) {
                            DB::table('goods')->where('goods_id', $row['goods_id'])->update(['goods_thumb' => $goods_thumb]);
                            // 防止原图被删除
                            if ($row['goods_thumb'] != $row['original_img']) {
                                @unlink(ROOT_PATH.$row['goods_thumb']);
                            }
                        }
                    } else {
                        $this->replace_image($goods_thumb, $row['goods_thumb'], $row['goods_id'], $silent);
                    }
                }
            }
        } else {
            // 遍历商品相册
            $res = DB::table('goods_gallery as album')
                ->select('album.goods_id', 'album.img_id', 'album.img_url', 'album.thumb_url', 'album.img_original')
                ->offset(($page - 1) * $page_size)
                ->limit($page_size)
                ->get();

            foreach ($res as $row) {
                $thumb_url = '';
                $image = '';

                // 水印
                if ($watermark && file_exists(ROOT_PATH.$row['img_original'])) {
                    if (empty($row['img_url'])) {
                        $dir = dirname(ROOT_PATH.$row['img_original']).'/';
                    } else {
                        $dir = dirname(ROOT_PATH.$row['img_url']).'/';
                    }

                    $file_name = cls_image::unique_name($dir);
                    $file_name .= cls_image::get_filetype(empty($row['img_url']) ? $row['img_original'] : $row['img_url']);

                    copy(ROOT_PATH.$row['img_original'], $dir.$file_name);
                    $image = $GLOBALS['image']->add_watermark($dir.$file_name, '', cfg('watermark'), cfg('watermark_place'), cfg('watermark_alpha'));
                    if (! $image) {
                        @unlink($dir.$file_name);
                        $msg = sprintf(lang('error_pos'), $row['goods_id'])."\n".$GLOBALS['image']->error_msg();
                        if ($silent) {
                            $GLOBALS['err_msg'][] = $msg;

                            continue;
                        } else {
                            return $this->make_json_error($msg);
                        }
                    }
                    // 重新格式化图片名称
                    $image = GoodsHelper::reformat_image_name('gallery', $row['goods_id'], $image, 'goods');
                    if ($change || empty($row['img_url']) || $row['img_original'] === $row['img_url']) {
                        if ($image != $row['img_url']) {
                            DB::table('goods_gallery')->where('img_id', $row['img_id'])->update(['img_url' => $image]);
                            if ($row['img_original'] != $row['img_url']) {
                                @unlink(ROOT_PATH.$row['img_url']);
                            }
                        }
                    } else {
                        $this->replace_image($image, $row['img_url'], $row['goods_id'], $silent);
                    }
                }

                // 缩略图
                if ($thumb) {
                    if (empty($row['thumb_url'])) {
                        $dir = dirname(ROOT_PATH.$row['img_original']).'/';
                    } else {
                        $dir = dirname(ROOT_PATH.$row['thumb_url']).'/';
                    }

                    $thumb_url = $GLOBALS['image']->make_thumb(ROOT_PATH.$row['img_original'], cfg('thumb_width'), cfg('thumb_height'), $dir);

                    if (! $thumb_url) {
                        $msg = sprintf(lang('error_pos'), $row['goods_id'])."\n".$GLOBALS['image']->error_msg();
                        if ($silent) {
                            $GLOBALS['err_msg'][] = $msg;

                            continue;
                        } else {
                            return $this->make_json_error($msg);
                        }
                    }
                    // 重新格式化图片名称
                    $thumb_url = GoodsHelper::reformat_image_name('gallery_thumb', $row['goods_id'], $thumb_url, 'thumb');
                    if ($change || empty($row['thumb_url'])) {
                        if ($thumb_url != $row['thumb_url']) {
                            DB::table('goods_gallery')->where('img_id', $row['img_id'])->update(['thumb_url' => $thumb_url]);
                            @unlink(ROOT_PATH.$row['thumb_url']);
                        }
                    } else {
                        $this->replace_image($thumb_url, $row['thumb_url'], $row['goods_id'], $silent);
                    }
                }
            }
        }
    }

    /**
     * 图片处理函数
     *
     * @param  int  $page
     * @param  int  $page_size
     * @param  int  $type
     * @param  bool  $thumb  是否生成缩略图
     * @param  bool  $watermark  是否生成水印图
     * @param  bool  $change  true 生成新图，删除旧图 false 用新图覆盖旧图
     * @param  bool  $silent  是否执行能忽略错误
     * @return void
     */
    private function process_image_ex($page = 1, $page_size = 100, $type = 0, $thumb = true, $watermark = true, $change = false, $silent = true)
    {
        if ($type === 0) {
            $res = DB::table('goods as g')
                ->select('g.goods_id', 'g.original_img', 'g.goods_img', 'g.goods_thumb')
                ->where('g.original_img', '>', '')
                ->offset(($page - 1) * $page_size)
                ->limit($page_size)
                ->get();

            foreach ($res as $row) {
                if ($thumb) {
                    CommonHelper::get_image_path('');
                }
                if ($watermark) {
                    CommonHelper::get_image_path('');
                }
            }
        } else {
            $res = DB::table('goods_gallery as album')
                ->select('album.goods_id', 'album.img_id', 'album.img_url', 'album.thumb_url', 'album.img_original')
                ->offset(($page - 1) * $page_size)
                ->limit($page_size)
                ->get();

            foreach ($res as $row) {
                if ($thumb) {
                    CommonHelper::get_image_path($row['img_original']);
                }
                if ($watermark) {
                    CommonHelper::get_image_path($row['img_original']);
                }
            }
        }
    }

    /**
     *  用新图片替换指定图片
     *
     * @param  string  $new_image  新图片
     * @param  string  $old_image  旧图片
     * @param  string  $goods_id  商品图片
     * @param  bool  $silent  是否使用静态函数
     * @return void
     */
    private function replace_image($new_image, $old_image, $goods_id, $silent)
    {
        $error = false;
        if (file_exists(ROOT_PATH.$old_image)) {
            @rename(ROOT_PATH.$old_image, ROOT_PATH.$old_image.'.bak');
            if (! @rename(ROOT_PATH.$new_image, ROOT_PATH.$old_image)) {
                $error = true;
            }
        } else {
            if (! @rename(ROOT_PATH.$new_image, ROOT_PATH.$old_image)) {
                $error = true;
            }
        }
        if ($error === true) {
            if (file_exists(ROOT_PATH.$old_image.'.bak')) {
                @rename(ROOT_PATH.$old_image.'.bak', ROOT_PATH.$old_image);
            }
            $msg = sprintf(lang('error_pos'), $goods_id)."\n".sprintf(lang('error_rename'), $new_image, $old_image);
            if ($silent) {
                $GLOBALS['err_msg'][] = $msg;
            } else {
                return $this->make_json_error($msg);
            }
        } else {
            if (file_exists(ROOT_PATH.$old_image.'.bak')) {
                @unlink(ROOT_PATH.$old_image.'.bak');
            }

            return;
        }
    }
}
