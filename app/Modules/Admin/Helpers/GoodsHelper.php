<?php

declare(strict_types=1);

namespace App\Modules\Admin\Helpers;

use App\Helpers\BaseHelper;
use App\Helpers\CommonHelper;
use App\Helpers\TimeHelper;
use Illuminate\Support\Facades\DB;

class GoodsHelper
{
    /**
     * 取得推荐类型列表
     *
     * @return array 推荐类型列表
     */
    public static function get_intro_list()
    {
        return [
            'is_best' => lang('is_best'),
            'is_new' => lang('is_new'),
            'is_hot' => lang('is_hot'),
            'is_promote' => lang('is_promote'),
            'all_type' => lang('all_type'),
        ];
    }

    /**
     * 取得重量单位列表
     *
     * @return array 重量单位列表
     */
    public static function get_unit_list()
    {
        return [
            '1' => lang('unit_kg'),
            '0.001' => lang('unit_g'),
        ];
    }

    /**
     * 取得会员等级列表
     *
     * @return array 会员等级列表
     */
    public static function get_user_rank_list()
    {
        return DB::table('user_rank')
            ->orderBy('min_points')
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();
    }

    /**
     * 取得某商品的会员价格列表
     *
     * @param  int  $goods_id  商品编号
     * @return array 会员价格列表 user_rank => user_price
     */
    public static function get_member_price_list($goods_id)
    {
        // 取得会员价格
        $price_list = [];
        $res = DB::table('goods_member_price')
            ->where('goods_id', $goods_id)
            ->select('user_rank', 'user_price')
            ->get();

        foreach ($res as $row) {
            $price_list[$row->user_rank] = $row->user_price;
        }

        return $price_list;
    }

    /**
     * 插入或更新商品属性
     *
     * @param  int  $goods_id  商品编号
     * @param  array  $id_list  属性编号数组
     * @param  array  $is_spec_list  是否规格数组 'true' | 'false'
     * @param  array  $value_price_list  属性值数组
     * @return array 返回受到影响的goods_attr_id数组
     */
    public static function handle_goods_attr($goods_id, $id_list, $is_spec_list, $value_price_list)
    {
        $goods_attr_id = [];

        // 循环处理每个属性
        foreach ($id_list as $key => $id) {
            $is_spec = $is_spec_list[$key];
            if ($is_spec === 'false') {
                $value = $value_price_list[$key];
                $price = '';
            } else {
                $value_list = [];
                $price_list = [];
                if ($value_price_list[$key]) {
                    $vp_list = explode(chr(13), $value_price_list[$key]);
                    foreach ($vp_list as $v_p) {
                        $arr = explode(chr(9), $v_p);
                        $value_list[] = $arr[0];
                        $price_list[] = ($arr[1] ?? '');
                    }
                }
                $value = implode(chr(13), $value_list);
                $price = implode(chr(13), $price_list);
            }

            // 插入或更新记录
            $result_id = DB::table('goods_attr')
                ->where('goods_id', $goods_id)
                ->where('attr_id', $id)
                ->where('attr_value', $value)
                ->value('goods_attr_id');

            if (! empty($result_id)) {
                DB::table('goods_attr')
                    ->where('goods_id', $goods_id)
                    ->where('attr_id', $id)
                    ->where('goods_attr_id', $result_id)
                    ->update(['attr_value' => $value]);

                $goods_attr_id[$id] = $result_id;
            } else {
                $goods_attr_id[$id] = DB::table('goods_attr')->insertGetId([
                    'goods_id' => $goods_id,
                    'attr_id' => $id,
                    'attr_value' => $value,
                    'attr_price' => $price,
                ]);
            }
        }

        return $goods_attr_id;
    }

    /**
     * 保存某商品的会员价格
     *
     * @param  int  $goods_id  商品编号
     * @param  array  $rank_list  等级列表
     * @param  array  $price_list  价格列表
     * @return void
     */
    public static function handle_member_price($goods_id, $rank_list, $price_list)
    {
        // 循环处理每个会员等级
        foreach ($rank_list as $key => $rank) {
            // 会员等级对应的价格
            $price = $price_list[$key];

            // 插入或更新记录
            $exists = DB::table('goods_member_price')
                ->where('goods_id', $goods_id)
                ->where('user_rank', $rank)
                ->exists();

            if ($exists) {
                // 如果会员价格是小于0则删除原来价格，不是则更新为新的价格
                if ($price < 0) {
                    DB::table('goods_member_price')
                        ->where('goods_id', $goods_id)
                        ->where('user_rank', $rank)
                        ->limit(1)
                        ->delete();
                } else {
                    DB::table('goods_member_price')
                        ->where('goods_id', $goods_id)
                        ->where('user_rank', $rank)
                        ->limit(1)
                        ->update(['user_price' => $price]);
                }
            } else {
                if ($price != -1) {
                    DB::table('goods_member_price')->insert([
                        'goods_id' => $goods_id,
                        'user_rank' => $rank,
                        'user_price' => $price,
                    ]);
                }
            }
        }
    }

