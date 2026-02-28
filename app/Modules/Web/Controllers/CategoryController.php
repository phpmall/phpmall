<?php

declare(strict_types=1);

namespace App\Modules\Web\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\CommonHelper;
use App\Helpers\GoodsHelper;
use App\Helpers\MainHelper;
use App\Helpers\TimeHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class CategoryController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        // 获得请求的分类 ID
        if (isset($_REQUEST['id'])) {
            $cat_id = intval($_REQUEST['id']);
        } elseif (isset($_REQUEST['category'])) {
            $cat_id = intval($_REQUEST['category']);
        } else {
            // 如果分类ID为0，则返回首页
            return response()->redirectTo('/');
        }

        // 初始化分页信息
        $page = isset($_REQUEST['page']) && intval($_REQUEST['page']) > 0 ? intval($_REQUEST['page']) : 1;
        $size = cfg('page_size') && intval(cfg('page_size')) > 0 ? intval(cfg('page_size')) : 10;
        $brand = isset($_REQUEST['brand']) && intval($_REQUEST['brand']) > 0 ? intval($_REQUEST['brand']) : 0;
        $price_max = isset($_REQUEST['price_max']) && intval($_REQUEST['price_max']) > 0 ? intval($_REQUEST['price_max']) : 0;
        $price_min = isset($_REQUEST['price_min']) && intval($_REQUEST['price_min']) > 0 ? intval($_REQUEST['price_min']) : 0;
        $filter_attr_str = isset($_REQUEST['filter_attr']) ? htmlspecialchars(trim($_REQUEST['filter_attr'])) : '0';

        $filter_attr_str = trim(urldecode($filter_attr_str));
        $filter_attr_str = preg_match('/^[\d\.]+$/', $filter_attr_str) ? $filter_attr_str : '';
        $filter_attr = empty($filter_attr_str) ? '' : explode('.', $filter_attr_str);

        // 排序、显示方式以及类型
        $default_display_type = cfg('show_order_type') === '0' ? 'list' : (cfg('show_order_type') === '1' ? 'grid' : 'text');
        $default_sort_order_method = cfg('sort_order_method') === '0' ? 'DESC' : 'ASC';
        $default_sort_order_type = cfg('sort_order_type') === '0' ? 'goods_id' : (cfg('sort_order_type') === '1' ? 'shop_price' : 'last_update');

        $sort = (isset($_REQUEST['sort']) && in_array(trim(strtolower($_REQUEST['sort'])), ['goods_id', 'shop_price', 'last_update'])) ? trim($_REQUEST['sort']) : $default_sort_order_type;
        $order = (isset($_REQUEST['order']) && in_array(trim(strtoupper($_REQUEST['order'])), ['ASC', 'DESC'])) ? trim($_REQUEST['order']) : $default_sort_order_method;
        $ecsCookie = Cookie::get('ECS');
        $displayValue = is_array($ecsCookie) ? ($ecsCookie['display'] ?? '') : '';
        $display = (isset($_REQUEST['display']) && in_array(trim(strtolower($_REQUEST['display'])), ['list', 'grid', 'text'])) ? trim($_REQUEST['display']) : ($displayValue ?: $default_display_type);
        $display = in_array($display, ['list', 'grid', 'text']) ? $display : 'text';
        Cookie::queue('ECS[display]', $display, TimeHelper::gmtime() + 86400 * 7);

        // 页面的缓存ID
        $cache_id = sprintf('%X', crc32($cat_id.'-'.$display.'-'.$sort.'-'.$order.'-'.$page.'-'.$size.'-'.Session::get('user_rank').'-'.
            cfg('lang').'-'.$brand.'-'.$price_max.'-'.$price_min.'-'.$filter_attr_str));

        if (! $this->is_cached('category', $cache_id)) {
            // 如果页面没有被缓存则重新获取页面的内容

            $children = CommonHelper::get_children($cat_id);

            $cat = $this->get_cat_info($cat_id);   // 获得分类的相关信息

            if (! empty($cat)) {
                $this->assign('keywords', htmlspecialchars($cat['keywords']));
                $this->assign('description', htmlspecialchars($cat['cat_desc']));
                $this->assign('cat_style', htmlspecialchars($cat['style']));
            } else {
                // 如果分类不存在则返回首页
                return response()->redirectTo('/');
            }

            // 赋值固定内容
            if ($brand > 0) {
                $brand_name = DB::table('goods_brand')->where('brand_id', $brand)->value('brand_name');
            } else {
                $brand_name = '';
            }

            // 获取价格分级
            if ($cat['grade'] === 0 && $cat['parent_id'] != 0) {
                $cat['grade'] = $this->get_parent_grade($cat_id); // 如果当前分类级别为空，取最近的上级分类
            }

            if ($cat['grade'] > 1) {
                // 需要价格分级

                /*
                    算法思路：
                        1、当分级大于1时，进行价格分级
                        2、取出该类下商品价格的最大值、最小值
                        3、根据商品价格的最大值来计算商品价格的分级数量级：
                                价格范围(不含最大值)    分级数量级
                                0-0.1                   0.001
                                0.1-1                   0.01
                                1-10                    0.1
                                10-100                  1
                                100-1000                10
                                1000-10000              100
                        4、计算价格跨度：
                                取整((最大值-最小值) / (价格分级数) / 数量级) * 数量级
                        5、根据价格跨度计算价格范围区间
                        6、查询数据库

                    可能存在问题：
                        1、
                        由于价格跨度是由最大值、最小值计算出来的
                        然后再通过价格跨度来确定显示时的价格范围区间
                        所以可能会存在价格分级数量不正确的问题
                        该问题没有证明
                        2、
                        当价格=最大值时，分级会多出来，已被证明存在
                */

                $row = DB::table('goods')
                    ->selectRaw('min(shop_price) AS min, max(shop_price) as max')
                    ->where(function ($query) use ($children) {
                        $query->whereRaw($children)
                            ->orWhereRaw(GoodsHelper::get_extension_goods($children));
                    })
                    ->where('is_delete', 0)
                    ->where('is_on_sale', 1)
                    ->where('is_alone_sale', 1)
                    ->first();
                $row = (array) $row;

                // 取得价格分级最小单位级数，比如，千元商品最小以100为级数
                $price_grade = 0.0001;
                for ($i = -2; $i <= log10($row['max']); $i++) {
                    $price_grade *= 10;
                }

                // 跨度
                $dx = ceil(($row['max'] - $row['min']) / ($cat['grade']) / $price_grade) * $price_grade;
                if ($dx === 0) {
                    $dx = $price_grade;
                }

                for ($i = 1; $row['min'] > $dx * $i; $i++);

                for ($j = 1; $row['min'] > $dx * ($i - 1) + $price_grade * $j; $j++);
                $row['min'] = $dx * ($i - 1) + $price_grade * ($j - 1);

                for (; $row['max'] >= $dx * $i; $i++);
                $row['max'] = $dx * ($i) + $price_grade * ($j - 1);

                $price_grade = DB::table('goods')
                    ->selectRaw("(FLOOR((shop_price - $row[min]) / $dx)) AS sn, COUNT(*) AS goods_num")
                    ->where(function ($query) use ($children) {
                        $query->whereRaw($children)
                            ->orWhereRaw(GoodsHelper::get_extension_goods($children));
                    })
                    ->where('is_delete', 0)
                    ->where('is_on_sale', 1)
                    ->where('is_alone_sale', 1)
                    ->groupBy('sn')
                    ->get()
                    ->map(fn ($item) => (array) $item)
                    ->all();

                foreach ($price_grade as $key => $val) {
                    $temp_key = $key + 1;
                    $price_grade[$temp_key]['goods_num'] = $val['goods_num'];
                    $price_grade[$temp_key]['start'] = $row['min'] + round($dx * $val['sn']);
                    $price_grade[$temp_key]['end'] = $row['min'] + round($dx * ($val['sn'] + 1));
                    $price_grade[$temp_key]['price_range'] = $price_grade[$temp_key]['start'].'&nbsp;-&nbsp;'.$price_grade[$temp_key]['end'];
                    $price_grade[$temp_key]['formated_start'] = CommonHelper::price_format($price_grade[$temp_key]['start']);
                    $price_grade[$temp_key]['formated_end'] = CommonHelper::price_format($price_grade[$temp_key]['end']);
                    $price_grade[$temp_key]['url'] = build_uri('category', ['cid' => $cat_id, 'bid' => $brand, 'price_min' => $price_grade[$temp_key]['start'], 'price_max' => $price_grade[$temp_key]['end'], 'filter_attr' => $filter_attr_str], $cat['cat_name']);

                    // 判断价格区间是否被选中
                    if (isset($_REQUEST['price_min']) && $price_grade[$temp_key]['start'] === $price_min && $price_grade[$temp_key]['end'] === $price_max) {
                        $price_grade[$temp_key]['selected'] = 1;
                    } else {
                        $price_grade[$temp_key]['selected'] = 0;
                    }
                }

                $price_grade[0]['start'] = 0;
                $price_grade[0]['end'] = 0;
                $price_grade[0]['price_range'] = lang('all_attribute');
                $price_grade[0]['url'] = build_uri('category', ['cid' => $cat_id, 'bid' => $brand, 'price_min' => 0, 'price_max' => 0, 'filter_attr' => $filter_attr_str], $cat['cat_name']);
                $price_grade[0]['selected'] = empty($price_max) ? 1 : 0;

                $this->assign('price_grade', $price_grade);
            }

            // 品牌筛选

            $brands = DB::table('goods_brand as b')
                ->select('b.brand_id', 'b.brand_name', DB::raw('COUNT(*) AS goods_num'))
                ->join('goods as g', 'g.brand_id', '=', 'b.brand_id')
                ->leftJoin('goods_cat as gc', 'g.goods_id', '=', 'gc.goods_id')
                ->where(function ($query) use ($children, $cat_id) {
                    $query->whereRaw($children)
                        ->orWhereIn('gc.cat_id', array_unique(array_merge([$cat_id], array_keys(CommonHelper::cat_list($cat_id, 0, false)))));
                })
                ->where('b.is_show', 1)
                ->where('g.is_on_sale', 1)
                ->where('g.is_alone_sale', 1)
                ->where('g.is_delete', 0)
                ->groupBy('b.brand_id')
                ->having('goods_num', '>', 0)
                ->orderBy('b.sort_order')
                ->orderBy('b.brand_id')
                ->get()
                ->map(fn ($item) => (array) $item)
                ->all();

            foreach ($brands as $key => $val) {
                $temp_key = $key + 1;
                $brands[$temp_key]['brand_name'] = $val['brand_name'];
                $brands[$temp_key]['url'] = build_uri('category', ['cid' => $cat_id, 'bid' => $val['brand_id'], 'price_min' => $price_min, 'price_max' => $price_max, 'filter_attr' => $filter_attr_str], $cat['cat_name']);

                // 判断品牌是否被选中
                if ($brand === $brands[$key]['brand_id']) {
                    $brands[$temp_key]['selected'] = 1;
                } else {
                    $brands[$temp_key]['selected'] = 0;
                }
            }

            $brands[0]['brand_name'] = lang('all_attribute');
            $brands[0]['url'] = build_uri('category', ['cid' => $cat_id, 'bid' => 0, 'price_min' => $price_min, 'price_max' => $price_max, 'filter_attr' => $filter_attr_str], $cat['cat_name']);
            $brands[0]['selected'] = empty($brand) ? 1 : 0;

            $this->assign('brands', $brands);

            // 属性筛选
            $ext = ''; // 商品查询条件扩展
            if ($cat['filter_attr'] > 0) {
                $cat_filter_attr = explode(',', $cat['filter_attr']);       // 提取出此分类的筛选属性
                $all_attr_list = [];

                foreach ($cat_filter_attr as $key => $value) {
                    $temp_name = DB::table('goods_type_attribute as a')
                        ->join('goods_attr as ga', 'a.attr_id', '=', 'ga.attr_id')
                        ->join('goods as g', 'g.goods_id', '=', 'ga.goods_id')
                        ->where(function ($query) use ($children) {
                            $query->whereRaw($children)
                                ->orWhereRaw(GoodsHelper::get_extension_goods($children));
                        })
                        ->where('g.is_delete', 0)
                        ->where('g.is_on_sale', 1)
                        ->where('g.is_alone_sale', 1)
                        ->where('a.attr_id', $value)
                        ->value('a.attr_name');
                    if ($temp_name) {
                        $all_attr_list[$key]['filter_attr_name'] = $temp_name;

                        $attr_list = DB::table('goods_attr as a')
                            ->select('a.attr_id', DB::raw('MIN(a.goods_attr_id) AS goods_id'), 'a.attr_value')
                            ->join('goods as g', 'g.goods_id', '=', 'a.goods_id')
                            ->where(function ($query) use ($children) {
                                $query->whereRaw($children)
                                    ->orWhereRaw(GoodsHelper::get_extension_goods($children));
                            })
                            ->where('g.is_delete', 0)
                            ->where('g.is_on_sale', 1)
                            ->where('g.is_alone_sale', 1)
                            ->where('a.attr_id', $value)
                            ->groupBy('a.attr_value')
                            ->get()
                            ->map(fn ($item) => (array) $item)
                            ->all();

                        $temp_arrt_url_arr = [];

                        for ($i = 0; $i < count($cat_filter_attr); $i++) {        // 获取当前url中已选择属性的值，并保留在数组中
                            $temp_arrt_url_arr[$i] = ! empty($filter_attr[$i]) ? $filter_attr[$i] : 0;
                        }

                        $temp_arrt_url_arr[$key] = 0;                           // “全部”的信息生成
                        $temp_arrt_url = implode('.', $temp_arrt_url_arr);
                        $all_attr_list[$key]['attr_list'][0]['attr_value'] = lang('all_attribute');
                        $all_attr_list[$key]['attr_list'][0]['url'] = build_uri('category', ['cid' => $cat_id, 'bid' => $brand, 'price_min' => $price_min, 'price_max' => $price_max, 'filter_attr' => $temp_arrt_url], $cat['cat_name']);
                        $all_attr_list[$key]['attr_list'][0]['selected'] = empty($filter_attr[$key]) ? 1 : 0;

                        foreach ($attr_list as $k => $v) {
                            $temp_key = $k + 1;
                            $temp_arrt_url_arr[$key] = $v['goods_id'];       // 为url中代表当前筛选属性的位置变量赋值,并生成以‘.’分隔的筛选属性字符串
                            $temp_arrt_url = implode('.', $temp_arrt_url_arr);

                            $all_attr_list[$key]['attr_list'][$temp_key]['attr_value'] = $v['attr_value'];
                            $all_attr_list[$key]['attr_list'][$temp_key]['url'] = build_uri('category', ['cid' => $cat_id, 'bid' => $brand, 'price_min' => $price_min, 'price_max' => $price_max, 'filter_attr' => $temp_arrt_url], $cat['cat_name']);

                            if (! empty($filter_attr[$key]) and $filter_attr[$key] === $v['goods_id']) {
                                $all_attr_list[$key]['attr_list'][$temp_key]['selected'] = 1;
                            } else {
                                $all_attr_list[$key]['attr_list'][$temp_key]['selected'] = 0;
                            }
                        }
                    }
                }

                $this->assign('filter_attr_list', $all_attr_list);
                // 扩展商品查询条件
                if (! empty($filter_attr)) {
                    $ext_sql = 'SELECT DISTINCT(b.goods_id) FROM '.ecs()->table('goods_attr').' AS a, '.ecs()->table('goods_attr').' AS b '.'WHERE ';
                    $ext_group_goods = [];

                    foreach ($filter_attr as $k => $v) {                      // 查出符合所有筛选属性条件的商品id */
                        if (is_numeric($v) && $v != 0 && isset($cat_filter_attr[$k])) {
                            $ext_group_goods = DB::table('goods_attr as a')
                                ->join('goods_attr as b', 'b.attr_value', '=', 'a.attr_value')
                                ->where('b.attr_id', $cat_filter_attr[$k])
                                ->where('a.goods_attr_id', $v)
                                ->distinct()
                                ->pluck('b.goods_id')
                                ->all();
                            $ext .= ' AND '.db_create_in($ext_group_goods, 'g.goods_id');
                        }
                    }
                }
            }

            $this->assign_template('c', [$cat_id]);

            $position = $this->assign_ur_here($cat_id, $brand_name);
            $this->assign('page_title', $position['title']);    // 页面标题
            $this->assign('ur_here', $position['ur_here']);  // 当前位置

            $this->assign('categories', GoodsHelper::get_categories_tree($cat_id)); // 分类树
            $this->assign('helps', MainHelper::get_shop_help());              // 网店帮助
            $this->assign('top_goods', GoodsHelper::get_top10());                  // 销售排行
            $this->assign('show_marketprice', cfg('show_marketprice'));
            $this->assign('category', $cat_id);
            $this->assign('brand_id', $brand);
            $this->assign('price_max', $price_max);
            $this->assign('price_min', $price_min);
            $this->assign('filter_attr', $filter_attr_str);
            $this->assign('feed_url', (cfg('rewrite') === 1) ? "feed-c$cat_id.xml" : 'feed.php?cat='.$cat_id); // RSS URL

            if ($brand > 0) {
                $arr['all'] = [
                    'brand_id' => 0,
                    'brand_name' => lang('all_goods'),
                    'brand_logo' => '',
                    'goods_num' => '',
                    'url' => build_uri('category', ['cid' => $cat_id], $cat['cat_name']),
                ];
            } else {
                $arr = [];
            }

            $brand_list = array_merge($arr, CommonHelper::get_brands($cat_id, 'category'));

            $this->assign('data_dir', DATA_DIR);
            $this->assign('brand_list', $brand_list);
            $this->assign('promotion_info', CommonHelper::get_promotion_info());

            // 调查
            $vote = MainHelper::get_vote();
            if (! empty($vote)) {
                $this->assign('vote_id', $vote['id']);
                $this->assign('vote', $vote['content']);
            }

            $this->assign('best_goods', GoodsHelper::get_category_recommend_goods('best', $children, $brand, $price_min, $price_max, $ext));
            $this->assign('promotion_goods', GoodsHelper::get_category_recommend_goods('promote', $children, $brand, $price_min, $price_max, $ext));
            $this->assign('hot_goods', GoodsHelper::get_category_recommend_goods('hot', $children, $brand, $price_min, $price_max, $ext));

            $count = $this->get_cagtegory_goods_count($children, $brand, $price_min, $price_max, $ext);
            $max_page = ($count > 0) ? ceil($count / $size) : 1;
            if ($page > $max_page) {
                $page = $max_page;
            }
            $goodslist = $this->category_get_goods($children, $brand, $price_min, $price_max, $ext, $size, $page, $sort, $order);
            if ($display === 'grid') {
                if (count($goodslist) % 2 != 0) {
                    $goodslist[] = [];
                }
            }
            $this->assign('goods_list', $goodslist);
            $this->assign('category', $cat_id);
            $this->assign('script_name', 'category');

            MainHelper::assign_pager('category', $cat_id, $count, $size, $sort, $order, $page, '', $brand, $price_min, $price_max, $display, $filter_attr_str); // 分页
            $this->assign_dynamic('category'); // 动态内容
        }

        return $this->display('category', $cache_id);
    }

    /**
     * 获得分类的信息
     *
     * @param  int  $cat_id
     * @return void
     */
    private function get_cat_info($cat_id)
    {
        $row = DB::table('goods_category')
            ->select('cat_name', 'keywords', 'cat_desc', 'style', 'grade', 'filter_attr', 'parent_id')
            ->where('cat_id', $cat_id)
            ->first();

        return (array) $row;
    }

    /**
     * 获得分类下的商品
     *
     * @param  string  $children
     * @return array
     */
    private function category_get_goods($children, $brand, $min, $max, $ext, $size, $page, $sort, $order)
    {
        $display = $GLOBALS['display'];
        $where = 'g.is_on_sale = 1 AND g.is_alone_sale = 1 AND '.
            "g.is_delete = 0 AND ($children OR ".GoodsHelper::get_extension_goods($children).')';

        if ($brand > 0) {
            $where .= "AND g.brand_id=$brand ";
        }

        if ($min > 0) {
            $where .= " AND g.shop_price >= $min ";
        }

        if ($max > 0) {
            $where .= " AND g.shop_price <= $max ";
        }

        // 获得商品列表
        $res = DB::table('goods as g')
            ->select('g.goods_id', 'g.goods_name', 'g.goods_name_style', 'g.market_price', 'g.is_new', 'g.is_best', 'g.is_hot', 'g.shop_price AS org_price', DB::raw("IFNULL(mp.user_price, g.shop_price * '".Session::get('discount', 1)."') AS shop_price"), 'g.promote_price', 'g.goods_type', 'g.promote_start_date', 'g.promote_end_date', 'g.goods_brief', 'g.goods_thumb', 'g.goods_img')
            ->leftJoin('goods_member_price as mp', function ($join) {
                $join->on('mp.goods_id', '=', 'g.goods_id')
                    ->where('mp.user_rank', '=', Session::get('user_rank'));
            })
            ->whereRaw($where)
            ->whereRaw($ext ? substr($ext, 5) : '1=1') // remove leading ' AND '
            ->orderBy($sort, $order)
            ->offset(($page - 1) * $size)
            ->limit($size)
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        $arr = [];
        foreach ($res as $row) {
            if ($row['promote_price'] > 0) {
                $promote_price = GoodsHelper::bargain_price($row['promote_price'], $row['promote_start_date'], $row['promote_end_date']);
            } else {
                $promote_price = 0;
            }

            // 处理商品水印图片
            $watermark_img = '';

            if ($promote_price != 0) {
                $watermark_img = 'watermark_promote_small';
            } elseif ($row['is_new'] != 0) {
                $watermark_img = 'watermark_new_small';
            } elseif ($row['is_best'] != 0) {
                $watermark_img = 'watermark_best_small';
            } elseif ($row['is_hot'] != 0) {
                $watermark_img = 'watermark_hot_small';
            }

            if ($watermark_img != '') {
                $arr[$row['goods_id']]['watermark_img'] = $watermark_img;
            }

            $arr[$row['goods_id']]['goods_id'] = $row['goods_id'];
            if ($display === 'grid') {
                $arr[$row['goods_id']]['goods_name'] = cfg('goods_name_length') > 0 ? Str::substr($row['goods_name'], cfg('goods_name_length')) : $row['goods_name'];
            } else {
                $arr[$row['goods_id']]['goods_name'] = $row['goods_name'];
            }
            $arr[$row['goods_id']]['name'] = $row['goods_name'];
            $arr[$row['goods_id']]['goods_brief'] = $row['goods_brief'];
            $arr[$row['goods_id']]['goods_style_name'] = GoodsHelper::add_style($row['goods_name'], $row['goods_name_style']);
            $arr[$row['goods_id']]['market_price'] = CommonHelper::price_format($row['market_price']);
            $arr[$row['goods_id']]['shop_price'] = CommonHelper::price_format($row['shop_price']);
            $arr[$row['goods_id']]['type'] = $row['goods_type'];
            $arr[$row['goods_id']]['promote_price'] = ($promote_price > 0) ? CommonHelper::price_format($promote_price) : '';
            $arr[$row['goods_id']]['goods_thumb'] = CommonHelper::get_image_path($row['goods_thumb']);
            $arr[$row['goods_id']]['goods_img'] = CommonHelper::get_image_path($row['goods_img']);
            $arr[$row['goods_id']]['url'] = build_uri('goods', ['gid' => $row['goods_id']], $row['goods_name']);
        }

        return $arr;
    }

    /**
     * 获得分类下的商品总数
     *
     * @param  string  $cat_id
     * @return int
     */
    private function get_cagtegory_goods_count($children, $brand = 0, $min = 0, $max = 0, $ext = '')
    {
        $where = "g.is_on_sale = 1 AND g.is_alone_sale = 1 AND g.is_delete = 0 AND ($children OR ".GoodsHelper::get_extension_goods($children).')';

        if ($brand > 0) {
            $where .= " AND g.brand_id = $brand ";
        }

        if ($min > 0) {
            $where .= " AND g.shop_price >= $min ";
        }

        if ($max > 0) {
            $where .= " AND g.shop_price <= $max ";
        }

        // 返回商品总数
        return DB::table('goods as g')
            ->whereRaw($where)
            ->whereRaw($ext ? substr($ext, 5) : '1=1')
            ->count();
    }

    /**
     * 取得最近的上级分类的grade值
     *
     * @param  int  $cat_id  //当前的cat_id
     * @return int
     */
    private function get_parent_grade($cat_id)
    {
        static $res = null;

        if ($res === null) {
            $data = BaseHelper::read_static_cache('cat_parent_grade');
            if ($data === false) {
                $res = DB::table('goods_category')
                    ->select('parent_id', 'cat_id', 'grade')
                    ->get()
                    ->map(fn ($item) => (array) $item)
                    ->all();
                BaseHelper::write_static_cache('cat_parent_grade', $res);
            } else {
                $res = $data;
            }
        }

        if (! $res) {
            return 0;
        }

        $parent_arr = [];
        $grade_arr = [];

        foreach ($res as $val) {
            $parent_arr[$val['cat_id']] = $val['parent_id'];
            $grade_arr[$val['cat_id']] = $val['grade'];
        }

        while ($parent_arr[$cat_id] > 0 && $grade_arr[$cat_id] === 0) {
            $cat_id = $parent_arr[$cat_id];
        }

        return $grade_arr[$cat_id];
    }
}
