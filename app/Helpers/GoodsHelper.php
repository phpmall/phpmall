<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class GoodsHelper
{
    /**
     * 商品推荐usort用自定义排序行数
     */
    public static function goods_sort($goods_a, $goods_b)
    {
        if ($goods_a['sort_order'] === $goods_b['sort_order']) {
            return 0;
        }

        return ($goods_a['sort_order'] < $goods_b['sort_order']) ? -1 : 1;
    }

    /**
     * 获得指定分类同级的所有分类以及该分类下的子分类
     *
     * @param  int  $cat_id  分类编号
     * @return array
     */
    public static function get_categories_tree($cat_id = 0)
    {
        if ($cat_id > 0) {
            $parent_id = DB::table('goods_category')
                ->where('cat_id', $cat_id)
                ->value('parent_id');
        } else {
            $parent_id = 0;
        }

        /*
         判断当前分类中全是是否是底级分类，
         如果是取出底级分类上级分类，
         如果不是取当前分类及其下的子分类
        */
        if (DB::table('goods_category')->where('parent_id', $parent_id)->where('is_show', 1)->count() > 0 || $parent_id === 0) {
            // 获取当前分类及其子分类
            $res = DB::table('goods_category')
                ->select('cat_id', 'cat_name', 'parent_id', 'is_show')
                ->where('parent_id', $parent_id)
                ->where('is_show', 1)
                ->orderBy('sort_order')
                ->orderBy('cat_id')
                ->get()
                ->map(fn ($item) => (array) $item)
                ->all();

            foreach ($res as $row) {
                if ($row['is_show']) {
                    $cat_arr[$row['cat_id']]['id'] = $row['cat_id'];
                    $cat_arr[$row['cat_id']]['name'] = $row['cat_name'];
                    $cat_arr[$row['cat_id']]['url'] = build_uri('category', ['cid' => $row['cat_id']], (string) $row['cat_name']);

                    if (isset($row['cat_id']) != null) {
                        $cat_arr[$row['cat_id']]['cat_id'] = GoodsHelper::get_child_tree((int) $row['cat_id']);
                    }
                }
            }
        }
        if (isset($cat_arr)) {
            return $cat_arr;
        }
    }

    public static function get_child_tree($tree_id = 0)
    {
        $three_arr = [];
        if (DB::table('goods_category')->where('parent_id', $tree_id)->where('is_show', 1)->count() > 0 || $tree_id === 0) {
            $res = DB::table('goods_category')
                ->select('cat_id', 'cat_name', 'parent_id', 'is_show')
                ->where('parent_id', $tree_id)
                ->where('is_show', 1)
                ->orderBy('sort_order')
                ->orderBy('cat_id')
                ->get()
                ->map(fn ($item) => (array) $item)
                ->all();

            foreach ($res as $row) {
                if ($row['is_show']) {
                    $three_arr[$row['cat_id']]['id'] = $row['cat_id'];
                }
                $three_arr[$row['cat_id']]['name'] = $row['cat_name'];
                $three_arr[$row['cat_id']]['url'] = build_uri('category', ['cid' => $row['cat_id']], (string) $row['cat_name']);

                if (isset($row['cat_id']) != null) {
                    $three_arr[$row['cat_id']]['cat_id'] = GoodsHelper::get_child_tree((int) $row['cat_id']);
                }
            }
        }

        return $three_arr;
    }

    /**
     * 调用当前分类的销售排行榜
     *
     * @param  string  $cats  查询的分类
     * @return array
     */
    public static function get_top10($cats = '')
    {
        $query = DB::table('goods as g')
            ->select('g.goods_id', 'g.goods_name', 'g.shop_price', 'g.goods_thumb', DB::raw('SUM(og.goods_number) as goods_number'))
            ->join('order_info as o', 'og.order_id', '=', 'o.order_id')
            ->join('order_goods as og', 'og.goods_id', '=', 'g.goods_id')
            ->where('g.is_on_sale', 1)
            ->where('g.is_alone_sale', 1)
            ->where('g.is_delete', 0)
            ->whereIn('o.order_status', [OS_CONFIRMED, OS_SPLITED])
            ->whereIn('o.pay_status', [PS_PAYED, PS_PAYING])
            ->whereIn('o.shipping_status', [SS_SHIPPED, SS_RECEIVED]);

        if (! empty($cats)) {
            $query->whereRaw("($cats OR ".GoodsHelper::get_extension_goods($cats).')');
        }

        // 排行统计的时间
        switch (cfg('top10_time')) {
            case 1: // 一年
                $query->where('o.order_sn', '>=', date('Ymd', TimeHelper::gmtime() - 365 * 86400));
                break;
            case 2: // 半年
                $query->where('o.order_sn', '>=', date('Ymd', TimeHelper::gmtime() - 180 * 86400));
                break;
            case 3: // 三个月
                $query->where('o.order_sn', '>=', date('Ymd', TimeHelper::gmtime() - 90 * 86400));
                break;
            case 4: // 一个月
                $query->where('o.order_sn', '>=', date('Ymd', TimeHelper::gmtime() - 30 * 86400));
                break;
        }

        if (cfg('use_storage') === 1) {
            $query->where('g.goods_number', '>', 0);
        }

        $arr = $query->groupBy('g.goods_id')
            ->orderByDesc('goods_number')
            ->orderByDesc('g.goods_id')
            ->limit((int) cfg('top_number'))
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        for ($i = 0, $count = count($arr); $i < $count; $i++) {
            $arr[$i]['short_name'] = (int) cfg('goods_name_length') > 0 ?
                Str::substr($arr[$i]['goods_name'], (int) cfg('goods_name_length')) : $arr[$i]['goods_name'];
            $arr[$i]['url'] = build_uri('goods', ['gid' => $arr[$i]['goods_id']], $arr[$i]['goods_name']);
            $arr[$i]['thumb'] = CommonHelper::get_image_path($arr[$i]['goods_thumb']);
            $arr[$i]['price'] = CommonHelper::price_format($arr[$i]['shop_price']);
        }

        return $arr;
    }

    /**
     * 获得推荐商品
     *
     * @param  string  $type  推荐类型，可以是 best, new, hot
     * @return array
     */
    public static function get_recommend_goods($type = '', $cats = '')
    {
        if (! in_array($type, ['best', 'new', 'hot'])) {
            return [];
        }

        // 取不同推荐对应的商品
        static $type_goods = [];
        if (empty($type_goods[$type])) {
            // 初始化数据
            $type_goods['best'] = [];
            $type_goods['new'] = [];
            $type_goods['hot'] = [];
            $data = BaseHelper::read_static_cache('recommend_goods');
            if ($data === false) {
                $goods_res = DB::table('goods as g')
                    ->select('g.goods_id', 'g.is_best', 'g.is_new', 'g.is_hot', 'g.is_promote', 'b.brand_name', 'g.sort_order')
                    ->leftJoin('goods_brand as b', 'b.brand_id', '=', 'g.brand_id')
                    ->where('g.is_on_sale', 1)
                    ->where('g.is_alone_sale', 1)
                    ->where('g.is_delete', 0)
                    ->where(function ($query) {
                        $query->where('g.is_best', 1)
                            ->orWhere('g.is_new', 1)
                            ->orWhere('g.is_hot', 1);
                    })
                    ->orderBy('g.sort_order')
                    ->orderByDesc('g.last_update')
                    ->get()
                    ->map(fn ($item) => (array) $item)
                    ->all();
                // 定义推荐,最新，热门，促销商品
                $goods_data['best'] = [];
                $goods_data['new'] = [];
                $goods_data['hot'] = [];
                $goods_data['brand'] = [];
                if (! empty($goods_res)) {
                    foreach ($goods_res as $data) {
                        if ($data['is_best'] === 1) {
                            $goods_data['best'][] = ['goods_id' => $data['goods_id'], 'sort_order' => $data['sort_order']];
                        }
                        if ($data['is_new'] === 1) {
                            $goods_data['new'][] = ['goods_id' => $data['goods_id'], 'sort_order' => $data['sort_order']];
                        }
                        if ($data['is_hot'] === 1) {
                            $goods_data['hot'][] = ['goods_id' => $data['goods_id'], 'sort_order' => $data['sort_order']];
                        }
                        if ($data['brand_name'] != '') {
                            $goods_data['brand'][$data['goods_id']] = $data['brand_name'];
                        }
                    }
                }
                BaseHelper::write_static_cache('recommend_goods', $goods_data);
            } else {
                $goods_data = $data;
            }

            $time = TimeHelper::gmtime();
            $order_type = cfg('recommend_order');

            // 按推荐数量及排序取每一项推荐显示的商品 order_type可以根据后台设定进行各种条件显示
            static $type_array = [];
            $type2lib = ['best' => 'recommend_best', 'new' => 'recommend_new', 'hot' => 'recommend_hot'];
            if (empty($type_array)) {
                foreach ($type2lib as $key => $data) {
                    if (! empty($goods_data[$key])) {
                        $num = MainHelper::get_library_number($data);
                        $data_count = count($goods_data[$key]);
                        $num = $data_count > $num ? $num : $data_count;
                        if ($order_type === 0) {
                            // usort($goods_data[$key], 'goods_sort');
                            $rand_key = array_slice($goods_data[$key], 0, $num);
                            foreach ($rand_key as $key_data) {
                                $type_array[$key][] = $key_data['goods_id'];
                            }
                        } else {
                            $rand_key = array_rand($goods_data[$key], $num);
                            if ($num === 1) {
                                $type_array[$key][] = $goods_data[$key][$rand_key]['goods_id'];
                            } else {
                                foreach ($rand_key as $key_data) {
                                    $type_array[$key][] = $goods_data[$key][$key_data]['goods_id'];
                                }
                            }
                        }
                    } else {
                        $type_array[$key] = [];
                    }
                }
            }

            // 取出所有符合条件的商品数据，并将结果存入对应的推荐类型数组中
            $query = DB::table('goods as g')
                ->select('g.goods_id', 'g.goods_name', 'g.goods_name_style', 'g.market_price', 'g.shop_price AS org_price', 'g.promote_price', DB::raw("IFNULL(mp.user_price, g.shop_price * '".(Session::get('discount') ?? 1)."') AS shop_price"), 'promote_start_date', 'promote_end_date', 'g.goods_brief', 'g.goods_thumb', 'g.goods_img', DB::raw('RAND() AS rnd'))
                ->leftJoin('goods_member_price as mp', function ($join) {
                    $join->on('mp.goods_id', '=', 'g.goods_id')
                        ->where('mp.user_rank', '=', Session::get('user_rank') ?? 0);
                });

            $type_merge = array_merge($type_array['new'], $type_array['best'], $type_array['hot']);
            $type_merge = array_unique($type_merge);

            $result = $query->whereIn('g.goods_id', $type_merge)
                ->orderBy('g.sort_order')
                ->orderByDesc('g.last_update')
                ->get()
                ->map(fn ($item) => (array) $item)
                ->all();
            foreach ($result as $idx => $row) {
                if ($row['promote_price'] > 0) {
                    $promote_price = GoodsHelper::bargain_price($row['promote_price'], $row['promote_start_date'], $row['promote_end_date']);
                    $goods[$idx]['promote_price'] = $promote_price > 0 ? CommonHelper::price_format($promote_price) : '';
                } else {
                    $goods[$idx]['promote_price'] = '';
                }

                $goods[$idx]['id'] = $row['goods_id'];
                $goods[$idx]['name'] = $row['goods_name'];
                $goods[$idx]['brief'] = $row['goods_brief'];
                $goods[$idx]['brand_name'] = isset($goods_data['brand'][$row['goods_id']]) ? $goods_data['brand'][$row['goods_id']] : '';
                $goods[$idx]['goods_style_name'] = GoodsHelper::add_style($row['goods_name'], $row['goods_name_style']);

                $goods[$idx]['short_name'] = (int) cfg('goods_name_length') > 0 ?
                    Str::substr($row['goods_name'], (int) cfg('goods_name_length')) : $row['goods_name'];
                $goods[$idx]['short_style_name'] = GoodsHelper::add_style($goods[$idx]['short_name'], $row['goods_name_style']);
                $goods[$idx]['market_price'] = CommonHelper::price_format($row['market_price']);
                $goods[$idx]['shop_price'] = CommonHelper::price_format($row['shop_price']);
                $goods[$idx]['thumb'] = CommonHelper::get_image_path($row['goods_thumb']);
                $goods[$idx]['goods_img'] = CommonHelper::get_image_path($row['goods_img']);
                $goods[$idx]['url'] = build_uri('goods', ['gid' => $row['goods_id']], $row['goods_name']);
                if (in_array($row['goods_id'], $type_array['best'])) {
                    $type_goods['best'][] = $goods[$idx];
                }
                if (in_array($row['goods_id'], $type_array['new'])) {
                    $type_goods['new'][] = $goods[$idx];
                }
                if (in_array($row['goods_id'], $type_array['hot'])) {
                    $type_goods['hot'][] = $goods[$idx];
                }
            }
        }

        return $type_goods[$type];
    }

    /**
     * 获得促销商品
     *
     * @return array
     */
    public static function get_promote_goods($cats = '')
    {
        $time = TimeHelper::gmtime();
        $order_type = cfg('recommend_order');

        // 取得促销lbi的数量限制
        $num = MainHelper::get_library_number('recommend_promotion');
        $query = DB::table('goods as g')
            ->select('g.goods_id', 'g.goods_name', 'g.goods_name_style', 'g.market_price', 'g.shop_price AS org_price', 'g.promote_price', DB::raw("IFNULL(mp.user_price, g.shop_price * '".(Session::get('discount') ?? 1)."') AS shop_price"), 'promote_start_date', 'promote_end_date', 'g.goods_brief', 'g.goods_thumb', 'goods_img', 'b.brand_name', 'g.is_best', 'g.is_new', 'g.is_hot', 'g.is_promote', DB::raw('RAND() AS rnd'))
            ->leftJoin('goods_brand as b', 'b.brand_id', '=', 'g.brand_id')
            ->leftJoin('goods_member_price as mp', function ($join) {
                $join->on('mp.goods_id', '=', 'g.goods_id')
                    ->where('mp.user_rank', '=', Session::get('user_rank') ?? 0);
            })
            ->where('g.is_on_sale', 1)
            ->where('g.is_alone_sale', 1)
            ->where('g.is_delete', 0)
            ->where('g.is_promote', 1)
            ->where('promote_start_date', '<=', $time)
            ->where('promote_end_date', '>=', $time);

        if ($order_type === 0) {
            $query->orderBy('g.sort_order')->orderByDesc('g.last_update');
        } else {
            $query->orderBy('rnd');
        }

        $result = $query->limit((int) $num)->get()->map(fn ($item) => (array) $item)->all();

        $goods = [];
        foreach ($result as $idx => $row) {
            if ($row['promote_price'] > 0) {
                $promote_price = GoodsHelper::bargain_price($row['promote_price'], $row['promote_start_date'], $row['promote_end_date']);
                $goods[$idx]['promote_price'] = $promote_price > 0 ? CommonHelper::price_format($promote_price) : '';
            } else {
                $goods[$idx]['promote_price'] = '';
            }

            $goods[$idx]['id'] = $row['goods_id'];
            $goods[$idx]['name'] = $row['goods_name'];
            $goods[$idx]['brief'] = $row['goods_brief'];
            $goods[$idx]['brand_name'] = $row['brand_name'];
            $goods[$idx]['goods_style_name'] = GoodsHelper::add_style($row['goods_name'], (string) $row['goods_name_style']);
            $goods[$idx]['short_name'] = (int) cfg('goods_name_length') > 0 ? Str::substr($row['goods_name'], (int) cfg('goods_name_length')) : $row['goods_name'];
            $goods[$idx]['short_style_name'] = GoodsHelper::add_style($goods[$idx]['short_name'], (string) $row['goods_name_style']);
            $goods[$idx]['market_price'] = CommonHelper::price_format($row['market_price']);
            $goods[$idx]['shop_price'] = CommonHelper::price_format($row['shop_price']);
            $goods[$idx]['thumb'] = CommonHelper::get_image_path($row['goods_thumb']);
            $goods[$idx]['goods_img'] = CommonHelper::get_image_path($row['goods_img']);
            $goods[$idx]['url'] = build_uri('goods', ['gid' => $row['goods_id']], $row['goods_name']);
        }

        return $goods;
    }

    /**
     * 获得指定分类下的推荐商品
     *
     * @param  string  $type  推荐类型，可以是 best, new, hot, promote
     * @param  string  $cats  分类的ID
     * @param  int  $brand  品牌的ID
     * @param  int  $min  商品价格下限
     * @param  int  $max  商品价格上限
     * @param  string  $ext  商品扩展查询
     * @return array
     */
    public static function get_category_recommend_goods($type = '', $cats = '', $brand = 0, $min = 0, $max = 0, $ext = '')
    {
        $query = DB::table('goods as g')
            ->select('g.goods_id', 'g.goods_name', 'g.goods_name_style', 'g.market_price', 'g.shop_price AS org_price', 'g.promote_price', DB::raw("IFNULL(mp.user_price, g.shop_price * '".(Session::get('discount') ?? 1)."') AS shop_price"), 'promote_start_date', 'promote_end_date', 'g.goods_brief', 'g.goods_thumb', 'goods_img', 'b.brand_name')
            ->leftJoin('goods_brand as b', 'b.brand_id', '=', 'g.brand_id')
            ->leftJoin('goods_member_price as mp', function ($join) {
                $join->on('mp.goods_id', '=', 'g.goods_id')
                    ->where('mp.user_rank', '=', Session::get('user_rank') ?? 0);
            })
            ->where('g.is_on_sale', 1)
            ->where('g.is_alone_sale', 1)
            ->where('g.is_delete', 0);

        if ($brand > 0) {
            $query->where('g.brand_id', $brand);
        }

        if ($min > 0) {
            $query->where('g.shop_price', '>=', $min);
        }

        if ($max > 0) {
            $query->where('g.shop_price', '<=', $max);
        }

        if ($ext) {
            $query->whereRaw(trim($ext, ' AND'));
        }

        $type2lib = ['best' => 'recommend_best', 'new' => 'recommend_new', 'hot' => 'recommend_hot', 'promote' => 'recommend_promotion'];
        $num = MainHelper::get_library_number($type2lib[$type]);

        switch ($type) {
            case 'best':
                $query->where('is_best', 1);
                break;
            case 'new':
                $query->where('is_new', 1);
                break;
            case 'hot':
                $query->where('is_hot', 1);
                break;
            case 'promote':
                $time = TimeHelper::gmtime();
                $query->where('is_promote', 1)
                    ->where('promote_start_date', '<=', $time)
                    ->where('promote_end_date', '>=', $time);
                break;
        }

        if (! empty($cats)) {
            $query->whereRaw('('.$cats.' OR '.GoodsHelper::get_extension_goods($cats).')');
        }

        $order_type = cfg('recommend_order');
        if ($order_type === 0) {
            $query->orderBy('g.sort_order')->orderByDesc('g.last_update');
        } else {
            $query->orderBy(DB::raw('RAND()'));
        }

        $res = $query->limit((int) $num)->get()->map(fn ($item) => (array) $item)->all();

        $idx = 0;
        $goods = [];
        foreach ($res as $row) {
            if ($row['promote_price'] > 0) {
                $promote_price = GoodsHelper::bargain_price($row['promote_price'], $row['promote_start_date'], $row['promote_end_date']);
                $goods[$idx]['promote_price'] = $promote_price > 0 ? CommonHelper::price_format($promote_price) : '';
            } else {
                $goods[$idx]['promote_price'] = '';
            }

            $goods[$idx]['id'] = $row['goods_id'];
            $goods[$idx]['name'] = $row['goods_name'];
            $goods[$idx]['brief'] = $row['goods_brief'];
            $goods[$idx]['brand_name'] = $row['brand_name'];
            $goods[$idx]['short_name'] = (int) cfg('goods_name_length') > 0 ?
                Str::substr($row['goods_name'], (int) cfg('goods_name_length')) : $row['goods_name'];
            $goods[$idx]['market_price'] = CommonHelper::price_format($row['market_price']);
            $goods[$idx]['shop_price'] = CommonHelper::price_format($row['shop_price']);
            $goods[$idx]['thumb'] = CommonHelper::get_image_path($row['goods_thumb']);
            $goods[$idx]['goods_img'] = CommonHelper::get_image_path($row['goods_img']);
            $goods[$idx]['url'] = build_uri('goods', ['gid' => $row['goods_id']], $row['goods_name']);

            $goods[$idx]['short_style_name'] = GoodsHelper::add_style($goods[$idx]['short_name'], (string) $row['goods_name_style']);
            $idx++;
        }

        return $goods;
    }

    /**
     * 获得商品的详细信息
     *
     * @param  int  $goods_id
     * @return array|bool
     */
    public static function get_goods_info($goods_id)
    {
        $time = TimeHelper::gmtime();
        $row = (array) DB::table('goods as g')
            ->select('g.*', 'c.measure_unit', 'b.brand_id', 'b.brand_name AS goods_brand', 'm.type_money AS bonus_money', DB::raw('IFNULL(AVG(r.comment_rank), 0) AS comment_rank'), DB::raw("IFNULL(mp.user_price, g.shop_price * '".(Session::get('discount') ?? 1)."') AS rank_price"))
            ->leftJoin('goods_category as c', 'g.cat_id', '=', 'c.cat_id')
            ->leftJoin('goods_brand as b', 'g.brand_id', '=', 'b.brand_id')
            ->leftJoin('comment as r', function ($join) {
                $join->on('r.id_value', '=', 'g.goods_id')
                    ->where('comment_type', 0)
                    ->where('r.parent_id', 0)
                    ->where('r.status', 1);
            })
            ->leftJoin('activity_bonus as m', function ($join) use ($time) {
                $join->on('g.bonus_type_id', '=', 'm.type_id')
                    ->where('m.send_start_date', '<=', $time)
                    ->where('m.send_end_date', '>=', $time);
            })
            ->leftJoin('goods_member_price as mp', function ($join) {
                $join->on('mp.goods_id', '=', 'g.goods_id')
                    ->where('mp.user_rank', '=', Session::get('user_rank') ?? 0);
            })
            ->where('g.goods_id', $goods_id)
            ->where('g.is_delete', 0)
            ->groupBy('g.goods_id')
            ->first();

        if (isset($row['goods_id'])) {
            // 用户评论级别取整
            $row['comment_rank'] = ceil($row['comment_rank']) === 0 ? 5 : ceil($row['comment_rank']);

            // 获得商品的销售价格
            $row['market_price'] = CommonHelper::price_format($row['market_price']);
            $row['shop_price_formated'] = CommonHelper::price_format($row['shop_price']);

            // 修正促销价格
            if ($row['promote_price'] > 0) {
                $promote_price = GoodsHelper::bargain_price($row['promote_price'], $row['promote_start_date'], $row['promote_end_date']);
            } else {
                $promote_price = 0;
            }

            // 处理商品水印图片
            $watermark_img = '';

            if ($promote_price != 0) {
                $watermark_img = 'watermark_promote';
            } elseif ($row['is_new'] != 0) {
                $watermark_img = 'watermark_new';
            } elseif ($row['is_best'] != 0) {
                $watermark_img = 'watermark_best';
            } elseif ($row['is_hot'] != 0) {
                $watermark_img = 'watermark_hot';
            }

            if ($watermark_img != '') {
                $row['watermark_img'] = $watermark_img;
            }

            $row['promote_price_org'] = $promote_price;
            $row['promote_price'] = CommonHelper::price_format($promote_price);

            // 修正重量显示
            $row['goods_weight'] = (intval($row['goods_weight']) > 0) ?
                $row['goods_weight'].lang('kilogram') :
                ($row['goods_weight'] * 1000).lang('gram');

            // 修正上架时间显示
            $row['add_time'] = TimeHelper::local_date(cfg('date_format'), $row['add_time']);

            // 促销时间倒计时
            $time = TimeHelper::gmtime();
            if ($time >= $row['promote_start_date'] && $time <= $row['promote_end_date']) {
                $row['gmt_end_time'] = $row['promote_end_date'];
            } else {
                $row['gmt_end_time'] = 0;
            }

            // 是否显示商品库存数量
            $row['goods_number'] = (cfg('use_storage') === 1) ? $row['goods_number'] : '';

            // 修正积分：转换为可使用多少积分（原来是可以使用多少钱的积分）
            $row['integral'] = cfg('integral_scale') ? round($row['integral'] * 100 / cfg('integral_scale')) : 0;

            // 修正优惠券
            $row['bonus_money'] = ($row['bonus_money'] === 0) ? 0 : CommonHelper::price_format($row['bonus_money'], false);

            // 修正商品图片
            $row['goods_img'] = CommonHelper::get_image_path($row['goods_img']);
            $row['goods_thumb'] = CommonHelper::get_image_path($row['goods_thumb']);

            return $row;
        } else {
            return false;
        }
    }

    /**
     * 获得商品的属性和规格
     *
     * @param  int  $goods_id
     * @return array
     */
    public static function get_goods_properties($goods_id)
    {
        // 对属性进行重新排序和分组
        $grp = DB::table('goods_type as gt')
            ->join('goods as g', 'gt.cat_id', '=', 'g.goods_type')
            ->where('g.goods_id', $goods_id)
            ->value('attr_group');

        if (! empty($grp)) {
            $groups = explode("\n", strtr($grp, "\r", ''));
        }

        // 获得商品的规格
        $res = DB::table('goods_attr as g')
            ->select('a.attr_id', 'a.attr_name', 'a.attr_group', 'a.is_linked', 'a.attr_type', 'g.goods_attr_id', 'g.attr_value', 'g.attr_price')
            ->leftJoin('goods_type_attribute as a', 'a.attr_id', '=', 'g.attr_id')
            ->where('g.goods_id', $goods_id)
            ->orderBy('a.sort_order')
            ->orderBy('g.attr_price')
            ->orderBy('g.goods_attr_id')
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        $arr['pro'] = [];     // 属性
        $arr['spe'] = [];     // 规格
        $arr['lnk'] = [];     // 关联的属性

        foreach ($res as $row) {
            $row['attr_value'] = str_replace("\n", '<br />', $row['attr_value']);

            if ($row['attr_type'] === 0) {
                $group = (isset($groups[$row['attr_group']])) ? $groups[$row['attr_group']] : lang('goods_attr');

                $arr['pro'][$group][$row['attr_id']]['name'] = $row['attr_name'];
                $arr['pro'][$group][$row['attr_id']]['value'] = $row['attr_value'];
            } else {
                $arr['spe'][$row['attr_id']]['attr_type'] = $row['attr_type'];
                $arr['spe'][$row['attr_id']]['name'] = $row['attr_name'];
                $arr['spe'][$row['attr_id']]['values'][] = [
                    'label' => $row['attr_value'],
                    'price' => $row['attr_price'],
                    'format_price' => CommonHelper::price_format(abs($row['attr_price']), false),
                    'id' => $row['goods_attr_id']];
            }

            if ($row['is_linked'] === 1) {
                // 如果该属性需要关联，先保存下来
                $arr['lnk'][$row['attr_id']]['name'] = $row['attr_name'];
                $arr['lnk'][$row['attr_id']]['value'] = $row['attr_value'];
            }
        }

        return $arr;
    }

    /**
     * 获得属性相同的商品
     *
     * @param  array  $attr  // 包含了属性名称,ID的数组
     * @return array
     */
    public static function get_same_attribute_goods($attr)
    {
        $lnk = [];

        if (! empty($attr)) {
            foreach ($attr['lnk'] as $key => $val) {
                $lnk[$key]['title'] = sprintf(lang('same_attrbiute_goods'), $val['name'], $val['value']);

                // 查找符合条件的商品
                $res = DB::table('goods as g')
                    ->select('g.goods_id', 'g.goods_name', 'g.goods_thumb', 'g.goods_img', 'g.shop_price AS org_price', DB::raw("IFNULL(mp.user_price, g.shop_price * '".(Session::get('discount') ?? 1)."') AS shop_price"), 'g.market_price', 'g.promote_price', 'g.promote_start_date', 'g.promote_end_date')
                    ->leftJoin('goods_attr as a', 'g.goods_id', '=', 'a.goods_id')
                    ->leftJoin('goods_member_price as mp', function ($join) {
                        $join->on('mp.goods_id', '=', 'g.goods_id')
                            ->where('mp.user_rank', '=', Session::get('user_rank') ?? 0);
                    })
                    ->where('a.attr_id', $key)
                    ->where('g.is_on_sale', 1)
                    ->where('a.attr_value', $val['value'])
                    ->where('g.goods_id', '<>', request()->input('id'))
                    ->limit((int) cfg('attr_related_number'))
                    ->get()
                    ->map(fn ($item) => (array) $item)
                    ->all();

                foreach ($res as $row) {
                    $lnk[$key]['goods'][$row['goods_id']]['goods_id'] = $row['goods_id'];
                    $lnk[$key]['goods'][$row['goods_id']]['goods_name'] = $row['goods_name'];
                    $lnk[$key]['goods'][$row['goods_id']]['short_name'] = (int) cfg('goods_name_length') > 0 ?
                        Str::substr($row['goods_name'], (int) cfg('goods_name_length')) : $row['goods_name'];
                    $lnk[$key]['goods'][$row['goods_id']]['goods_thumb'] = (empty($row['goods_thumb'])) ? cfg('no_picture') : $row['goods_thumb'];
                    $lnk[$key]['goods'][$row['goods_id']]['market_price'] = CommonHelper::price_format($row['market_price']);
                    $lnk[$key]['goods'][$row['goods_id']]['shop_price'] = CommonHelper::price_format($row['shop_price']);
                    $lnk[$key]['goods'][$row['goods_id']]['promote_price'] = GoodsHelper::bargain_price(
                        $row['promote_price'],
                        $row['promote_start_date'],
                        $row['promote_end_date']
                    );
                    $lnk[$key]['goods'][$row['goods_id']]['url'] = build_uri('goods', ['gid' => $row['goods_id']], $row['goods_name']);
                }
            }
        }

        return $lnk;
    }

    /**
     * 获得指定商品的相册
     *
     * @param  int  $goods_id
     * @return array
     */
    public static function get_goods_gallery($goods_id)
    {
        $row = DB::table('goods_gallery')
            ->select('img_id', 'img_url', 'thumb_url', 'img_desc')
            ->where('goods_id', $goods_id)
            ->orderBy('sort_order')
            ->limit((int) cfg('goods_gallery_number'))
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();
        // 格式化相册图片路径
        foreach ($row as $key => $gallery_img) {
            $row[$key]['img_url'] = CommonHelper::get_image_path($gallery_img['img_url']);
            $row[$key]['thumb_url'] = CommonHelper::get_image_path($gallery_img['thumb_url']);
        }

        return $row;
    }

    /**
     * 获得指定分类下的商品
     *
     * @param  int  $cat_id  分类ID
     * @param  int  $num  数量
     * @param  string  $from  来自web/wap的调用
     * @param  string  $order_rule  指定商品排序规则
     * @return array
     */
    public static function assign_cat_goods($cat_id, $num = 0, $from = 'web', $order_rule = '')
    {
        $children = CommonHelper::get_children($cat_id);

        $query = DB::table('goods as g')
            ->select('g.goods_id', 'g.goods_name', 'g.market_price', 'g.shop_price AS org_price', DB::raw("IFNULL(mp.user_price, g.shop_price * '".(Session::get('discount') ?? 1)."') AS shop_price"), 'g.promote_price', 'promote_start_date', 'promote_end_date', 'g.goods_brief', 'g.goods_thumb', 'g.goods_img')
            ->leftJoin('goods_member_price as mp', function ($join) {
                $join->on('mp.goods_id', '=', 'g.goods_id')
                    ->where('mp.user_rank', '=', Session::get('user_rank') ?? 0);
            })
            ->where('g.is_on_sale', 1)
            ->where('g.is_alone_sale', 1)
            ->where('g.is_delete', 0)
            ->whereRaw("($children OR ".GoodsHelper::get_extension_goods($children).')');

        if ($order_rule) {
            $query->whereRaw(trim($order_rule, ' ORDER BY'));
        } else {
            $query->orderBy('g.sort_order')->orderByDesc('g.goods_id');
        }

        if ($num > 0) {
            $query->limit((int) $num);
        }

        $res = $query->get()->map(fn ($item) => (array) $item)->all();

        $goods = [];
        foreach ($res as $idx => $row) {
            if ($row['promote_price'] > 0) {
                $promote_price = GoodsHelper::bargain_price($row['promote_price'], $row['promote_start_date'], $row['promote_end_date']);
                $goods[$idx]['promote_price'] = $promote_price > 0 ? CommonHelper::price_format($promote_price) : '';
            } else {
                $goods[$idx]['promote_price'] = '';
            }

            $goods[$idx]['id'] = $row['goods_id'];
            $goods[$idx]['name'] = $row['goods_name'];
            $goods[$idx]['brief'] = $row['goods_brief'];
            $goods[$idx]['market_price'] = CommonHelper::price_format($row['market_price']);
            $goods[$idx]['short_name'] = (int) cfg('goods_name_length') > 0 ?
                Str::substr($row['goods_name'], (int) cfg('goods_name_length')) : $row['goods_name'];
            $goods[$idx]['shop_price'] = CommonHelper::price_format($row['shop_price']);
            $goods[$idx]['thumb'] = CommonHelper::get_image_path($row['goods_thumb']);
            $goods[$idx]['goods_img'] = CommonHelper::get_image_path($row['goods_img']);
            $goods[$idx]['url'] = build_uri('goods', ['gid' => $row['goods_id']], $row['goods_name']);
        }

        if ($from === 'web') {
            tpl()->assign('cat_goods_'.$cat_id, $goods);
        } elseif ($from === 'wap') {
            $cat['goods'] = $goods;
        }

        // 分类信息
        $cat['name'] = DB::table('goods_category')->where('cat_id', $cat_id)->value('cat_name');
        $cat['url'] = build_uri('category', ['cid' => $cat_id], $cat['name']);
        $cat['id'] = $cat_id;

        return $cat;
    }

    /**
     * 获得指定的品牌下的商品
     *
     * @param  int  $brand_id  品牌的ID
     * @param  int  $num  数量
     * @param  int  $cat_id  分类编号
     * @param  string  $order_rule  指定商品排序规则
     * @return void
     */
    public static function assign_brand_goods($brand_id, $num = 0, $cat_id = 0, $order_rule = '')
    {
        $query = DB::table('goods as g')
            ->select('g.goods_id', 'g.goods_name', 'g.market_price', 'g.shop_price AS org_price', DB::raw("IFNULL(mp.user_price, g.shop_price * '".(Session::get('discount') ?? 1)."') AS shop_price"), 'g.promote_price', 'g.promote_start_date', 'g.promote_end_date', 'g.goods_brief', 'g.goods_thumb', 'g.goods_img')
            ->leftJoin('goods_member_price as mp', function ($join) {
                $join->on('mp.goods_id', '=', 'g.goods_id')
                    ->where('mp.user_rank', '=', Session::get('user_rank') ?? 0);
            })
            ->where('g.is_on_sale', 1)
            ->where('g.is_alone_sale', 1)
            ->where('g.is_delete', 0)
            ->where('g.brand_id', $brand_id);

        if ($cat_id > 0) {
            $query->whereRaw(CommonHelper::get_children($cat_id));
        }

        if ($order_rule) {
            $query->whereRaw(trim($order_rule, ' ORDER BY'));
        } else {
            $query->orderBy('g.sort_order')->orderByDesc('g.goods_id');
        }

        if ($num > 0) {
            $query->limit((int) $num);
        }

        $res = $query->get()->map(fn ($item) => (array) $item)->all();

        $idx = 0;
        $goods = [];
        foreach ($res as $row) {
            if ($row['promote_price'] > 0) {
                $promote_price = GoodsHelper::bargain_price($row['promote_price'], $row['promote_start_date'], $row['promote_end_date']);
            } else {
                $promote_price = 0;
            }

            $goods[$idx]['id'] = $row['goods_id'];
            $goods[$idx]['name'] = $row['goods_name'];
            $goods[$idx]['short_name'] = (int) cfg('goods_name_length') > 0 ?
                Str::substr($row['goods_name'], (int) cfg('goods_name_length')) : $row['goods_name'];
            $goods[$idx]['market_price'] = CommonHelper::price_format($row['market_price']);
            $goods[$idx]['shop_price'] = CommonHelper::price_format($row['shop_price']);
            $goods[$idx]['promote_price'] = $promote_price > 0 ? CommonHelper::price_format($promote_price) : '';
            $goods[$idx]['brief'] = $row['goods_brief'];
            $goods[$idx]['thumb'] = CommonHelper::get_image_path($row['goods_thumb']);
            $goods[$idx]['goods_img'] = CommonHelper::get_image_path($row['goods_img']);
            $goods[$idx]['url'] = build_uri('goods', ['gid' => $row['goods_id']], $row['goods_name']);

            $idx++;
        }

        // 分类信息
        $brand['name'] = DB::table('goods_brand')->where('brand_id', $brand_id)->value('brand_name');
        $brand['url'] = build_uri('brand', ['bid' => $brand_id], $brand['name']);

        $brand_goods = ['brand' => $brand, 'goods' => $goods];

        return $brand_goods;
    }

    /**
     * 获得所有扩展分类属于指定分类的所有商品ID
     *
     * @param  string  $cat_id  分类查询字符串
     * @return string
     */
    public static function get_extension_goods($cats)
    {
        $extension_goods_array = '';
        $extension_goods_array = DB::table('goods_cat as g')->whereRaw($cats)->pluck('goods_id')->all();

        return db_create_in($extension_goods_array, 'g.goods_id');
    }

    /**
     * 判断某个商品是否正在特价促销期
     *
     * @param  float  $price  促销价格
     * @param  string  $start  促销开始日期
     * @param  string  $end  促销结束日期
     * @return float 如果还在促销期则返回促销价，否则返回0
     */
    public static function bargain_price($price, $start, $end)
    {
        if ($price === 0) {
            return 0;
        } else {
            $time = TimeHelper::gmtime();
            if ($time >= $start && $time <= $end) {
                return $price;
            } else {
                return 0;
            }
        }
    }

    /**
     * 获得指定的规格的价格
     *
     * @param  mixed  $spec  规格ID的数组或者逗号分隔的字符串
     * @return void
     */
    public static function spec_price($spec)
    {
        if (! empty($spec)) {
            if (is_array($spec)) {
                foreach ($spec as $key => $val) {
                    $spec[$key] = addslashes($val);
                }
            } else {
                $spec = addslashes($spec);
            }

            $price = (float) DB::table('goods_attr')->whereIn('goods_attr_id', (array) $spec)->sum('attr_price');
        } else {
            $price = 0;
        }

        return $price;
    }

    /**
     * 取得团购活动信息
     *
     * @param  int  $group_buy_id  团购活动id
     * @param  int  $current_num  本次购买数量（计算当前价时要加上的数量）
     * @return array
     *               status          状态：
     */
    public static function group_buy_info($group_buy_id, $current_num = 0)
    {
        // 取得团购活动信息
        $group_buy = (array) DB::table('goods_activity')
            ->select('*', 'act_id AS group_buy_id', 'act_desc AS group_buy_desc', 'start_time AS start_date', 'end_time AS end_date')
            ->where('act_id', (int) $group_buy_id)
            ->where('act_type', GAT_GROUP_BUY)
            ->first();

        // 如果为空，返回空数组
        if (empty($group_buy)) {
            return [];
        }

        $ext_info = unserialize($group_buy['ext_info']);
        $group_buy = array_merge($group_buy, $ext_info);

        // 格式化时间
        $group_buy['formated_start_date'] = TimeHelper::local_date('Y-m-d H:i', $group_buy['start_time']);
        $group_buy['formated_end_date'] = TimeHelper::local_date('Y-m-d H:i', $group_buy['end_time']);

        // 格式化保证金
        $group_buy['formated_deposit'] = CommonHelper::price_format($group_buy['deposit'], false);

        // 处理价格阶梯
        $price_ladder = $group_buy['price_ladder'];
        if (! is_array($price_ladder) || empty($price_ladder)) {
            $price_ladder = [['amount' => 0, 'price' => 0]];
        } else {
            foreach ($price_ladder as $key => $amount_price) {
                $price_ladder[$key]['formated_price'] = CommonHelper::price_format($amount_price['price'], false);
            }
        }
        $group_buy['price_ladder'] = $price_ladder;

        // 统计信息
        $stat = GoodsHelper::group_buy_stat($group_buy_id, $group_buy['deposit']);
        $group_buy = array_merge($group_buy, $stat);

        // 计算当前价
        $cur_price = $price_ladder[0]['price']; // 初始化
        $cur_amount = $stat['valid_goods'] + $current_num; // 当前数量
        foreach ($price_ladder as $amount_price) {
            if ($cur_amount >= $amount_price['amount']) {
                $cur_price = $amount_price['price'];
            } else {
                break;
            }
        }
        $group_buy['cur_price'] = $cur_price;
        $group_buy['formated_cur_price'] = CommonHelper::price_format($cur_price, false);

        // 最终价
        $group_buy['trans_price'] = $group_buy['cur_price'];
        $group_buy['formated_trans_price'] = $group_buy['formated_cur_price'];
        $group_buy['trans_amount'] = $group_buy['valid_goods'];

        // 状态
        $group_buy['status'] = GoodsHelper::group_buy_status($group_buy);
        if (isset(lang('gbs')[$group_buy['status']])) {
            $group_buy['status_desc'] = lang('gbs')[$group_buy['status']];
        }

        $group_buy['start_time'] = $group_buy['formated_start_date'];
        $group_buy['end_time'] = $group_buy['formated_end_date'];

        return $group_buy;
    }

    /*
     * 取得某团购活动统计信息
     * @param   int     $group_buy_id   团购活动id
     * @param   float   $deposit        保证金
     * @return  array   统计信息
     *                  total_order     总订单数
     *                  total_goods     总商品数
     *                  valid_order     有效订单数
     *                  valid_goods     有效商品数
     */
    public static function group_buy_stat($group_buy_id, $deposit)
    {
        $group_buy_id = intval($group_buy_id);

        // 取得团购活动商品ID
        $group_buy_goods_id = DB::table('goods_activity')
            ->where('act_id', (int) $group_buy_id)
            ->where('act_type', GAT_GROUP_BUY)
            ->value('goods_id');

        // 取得总订单数和总商品数
        $stat = (array) DB::table('order_info as o')
            ->select(DB::raw('COUNT(*) AS total_order'), DB::raw('SUM(g.goods_number) AS total_goods'))
            ->join('order_goods as g', 'o.order_id', '=', 'g.order_id')
            ->where('o.extension_code', 'group_buy')
            ->where('o.extension_id', $group_buy_id)
            ->where('g.goods_id', $group_buy_goods_id)
            ->whereIn('o.order_status', [OS_CONFIRMED, OS_UNCONFIRMED])
            ->first();
        if ($stat['total_order'] === 0) {
            $stat['total_goods'] = 0;
        }

        // 取得有效订单数和有效商品数
        $deposit = floatval($deposit);
        if ($deposit > 0 && $stat['total_order'] > 0) {
            $row = (array) DB::table('order_info as o')
                ->select(DB::raw('COUNT(*) AS total_order'), DB::raw('SUM(g.goods_number) AS total_goods'))
                ->join('order_goods as g', 'o.order_id', '=', 'g.order_id')
                ->where('o.extension_code', 'group_buy')
                ->where('o.extension_id', $group_buy_id)
                ->where('g.goods_id', $group_buy_goods_id)
                ->whereIn('o.order_status', [OS_CONFIRMED, OS_UNCONFIRMED])
                ->whereRaw('(o.money_paid + o.surplus) >= ?', [$deposit])
                ->first();

            $stat['valid_order'] = $row['total_order'];
            if ($stat['valid_order'] === 0) {
                $stat['valid_goods'] = 0;
            } else {
                $stat['valid_goods'] = $row['total_goods'];
            }
        } else {
            $stat['valid_order'] = $stat['total_order'];
            $stat['valid_goods'] = $stat['total_goods'];
        }

        return $stat;
    }

    /**
     * 获得团购的状态
     *
     * @param array
     * @return int
     */
    public static function group_buy_status($group_buy)
    {
        $now = TimeHelper::gmtime();
        if ($group_buy['is_finished'] === 0) {
            // 未处理
            if ($now < $group_buy['start_time']) {
                $status = GBS_PRE_START;
            } elseif ($now > $group_buy['end_time']) {
                $status = GBS_FINISHED;
            } else {
                if ($group_buy['restrict_amount'] === 0 || $group_buy['valid_goods'] < $group_buy['restrict_amount']) {
                    $status = GBS_UNDER_WAY;
                } else {
                    $status = GBS_FINISHED;
                }
            }
        } elseif ($group_buy['is_finished'] === GBS_SUCCEED) {
            // 已处理，团购成功
            $status = GBS_SUCCEED;
        } elseif ($group_buy['is_finished'] === GBS_FAIL) {
            // 已处理，团购失败
            $status = GBS_FAIL;
        }

        return $status;
    }

    /**
     * 取得拍卖活动信息
     *
     * @param  int  $act_id  活动id
     * @return array
     */
    public static function auction_info($act_id, $config = false)
    {
        $auction = (array) DB::table('goods_activity')
            ->where('act_id', $act_id)
            ->first();

        if (empty($auction) || $auction['act_type'] != GAT_AUCTION) {
            return [];
        }
        $auction['status_no'] = GoodsHelper::auction_status($auction);
        if ($config === true) {
            $auction['start_time'] = TimeHelper::local_date('Y-m-d H:i', $auction['start_time']);
            $auction['end_time'] = TimeHelper::local_date('Y-m-d H:i', $auction['end_time']);
        } else {
            $auction['start_time'] = TimeHelper::local_date(cfg('time_format'), $auction['start_time']);
            $auction['end_time'] = TimeHelper::local_date(cfg('time_format'), $auction['end_time']);
        }
        $ext_info = unserialize($auction['ext_info']);
        $auction = array_merge($auction, $ext_info);
        $auction['formated_start_price'] = CommonHelper::price_format($auction['start_price']);
        $auction['formated_end_price'] = CommonHelper::price_format($auction['end_price']);
        $auction['formated_amplitude'] = CommonHelper::price_format($auction['amplitude']);
        $auction['formated_deposit'] = CommonHelper::price_format($auction['deposit']);

        // 查询出价用户数和最后出价
        $auction['bid_user_count'] = DB::table('activity_auction')
            ->where('act_id', $act_id)
            ->distinct()
            ->count('bid_user');

        if ($auction['bid_user_count'] > 0) {
            $row = (array) DB::table('activity_auction as a')
                ->select('a.*', 'u.user_name')
                ->join('user as u', 'a.bid_user', '=', 'u.user_id')
                ->where('act_id', $act_id)
                ->orderByDesc('a.log_id')
                ->first();
            $row['formated_bid_price'] = CommonHelper::price_format($row['bid_price'], false);
            $row['bid_time'] = TimeHelper::local_date(cfg('time_format'), $row['bid_time']);
            $auction['last_bid'] = $row;
        }

        // 查询已确认订单数
        if ($auction['status_no'] > 1) {
            $auction['order_count'] = DB::table('order_info')
                ->where('extension_code', 'auction')
                ->where('extension_id', $act_id)
                ->whereIn('order_status', [OS_CONFIRMED, OS_UNCONFIRMED])
                ->count();
        } else {
            $auction['order_count'] = 0;
        }

        // 当前价
        $auction['current_price'] = isset($auction['last_bid']) ? $auction['last_bid']['bid_price'] : $auction['start_price'];
        $auction['formated_current_price'] = CommonHelper::price_format($auction['current_price'], false);

        return $auction;
    }

    /**
     * 取得拍卖活动出价记录
     *
     * @param  int  $act_id  活动id
     * @return array
     */
    public static function auction_log($act_id)
    {
        $log = [];
        $res = DB::table('activity_auction as a')
            ->select('a.*', 'u.user_name')
            ->join('user as u', 'a.bid_user', '=', 'u.user_id')
            ->where('act_id', $act_id)
            ->orderByDesc('a.log_id')
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();
        foreach ($res as $row) {
            $row['bid_time'] = TimeHelper::local_date(cfg('time_format'), $row['bid_time']);
            $row['formated_bid_price'] = CommonHelper::price_format($row['bid_price'], false);
            $log[] = $row;
        }

        return $log;
    }

    /**
     * 计算拍卖活动状态（注意参数一定是原始信息）
     *
     * @param  array  $auction  拍卖活动原始信息
     * @return int
     */
    public static function auction_status($auction)
    {
        $now = TimeHelper::gmtime();
        if ($auction['is_finished'] === 0) {
            if ($now < $auction['start_time']) {
                return PRE_START; // 未开始
            } elseif ($now > $auction['end_time']) {
                return FINISHED; // 已结束，未处理
            } else {
                return UNDER_WAY; // 进行中
            }
        } elseif ($auction['is_finished'] === 1) {
            return FINISHED; // 已结束，未处理
        } else {
            return SETTLED; // 已结束，已处理
        }
    }

    /**
     * 取得商品信息
     *
     * @param  int  $goods_id  商品id
     * @return array
     */
    public static function goods_info($goods_id)
    {
        $row = (array) DB::table('goods as g')
            ->select('g.*', 'b.brand_name')
            ->leftJoin('goods_brand as b', 'g.brand_id', '=', 'b.brand_id')
            ->where('g.goods_id', $goods_id)
            ->first();
        if (! empty($row)) {
            // 修正重量显示
            $row['goods_weight'] = (intval($row['goods_weight']) > 0) ?
                $row['goods_weight'].lang('kilogram') :
                ($row['goods_weight'] * 1000).lang('gram');

            // 修正图片
            $row['goods_img'] = CommonHelper::get_image_path($row['goods_img']);
        }

        return $row;
    }

    /**
     * 取得优惠活动信息
     *
     * @param  int  $act_id  活动id
     * @return array
     */
    public static function favourable_info($act_id)
    {
        $row = (array) DB::table('activity')
            ->where('act_id', $act_id)
            ->first();
        if (! empty($row)) {
            $row['start_time'] = TimeHelper::local_date(cfg('time_format'), $row['start_time']);
            $row['end_time'] = TimeHelper::local_date(cfg('time_format'), $row['end_time']);
            $row['formated_min_amount'] = CommonHelper::price_format($row['min_amount']);
            $row['formated_max_amount'] = CommonHelper::price_format($row['max_amount']);
            $row['gift'] = unserialize($row['gift']);
            if ($row['act_type'] === FAT_GOODS) {
                $row['act_type_ext'] = round($row['act_type_ext']);
            }
        }

        return $row;
    }

    /**
     * 批发信息
     *
     * @param  int  $act_id  活动id
     * @return array
     */
    public static function wholesale_info($act_id)
    {
        $row = (array) DB::table('activity_wholesale')
            ->where('act_id', $act_id)
            ->first();
        if (! empty($row)) {
            $row['price_list'] = unserialize($row['prices']);
        }

        return $row;
    }

    /**
     * 添加商品名样式
     *
     * @param  string  $goods_name  商品名称
     * @param  string  $style  样式参数
     * @return string
     */
    public static function add_style($goods_name, $style)
    {
        $goods_style_name = $goods_name;

        $arr = explode('+', $style);

        $font_color = ! empty($arr[0]) ? $arr[0] : '';
        $font_style = ! empty($arr[1]) ? $arr[1] : '';

        if ($font_color != '') {
            $goods_style_name = '<font color='.(string) $font_color.'>'.$goods_style_name.'</font>';
        }
        if ($font_style != '') {
            $goods_style_name = '<'.(string) $font_style.'>'.$goods_style_name.'</'.(string) $font_style.'>';
        }

        return $goods_style_name;
    }

    /**
     * 取得商品属性
     *
     * @param  int  $goods_id  商品id
     * @return array
     */
    public static function get_goods_attr($goods_id)
    {
        $attr_list = [];
        $attr_id_list = DB::table('goods as g')
            ->join('goods_type_attribute as a', 'g.goods_type', '=', 'a.cat_id')
            ->where('g.goods_id', $goods_id)
            ->where('a.attr_type', 1)
            ->pluck('a.attr_id')
            ->all();

        $res = DB::table('goods as g')
            ->select('a.attr_id', 'a.attr_name')
            ->join('goods_type_attribute as a', 'g.goods_type', '=', 'a.cat_id')
            ->where('g.goods_id', $goods_id)
            ->where('a.attr_type', 1)
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();
        foreach ($res as $attr) {
            if (defined('ECS_ADMIN')) {
                $attr['goods_attr_list'] = [0 => lang('select_please')];
            } else {
                $attr['goods_attr_list'] = [];
            }
            $attr_list[$attr['attr_id']] = $attr;
        }

        $res = DB::table('goods_attr')
            ->select('attr_id', 'goods_attr_id', 'attr_value')
            ->where('goods_id', $goods_id)
            ->whereIn('attr_id', $attr_id_list)
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();
        foreach ($res as $goods_attr) {
            $attr_list[$goods_attr['attr_id']]['goods_attr_list'][$goods_attr['goods_attr_id']] = $goods_attr['attr_value'];
        }

        return $attr_list;
    }

    /**
     * 获得购物车中商品的配件
     *
     * @param  array  $goods_list
     * @return array
     */
    public static function get_goods_fittings($goods_list = [])
    {
        $temp_index = 0;
        $arr = [];

        $res = DB::table('activity_group as gg')
            ->select('gg.parent_id', 'ggg.goods_name AS parent_name', 'gg.goods_id', 'gg.goods_price', 'g.goods_name', 'g.goods_thumb', 'g.goods_img', 'g.shop_price AS org_price', DB::raw("IFNULL(mp.user_price, g.shop_price * '".(Session::get('discount') ?? 1)."') AS shop_price"))
            ->leftJoin('goods as g', 'g.goods_id', '=', 'gg.goods_id')
            ->leftJoin('goods_member_price as mp', function ($join) {
                $join->on('mp.goods_id', '=', 'gg.goods_id')
                    ->where('mp.user_rank', '=', Session::get('user_rank') ?? 0);
            })
            ->leftJoin('goods as ggg', 'ggg.goods_id', '=', 'gg.parent_id')
            ->whereIn('gg.parent_id', (array) $goods_list)
            ->where('g.is_delete', 0)
            ->where('g.is_on_sale', 1)
            ->orderBy('gg.parent_id')
            ->orderBy('gg.goods_id')
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        foreach ($res as $row) {
            $arr[$temp_index]['parent_id'] = $row['parent_id']; // 配件的基本件ID
            $arr[$temp_index]['parent_name'] = $row['parent_name']; // 配件的基本件的名称
            $arr[$temp_index]['parent_short_name'] = (int) cfg('goods_name_length') > 0 ?
                Str::substr($row['parent_name'], (int) cfg('goods_name_length')) : $row['parent_name']; // 配件的基本件显示的名称
            $arr[$temp_index]['goods_id'] = $row['goods_id']; // 配件的商品ID
            $arr[$temp_index]['goods_name'] = $row['goods_name']; // 配件的名称
            $arr[$temp_index]['short_name'] = (int) cfg('goods_name_length') > 0 ?
                Str::substr($row['goods_name'], (int) cfg('goods_name_length')) : $row['goods_name']; // 配件显示的名称
            $arr[$temp_index]['fittings_price'] = CommonHelper::price_format($row['goods_price']); // 配件价格
            $arr[$temp_index]['shop_price'] = CommonHelper::price_format($row['shop_price']); // 配件原价格
            $arr[$temp_index]['goods_thumb'] = CommonHelper::get_image_path($row['goods_thumb']);
            $arr[$temp_index]['goods_img'] = CommonHelper::get_image_path($row['goods_img']);
            $arr[$temp_index]['url'] = build_uri('goods', ['gid' => $row['goods_id']], $row['goods_name']);
            $temp_index++;
        }

        return $arr;
    }

    /**
     * 取指定规格的货品信息
     *
     * @param  string  $goods_id
     * @param  array  $spec_goods_attr_id
     * @return array
     */
    public static function get_products_info($goods_id, $spec_goods_attr_id)
    {
        $return_array = [];

        if (empty($spec_goods_attr_id) || ! is_array($spec_goods_attr_id) || empty($goods_id)) {
            return $return_array;
        }

        $goods_attr_array = CommonHelper::sort_goods_attr_id_array($spec_goods_attr_id);

        if (isset($goods_attr_array['sort'])) {
            $goods_attr = implode('|', $goods_attr_array['sort']);

            $return_array = (array) DB::table('goods_product')
                ->where('goods_id', $goods_id)
                ->where('goods_attr', $goods_attr)
                ->first();
        }

        return $return_array;
    }
}