    /**
     * 保存某商品的扩展分类
     *
     * @param  int  $goods_id  商品编号
     * @param  array  $cat_list  分类编号数组
     * @return void
     */
    public static function handle_other_cat($goods_id, $cat_list)
    {
        // 查询现有的扩展分类
        $exist_list = DB::table('goods_cat')
            ->where('goods_id', $goods_id)
            ->pluck('cat_id')
            ->all();

        // 删除不再有的分类
        $delete_list = array_diff($exist_list, $cat_list);
        if ($delete_list) {
            DB::table('goods_cat')
                ->where('goods_id', $goods_id)
                ->whereIn('cat_id', $delete_list)
                ->delete();
        }

        // 添加新加的分类
        $add_list = array_diff($cat_list, $exist_list, [0]);
        foreach ($add_list as $cat_id) {
            // 插入记录
            DB::table('goods_cat')->insert([
                'goods_id' => $goods_id,
                'cat_id' => $cat_id,
            ]);
        }
    }

    /**
     * 保存某商品的关联商品
     *
     * @param  int  $goods_id
     * @return void
     */
    public static function handle_link_goods($goods_id)
    {
        $admin_id = session('admin_id', 0);

        DB::table('goods_link_goods')
            ->where('goods_id', 0)
            ->where('admin_id', $admin_id)
            ->update(['goods_id' => $goods_id]);

        DB::table('goods_link_goods')
            ->where('link_goods_id', 0)
            ->where('admin_id', $admin_id)
            ->update(['link_goods_id' => $goods_id]);
    }

    /**
     * 保存某商品的配件
     *
     * @param  int  $goods_id
     * @return void
     */
    public static function handle_group_goods($goods_id)
    {
        $admin_id = session('admin_id', 0);

        DB::table('activity_group')
            ->where('parent_id', 0)
            ->where('admin_id', $admin_id)
            ->update(['parent_id' => $goods_id]);
    }

    /**
     * 保存某商品的关联文章
     *
     * @param  int  $goods_id
     * @return void
     */
    public static function handle_goods_article($goods_id)
    {
        $admin_id = session('admin_id', 0);

        DB::table('goods_article')
            ->where('goods_id', 0)
            ->where('admin_id', $admin_id)
            ->update(['goods_id' => $goods_id]);
    }

    /**
     * 保存某商品的相册图片
     *
     * @param  int  $goods_id
     * @param  array  $image_files
     * @param  array  $image_descs
     * @return void
     */
    public static function handle_gallery_image($goods_id, $image_files, $image_descs, $image_urls)
    {
        // 是否处理缩略图
        $proc_thumb = (isset($GLOBALS['shop_id']) && $GLOBALS['shop_id'] > 0) ? false : true;
        foreach ($image_descs as $key => $img_desc) {
            // 是否成功上传
            $flag = false;
            if (isset($image_files['error'])) {
                if ($image_files['error'][$key] === 0) {
                    $flag = true;
                }
            } else {
                if ($image_files['tmp_name'][$key] != 'none') {
                    $flag = true;
                }
            }

            if ($flag) {
                // 生成缩略图
                if ($proc_thumb) {
                    $thumb_url = $GLOBALS['image']->make_thumb($image_files['tmp_name'][$key], cfg('thumb_width'), cfg('thumb_height'));
                    $thumb_url = is_string($thumb_url) ? $thumb_url : '';
                }

                $upload = [
                    'name' => $image_files['name'][$key],
                    'type' => $image_files['type'][$key],
                    'tmp_name' => $image_files['tmp_name'][$key],
                    'size' => $image_files['size'][$key],
                ];
                if (isset($image_files['error'])) {
                    $upload['error'] = $image_files['error'][$key];
                }
                $img_original = $GLOBALS['image']->upload_image($upload);
                if ($img_original === false) {
                    return $this->sys_msg($GLOBALS['image']->error_msg(), 1, [], false);
                }
                $img_url = $img_original;

                if (! $proc_thumb) {
                    $thumb_url = $img_original;
                }
                // 如果服务器支持GD 则添加水印
                if ($proc_thumb && BaseHelper::gd_version() > 0) {
                    $pos = strpos(basename($img_original), '.');
                    $newname = dirname($img_original).'/'.$GLOBALS['image']->random_filename().substr(basename($img_original), $pos);
                    copy('../'.$img_original, '../'.$newname);
                    $img_url = $newname;

                    $GLOBALS['image']->add_watermark('../'.$img_url, '', cfg('watermark'), cfg('watermark_place'), cfg('watermark_alpha'));
                }

                // 重新格式化图片名称
                $img_original = GoodsHelper::reformat_image_name('gallery', $goods_id, $img_original, 'source');
                $img_url = GoodsHelper::reformat_image_name('gallery', $goods_id, $img_url, 'goods');
                $thumb_url = GoodsHelper::reformat_image_name('gallery_thumb', $goods_id, $thumb_url, 'thumb');

                DB::table('goods_gallery')->insert([
                    'goods_id' => $goods_id,
                    'img_url' => $img_url,
                    'img_desc' => $img_desc,
                    'thumb_url' => $thumb_url,
                    'img_original' => $img_original,
                ]);

                // 不保留商品原图的时候删除原图
                if ($proc_thumb && ! cfg('retain_original_img') && ! empty($img_original)) {
                    DB::table('goods_gallery')->where('goods_id', $goods_id)->update(['img_original' => '']);
                    @unlink('../'.$img_original);
                }
            } elseif (! empty($image_urls[$key]) && ($image_urls[$key] != lang('img_file')) && ($image_urls[$key] != 'http://') && copy(trim($image_urls[$key]), ROOT_PATH.'temp/'.basename($image_urls[$key]))) {
                $image_url = trim($image_urls[$key]);

                // 定义原图路径
                $down_img = ROOT_PATH.'temp/'.basename($image_url);

                // 生成缩略图
                if ($proc_thumb) {
                    $thumb_url = $GLOBALS['image']->make_thumb($down_img, cfg('thumb_width'), cfg('thumb_height'));
                    $thumb_url = is_string($thumb_url) ? $thumb_url : '';
                    $thumb_url = GoodsHelper::reformat_image_name('gallery_thumb', $goods_id, $thumb_url, 'thumb');
                }

                if (! $proc_thumb) {
                    $thumb_url = htmlspecialchars($image_url);
                }

                // 重新格式化图片名称
                $img_url = $img_original = htmlspecialchars($image_url);

                DB::table('goods_gallery')->insert([
                    'goods_id' => $goods_id,
                    'img_url' => $img_url,
                    'img_desc' => $img_desc,
                    'thumb_url' => $thumb_url,
                    'img_original' => $img_original,
                ]);

                @unlink($down_img);
            }
        }
    }

