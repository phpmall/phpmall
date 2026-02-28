<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CommonHelper
{
    /**
     * 验证输入的邮件地址是否合法
     *
     * @param  string  $email  需要验证的邮件地址
     * @return bool
     */
    public static function is_email($user_email)
    {
        $chars = '/^([a-z0-9+_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,6}$/i';
        if (strpos($user_email, '@') !== false && strpos($user_email, '.') !== false) {
            if (preg_match($chars, $user_email)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 检查是否为一个合法的时间格式
     *
     * @param  string  $time
     * @return void
     */
    public static function is_time($time)
    {
        $pattern = '/[\d]{4}-[\d]{1,2}-[\d]{1,2}\s[\d]{1,2}:[\d]{1,2}:[\d]{1,2}/';

        return preg_match($pattern, $time);
    }

    /**
     * 创建地区的返回信息
     *
     * @param  array  $arr  地区数组 *
     * @return void
     */
    public static function region_result($parent, $sel_name, $type)
    {
        $arr = CommonHelper::get_regions($type, $parent);
        foreach ($arr as $v) {
            $region = &$cp->add_node('region');
            $region_id = &$region->add_node('id');
            $region_name = &$region->add_node('name');

            $region_id->set_data($v['region_id']);
            $region_name->set_data($v['region_name']);
        }
        $select_obj = &$cp->add_node('select');
        $select_obj->set_data($sel_name);
    }

    /**
     * 获得指定国家的所有省份
     *
     * @param int     country    国家的编号
     * @return array
     */
    public static function get_regions($type = 0, $parent = 0)
    {
        return DB::table('shop_region')
            ->select('region_id', 'region_name')
            ->where('region_type', $type)
            ->where('parent_id', $parent)
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();
    }

    /**
     * 获得配送区域中指定的配送方式的配送费用的计算参数
     *
     * @param  int  $area_id  配送区域ID
     * @return array;
     */
    public static function get_shipping_config($area_id)
    {
        // 获得配置信息
        $cfg = DB::table('shipping_area')->where('shipping_area_id', $area_id)->value('configure');

        if ($cfg) {
            // 拆分成配置信息的数组
            $arr = unserialize($cfg);
        } else {
            $arr = [];
        }

        return $arr;
    }

    /**
     * 初始化会员数据整合类
     *
     * @return object
     */
    public static function init_users()
    {
        $set_modules = false;
        static $cls = null;
        if ($cls != null) {
            return $cls;
        }
        include_once ROOT_PATH.'includes/modules/integrates/'.cfg('integrate_code').'.php';
        $cfg = unserialize(cfg('integrate_config'));
        $cls = new cfg('integrate_code')($cfg);

        return $cls;
    }

    /**
     * 获得指定分类下的子分类的数组
     *
     * @param  int  $cat_id  分类的ID
     * @param  int  $selected  当前选中分类的ID
     * @param  bool  $re_type  返回的类型: 值为真时返回下拉列表,否则返回数组
     * @param  int  $level  限定返回的级数。为0时返回所有级数
     * @param  bool  $is_show_all  如果为true显示所有分类，如果为false隐藏不可见分类。
     * @return mixed
     */
    public static function cat_list($cat_id = 0, $selected = 0, $re_type = true, $level = 0, $is_show_all = true)
    {
        static $res = null;

        if ($res === null) {
            $data = BaseHelper::read_static_cache('cat_pid_releate');
            if ($data === false) {
                $res = DB::table('goods_category as c')
                    ->select('c.cat_id', 'c.cat_name', 'c.measure_unit', 'c.parent_id', 'c.is_show', 'c.show_in_nav', 'c.grade', 'c.sort_order', DB::raw('COUNT(s.cat_id) AS has_children'))
                    ->leftJoin('goods_category as s', 's.parent_id', '=', 'c.cat_id')
                    ->groupBy('c.cat_id', 'c.cat_name', 'c.measure_unit', 'c.parent_id', 'c.is_show', 'c.show_in_nav', 'c.grade', 'c.sort_order')
                    ->orderBy('c.parent_id')
                    ->orderBy('c.sort_order')
                    ->get()
                    ->map(fn ($item) => (array) $item)
                    ->all();

                $res2 = DB::table('goods')
                    ->select('cat_id', DB::raw('COUNT(*) AS goods_num'))
                    ->where('is_delete', 0)
                    ->where('is_on_sale', 1)
                    ->groupBy('cat_id')
                    ->get()
                    ->map(fn ($item) => (array) $item)
                    ->all();

                $res3 = DB::table('goods_cat as gc')
                    ->select('gc.cat_id', DB::raw('COUNT(*) AS goods_num'))
                    ->leftJoin('goods as g', 'g.goods_id', '=', 'gc.goods_id')
                    ->where('g.is_delete', 0)
                    ->where('g.is_on_sale', 1)
                    ->groupBy('gc.cat_id')
                    ->get()
                    ->map(fn ($item) => (array) $item)
                    ->all();

                $newres = [];
                foreach ($res2 as $k => $v) {
                    $newres[$v['cat_id']] = $v['goods_num'];
                    foreach ($res3 as $ks => $vs) {
                        if ($v['cat_id'] === $vs['cat_id']) {
                            $newres[$v['cat_id']] = $v['goods_num'] + $vs['goods_num'];
                        }
                    }
                }

                foreach ($res as $k => $v) {
                    $res[$k]['goods_num'] = ! empty($newres[$v['cat_id']]) ? $newres[$v['cat_id']] : 0;
                }
                // 如果数组过大，不采用静态缓存方式
                if (count($res) <= 1000) {
                    BaseHelper::write_static_cache('cat_pid_releate', $res);
                }
            } else {
                $res = $data;
            }
        }

        if (empty($res) === true) {
            return $re_type ? '' : [];
        }

        $options = CommonHelper::cat_options($cat_id, $res); // 获得指定分类下的子分类的数组

        $children_level = 99999; // 大于这个分类的将被删除
        if ($is_show_all === false) {
            foreach ($options as $key => $val) {
                if ($val['level'] > $children_level) {
                    unset($options[$key]);
                } else {
                    if ($val['is_show'] === 0) {
                        unset($options[$key]);
                        if ($children_level > $val['level']) {
                            $children_level = $val['level']; // 标记一下，这样子分类也能删除
                        }
                    } else {
                        $children_level = 99999; // 恢复初始值
                    }
                }
            }
        }

        // 截取到指定的缩减级别
        if ($level > 0) {
            if ($cat_id === 0) {
                $end_level = $level;
            } else {
                $first_item = reset($options); // 获取第一个元素
                $end_level = $first_item['level'] + $level;
            }

            // 保留level小于end_level的部分
            foreach ($options as $key => $val) {
                if ($val['level'] >= $end_level) {
                    unset($options[$key]);
                }
            }
        }

        if ($re_type === true) {
            $select = '';
            foreach ($options as $var) {
                $select .= '<option value="'.$var['cat_id'].'" ';
                $select .= ($selected === $var['cat_id']) ? "selected='ture'" : '';
                $select .= '>';
                if ($var['level'] > 0) {
                    $select .= str_repeat('&nbsp;', $var['level'] * 4);
                }
                $select .= htmlspecialchars(addslashes($var['cat_name']), ENT_QUOTES).'</option>';
            }

            return $select;
        } else {
            foreach ($options as $key => $value) {
                $options[$key]['url'] = build_uri('category', ['cid' => $value['cat_id']], $value['cat_name']);
            }

            return $options;
        }
    }

    /**
     * 过滤和排序所有分类，返回一个带有缩进级别的数组
     *
     * @param  int  $cat_id  上级分类ID
     * @param  array  $arr  含有所有分类的数组
     * @param  int  $level  级别
     * @return void
     */
    public static function cat_options($spec_cat_id, $arr)
    {
        static $cat_options = [];

        if (isset($cat_options[$spec_cat_id])) {
            return $cat_options[$spec_cat_id];
        }

        if (! isset($cat_options[0])) {
            $level = $last_cat_id = 0;
            $options = $cat_id_array = $level_array = [];
            $data = BaseHelper::read_static_cache('cat_option_static');
            if ($data === false) {
                while (! empty($arr)) {
                    foreach ($arr as $key => $value) {
                        $cat_id = $value['cat_id'];
                        if ($level === 0 && $last_cat_id === 0) {
                            if ($value['parent_id'] > 0) {
                                break;
                            }

                            $options[$cat_id] = $value;
                            $options[$cat_id]['level'] = $level;
                            $options[$cat_id]['id'] = $cat_id;
                            $options[$cat_id]['name'] = $value['cat_name'];
                            unset($arr[$key]);

                            if ($value['has_children'] === 0) {
                                continue;
                            }
                            $last_cat_id = $cat_id;
                            $cat_id_array = [$cat_id];
                            $level_array[$last_cat_id] = ++$level;

                            continue;
                        }

                        if ($value['parent_id'] === $last_cat_id) {
                            $options[$cat_id] = $value;
                            $options[$cat_id]['level'] = $level;
                            $options[$cat_id]['id'] = $cat_id;
                            $options[$cat_id]['name'] = $value['cat_name'];
                            unset($arr[$key]);

                            if ($value['has_children'] > 0) {
                                if (end($cat_id_array) != $last_cat_id) {
                                    $cat_id_array[] = $last_cat_id;
                                }
                                $last_cat_id = $cat_id;
                                $cat_id_array[] = $cat_id;
                                $level_array[$last_cat_id] = ++$level;
                            }
                        } elseif ($value['parent_id'] > $last_cat_id) {
                            break;
                        }
                    }

                    $count = count($cat_id_array);
                    if ($count > 1) {
                        $last_cat_id = array_pop($cat_id_array);
                    } elseif ($count === 1) {
                        if ($last_cat_id != end($cat_id_array)) {
                            $last_cat_id = end($cat_id_array);
                        } else {
                            $level = 0;
                            $last_cat_id = 0;
                            $cat_id_array = [];

                            continue;
                        }
                    }

                    if ($last_cat_id && isset($level_array[$last_cat_id])) {
                        $level = $level_array[$last_cat_id];
                    } else {
                        $level = 0;
                    }
                }
                // 如果数组过大，不采用静态缓存方式
                if (count($options) <= 2000) {
                    BaseHelper::write_static_cache('cat_option_static', $options);
                }
            } else {
                $options = $data;
            }
            $cat_options[0] = $options;
        } else {
            $options = $cat_options[0];
        }

        if (! $spec_cat_id) {
            return $options;
        } else {
            if (empty($options[$spec_cat_id])) {
                return [];
            }

            $spec_cat_id_level = $options[$spec_cat_id]['level'];

            foreach ($options as $key => $value) {
                if ($key != $spec_cat_id) {
                    unset($options[$key]);
                } else {
                    break;
                }
            }

            $spec_cat_id_array = [];
            foreach ($options as $key => $value) {
                if (
                    ($spec_cat_id_level === $value['level'] && $value['cat_id'] != $spec_cat_id) ||
                    ($spec_cat_id_level > $value['level'])
                ) {
                    break;
                } else {
                    $spec_cat_id_array[$key] = $value;
                }
            }
            $cat_options[$spec_cat_id] = $spec_cat_id_array;

            return $spec_cat_id_array;
        }
    }

    /**
     * 载入配置信息
     *
     * @return array
     */
    public static function load_config()
    {
        $arr = [];

        $data = BaseHelper::read_static_cache('shop_config');
        if ($data === false) {
            $res = DB::table('shop_config')
                ->select('code', 'value')
                ->where('parent_id', '>', 0)
                ->get()
                ->map(fn ($item) => (array) $item)
                ->all();

            foreach ($res as $row) {
                $arr[$row['code']] = $row['value'];
            }

            // 对数值型设置处理
            $arr['watermark_alpha'] = intval($arr['watermark_alpha']);
            $arr['market_price_rate'] = floatval($arr['market_price_rate']);
            $arr['integral_scale'] = floatval($arr['integral_scale']);
            // $arr['integral_percent']     = floatval($arr['integral_percent']);
            $arr['cache_time'] = intval($arr['cache_time']);
            $arr['thumb_width'] = intval($arr['thumb_width']);
            $arr['thumb_height'] = intval($arr['thumb_height']);
            $arr['image_width'] = intval($arr['image_width']);
            $arr['image_height'] = intval($arr['image_height']);
            $arr['best_number'] = ! empty($arr['best_number']) && intval($arr['best_number']) > 0 ? intval($arr['best_number']) : 3;
            $arr['new_number'] = ! empty($arr['new_number']) && intval($arr['new_number']) > 0 ? intval($arr['new_number']) : 3;
            $arr['hot_number'] = ! empty($arr['hot_number']) && intval($arr['hot_number']) > 0 ? intval($arr['hot_number']) : 3;
            $arr['promote_number'] = ! empty($arr['promote_number']) && intval($arr['promote_number']) > 0 ? intval($arr['promote_number']) : 3;
            $arr['top_number'] = intval($arr['top_number']) > 0 ? intval($arr['top_number']) : 10;
            $arr['history_number'] = intval($arr['history_number']) > 0 ? intval($arr['history_number']) : 5;
            $arr['comments_number'] = intval($arr['comments_number']) > 0 ? intval($arr['comments_number']) : 5;
            $arr['article_number'] = intval($arr['article_number']) > 0 ? intval($arr['article_number']) : 5;
            $arr['page_size'] = intval($arr['page_size']) > 0 ? intval($arr['page_size']) : 10;
            $arr['bought_goods'] = intval($arr['bought_goods']);
            $arr['goods_name_length'] = intval($arr['goods_name_length']);
            $arr['top10_time'] = intval($arr['top10_time']);
            $arr['goods_gallery_number'] = intval($arr['goods_gallery_number']) ? intval($arr['goods_gallery_number']) : 5;
            $arr['no_picture'] = ! empty($arr['no_picture']) ? str_replace('../', './', $arr['no_picture']) : 'images/nopic.png'; // 修改默认商品图片的路径
            $arr['qq'] = ! empty($arr['qq']) ? $arr['qq'] : '';
            $arr['ww'] = ! empty($arr['ww']) ? $arr['ww'] : '';
            $arr['default_storage'] = isset($arr['default_storage']) ? intval($arr['default_storage']) : 1;
            $arr['min_goods_amount'] = isset($arr['min_goods_amount']) ? floatval($arr['min_goods_amount']) : 0;
            $arr['one_step_buy'] = empty($arr['one_step_buy']) ? 0 : 1;
            $arr['invoice_type'] = empty($arr['invoice_type']) ? ['type' => [], 'rate' => []] : unserialize($arr['invoice_type']);
            $arr['show_order_type'] = isset($arr['show_order_type']) ? $arr['show_order_type'] : 0;    // 显示方式默认为列表方式
            $arr['help_open'] = isset($arr['help_open']) ? $arr['help_open'] : 1;    // 显示方式默认为列表方式

            // 限定语言项
            $lang_array = ['zh_cn', 'zh_tw', 'en_us'];
            if (empty($arr['lang']) || ! in_array($arr['lang'], $lang_array)) {
                $arr['lang'] = 'zh_cn'; // 默认语言为简体中文
            }

            if (empty($arr['integrate_code'])) {
                $arr['integrate_code'] = 'phpmall'; // 默认的会员整合插件为 phpmall
            }
            BaseHelper::write_static_cache('shop_config', $arr);
        } else {
            $arr = $data;
        }

        return $arr;
    }

    /**
     * 取得品牌列表
     *
     * @return array 品牌列表 id => name
     */
    public static function get_brand_list()
    {
        $res = DB::table('goods_brand')
            ->select('brand_id', 'brand_name')
            ->orderBy('sort_order')
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        $brand_list = [];
        foreach ($res as $row) {
            $brand_list[$row['brand_id']] = addslashes($row['brand_name']);
        }

        return $brand_list;
    }

    /**
     * 获得某个分类下
     *
     * @param  int  $cat
     * @return array
     */
    public static function get_brands($cat = 0, $app = 'brand')
    {
        $template = basename(request()->path());
        $template = substr($template, 0, strrpos($template, '.'));
        static $static_page_libs = null;
        if ($static_page_libs === null) {
            $static_page_libs = $GLOBALS['page_libs'] ?? null;
        }

        $query = DB::table('goods_brand as b')
            ->select('b.brand_id', 'b.brand_name', 'b.brand_logo', 'b.brand_desc', DB::raw("COUNT(*) AS goods_num, IF(b.brand_logo > '', '1', '0') AS tag"))
            ->join('goods as g', 'g.brand_id', '=', 'b.brand_id')
            ->where('b.is_show', 1)
            ->where('g.is_on_sale', 1)
            ->where('g.is_alone_sale', 1)
            ->where('g.is_delete', 0);

        if ($cat > 0) {
            $query->whereRaw(CommonHelper::get_children($cat));
        }

        $query->groupBy('b.brand_id', 'b.brand_name', 'b.brand_logo', 'b.brand_desc')
            ->having('goods_num', '>', 0)
            ->orderByDesc('tag')
            ->orderBy('b.sort_order');

        if (isset($static_page_libs[$template]['/library/brands'])) {
            $num = MainHelper::get_library_number('brands');
            $query->limit($num);
        }

        $row = $query->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        foreach ($row as $key => $val) {
            $row[$key]['url'] = build_uri($app, ['cid' => $cat, 'bid' => $val['brand_id']], $val['brand_name']);
            $row[$key]['brand_desc'] = htmlspecialchars($val['brand_desc'], ENT_QUOTES);
        }

        return $row;
    }

    /**
     *  所有的促销活动信息
     *
     * @return array
     */
    public static function get_promotion_info($goods_id = '')
    {
        $snatch = [];
        $group = [];
        $auction = [];
        $package = [];
        $favourable = [];

        $gmtime = TimeHelper::gmtime();
        $query = DB::table('goods_activity')
            ->select('act_id', 'act_name', 'act_type', 'start_time', 'end_time')
            ->where('is_finished', 0)
            ->where('start_time', '<=', $gmtime)
            ->where('end_time', '>=', $gmtime);

        if (! empty($goods_id)) {
            $query->where('goods_id', $goods_id);
        }
        $res = $query->get()->map(fn ($item) => (array) $item)->all();
        foreach ($res as $data) {
            switch ($data['act_type']) {
                case GAT_SNATCH: // 夺宝奇兵
                    $snatch[$data['act_id']]['act_name'] = $data['act_name'];
                    $snatch[$data['act_id']]['url'] = build_uri('snatch', ['sid' => $data['act_id']]);
                    $snatch[$data['act_id']]['time'] = sprintf(lang('promotion_time'), TimeHelper::local_date('Y-m-d', $data['start_time']), TimeHelper::local_date('Y-m-d', $data['end_time']));
                    $snatch[$data['act_id']]['sort'] = $data['start_time'];
                    $snatch[$data['act_id']]['type'] = 'snatch';
                    break;

                case GAT_GROUP_BUY: // 团购
                    $group[$data['act_id']]['act_name'] = $data['act_name'];
                    $group[$data['act_id']]['url'] = build_uri('group_buy', ['gbid' => $data['act_id']]);
                    $group[$data['act_id']]['time'] = sprintf(lang('promotion_time'), TimeHelper::local_date('Y-m-d', $data['start_time']), TimeHelper::local_date('Y-m-d', $data['end_time']));
                    $group[$data['act_id']]['sort'] = $data['start_time'];
                    $group[$data['act_id']]['type'] = 'group_buy';
                    break;

                case GAT_AUCTION: // 拍卖
                    $auction[$data['act_id']]['act_name'] = $data['act_name'];
                    $auction[$data['act_id']]['url'] = build_uri('auction', ['auid' => $data['act_id']]);
                    $auction[$data['act_id']]['time'] = sprintf(lang('promotion_time'), TimeHelper::local_date('Y-m-d', $data['start_time']), TimeHelper::local_date('Y-m-d', $data['end_time']));
                    $auction[$data['act_id']]['sort'] = $data['start_time'];
                    $auction[$data['act_id']]['type'] = 'auction';
                    break;

                case GAT_PACKAGE: // 礼包
                    $package[$data['act_id']]['act_name'] = $data['act_name'];
                    $package[$data['act_id']]['url'] = 'package.php#'.$data['act_id'];
                    $package[$data['act_id']]['time'] = sprintf(lang('promotion_time'), TimeHelper::local_date('Y-m-d', $data['start_time']), TimeHelper::local_date('Y-m-d', $data['end_time']));
                    $package[$data['act_id']]['sort'] = $data['start_time'];
                    $package[$data['act_id']]['type'] = 'package';
                    break;
            }
        }

        $user_rank = ','.Session::get('user_rank').',';
        $favourable = [];
        $query = DB::table('activity')
            ->select('act_id', 'act_range', 'act_range_ext', 'act_name', 'start_time', 'end_time')
            ->where('start_time', '<=', $gmtime)
            ->where('end_time', '>=', $gmtime);

        if (! empty($goods_id)) {
            $query->whereRaw("CONCAT(',', user_rank, ',') LIKE '%".$user_rank."%'");
        }
        $res = $query->get()->map(fn ($item) => (array) $item)->all();

        if (empty($goods_id)) {
            foreach ($res as $rows) {
                $favourable[$rows['act_id']]['act_name'] = $rows['act_name'];
                $favourable[$rows['act_id']]['url'] = 'activity.php';
                $favourable[$rows['act_id']]['time'] = sprintf(lang('promotion_time'), TimeHelper::local_date('Y-m-d', $rows['start_time']), TimeHelper::local_date('Y-m-d', $rows['end_time']));
                $favourable[$rows['act_id']]['sort'] = $rows['start_time'];
                $favourable[$rows['act_id']]['type'] = 'favourable';
            }
        } else {
            $row = (array) DB::table('goods')
                ->select('cat_id', 'brand_id')
                ->where('goods_id', $goods_id)
                ->first();
            $category_id = $row['cat_id'];
            $brand_id = $row['brand_id'];

            foreach ($res as $rows) {
                if ($rows['act_range'] === FAR_ALL) {
                    $favourable[$rows['act_id']]['act_name'] = $rows['act_name'];
                    $favourable[$rows['act_id']]['url'] = 'activity.php';
                    $favourable[$rows['act_id']]['time'] = sprintf(lang('promotion_time'), TimeHelper::local_date('Y-m-d', $rows['start_time']), TimeHelper::local_date('Y-m-d', $rows['end_time']));
                    $favourable[$rows['act_id']]['sort'] = $rows['start_time'];
                    $favourable[$rows['act_id']]['type'] = 'favourable';
                } elseif ($rows['act_range'] === FAR_CATEGORY) {
                    // 找出分类id的子分类id
                    $id_list = [];
                    $raw_id_list = explode(',', $rows['act_range_ext']);
                    foreach ($raw_id_list as $id) {
                        $id_list = array_merge($id_list, array_keys(CommonHelper::cat_list($id, 0, false)));
                    }
                    $ids = implode(',', array_unique($id_list));

                    if (strpos(','.$ids.',', ','.$category_id.',') !== false) {
                        $favourable[$rows['act_id']]['act_name'] = $rows['act_name'];
                        $favourable[$rows['act_id']]['url'] = 'activity.php';
                        $favourable[$rows['act_id']]['time'] = sprintf(lang('promotion_time'), TimeHelper::local_date('Y-m-d', $rows['start_time']), TimeHelper::local_date('Y-m-d', $rows['end_time']));
                        $favourable[$rows['act_id']]['sort'] = $rows['start_time'];
                        $favourable[$rows['act_id']]['type'] = 'favourable';
                    }
                } elseif ($rows['act_range'] === FAR_BRAND) {
                    if (strpos(','.$rows['act_range_ext'].',', ','.$brand_id.',') !== false) {
                        $favourable[$rows['act_id']]['act_name'] = $rows['act_name'];
                        $favourable[$rows['act_id']]['url'] = 'activity.php';
                        $favourable[$rows['act_id']]['time'] = sprintf(lang('promotion_time'), TimeHelper::local_date('Y-m-d', $rows['start_time']), TimeHelper::local_date('Y-m-d', $rows['end_time']));
                        $favourable[$rows['act_id']]['sort'] = $rows['start_time'];
                        $favourable[$rows['act_id']]['type'] = 'favourable';
                    }
                } elseif ($rows['act_range'] === FAR_GOODS) {
                    if (strpos(','.$rows['act_range_ext'].',', ','.$goods_id.',') !== false) {
                        $favourable[$rows['act_id']]['act_name'] = $rows['act_name'];
                        $favourable[$rows['act_id']]['url'] = 'activity.php';
                        $favourable[$rows['act_id']]['time'] = sprintf(lang('promotion_time'), TimeHelper::local_date('Y-m-d', $rows['start_time']), TimeHelper::local_date('Y-m-d', $rows['end_time']));
                        $favourable[$rows['act_id']]['sort'] = $rows['start_time'];
                        $favourable[$rows['act_id']]['type'] = 'favourable';
                    }
                }
            }
        }

        //    if(!empty($goods_id))
        //    {
        //        return array('snatch'=>$snatch, 'group_buy'=>$group, 'auction'=>$auction, 'favourable'=>$favourable);
        //    }

        $sort_time = [];
        $arr = array_merge($snatch, $group, $auction, $package, $favourable);
        foreach ($arr as $key => $value) {
            $sort_time[] = $value['sort'];
        }
        array_multisort($sort_time, SORT_NUMERIC, SORT_DESC, $arr);

        return $arr;
    }

    /**
     * 获得指定分类下所有底层分类的ID
     *
     * @param  int  $cat  指定的分类ID
     * @return string
     */
    public static function get_children($cat = 0)
    {
        return 'g.cat_id '.db_create_in(array_unique(array_merge([$cat], array_keys(CommonHelper::cat_list($cat, 0, false)))));
    }

    /**
     * 获得指定文章分类下所有底层分类的ID
     *
     * @param  int  $cat  指定的分类ID
     * @return void
     */
    public static function get_article_children($cat = 0)
    {
        return db_create_in(array_unique(array_merge([$cat], array_keys(article_cat_list($cat, 0, false)))), 'cat_id');
    }

    /**
     * 获取邮件模板
     *
     * @param:  $tpl_name[string]       模板代码
     *
     * @return array
     */
    public static function get_mail_template($tpl_name)
    {
        return (array) DB::table('email_template')
            ->select('template_subject', 'is_html', 'template_content')
            ->where('template_code', $tpl_name)
            ->first();
    }

    /**
     * 记录订单操作记录
     *
     * @param  string  $order_sn  订单编号
     * @param  int  $order_status  订单状态
     * @param  int  $shipping_status  配送状态
     * @param  int  $pay_status  付款状态
     * @param  string  $note  备注
     * @param  string  $username  用户名，用户自己的操作则为 buyer
     * @return void
     */
    public static function order_action($order_sn, $order_status, $shipping_status, $pay_status, $note = '', $username = null, $place = 0)
    {
        if (is_null($username)) {
            $username = Session::get('admin_name');
        }

        $order_id = DB::table('order_info')->where('order_sn', $order_sn)->value('order_id');
        if ($order_id) {
            DB::table('order_action')->insert([
                'order_id' => $order_id,
                'action_user' => $username,
                'order_status' => $order_status,
                'shipping_status' => $shipping_status,
                'pay_status' => $pay_status,
                'action_place' => $place,
                'action_note' => $note,
                'log_time' => TimeHelper::gmtime(),
            ]);
        }
    }

    /**
     * 格式化商品价格
     *
     * @param  float  $price  商品价格
     * @return string
     */
    public static function price_format($price, $change_price = true)
    {
        if ($price === '') {
            $price = 0;
        }
        if ($change_price && defined('ECS_ADMIN') === false) {
            switch (cfg('price_format')) {
                case 0:
                    $price = number_format($price, 2, '.', '');
                    break;
                case 1: // 保留不为 0 的尾数
                    $price = preg_replace('/(.*)(\\.)([0-9]*?)0+$/', '\1\2\3', number_format($price, 2, '.', ''));

                    if (substr($price, -1) === '.') {
                        $price = substr($price, 0, -1);
                    }
                    break;
                case 2: // 不四舍五入，保留1位
                    $price = substr(number_format($price, 2, '.', ''), 0, -1);
                    break;
                case 3: // 直接取整
                    $price = intval($price);
                    break;
                case 4: // 四舍五入，保留 1 位
                    $price = number_format($price, 1, '.', '');
                    break;
                case 5: // 先四舍五入，不保留小数
                    $price = round($price);
                    break;
            }
        } else {
            $price = number_format($price, 2, '.', '');
        }

        return sprintf(cfg('currency_format'), $price);
    }

    /**
     * 返回订单中的虚拟商品
     *
     * @param  int  $order_id  订单id值
     * @param  bool  $shipping  是否已经发货
     * @return array()
     */
    public static function get_virtual_goods($order_id, $shipping = false)
    {
        $query = DB::table('order_goods')->where('order_id', $order_id)->where('extension_code', '>', '');

        if ($shipping) {
            $query->select('goods_id', 'goods_name', 'send_number as num', 'extension_code');
        } else {
            $query->select('goods_id', 'goods_name', DB::raw('(goods_number - send_number) as num'), 'extension_code')
                ->where('is_real', 0)
                ->whereRaw('(goods_number - send_number) > 0');
        }
        $res = $query->get()->map(fn ($item) => (array) $item)->all();

        $virtual_goods = [];
        foreach ($res as $row) {
            $virtual_goods[$row['extension_code']][] = ['goods_id' => $row['goods_id'], 'goods_name' => $row['goods_name'], 'num' => $row['num']];
        }

        return $virtual_goods;
    }

    /**
     *  虚拟商品发货
     *
     * @param  array  $virtual_goods  虚拟商品数组
     * @param  string  $msg  错误信息
     * @param  string  $order_sn  订单号。
     * @param  string  $process  设定当前流程：split，发货分单流程；other，其他，默认。
     * @return bool
     */
    public static function virtual_goods_ship(&$virtual_goods, &$msg, $order_sn, $return_result = false, $process = 'other')
    {
        $virtual_card = [];
        foreach ($virtual_goods as $code => $goods_list) {
            // 只处理虚拟卡
            if ($code === 'virtual_card') {
                foreach ($goods_list as $goods) {
                    if (CommonHelper::virtual_card_shipping($goods, $order_sn, $msg, $process)) {
                        if ($return_result) {
                            $virtual_card[] = ['goods_id' => $goods['goods_id'], 'goods_name' => $goods['goods_name'], 'info' => CommonHelper::virtual_card_result($order_sn, $goods)];
                        }
                    } else {
                        return false;
                    }
                }
                tpl()->assign('virtual_card', $virtual_card);
            }
        }

        return true;
    }

    /**
     *  虚拟卡发货
     *
     * @param  string  $goods  商品详情数组
     * @param  string  $order_sn  本次操作的订单
     * @param  string  $msg  返回信息
     * @param  string  $process  设定当前流程：split，发货分单流程；other，其他，默认。
     * @return bool
     */
    public static function virtual_card_shipping($goods, $order_sn, &$msg, $process = 'other')
    {
        // 包含加密解密函数所在文件

        // 检查有没有缺货
        $num = DB::table('goods_virtual_card')
            ->where('goods_id', $goods['goods_id'])
            ->where('is_saled', 0)
            ->count();

        if ($num < $goods['num']) {
            $msg .= sprintf(lang('virtual_card_oos'), $goods['goods_name']);

            return false;
        }

        // 取出卡片信息
        $arr = DB::table('goods_virtual_card')
            ->select('card_id', 'card_sn', 'card_password', 'end_date', 'crc32')
            ->where('goods_id', $goods['goods_id'])
            ->where('is_saled', 0)
            ->limit((int) $goods['num'])
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        $card_ids = [];
        $cards = [];

        foreach ($arr as $virtual_card) {
            $card_info = [];

            // 卡号和密码解密
            if ($virtual_card['crc32'] === 0 || $virtual_card['crc32'] === crc32(AUTH_KEY)) {
                $card_info['card_sn'] = CodeHelper::decrypt($virtual_card['card_sn']);
                $card_info['card_password'] = CodeHelper::decrypt($virtual_card['card_password']);
            } elseif ($virtual_card['crc32'] === crc32(OLD_AUTH_KEY)) {
                $card_info['card_sn'] = CodeHelper::decrypt($virtual_card['card_sn'], OLD_AUTH_KEY);
                $card_info['card_password'] = CodeHelper::decrypt($virtual_card['card_password'], OLD_AUTH_KEY);
            } else {
                $msg .= 'error key';

                return false;
            }
            $card_info['end_date'] = date(cfg('date_format'), $virtual_card['end_date']);
            $card_ids[] = $virtual_card['card_id'];
            $cards[] = $card_info;
        }

        // 标记已经取出的卡片
        $updated = DB::table('goods_virtual_card')
            ->whereIn('card_id', $card_ids)
            ->update([
                'is_saled' => 1,
                'order_sn' => $order_sn,
            ]);

        if (! $updated) {
            // $msg .= db()->error(); // Laravel doesn't have a direct equivalent for error() on the facade without driver specifics, but usually update failure throws exception or returns 0
            return false;
        }

        // 更新库存
        DB::table('goods')->where('goods_id', $goods['goods_id'])->decrement('goods_number', (int) $goods['num']);

        if (true) {
            // 获取订单信息
            $order = (array) DB::table('order_info')
                ->select('order_id', 'order_sn', 'consignee', 'email')
                ->where('order_sn', $order_sn)
                ->first();

            // 更新订单信息
            $update = DB::table('order_goods')
                ->where('order_id', $order['order_id'])
                ->where('goods_id', $goods['goods_id']);

            if ($process === 'split') {
                $update->increment('send_number', (int) $goods['num']);
            } else {
                $update->update(['send_number' => (int) $goods['num']]);
            }
        }

        // 发送邮件
        tpl()->assign('virtual_card', $cards);
        tpl()->assign('order', $order);
        tpl()->assign('goods', $goods);

        tpl()->assign('send_time', date('Y-m-d H:i:s'));
        tpl()->assign('shop_name', cfg('shop_name'));
        tpl()->assign('send_date', date('Y-m-d'));
        tpl()->assign('sent_date', date('Y-m-d'));

        $tpl = CommonHelper::get_mail_template('virtual_card');
        $content = tpl()->fetch('str:'.$tpl['template_content']);
        BaseHelper::send_mail($order['consignee'], $order['email'], $tpl['template_subject'], $content, $tpl['is_html']);

        return true;
    }

    /**
     *  返回虚拟卡信息
     *
     *
     * @return void
     */
    public static function virtual_card_result($order_sn, $goods)
    {
        // 包含加密解密函数所在文件

        // 获取已经发送的卡片数据
        $res = DB::table('goods_virtual_card')
            ->select('card_sn', 'card_password', 'end_date', 'crc32')
            ->where('goods_id', $goods['goods_id'])
            ->where('order_sn', $order_sn)
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        $cards = [];

        foreach ($res as $row) {
            // 卡号和密码解密
            if ($row['crc32'] === 0 || $row['crc32'] === crc32(AUTH_KEY)) {
                $row['card_sn'] = CodeHelper::decrypt($row['card_sn']);
                $row['card_password'] = CodeHelper::decrypt($row['card_password']);
            } elseif ($row['crc32'] === crc32(OLD_AUTH_KEY)) {
                $row['card_sn'] = CodeHelper::decrypt($row['card_sn'], OLD_AUTH_KEY);
                $row['card_password'] = CodeHelper::decrypt($row['card_password'], OLD_AUTH_KEY);
            } else {
                $row['card_sn'] = '***';
                $row['card_password'] = '***';
            }

            $cards[] = ['card_sn' => $row['card_sn'], 'card_password' => $row['card_password'], 'end_date' => date(cfg('date_format'), $row['end_date'])];
        }

        return $cards;
    }

    /**
     * 获取指定 id snatch 活动的结果
     *
     * @param  int  $id  snatch_id
     * @return array array(user_name, bie_price, bid_time, num)
     *               num通常为1，如果为2表示有2个用户取到最小值，但结果只返回最早出价用户。
     */
    public static function get_snatch_result($id)
    {
        $rec = (array) DB::table('activity_snatch as lg')
            ->select('u.user_id', 'u.user_name', 'u.email', 'lg.bid_price', 'lg.bid_time', DB::raw('count(*) as num'))
            ->leftJoin('user as u', 'lg.user_id', '=', 'u.user_id')
            ->where('lg.snatch_id', $id)
            ->groupBy('lg.bid_price', 'u.user_id', 'u.user_name', 'u.email', 'lg.bid_time')
            ->orderBy('num')
            ->orderBy('lg.bid_price')
            ->orderBy('lg.bid_time')
            ->first();

        if ($rec) {
            $rec['bid_time'] = TimeHelper::local_date(cfg('time_format'), $rec['bid_time']);
            $rec['formated_bid_price'] = CommonHelper::price_format($rec['bid_price'], false);

            // 活动信息
            $row = DB::table('goods_activity')
                ->where('act_id', $id)
                ->where('act_type', GAT_SNATCH)
                ->value('ext_info');
            $info = unserialize($row);

            if (! empty($info['max_price'])) {
                $rec['buy_price'] = ($rec['bid_price'] > $info['max_price']) ? $info['max_price'] : $rec['bid_price'];
            } else {
                $rec['buy_price'] = $rec['bid_price'];
            }

            // 检查订单
            $rec['order_count'] = DB::table('order_info')
                ->where('extension_code', 'snatch')
                ->where('extension_id', $id)
                ->whereIn('order_status', [OS_CONFIRMED, OS_UNCONFIRMED])
                ->count();
        }

        return $rec;
    }

    /**
     *  清除指定后缀的模板缓存或编译文件
     *
     * @param  bool  $is_cache  是否清除缓存还是清出编译文件
     * @param  string  $ext  需要删除的文件名，不包含后缀
     * @return int 返回清除的文件个数
     */
    public static function clear_tpl_files($is_cache = true, $ext = '')
    {
        $dirs = [];

        if (isset($GLOBALS['shop_id']) && $GLOBALS['shop_id'] > 0) {
            $tmp_dir = DATA_DIR;
        } else {
            $tmp_dir = 'temp';
        }
        if ($is_cache) {
            $cache_dir = ROOT_PATH.$tmp_dir.'/caches/';
            $dirs[] = ROOT_PATH.$tmp_dir.'/query_caches/';
            $dirs[] = ROOT_PATH.$tmp_dir.'/static_caches/';
            for ($i = 0; $i < 16; $i++) {
                $hash_dir = $cache_dir.dechex($i);
                $dirs[] = $hash_dir.'/';
            }
        } else {
            $dirs[] = ROOT_PATH.$tmp_dir.'/compiled/';
            $dirs[] = ROOT_PATH.$tmp_dir.'/compiled/admin/';
        }

        $str_len = strlen($ext);
        $count = 0;

        foreach ($dirs as $dir) {
            $folder = @opendir($dir);

            if ($folder === false) {
                continue;
            }

            while ($file = readdir($folder)) {
                if ($file === '.' || $file === '..' || $file === 'index.htm' || $file === 'index.html' || $file === '.gitignore') {
                    continue;
                }
                if (is_file($dir.$file)) {
                    // 如果有文件名则判断是否匹配
                    $pos = ($is_cache) ? strrpos($file, '_') : strrpos($file, '.');

                    if ($str_len > 0 && $pos !== false) {
                        $ext_str = substr($file, 0, $pos);

                        if ($ext_str === $ext) {
                            if (@unlink($dir.$file)) {
                                $count++;
                            }
                        }
                    } else {
                        if (@unlink($dir.$file)) {
                            $count++;
                        }
                    }
                }
            }
            closedir($folder);
        }

        return $count;
    }

    /**
     * 清除模版编译文件
     *
     * @param  mixed  $ext  模版文件名， 不包含后缀
     * @return void
     */
    public static function clear_compiled_files($ext = '')
    {
        return CommonHelper::clear_tpl_files(false, $ext);
    }

    /**
     * 清除缓存文件
     *
     * @param  mixed  $ext  模版文件名， 不包含后缀
     * @return void
     */
    public static function clear_cache_files($ext = '')
    {
        return CommonHelper::clear_tpl_files(true, $ext);
    }

    /**
     * 清除模版编译和缓存文件
     *
     * @param  mixed  $ext  模版文件名后缀
     * @return void
     */
    public static function clear_all_files($ext = '')
    {
        return CommonHelper::clear_tpl_files(false, $ext) + CommonHelper::clear_tpl_files(true, $ext);
    }

    /**
     * 页面上调用的js文件
     *
     * @param  string  $files
     * @return void
     */
    public static function smarty_insert_scripts($args)
    {
        static $scripts = [];

        $arr = explode(',', str_replace(' ', '', $args['files']));

        $str = '';
        foreach ($arr as $val) {
            if (in_array($val, $scripts) === false) {
                $scripts[] = $val;
                if ($val[0] === '.') {
                    $str .= '<script type="text/javascript" src="'.$val.'"></script>';
                } else {
                    $str .= '<script type="text/javascript" src="js/'.$val.'"></script>';
                }
            }
        }

        return $str;
    }

    /**
     * 创建分页的列表
     *
     * @param  int  $count
     * @return string
     */
    public static function smarty_create_pages($params)
    {
        extract($params);

        $str = '';
        $len = 10;

        if (empty($page)) {
            $page = 1;
        }

        if (! empty($count)) {
            $step = 1;
            $str .= "<option value='1'>1</option>";

            for ($i = 2; $i < $count; $i += $step) {
                $step = ($i >= $page + $len - 1 || $i <= $page - $len + 1) ? $len : 1;
                $str .= "<option value='$i'";
                $str .= $page === $i ? " selected='true'" : '';
                $str .= ">$i</option>";
            }

            if ($count > 1) {
                $str .= "<option value='$count'";
                $str .= $page === $count ? " selected='true'" : '';
                $str .= ">$count</option>";
            }
        }

        return $str;
    }

    /**
     * 格式化重量：小于1千克用克表示，否则用千克表示
     *
     * @param  float  $weight  重量
     * @return string
     */
    public static function formated_weight($weight)
    {
        $weight = round(floatval($weight), 3);
        if ($weight > 0) {
            if ($weight < 1) {
                // 小于1千克，用克表示
                return intval($weight * 1000).lang('gram');
            } else {
                // 大于1千克，用千克表示
                return $weight.lang('kilogram');
            }
        } else {
            return 0;
        }
    }

    /**
     * 记录帐户变动
     *
     * @param  int  $user_id  用户id
     * @param  float  $user_money  可用余额变动
     * @param  float  $frozen_money  冻结余额变动
     * @param  int  $rank_points  等级积分变动
     * @param  int  $pay_points  消费积分变动
     * @param  string  $change_desc  变动说明
     * @param  int  $change_type  变动类型：参见常量文件
     * @return void
     */
    public static function log_account_change($user_id, $user_money = 0, $frozen_money = 0, $rank_points = 0, $pay_points = 0, $change_desc = '', $change_type = ACT_OTHER)
    {
        // 插入帐户变动记录
        $account_log = [
            'user_id' => $user_id,
            'user_money' => $user_money,
            'frozen_money' => $frozen_money,
            'rank_points' => $rank_points,
            'pay_points' => $pay_points,
            'change_time' => TimeHelper::gmtime(),
            'change_desc' => $change_desc,
            'change_type' => $change_type,
        ];
        DB::table('user_account_log')->insert($account_log);

        // 更新用户信息
        DB::table('user')->where('user_id', $user_id)->incrementEach([
            'user_money' => (float) $user_money,
            'frozen_money' => (float) $frozen_money,
            'rank_points' => (int) $rank_points,
            'pay_points' => (int) $pay_points,
        ]);
    }

    /**
     * 调用UCenter的函数
     *
     * @param  string  $func
     * @param  array  $params
     * @return mixed
     */
    public static function uc_call($func, $params = null)
    {
        restore_error_handler();
        if (! function_exists($func)) {
            include_once ROOT_PATH.'uc_client/client.php';
        }

        $res = call_user_func_array($func, $params);

        set_error_handler('exception_handler');

        return $res;
    }

    /**
     * error_handle回调函数
     */
    public static function exception_handler($errno, $errstr, $errfile, $errline) {}

    /**
     * 重新获得商品图片与商品相册的地址
     *
     * @param  string  $image  原商品相册图片地址
     * @return string $url
     */
    public static function get_image_path($image = '')
    {
        return empty($image) ? cfg('no_picture') : $image;
    }

    /**
     * 调用使用UCenter插件时的函数
     *
     * @param  string  $func
     * @param  array  $params
     * @return mixed
     */
    public static function user_uc_call($func, $params = null)
    {
        if (cfg('integrate_code') && cfg('integrate_code') === 'ucenter') {
            restore_error_handler();
            if (! function_exists($func)) {
            }

            $res = call_user_func_array($func, $params);

            set_error_handler('exception_handler');

            return $res;
        } else {
            return;
        }
    }

    /**
     * 取得商品优惠价格列表
     *
     * @param  string  $goods_id  商品编号
     * @param  string  $price_type  价格类别(0为全店优惠比率，1为商品优惠价格，2为分类优惠比率)
     * @return 优惠价格列表
     */
    public static function get_volume_price_list($goods_id, $price_type = '1')
    {
        $volume_price = [];
        $temp_index = '0';

        $res = DB::table('goods_volume_price')
            ->select('volume_number', 'volume_price')
            ->where('goods_id', $goods_id)
            ->where('price_type', $price_type)
            ->orderBy('volume_number')
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        foreach ($res as $k => $v) {
            $volume_price[$temp_index] = [];
            $volume_price[$temp_index]['number'] = $v['volume_number'];
            $volume_price[$temp_index]['price'] = $v['volume_price'];
            $volume_price[$temp_index]['format_price'] = CommonHelper::price_format($v['volume_price']);
            $temp_index++;
        }

        return $volume_price;
    }

    /**
     * 取得商品最终使用价格
     *
     * @param  string  $goods_id  商品编号
     * @param  string  $goods_num  购买数量
     * @param  bool  $is_spec_price  是否加入规格价格
     * @param  mixed  $spec  规格ID的数组或者逗号分隔的字符串
     * @return 商品最终购买价格
     */
    public static function get_final_price($goods_id, $goods_num = '1', $is_spec_price = false, $spec = [])
    {
        $final_price = '0'; // 商品最终购买价格
        $volume_price = '0'; // 商品优惠价格
        $promote_price = '0'; // 商品促销价格
        $user_price = '0'; // 商品会员价格

        // 取得商品优惠价格列表
        $price_list = CommonHelper::get_volume_price_list($goods_id, '1');

        if (! empty($price_list)) {
            foreach ($price_list as $value) {
                if ($goods_num >= $value['number']) {
                    $volume_price = $value['price'];
                }
            }
        }

        // 取得商品促销价格列表
        // 取得商品信息
        $goods = (array) DB::table('goods as g')
            ->select('g.promote_price', 'g.promote_start_date', 'g.promote_end_date', DB::raw("IFNULL(mp.user_price, g.shop_price * '".(Session::get('discount') ?? 1)."') AS shop_price"))
            ->leftJoin('goods_member_price as mp', function ($join) {
                $join->on('mp.goods_id', '=', 'g.goods_id')
                    ->where('mp.user_rank', '=', Session::get('user_rank') ?? 0);
            })
            ->where('g.goods_id', $goods_id)
            ->where('g.is_delete', 0)
            ->first();

        // 计算商品的促销价格
        if ($goods['promote_price'] > 0) {
            $promote_price = GoodsHelper::bargain_price($goods['promote_price'], $goods['promote_start_date'], $goods['promote_end_date']);
        } else {
            $promote_price = 0;
        }

        // 取得商品会员价格列表
        $user_price = $goods['shop_price'];

        // 比较商品的促销价格，会员价格，优惠价格
        if (empty($volume_price) && empty($promote_price)) {
            // 如果优惠价格，促销价格都为空则取会员价格
            $final_price = $user_price;
        } elseif (! empty($volume_price) && empty($promote_price)) {
            // 如果优惠价格为空时不参加这个比较。
            $final_price = min($volume_price, $user_price);
        } elseif (empty($volume_price) && ! empty($promote_price)) {
            // 如果促销价格为空时不参加这个比较。
            $final_price = min($promote_price, $user_price);
        } elseif (! empty($volume_price) && ! empty($promote_price)) {
            // 取促销价格，会员价格，优惠价格最小值
            $final_price = min($volume_price, $promote_price, $user_price);
        } else {
            $final_price = $user_price;
        }

        // 如果需要加入规格价格
        if ($is_spec_price) {
            if (! empty($spec)) {
                $spec_price = GoodsHelper::spec_price($spec);
                $final_price += $spec_price;
            }
        }

        // 返回商品最终购买价格
        return $final_price;
    }

    /**
     * 将 goods_attr_id 的序列按照 attr_id 重新排序
     *
     * 注意：非规格属性的id会被排除
     *
     * @param  array  $goods_attr_id_array  一维数组
     * @param  string  $sort  序号：asc|desc，默认为：asc
     * @return string
     */
    public static function sort_goods_attr_id_array($goods_attr_id_array, $sort = 'asc')
    {
        if (empty($goods_attr_id_array)) {
            return $goods_attr_id_array;
        }

        // 重新排序
        $row = DB::table('goods_type_attribute as a')
            ->select('a.attr_type', 'v.attr_value', 'v.goods_attr_id')
            ->leftJoin('goods_attr as v', function ($join) {
                $join->on('v.attr_id', '=', 'a.attr_id')
                    ->where('a.attr_type', 1);
            })
            ->whereIn('v.goods_attr_id', (array) $goods_attr_id_array)
            ->orderBy('a.attr_id', $sort)
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        $return_arr = [];
        foreach ($row as $value) {
            $return_arr['sort'][] = $value['goods_attr_id'];

            $return_arr['row'][$value['goods_attr_id']] = $value;
        }

        return $return_arr;
    }

    /**
     * 是否存在规格
     *
     * @param  array  $goods_attr_id_array  一维数组
     * @return string
     */
    public static function is_spec($goods_attr_id_array, $sort = 'asc')
    {
        if (empty($goods_attr_id_array)) {
            return $goods_attr_id_array;
        }

        // 重新排序
        $row = DB::table('goods_type_attribute as a')
            ->select('a.attr_type', 'v.attr_value', 'v.goods_attr_id')
            ->leftJoin('goods_attr as v', function ($join) {
                $join->on('v.attr_id', '=', 'a.attr_id')
                    ->where('a.attr_type', 1);
            })
            ->whereIn('v.goods_attr_id', (array) $goods_attr_id_array)
            ->orderBy('a.attr_id', $sort)
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        $return_arr = [];
        foreach ($row as $value) {
            $return_arr['sort'][] = $value['goods_attr_id'];

            $return_arr['row'][$value['goods_attr_id']] = $value;
        }

        if (! empty($return_arr)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取指定id package 的信息
     *
     * @param  int  $id  package_id
     * @return array array(package_id, package_name, goods_id,start_time, end_time, min_price, integral)
     */
    public static function get_package_info($id)
    {
        $id = is_numeric($id) ? intval($id) : 0;
        $now = TimeHelper::gmtime();

        $package = (array) DB::table('goods_activity')
            ->select('act_id as id', 'act_name as package_name', 'goods_id', 'goods_name', 'start_time', 'end_time', 'act_desc', 'ext_info')
            ->where('act_id', $id)
            ->where('act_type', GAT_PACKAGE)
            ->first();

        // 将时间转成可阅读格式
        if ($package['start_time'] <= $now && $package['end_time'] >= $now) {
            $package['is_on_sale'] = '1';
        } else {
            $package['is_on_sale'] = '0';
        }
        $package['start_time'] = TimeHelper::local_date('Y-m-d H:i', $package['start_time']);
        $package['end_time'] = TimeHelper::local_date('Y-m-d H:i', $package['end_time']);
        $row = unserialize($package['ext_info']);
        unset($package['ext_info']);
        if ($row) {
            foreach ($row as $key => $val) {
                $package[$key] = $val;
            }
        }

        $goods_res = DB::table('activity_package as pg')
            ->select('pg.package_id', 'pg.goods_id', 'pg.goods_number', 'pg.admin_id', 'g.goods_sn', 'g.goods_name', 'g.market_price', 'g.goods_thumb', 'g.is_real', DB::raw("IFNULL(mp.user_price, g.shop_price * '".(Session::get('discount') ?? 1)."') AS rank_price"))
            ->leftJoin('goods as g', 'g.goods_id', '=', 'pg.goods_id')
            ->leftJoin('goods_member_price as mp', function ($join) {
                $join->on('mp.goods_id', '=', 'g.goods_id')
                    ->where('mp.user_rank', '=', Session::get('user_rank') ?? 0);
            })
            ->where('pg.package_id', $id)
            ->orderBy('pg.package_id')
            ->orderBy('pg.goods_id')
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        $market_price = 0;
        $real_goods_count = 0;
        $virtual_goods_count = 0;

        foreach ($goods_res as $key => $val) {
            $goods_res[$key]['goods_thumb'] = CommonHelper::get_image_path($val['goods_thumb']);
            $goods_res[$key]['market_price_format'] = CommonHelper::price_format($val['market_price']);
            $goods_res[$key]['rank_price_format'] = CommonHelper::price_format($val['rank_price']);
            $market_price += $val['market_price'] * $val['goods_number'];
            // 统计实体商品和虚拟商品的个数
            if ($val['is_real']) {
                $real_goods_count++;
            } else {
                $virtual_goods_count++;
            }
        }

        if ($real_goods_count > 0) {
            $package['is_real'] = 1;
        } else {
            $package['is_real'] = 0;
        }

        $package['goods_list'] = $goods_res;
        $package['market_package'] = $market_price;
        $package['market_package_format'] = CommonHelper::price_format($market_price);
        $package['package_price_format'] = CommonHelper::price_format($package['package_price']);

        return $package;
    }

    /**
     * 获得指定礼包的商品
     *
     * @param  int  $package_id
     * @return array
     */
    public static function get_package_goods($package_id)
    {
        $query = DB::table('activity_package as pg')
            ->select('pg.goods_id', 'g.goods_name', 'pg.goods_number', 'p.goods_attr', 'p.product_number', 'p.product_id')
            ->leftJoin('goods as g', 'pg.goods_id', '=', 'g.goods_id')
            ->leftJoin('goods_product as p', 'pg.product_id', '=', 'p.product_id')
            ->where('pg.package_id', $package_id);

        if ($package_id === 0) {
            $query->where('pg.admin_id', Session::get('admin_id') ?? 0);
        }

        $resource = $query->get()->map(fn ($item) => (array) $item)->all();
        if (! $resource) {
            return [];
        }

        $row = [];

        // 生成结果数组 取存在货品的商品id 组合商品id与货品id
        $good_product_str = '';
        foreach ($resource as $_row) {
            if ($_row['product_id'] > 0) {
                // 取存商品id
                $good_product_str .= ','.$_row['goods_id'];

                // 组合商品id与货品id
                $_row['g_p'] = $_row['goods_id'].'_'.$_row['product_id'];
            } else {
                // 组合商品id与货品id
                $_row['g_p'] = $_row['goods_id'];
            }

            // 生成结果数组
            $row[] = $_row;
        }
        $good_product_str = trim($good_product_str, ',');

        // 释放空间
        unset($resource, $_row, $sql);

        // 取商品属性
        if ($good_product_str != '') {
            $result_goods_attr = DB::table('goods_attr')
                ->select('goods_attr_id', 'attr_value')
                ->whereIn('goods_id', explode(',', $good_product_str))
                ->get()
                ->map(fn ($item) => (array) $item)
                ->all();

            $_goods_attr = [];
            foreach ($result_goods_attr as $value) {
                $_goods_attr[$value['goods_attr_id']] = $value['attr_value'];
            }
        }

        // 过滤货品
        $format[0] = '%s[%s]--[%d]';
        $format[1] = '%s--[%d]';
        foreach ($row as $key => $value) {
            if ($value['goods_attr'] != '') {
                $goods_attr_array = explode('|', $value['goods_attr']);

                $goods_attr = [];
                foreach ($goods_attr_array as $_attr) {
                    $goods_attr[] = $_goods_attr[$_attr];
                }

                $row[$key]['goods_name'] = sprintf($format[0], $value['goods_name'], implode('，', $goods_attr), $value['goods_number']);
            } else {
                $row[$key]['goods_name'] = sprintf($format[1], $value['goods_name'], $value['goods_number']);
            }
        }

        return $row;
    }

    /**
     * 取商品的货品列表
     *
     * @param  mixed  $goods_id  单个商品id；多个商品id数组；以逗号分隔商品id字符串
     * @param  string  $conditions  sql条件
     * @return array
     */
    public static function get_good_products($goods_id, $conditions = '')
    {
        if (empty($goods_id)) {
            return [];
        }

        $query = DB::table('goods_product');

        switch (gettype($goods_id)) {
            case 'integer':
                $query->where('goods_id', intval($goods_id));
                break;

            case 'string':
            case 'array':
                $query->whereIn('goods_id', (array) $goods_id);
                break;
        }

        if ($conditions) {
            $query->whereRaw(trim($conditions, ' AND'));
        }

        // 取货品
        $result_products = $query->get()->map(fn ($item) => (array) $item)->all();

        // 取商品属性
        $attr_query = DB::table('goods_attr');
        switch (gettype($goods_id)) {
            case 'integer':
                $attr_query->where('goods_id', intval($goods_id));
                break;

            case 'string':
            case 'array':
                $attr_query->whereIn('goods_id', (array) $goods_id);
                break;
        }
        $result_goods_attr = $attr_query->get()->map(fn ($item) => (array) $item)->all();

        $_goods_attr = [];
        foreach ($result_goods_attr as $value) {
            $_goods_attr[$value['goods_attr_id']] = $value['attr_value'];
        }

        // 过滤货品
        foreach ($result_products as $key => $value) {
            $goods_attr_array = explode('|', $value['goods_attr']);
            if (is_array($goods_attr_array)) {
                $goods_attr = [];
                foreach ($goods_attr_array as $_attr) {
                    $goods_attr[] = $_goods_attr[$_attr];
                }

                $goods_attr_str = implode('，', $goods_attr);
            }

            $result_products[$key]['goods_attr_str'] = $goods_attr_str;
        }

        return $result_products;
    }

    /**
     * 取商品的下拉框Select列表
     *
     * @param  int  $goods_id  商品id
     * @return array
     */
    public static function get_good_products_select($goods_id)
    {
        $return_array = [];
        $products = CommonHelper::get_good_products($goods_id);

        if (empty($products)) {
            return $return_array;
        }

        foreach ($products as $value) {
            $return_array[$value['product_id']] = $value['goods_attr_str'];
        }

        return $return_array;
    }

    /**
     * 取商品的规格列表
     *
     * @param  int  $goods_id  商品id
     * @param  string  $conditions  sql条件
     * @return array
     */
    public static function get_specifications_list($goods_id, $conditions = '')
    {
        // 取商品属性
        $query = DB::table('goods_attr as ga')
            ->select('ga.goods_attr_id', 'ga.attr_id', 'ga.attr_value', 'a.attr_name')
            ->join('goods_type_attribute as a', 'ga.attr_id', '=', 'a.attr_id')
            ->where('ga.goods_id', $goods_id);

        if ($conditions) {
            $query->whereRaw(trim($conditions, ' AND'));
        }

        $result = $query->get()->map(fn ($item) => (array) $item)->all();

        $return_array = [];
        foreach ($result as $value) {
            $return_array[$value['goods_attr_id']] = $value;
        }

        return $return_array;
    }
}