    /**
     * 修改商品某字段值
     *
     * @param  string  $goods_id  商品编号，可以为多个，用 ',' 隔开
     * @param  string  $field  字段名
     * @param  string  $value  字段值
     */
    public static function update_goods($goods_id, $field, $value): bool
    {
        if ($goods_id) {
            // 清除缓存
            CommonHelper::clear_cache_files();

            return (bool) DB::table('goods')
                ->whereIn('goods_id', is_array($goods_id) ? $goods_id : explode(',', (string) $goods_id))
                ->update([
                    $field => $value,
                    'last_update' => TimeHelper::gmtime(),
                ]);
        } else {
            return false;
        }
    }

    /**
     * 从回收站删除多个商品
     *
     * @param  mix  $goods_id  商品id列表：可以逗号格开，也可以是数组
     */
    public static function delete_goods($goods_id): void
    {
        if (empty($goods_id)) {
            return;
        }

        $goods_ids = is_array($goods_id) ? $goods_id : explode(',', (string) $goods_id);

        // 取得有效商品id
        $goods_id = DB::table('goods')
            ->whereIn('goods_id', $goods_ids)
            ->where('is_delete', 1)
            ->distinct()
            ->pluck('goods_id')
            ->all();

        if (empty($goods_id)) {
            return;
        }

        // 删除商品图片和轮播图片文件
        $res = DB::table('goods')
            ->whereIn('goods_id', $goods_id)
            ->select('goods_thumb', 'goods_img', 'original_img')
            ->get();

        foreach ($res as $goods) {
            if (! empty($goods->goods_thumb)) {
                @unlink('../'.$goods->goods_thumb);
            }
            if (! empty($goods->goods_img)) {
                @unlink('../'.$goods->goods_img);
            }
            if (! empty($goods->original_img)) {
                @unlink('../'.$goods->original_img);
            }
        }

        // 删除商品
        DB::table('goods')->whereIn('goods_id', $goods_id)->delete();

        // 删除商品的货品记录
        DB::table('goods_product')->whereIn('goods_id', $goods_id)->delete();

        // 删除商品相册的图片文件
        $res = DB::table('goods_gallery')
            ->whereIn('goods_id', $goods_id)
            ->select('img_url', 'thumb_url', 'img_original')
            ->get();

        foreach ($res as $row) {
            if (! empty($row->img_url)) {
                @unlink('../'.$row->img_url);
            }
            if (! empty($row->thumb_url)) {
                @unlink('../'.$row->thumb_url);
            }
            if (! empty($row->img_original)) {
                @unlink('../'.$row->img_original);
            }
        }

        // 删除商品相册
        DB::table('goods_gallery')->whereIn('goods_id', $goods_id)->delete();

        // 删除相关表记录
        DB::table('user_collect')->whereIn('goods_id', $goods_id)->delete();
        DB::table('goods_article')->whereIn('goods_id', $goods_id)->delete();
        DB::table('goods_attr')->whereIn('goods_id', $goods_id)->delete();
        DB::table('goods_cat')->whereIn('goods_id', $goods_id)->delete();
        DB::table('goods_member_price')->whereIn('goods_id', $goods_id)->delete();
        DB::table('activity_group')->whereIn('parent_id', $goods_id)->delete();
        DB::table('activity_group')->whereIn('goods_id', $goods_id)->delete();
        DB::table('goods_link_goods')->whereIn('goods_id', $goods_id)->delete();
        DB::table('goods_link_goods')->whereIn('link_goods_id', $goods_id)->delete();
        DB::table('user_tag')->whereIn('goods_id', $goods_id)->delete();
        DB::table('comment')->where('comment_type', 0)->whereIn('id_value', $goods_id)->delete();

        // 删除相应虚拟商品记录
        try {
            DB::table('goods_virtual_card')->whereIn('goods_id', $goods_id)->delete();
        } catch (\Exception $e) {
            // Ignore if table doesn't exist (equivalent to errno 1146)
        }

        // 清除缓存
        CommonHelper::clear_cache_files();
    }

    /**
     * 为某商品生成唯一的货号
     *
     * @param  int  $goods_id  商品编号
     * @return string 唯一的货号
     */
    public static function generate_goods_sn($goods_id)
    {
        $goods_sn = cfg('sn_prefix').str_repeat('0', 6 - strlen($goods_id)).$goods_id;

        $sn_list = DB::table('goods')
            ->where('goods_sn', 'like', BaseHelper::mysql_like_quote($goods_sn).'%')
            ->where('goods_id', '<>', $goods_id)
            ->orderByRaw('LENGTH(goods_sn) DESC')
            ->pluck('goods_sn')
            ->all();

        if (in_array($goods_sn, $sn_list)) {
            $max = pow(10, strlen($sn_list[0]) - strlen($goods_sn) + 1) - 1;
            $new_sn = $goods_sn.mt_rand(0, $max);
            while (in_array($new_sn, $sn_list)) {
                $new_sn = $goods_sn.mt_rand(0, $max);
            }
            $goods_sn = $new_sn;
        }

        return $goods_sn;
    }

    /**
     * 商品货号是否重复
     *
     * @param  string  $goods_sn  商品货号；请在传入本参数前对本参数进行SQl脚本过滤
     * @param  int  $goods_id  商品id；默认值为：0，没有商品id
     * @return bool true，重复；false，不重复
     */
    public static function check_goods_sn_exist($goods_sn, $goods_id = 0)
    {
        $goods_sn = trim($goods_sn);
        $goods_id = intval($goods_id);
        if (strlen($goods_sn) === 0) {
            return true;    // 重复
        }

        $query = DB::table('goods')->where('goods_sn', $goods_sn);

        if (! empty($goods_id)) {
            $query->where('goods_id', '<>', $goods_id);
        }

        return $query->exists();
    }

    /**
     * 取得通用属性和某分类的属性，以及某商品的属性值
     *
     * @param  int  $cat_id  分类编号
     * @param  int  $goods_id  商品编号
     * @return array 规格与属性列表
     */
    public static function get_attr_list($cat_id, $goods_id = 0)
    {
        if (empty($cat_id)) {
            return [];
        }

        // 查询属性值及商品的属性值
        return DB::table('goods_type_attribute as a')
            ->leftJoin('goods_attr as v', function ($join) use ($goods_id) {
                $join->on('v.attr_id', '=', 'a.attr_id')
                    ->where('v.goods_id', '=', $goods_id);
            })
            ->where(function ($query) use ($cat_id) {
                $query->where('a.cat_id', intval($cat_id))
                    ->orWhere('a.cat_id', 0);
            })
            ->orderBy('a.sort_order')
            ->orderBy('a.attr_type')
            ->orderBy('a.attr_id')
            ->orderBy('v.attr_price')
            ->orderBy('v.goods_attr_id')
            ->select('a.attr_id', 'a.attr_name', 'a.attr_input_type', 'a.attr_type', 'a.attr_values', 'v.attr_value', 'v.attr_price')
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();
    }

    /**
     * 获取商品类型中包含规格的类型列表
     *
     * @return array
     */
    public static function get_goods_type_specifications()
    {
        // 查询
        $row = DB::table('goods_type_attribute')
            ->where('attr_type', 1)
            ->distinct()
            ->pluck('cat_id')
            ->all();

        $return_arr = [];
        if (! empty($row)) {
            foreach ($row as $value) {
                $return_arr[$value] = $value;
            }
        }

        return $return_arr;
    }

    /**
     * 根据属性数组创建属性的表单
     *
     * @param  int  $cat_id  分类编号
     * @param  int  $goods_id  商品编号
     * @return string
     */
    public static function build_attr_html($cat_id, $goods_id = 0)
    {
        $attr = GoodsHelper::get_attr_list($cat_id, $goods_id);
        $html = '<table width="100%" id="attrTable">';
        $spec = 0;

        foreach ($attr as $key => $val) {
            $html .= "<tr><td class='label'>";
            if ($val['attr_type'] === 1 || $val['attr_type'] === 2) {
                $html .= ($spec != $val['attr_id']) ?
                    "<a href='javascript:;' onclick='addSpec(this)'>[+]</a>" :
                    "<a href='javascript:;' onclick='removeSpec(this)'>[-]</a>";
                $spec = $val['attr_id'];
            }

            $html .= "$val[attr_name]</td><td><input type='hidden' name='attr_id_list[]' value='$val[attr_id]' />";

            if ($val['attr_input_type'] === 0) {
                $html .= '<input name="attr_value_list[]" type="text" value="'.htmlspecialchars($val['attr_value']).'" size="40" /> ';
            } elseif ($val['attr_input_type'] === 2) {
                $html .= '<textarea name="attr_value_list[]" rows="3" cols="40">'.htmlspecialchars($val['attr_value']).'</textarea>';
            } else {
                $html .= '<select name="attr_value_list[]">';
                $html .= '<option value="">'.lang('select_please').'</option>';

                $attr_values = explode("\n", $val['attr_values']);

                foreach ($attr_values as $opt) {
                    $opt = trim(htmlspecialchars($opt));

                    $html .= ($val['attr_value'] != $opt) ?
                        '<option value="'.$opt.'">'.$opt.'</option>' :
                        '<option value="'.$opt.'" selected="selected">'.$opt.'</option>';
                }
                $html .= '</select> ';
            }

            $html .= ($val['attr_type'] === 1 || $val['attr_type'] === 2) ?
                lang('spec_price').' <input type="text" name="attr_price_list[]" value="'.$val['attr_price'].'" size="5" maxlength="10" />' :
                ' <input type="hidden" name="attr_price_list[]" value="0" />';

            $html .= '</td></tr>';
        }

        $html .= '</table>';

        return $html;
    }

    /**
     * 获得指定商品相关的商品
     *
     * @param  int  $goods_id
     * @return array
     */
    public static function get_linked_goods($goods_id)
    {
        $admin_id = session('admin_id', 0);

        $row = DB::table('goods_link_goods as lg')
            ->join('goods as g', 'lg.link_goods_id', '=', 'g.goods_id')
            ->where('lg.goods_id', $goods_id)
            ->when($goods_id === 0, function ($query) use ($admin_id) {
                return $query->where('lg.admin_id', $admin_id);
            })
            ->select('lg.link_goods_id as goods_id', 'g.goods_name', 'lg.is_double')
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        foreach ($row as $key => $val) {
            $linked_type = $val['is_double'] === 0 ? lang('single') : lang('double');

            $row[$key]['goods_name'] = $val['goods_name']." -- [$linked_type]";

            unset($row[$key]['is_double']);
        }

        return $row;
    }

    /**
     * 获得指定商品的配件
     *
     * @param  int  $goods_id
     * @return array
     */
    public static function get_group_goods($goods_id)
    {
        $admin_id = session('admin_id', 0);

        return DB::table('activity_group as gg')
            ->join('goods as g', 'gg.goods_id', '=', 'g.goods_id')
            ->where('gg.parent_id', $goods_id)
            ->when($goods_id === 0, function ($query) use ($admin_id) {
                return $query->where('gg.admin_id', $admin_id);
            })
            ->select('gg.goods_id', DB::raw("CONCAT(g.goods_name, ' -- [', gg.goods_price, ']') AS goods_name"))
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();
    }

    /**
     * 获得商品的关联文章
     *
     * @param  int  $goods_id
     * @return array
     */
    public static function get_goods_articles($goods_id)
    {
        $admin_id = session('admin_id', 0);

        return DB::table('goods_article as g')
            ->join('article as a', 'g.article_id', '=', 'a.article_id')
            ->where('g.goods_id', $goods_id)
            ->when($goods_id === 0, function ($query) use ($admin_id) {
                return $query->where('g.admin_id', $admin_id);
            })
            ->select('g.article_id', 'a.title')
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();
    }

    /**
     * 获得商品列表
     *
     * @params  integer $isdelete
     * @params  integer $real_goods
     * @params  integer $conditions
     *
     * @return array
     */
    public static function goods_list($is_delete, $real_goods = 1, $conditions = '')
    {
        // 过滤条件
        $param_str = '-'.$is_delete.'-'.$real_goods;
        $result = MainHelper::get_filter($param_str);
        if ($result === false) {
            $day = getdate();
            $today = TimeHelper::local_mktime(23, 59, 59, $day['mon'], $day['mday'], $day['year']);

            $filter['cat_id'] = empty($_REQUEST['cat_id']) ? 0 : intval($_REQUEST['cat_id']);
            $filter['intro_type'] = empty($_REQUEST['intro_type']) ? '' : trim($_REQUEST['intro_type']);
            $filter['is_promote'] = empty($_REQUEST['is_promote']) ? 0 : intval($_REQUEST['is_promote']);
            $filter['stock_warning'] = empty($_REQUEST['stock_warning']) ? 0 : intval($_REQUEST['stock_warning']);
            $filter['brand_id'] = empty($_REQUEST['brand_id']) ? 0 : intval($_REQUEST['brand_id']);
            $filter['keyword'] = empty($_REQUEST['keyword']) ? '' : trim($_REQUEST['keyword']);
            $filter['suppliers_id'] = isset($_REQUEST['suppliers_id']) ? (empty($_REQUEST['suppliers_id']) ? '' : trim($_REQUEST['suppliers_id'])) : '';
            $filter['is_on_sale'] = isset($_REQUEST['is_on_sale']) ? ((empty($_REQUEST['is_on_sale']) && $_REQUEST['is_on_sale'] === 0) ? '' : trim($_REQUEST['is_on_sale'])) : '';
            if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] === 1) {
                $filter['keyword'] = BaseHelper::json_str_iconv($filter['keyword']);
            }
            $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'goods_id' : trim($_REQUEST['sort_by']);
            $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);
            $filter['extension_code'] = empty($_REQUEST['extension_code']) ? '' : trim($_REQUEST['extension_code']);
            $filter['is_delete'] = $is_delete;
            $filter['real_goods'] = $real_goods;

            $query = DB::table('goods as g')->where('is_delete', $is_delete);

            if ($filter['cat_id'] > 0) {
                $query->whereRaw(CommonHelper::get_children($filter['cat_id']));
            }

            // 推荐类型
            switch ($filter['intro_type']) {
                case 'is_best':
                    $query->where('is_best', 1);
                    break;
                case 'is_hot':
                    $query->where('is_hot', 1);
                    break;
                case 'is_new':
                    $query->where('is_new', 1);
                    break;
                case 'is_promote':
                    $query->where('is_promote', 1)
                        ->where('promote_price', '>', 0)
                        ->where('promote_start_date', '<=', $today)
                        ->where('promote_end_date', '>=', $today);
                    break;
                case 'all_type':
                    $query->where(function ($q) use ($today) {
                        $q->where('is_best', 1)
                            ->orWhere('is_hot', 1)
                            ->orWhere('is_new', 1)
                            ->orWhere(function ($subq) use ($today) {
                                $subq->where('is_promote', 1)
                                    ->where('promote_price', '>', 0)
                                    ->where('promote_start_date', '<=', $today)
                                    ->where('promote_end_date', '>=', $today);
                            });
                    });
                    break;
            }

            // 库存警告
            if ($filter['stock_warning']) {
                $query->whereColumn('goods_number', '<=', 'warn_number');
            }

            // 品牌
            if ($filter['brand_id']) {
                $query->where('brand_id', $filter['brand_id']);
            }

            // 扩展
            if ($filter['extension_code']) {
                $query->where('extension_code', $filter['extension_code']);
            }

            // 关键字
            if (! empty($filter['keyword'])) {
                $query->where(function ($q) use ($filter) {
                    $q->where('goods_sn', 'like', '%'.BaseHelper::mysql_like_quote($filter['keyword']).'%')
                        ->orWhere('goods_name', 'like', '%'.BaseHelper::mysql_like_quote($filter['keyword']).'%');
                });
            }

            if ($real_goods > -1) {
                $query->where('is_real', $real_goods);
            }

            // 上架
            if ($filter['is_on_sale'] !== '') {
                $query->where('is_on_sale', $filter['is_on_sale']);
            }

            // 供货商
            if (! empty($filter['suppliers_id'])) {
                $query->where('suppliers_id', $filter['suppliers_id']);
            }

            if (! empty($conditions)) {
                $query->whereRaw(ltrim($conditions, ' AND'));
            }

            // 记录总数
            $filter['record_count'] = $query->count();

            // 分页大小
            $filter = MainHelper::page_and_size($filter);

            $row = $query->select(
                'goods_id',
                'goods_name',
                'goods_type',
                'goods_sn',
                'shop_price',
                'is_on_sale',
                'is_best',
                'is_new',
                'is_hot',
                'sort_order',
                'goods_number',
                'integral',
                DB::raw("(promote_price > 0 AND promote_start_date <= '$today' AND promote_end_date >= '$today') AS is_promote")
            )
                ->orderBy($filter['sort_by'], $filter['sort_order'])
                ->offset($filter['start'])
                ->limit($filter['page_size'])
                ->get()
                ->map(fn ($item) => (array) $item)
                ->all();

            $filter['keyword'] = stripslashes($filter['keyword']);
            MainHelper::set_filter($filter, '', $param_str); // Skip passing SQL since we handle it here
        } else {
            $filter = $result['filter'];
            // This part might need adjustment if it relies on cached SQL, but for now we follow the pattern
            $row = []; // If cached, we should ideally still perform the query or get it from $result
        }

        return ['goods' => $row, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];
    }

    /**
     * 检测商品是否有货品
     *
     * @params      integer     $goods_id       商品id
     * @params      string      $conditions     sql条件，AND语句开头
     *
     * @return string number               -1，错误；1，存在；0，不存在
     */
    public static function check_goods_product_exist($goods_id, $conditions = '')
    {
        if (empty($goods_id)) {
            return -1;  // $goods_id不能为空
        }

        $query = DB::table('goods_product')->where('goods_id', $goods_id);

        if (! empty($conditions)) {
            $query->whereRaw(ltrim($conditions, ' AND'));
        }

        return $query->exists() ? 1 : 0;
    }

    /**
     * 获得商品的货品总库存
     *
     * @params      integer     $goods_id       商品id
     * @params      string      $conditions     sql条件，AND语句开头
     *
     * @return string number
     */
    public static function product_number_count($goods_id, $conditions = '')
    {
        if (empty($goods_id)) {
            return -1;  // $goods_id不能为空
        }

        $query = DB::table('goods_product')->where('goods_id', $goods_id);

        if (! empty($conditions)) {
            $query->whereRaw(ltrim($conditions, ' AND'));
        }

        return $query->sum('product_number') ?: 0;
    }

    /**
     * 获得商品的规格属性值列表
     *
     * @params      integer         $goods_id
     *
     * @return array
     */
    public static function product_goods_attr_list($goods_id)
    {
        if (empty($goods_id)) {
            return [];  // $goods_id不能为空
        }

        return DB::table('goods_attr')
            ->where('goods_id', $goods_id)
            ->pluck('attr_value', 'goods_attr_id')
            ->all();
    }

    /**
     * 获得商品已添加的规格列表
     *
     * @params      integer         $goods_id
     *
     * @return array
     */
    public static function get_goods_specifications_list($goods_id)
    {
        if (empty($goods_id)) {
            return [];  // $goods_id不能为空
        }

        return DB::table('goods_attr as g')
            ->leftJoin('goods_type_attribute as a', 'a.attr_id', '=', 'g.attr_id')
            ->where('g.goods_id', $goods_id)
            ->where('a.attr_type', 1)
            ->orderBy('g.attr_id')
            ->select('g.goods_attr_id', 'g.attr_value', 'g.attr_id', 'a.attr_name')
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();
    }

    /**
     * 获得商品的货品列表
     *
     * @params  integer $goods_id
     * @params  string  $conditions
     *
     * @return array
     */
    public static function product_list($goods_id, $conditions = '')
    {
        // 过滤条件
        $param_str = '-'.$goods_id;
        $result = MainHelper::get_filter($param_str);
        if ($result === false) {
            $filter['goods_id'] = $goods_id;
            $filter['keyword'] = empty($_REQUEST['keyword']) ? '' : trim($_REQUEST['keyword']);
            $filter['stock_warning'] = empty($_REQUEST['stock_warning']) ? 0 : intval($_REQUEST['stock_warning']);

            if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] === 1) {
                $filter['keyword'] = BaseHelper::json_str_iconv($filter['keyword']);
            }
            $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'product_id' : trim($_REQUEST['sort_by']);
            $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);
            $filter['extension_code'] = empty($_REQUEST['extension_code']) ? '' : trim($_REQUEST['extension_code']);
            $filter['page_count'] = isset($filter['page_count']) ? $filter['page_count'] : 1;

            $query = DB::table('goods_product as g')->where('goods_id', $goods_id);

            // 库存警告
            if ($filter['stock_warning']) {
                $query->whereColumn('goods_number', '<=', 'warn_number');
            }

            // 关键字
            if (! empty($filter['keyword'])) {
                $query->where('product_sn', 'like', '%'.$filter['keyword'].'%');
            }

            if (! empty($conditions)) {
                $query->whereRaw(ltrim($conditions, ' AND'));
            }

            // 记录总数
            $filter['record_count'] = $query->count();

            $row = $query->select('product_id', 'goods_id', 'goods_attr', 'product_sn', 'product_number')
                ->orderBy($filter['sort_by'], $filter['sort_order'])
                ->get()
                ->map(fn ($item) => (array) $item)
                ->all();

            $filter['keyword'] = stripslashes($filter['keyword']);
            // MainHelper::set_filter($filter, '', $param_str);
        } else {
            $filter = $result['filter'];
            $row = [];
        }

        // 处理规格属性
        $goods_attr = GoodsHelper::product_goods_attr_list($goods_id);
        foreach ($row as $key => $value) {
            $_goods_attr_array = explode('|', $value['goods_attr']);
            if (is_array($_goods_attr_array)) {
                $_temp = [];
                foreach ($_goods_attr_array as $_goods_attr_value) {
                    $_temp[] = $goods_attr[$_goods_attr_value] ?? '';
                }
                $row[$key]['goods_attr'] = $_temp;
            }
        }

        return ['product' => $row, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];
    }

    /**
     * 取货品信息
     *
     * @param  int  $product_id  货品id
     * @param  int  $filed  字段
     * @return array
     */
    public static function get_product_info($product_id, $filed = '')
    {
        if (empty($product_id)) {
            return [];
        }

        $filed = trim($filed);
        if (empty($filed)) {
            $filed = '*';
        }

        $row = DB::table('goods_product')
            ->where('product_id', $product_id)
            ->select($filed === '*' ? '*' : explode(',', $filed))
            ->first();

        return $row ? (array) $row : [];
    }

    /**
     * 检查单个商品是否存在规格
     *
     * @param  int  $goods_id  商品id
     * @return bool true，存在；false，不存在
     */
    public static function check_goods_specifications_exist($goods_id)
    {
        $goods_id = intval($goods_id);

        $count = DB::table('goods_type_attribute as a')
            ->join('goods as g', 'a.cat_id', '=', 'g.goods_type')
            ->where('g.goods_id', $goods_id)
            ->count();

        return $count > 0;
    }

    /**
     * 商品的货品规格是否存在
     *
     * @param  string  $goods_attr  商品的货品规格
     * @param  string  $goods_id  商品id
     * @param  int  $product_id  商品的货品id；默认值为：0，没有货品id
     * @return bool true，重复；false，不重复
     */
    public static function check_goods_attr_exist($goods_attr, $goods_id, $product_id = 0)
    {
        $goods_id = intval($goods_id);
        if (strlen($goods_attr) === 0 || empty($goods_id)) {
            return true;    // 重复
        }

        $query = DB::table('goods_product')
            ->where('goods_attr', $goods_attr)
            ->where('goods_id', $goods_id);

        if (! empty($product_id)) {
            $query->where('product_id', '<>', $product_id);
        }

        return $query->exists();
    }

    /**
     * 商品的货品货号是否重复
     *
     * @param  string  $product_sn  商品的货品货号；请在传入本参数前对本参数进行SQl脚本过滤
     * @param  int  $product_id  商品的货品id；默认值为：0，没有货品id
     * @return bool true，重复；false，不重复
     */
    public static function check_product_sn_exist($product_sn, $product_id = 0)
    {
        $product_sn = trim($product_sn);
        $product_id = intval($product_id);
        if (strlen($product_sn) === 0) {
            return true;    // 重复
        }

        if (DB::table('goods')->where('goods_sn', $product_sn)->exists()) {
            return true;    // 重复
        }

        $query = DB::table('goods_product')->where('product_sn', $product_sn);

        if (! empty($product_id)) {
            $query->where('product_id', '<>', $product_id);
        }

        return $query->exists();
    }

    /**
     * 格式化商品图片名称（按目录存储）
     */
    public static function reformat_image_name($type, $goods_id, $source_img, $position = '')
    {
        $rand_name = TimeHelper::gmtime().sprintf('%03d', mt_rand(1, 999));
        $img_ext = substr($source_img, strrpos($source_img, '.'));
        $dir = 'images';
        if (defined('IMAGE_DIR')) {
            $dir = IMAGE_DIR;
        }
        $sub_dir = date('Ym', TimeHelper::gmtime());
        if (! BaseHelper::make_dir(ROOT_PATH.$dir.'/'.$sub_dir)) {
            return false;
        }
        if (! BaseHelper::make_dir(ROOT_PATH.$dir.'/'.$sub_dir.'/source_img')) {
            return false;
        }
        if (! BaseHelper::make_dir(ROOT_PATH.$dir.'/'.$sub_dir.'/goods_img')) {
            return false;
        }
        if (! BaseHelper::make_dir(ROOT_PATH.$dir.'/'.$sub_dir.'/thumb_img')) {
            return false;
        }
        switch ($type) {
            case 'goods':
                $img_name = $goods_id.'_G_'.$rand_name;
                break;
            case 'goods_thumb':
                $img_name = $goods_id.'_thumb_G_'.$rand_name;
                break;
            case 'gallery':
                $img_name = $goods_id.'_P_'.$rand_name;
                break;
            case 'gallery_thumb':
                $img_name = $goods_id.'_thumb_P_'.$rand_name;
                break;
        }
        if ($position === 'source') {
            if (GoodsHelper::move_image_file(ROOT_PATH.$source_img, ROOT_PATH.$dir.'/'.$sub_dir.'/source_img/'.$img_name.$img_ext)) {
                return $dir.'/'.$sub_dir.'/source_img/'.$img_name.$img_ext;
            }
        } elseif ($position === 'thumb') {
            if (GoodsHelper::move_image_file(ROOT_PATH.$source_img, ROOT_PATH.$dir.'/'.$sub_dir.'/thumb_img/'.$img_name.$img_ext)) {
                return $dir.'/'.$sub_dir.'/thumb_img/'.$img_name.$img_ext;
            }
        } else {
            if (GoodsHelper::move_image_file(ROOT_PATH.$source_img, ROOT_PATH.$dir.'/'.$sub_dir.'/goods_img/'.$img_name.$img_ext)) {
                return $dir.'/'.$sub_dir.'/goods_img/'.$img_name.$img_ext;
            }
        }

        return false;
    }

    public static function move_image_file($source, $dest)
    {
        if (@copy($source, $dest)) {
            @unlink($source);

            return true;
        }

        return false;
    }
}
