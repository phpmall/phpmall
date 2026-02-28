<?php

declare(strict_types=1);

namespace App\Modules\Web\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\CommonHelper;
use App\Helpers\GoodsHelper;
use App\Helpers\MainHelper;
use App\Helpers\TimeHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class SearchController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        if (empty($_GET['encode'])) {
            $string = array_merge($_GET, $_POST);
            $string = BaseHelper::stripslashes_deep($string);
            $string['search_encode_time'] = time();
            $string = str_replace('+', '%2b', base64_encode(serialize($string)));

            header("Location: search.php?encode=$string\n");
            exit;
        } else {
            $string = base64_decode(trim($_GET['encode']));
            if ($string !== false) {
                $string = unserialize($string);
                if ($string !== false) {
                    // 用户在重定向的情况下当作一次访问
                    if (! empty($string['search_encode_time'])) {
                        if (time() > $string['search_encode_time'] + 2) {
                            define('INGORE_VISIT_STATS', true);
                        }
                    } else {
                        define('INGORE_VISIT_STATS', true);
                    }
                } else {
                    $string = [];
                }
            } else {
                $string = [];
            }
        }

        $_REQUEST = array_merge($_REQUEST, BaseHelper::addslashes_deep($string));

        /**
         * 高级搜索
         */
        if ($action === 'advanced_search') {
            $goods_type = ! empty($_REQUEST['goods_type']) ? intval($_REQUEST['goods_type']) : 0;
            $attributes = $this->get_seachable_attributes($goods_type);
            $this->assign('goods_type_selected', $goods_type);
            $this->assign('goods_type_list', $attributes['cate']);
            $this->assign('goods_attributes', $attributes['attr']);

            $this->assign_template();
            $this->assign_dynamic('search');
            $position = $this->assign_ur_here(0, lang('advanced_search'));
            $this->assign('page_title', $position['title']);    // 页面标题
            $this->assign('ur_here', $position['ur_here']);  // 当前位置

            $this->assign('categories', GoodsHelper::get_categories_tree()); // 分类树
            $this->assign('helps', MainHelper::get_shop_help());       // 网店帮助
            $this->assign('top_goods', GoodsHelper::get_top10());           // 销售排行
            $this->assign('promotion_info', CommonHelper::get_promotion_info());
            $this->assign('cat_list', CommonHelper::cat_list(0, 0, true, 2, false));
            $this->assign('brand_list', CommonHelper::get_brand_list());
            $this->assign('action', 'form');
            $this->assign('use_storage', cfg('use_storage'));

            return $this->display('search');
        }
        /**
         * 搜索结果
         */ else {
            $_REQUEST['keywords'] = ! empty($_REQUEST['keywords']) ? htmlspecialchars(trim($_REQUEST['keywords'])) : '';
            $_REQUEST['brand'] = ! empty($_REQUEST['brand']) ? intval($_REQUEST['brand']) : 0;
            $_REQUEST['category'] = ! empty($_REQUEST['category']) ? intval($_REQUEST['category']) : 0;
            $_REQUEST['min_price'] = ! empty($_REQUEST['min_price']) ? intval($_REQUEST['min_price']) : 0;
            $_REQUEST['max_price'] = ! empty($_REQUEST['max_price']) ? intval($_REQUEST['max_price']) : 0;
            $_REQUEST['goods_type'] = ! empty($_REQUEST['goods_type']) ? intval($_REQUEST['goods_type']) : 0;
            $_REQUEST['sc_ds'] = ! empty($_REQUEST['sc_ds']) ? intval($_REQUEST['sc_ds']) : 0;
            $_REQUEST['outstock'] = ! empty($_REQUEST['outstock']) ? 1 : 0;

            $action = '';
            if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'form') {
                // 要显示高级搜索栏
                $adv_value['keywords'] = htmlspecialchars(stripcslashes($_REQUEST['keywords']));
                $adv_value['brand'] = $_REQUEST['brand'];
                $adv_value['min_price'] = $_REQUEST['min_price'];
                $adv_value['max_price'] = $_REQUEST['max_price'];
                $adv_value['category'] = $_REQUEST['category'];

                $attributes = $this->get_seachable_attributes($_REQUEST['goods_type']);

                // 将提交数据重新赋值
                foreach ($attributes['attr'] as $key => $val) {
                    if (! empty($_REQUEST['attr'][$val['id']])) {
                        if ($val['type'] === 2) {
                            $attributes['attr'][$key]['value']['from'] = ! empty($_REQUEST['attr'][$val['id']]['from']) ? htmlspecialchars(stripcslashes(trim($_REQUEST['attr'][$val['id']]['from']))) : '';
                            $attributes['attr'][$key]['value']['to'] = ! empty($_REQUEST['attr'][$val['id']]['to']) ? htmlspecialchars(stripcslashes(trim($_REQUEST['attr'][$val['id']]['to']))) : '';
                        } else {
                            $attributes['attr'][$key]['value'] = ! empty($_REQUEST['attr'][$val['id']]) ? htmlspecialchars(stripcslashes(trim($_REQUEST['attr'][$val['id']]))) : '';
                        }
                    }
                }
                if ($_REQUEST['sc_ds']) {
                    $this->assign('scck', 'checked');
                }
                $this->assign('adv_val', $adv_value);
                $this->assign('goods_type_list', $attributes['cate']);
                $this->assign('goods_attributes', $attributes['attr']);
                $this->assign('goods_type_selected', $_REQUEST['goods_type']);
                $this->assign('cat_list', CommonHelper::cat_list(0, $adv_value['category'], true, 2, false));
                $this->assign('brand_list', CommonHelper::get_brand_list());
                $this->assign('action', 'form');
                $this->assign('use_storage', cfg('use_storage'));

                $action = 'form';
            }

            // 初始化搜索条件
            $keywords = '';
            $tag_where = '';
            if (! empty($_REQUEST['keywords'])) {
                $arr = [];
                if (stristr($_REQUEST['keywords'], ' AND ') !== false) {
                    // 检查关键字中是否有AND，如果存在就是并
                    $arr = explode('AND', $_REQUEST['keywords']);
                    $operator = ' AND ';
                } elseif (stristr($_REQUEST['keywords'], ' OR ') !== false) {
                    // 检查关键字中是否有OR，如果存在就是或
                    $arr = explode('OR', $_REQUEST['keywords']);
                    $operator = ' OR ';
                } elseif (stristr($_REQUEST['keywords'], ' + ') !== false) {
                    // 检查关键字中是否有加号，如果存在就是或
                    $arr = explode('+', $_REQUEST['keywords']);
                    $operator = ' OR ';
                } else {
                    // 检查关键字中是否有空格，如果存在就是并
                    $arr = explode(' ', $_REQUEST['keywords']);
                    $operator = ' AND ';
                }

                $keywords = 'AND (';
                $goods_ids = [];
                foreach ($arr as $key => $val) {
                    if ($key > 0 && $key < count($arr) && count($arr) > 1) {
                        $keywords .= $operator;
                    }
                    $val = BaseHelper::mysql_like_quote(trim($val));
                    $sc_dsad = $_REQUEST['sc_ds'] ? " OR goods_desc LIKE '%$val%'" : '';
                    $keywords .= "(goods_name LIKE '%$val%' OR goods_sn LIKE '%$val%' OR keywords LIKE '%$val%' $sc_dsad)";

                    $goods_ids = array_merge($goods_ids, DB::table('user_tag')
                        ->where('tag_words', 'like', "%$val%")
                        ->distinct()
                        ->pluck('goods_id')
                        ->all());

                    DB::table('search_keywords')->updateOrInsert(
                        ['date' => TimeHelper::local_date('Y-m-d'), 'searchengine' => 'phpmall', 'keyword' => str_replace('%', '', $val)],
                        ['count' => DB::raw('count + 1')]
                    );
                }
                $keywords .= ')';

                $goods_ids = array_unique($goods_ids);
                $tag_where = implode(',', $goods_ids);
                if (! empty($tag_where)) {
                    $tag_where = 'OR g.goods_id '.db_create_in($tag_where);
                }
            }

            $category = ! empty($_REQUEST['category']) ? intval($_REQUEST['category']) : 0;
            $categories = ($category > 0) ? ' AND '.CommonHelper::get_children($category) : '';
            $brand = $_REQUEST['brand'] ? " AND brand_id = '$_REQUEST[brand]'" : '';
            $outstock = ! empty($_REQUEST['outstock']) ? ' AND g.goods_number > 0 ' : '';

            $min_price = $_REQUEST['min_price'] != 0 ? " AND g.shop_price >= '$_REQUEST[min_price]'" : '';
            $max_price = $_REQUEST['max_price'] != 0 || $_REQUEST['min_price'] < 0 ? " AND g.shop_price <= '$_REQUEST[max_price]'" : '';

            // 排序、显示方式以及类型
            $default_display_type = cfg('show_order_type') === '0' ? 'list' : (cfg('show_order_type') === '1' ? 'grid' : 'text');
            $default_sort_order_method = cfg('sort_order_method') === '0' ? 'DESC' : 'ASC';
            $default_sort_order_type = cfg('sort_order_type') === '0' ? 'goods_id' : (cfg('sort_order_type') === '1' ? 'shop_price' : 'last_update');

            $sort = (isset($_REQUEST['sort']) && in_array(trim(strtolower($_REQUEST['sort'])), ['goods_id', 'shop_price', 'last_update'])) ? trim($_REQUEST['sort']) : $default_sort_order_type;
            $order = (isset($_REQUEST['order']) && in_array(trim(strtoupper($_REQUEST['order'])), ['ASC', 'DESC'])) ? trim($_REQUEST['order']) : $default_sort_order_method;
            $display = (isset($_REQUEST['display']) && in_array(trim(strtolower($_REQUEST['display'])), ['list', 'grid', 'text'])) ? trim($_REQUEST['display']) : (Session::has('display_search') ? Session::get('display_search') : $default_display_type);

            Session::put('display_search', $display);

            $page = ! empty($_REQUEST['page']) && intval($_REQUEST['page']) > 0 ? intval($_REQUEST['page']) : 1;
            $size = ! empty(cfg('page_size')) && intval(cfg('page_size')) > 0 ? intval(cfg('page_size')) : 10;

            $intromode = '';    // 方式，用于决定搜索结果页标题图片

            if (! empty($_REQUEST['intro'])) {
                switch ($_REQUEST['intro']) {
                    case 'best':
                        $intro = ' AND g.is_best = 1';
                        $intromode = 'best';
                        $ur_here = lang('best_goods');
                        break;
                    case 'new':
                        $intro = ' AND g.is_new = 1';
                        $intromode = 'new';
                        $ur_here = lang('new_goods');
                        break;
                    case 'hot':
                        $intro = ' AND g.is_hot = 1';
                        $intromode = 'hot';
                        $ur_here = lang('hot_goods');
                        break;
                    case 'promotion':
                        $time = TimeHelper::gmtime();
                        $intro = " AND g.promote_price > 0 AND g.promote_start_date <= '$time' AND g.promote_end_date >= '$time'";
                        $intromode = 'promotion';
                        $ur_here = lang('promotion_goods');
                        break;
                    default:
                        $intro = '';
                }
            } else {
                $intro = '';
            }

            if (empty($ur_here)) {
                $ur_here = lang('search_goods');
            }

            // ------------------------------------------------------
            // -- 属性检索
            // ------------------------------------------------------
            $attr_in = '';
            $attr_num = 0;
            $attr_url = '';
            $attr_arg = [];

            if (! empty($_REQUEST['attr'])) {
                $sql = 'SELECT goods_id, COUNT(*) AS num FROM '.ecs()->table('goods_attr').' WHERE 0 ';
                foreach ($_REQUEST['attr'] as $key => $val) {
                    if (is_not_null($val) && is_numeric($key)) {
                        $attr_num++;
                        $sql .= ' OR (1 ';

                        if (is_array($val)) {
                            $sql .= " AND attr_id = '$key'";

                            if (! empty($val['from'])) {
                                $sql .= is_numeric($val['from']) ? ' AND attr_value >= '.floatval($val['from']) : " AND attr_value >= '$val[from]'";
                                $attr_arg["attr[$key][from]"] = $val['from'];
                                $attr_url .= "&amp;attr[$key][from]=$val[from]";
                            }

                            if (! empty($val['to'])) {
                                $sql .= is_numeric($val['to']) ? ' AND attr_value <= '.floatval($val['to']) : " AND attr_value <= '$val[to]'";
                                $attr_arg["attr[$key][to]"] = $val['to'];
                                $attr_url .= "&amp;attr[$key][to]=$val[to]";
                            }
                        } else {
                            // 处理选购中心过来的链接
                            $sql .= isset($_REQUEST['pickout']) ? " AND attr_id = '$key' AND attr_value = '".$val."' " : " AND attr_id = '$key' AND attr_value LIKE '%".BaseHelper::mysql_like_quote($val)."%' ";
                            $attr_url .= "&amp;attr[$key]=$val";
                            $attr_arg["attr[$key]"] = $val;
                        }

                        $sql .= ')';
                    }
                }

                // 如果检索条件都是无效的，就不用检索
                if ($attr_num > 0) {
                    $row = DB::table('goods_attr')
                        ->where(function ($query) {
                            foreach ($_REQUEST['attr'] as $key => $val) {
                                if ($this->is_not_null($val) && is_numeric($key)) {
                                    $query->orWhere(function ($q) use ($key, $val) {
                                        $q->where('attr_id', $key);
                                        if (is_array($val)) {
                                            if (! empty($val['from'])) {
                                                $q->where('attr_value', '>=', is_numeric($val['from']) ? floatval($val['from']) : $val['from']);
                                            }
                                            if (! empty($val['to'])) {
                                                $q->where('attr_value', '<=', is_numeric($val['to']) ? floatval($val['to']) : $val['to']);
                                            }
                                        } else {
                                            $q->where('attr_value', 'like', '%'.BaseHelper::mysql_like_quote($val).'%');
                                        }
                                    });
                                }
                            }
                        })
                        ->groupBy('goods_id')
                        ->having(DB::raw('COUNT(*)'), '=', $attr_num)
                        ->pluck('goods_id')
                        ->all();
                    if (count($row)) {
                        $attr_in = ' AND '.db_create_in($row, 'g.goods_id');
                    } else {
                        $attr_in = ' AND 0 ';
                    }
                }
            } elseif (isset($_REQUEST['pickout'])) {
                // 从选购中心进入的链接
                $col = DB::table('goods_attr')->distinct()->pluck('goods_id')->all();
                // 如果商店没有设置商品属性,那么此检索条件是无效的
                if (! empty($col)) {
                    $attr_in = ' AND '.db_create_in($col, 'g.goods_id');
                }
            }

            // 获得符合条件的商品总数
            $count = DB::table('goods as g')
                ->where('g.is_delete', 0)
                ->where('g.is_on_sale', 1)
                ->where('g.is_alone_sale', 1)
                ->whereRaw($attr_in ? substr($attr_in, 5) : '1=1')
                ->where(function ($query) use ($categories, $keywords, $brand, $min_price, $max_price, $intro, $outstock, $tag_where) {
                    $query->whereRaw('1 '.$categories.$keywords.$brand.$min_price.$max_price.$intro.$outstock)
                        ->orWhereRaw($tag_where ? substr($tag_where, 3) : '0=1');
                })
                ->count();

            $max_page = ($count > 0) ? ceil($count / $size) : 1;
            if ($page > $max_page) {
                $page = $max_page;
            }

            // 查询商品
            $res = DB::table('goods as g')
                ->select('g.goods_id', 'g.goods_name', 'g.market_price', 'g.is_new', 'g.is_best', 'g.is_hot', 'g.shop_price AS org_price', DB::raw("IFNULL(mp.user_price, g.shop_price * '".Session::get('discount', 1)."') AS shop_price"), 'g.promote_price', 'g.promote_start_date', 'g.promote_end_date', 'g.goods_thumb', 'g.goods_img', 'g.goods_brief', 'g.goods_type')
                ->leftJoin('goods_member_price as mp', function ($join) {
                    $join->on('mp.goods_id', '=', 'g.goods_id')
                        ->where('mp.user_rank', '=', Session::get('user_rank'));
                })
                ->where('g.is_delete', 0)
                ->where('g.is_on_sale', 1)
                ->where('g.is_alone_sale', 1)
                ->whereRaw($attr_in ? substr($attr_in, 5) : '1=1')
                ->where(function ($query) use ($categories, $keywords, $brand, $min_price, $max_price, $intro, $outstock, $tag_where) {
                    $query->whereRaw('1 '.$categories.$keywords.$brand.$min_price.$max_price.$intro.$outstock)
                        ->orWhereRaw($tag_where ? substr($tag_where, 3) : '0=1');
                })
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
                $arr[$row['goods_id']]['type'] = $row['goods_type'];
                $arr[$row['goods_id']]['market_price'] = CommonHelper::price_format($row['market_price']);
                $arr[$row['goods_id']]['shop_price'] = CommonHelper::price_format($row['shop_price']);
                $arr[$row['goods_id']]['promote_price'] = ($promote_price > 0) ? CommonHelper::price_format($promote_price) : '';
                $arr[$row['goods_id']]['goods_brief'] = $row['goods_brief'];
                $arr[$row['goods_id']]['goods_thumb'] = CommonHelper::get_image_path($row['goods_thumb']);
                $arr[$row['goods_id']]['goods_img'] = CommonHelper::get_image_path($row['goods_img']);
                $arr[$row['goods_id']]['url'] = build_uri('goods', ['gid' => $row['goods_id']], $row['goods_name']);
            }

            if ($display === 'grid') {
                if (count($arr) % 2 != 0) {
                    $arr[] = [];
                }
            }
            $this->assign('goods_list', $arr);
            $this->assign('category', $category);
            $this->assign('keywords', htmlspecialchars(stripslashes($_REQUEST['keywords'])));
            $this->assign('search_keywords', stripslashes(htmlspecialchars_decode($_REQUEST['keywords'])));
            $this->assign('brand', $_REQUEST['brand']);
            $this->assign('min_price', $min_price);
            $this->assign('max_price', $max_price);
            $this->assign('outstock', $_REQUEST['outstock']);

            // 分页
            $url_format = "search.php?category=$category&amp;keywords=".urlencode(stripslashes($_REQUEST['keywords'])).'&amp;brand='.$_REQUEST['brand'].'&amp;action='.$action.'&amp;goods_type='.$_REQUEST['goods_type'].'&amp;sc_ds='.$_REQUEST['sc_ds'];
            if (! empty($intromode)) {
                $url_format .= '&amp;intro='.$intromode;
            }
            if (isset($_REQUEST['pickout'])) {
                $url_format .= '&amp;pickout=1';
            }
            $url_format .= '&amp;min_price='.$_REQUEST['min_price'].'&amp;max_price='.$_REQUEST['max_price']."&amp;sort=$sort";

            $url_format .= "$attr_url&amp;order=$order&amp;page=";

            $pager['search'] = [
                'keywords' => stripslashes(urlencode($_REQUEST['keywords'])),
                'category' => $category,
                'brand' => $_REQUEST['brand'],
                'sort' => $sort,
                'order' => $order,
                'min_price' => $_REQUEST['min_price'],
                'max_price' => $_REQUEST['max_price'],
                'action' => $action,
                'intro' => empty($intromode) ? '' : trim($intromode),
                'goods_type' => $_REQUEST['goods_type'],
                'sc_ds' => $_REQUEST['sc_ds'],
                'outstock' => $_REQUEST['outstock'],
            ];
            $pager['search'] = array_merge($pager['search'], $attr_arg);

            $pager = MainHelper::get_pager('search.php', $pager['search'], $count, $page, $size);
            $pager['display'] = $display;

            $this->assign('url_format', $url_format);
            $this->assign('pager', $pager);

            $this->assign_template();
            $this->assign_dynamic('search');
            $position = $this->assign_ur_here(0, $ur_here.($_REQUEST['keywords'] ? '_'.$_REQUEST['keywords'] : ''));
            $this->assign('page_title', $position['title']);    // 页面标题
            $this->assign('ur_here', $position['ur_here']);  // 当前位置
            $this->assign('intromode', $intromode);
            $this->assign('categories', GoodsHelper::get_categories_tree()); // 分类树
            $this->assign('helps', MainHelper::get_shop_help());      // 网店帮助
            $this->assign('top_goods', GoodsHelper::get_top10());           // 销售排行
            $this->assign('promotion_info', CommonHelper::get_promotion_info());

            return $this->display('search');
        }
    }

    /**
     * @return void
     */
    private function is_not_null($value)
    {
        if (is_array($value)) {
            return (! empty($value['from'])) || (! empty($value['to']));
        } else {
            return ! empty($value);
        }
    }

    /**
     * 获得可以检索的属性
     *
     * @params  integer $cat_id
     *
     * @return void
     */
    private function get_seachable_attributes($cat_id = 0)
    {
        $attributes = [
            'cate' => [],
            'attr' => [],
        ];

        // 获得可用的商品类型
        $cat = DB::table('goods_type as t')
            ->join('goods_type_attribute as a', 't.cat_id', '=', 'a.cat_id')
            ->where('t.enabled', 1)
            ->where('a.attr_index', '>', 0)
            ->select('t.cat_id', 'cat_name')
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        // 获取可以检索的属性
        if (! empty($cat)) {
            foreach ($cat as $val) {
                $attributes['cate'][$val['cat_id']] = $val['cat_name'];
            }
            $res = DB::table('goods_type_attribute as a')
                ->where('a.attr_index', '>', 0)
                ->when($cat_id > 0, function ($query) use ($cat_id) {
                    $query->where('a.cat_id', $cat_id);
                }, function ($query) use ($cat) {
                    $query->where('a.cat_id', $cat[0]['cat_id']);
                })
                ->select('attr_id', 'attr_name', 'attr_input_type', 'attr_type', 'attr_values', 'attr_index', 'sort_order')
                ->orderBy('cat_id')
                ->orderBy('sort_order')
                ->get()
                ->map(fn ($item) => (array) $item)
                ->all();

            foreach ($res as $row) {
                if ($row['attr_index'] === 1 && $row['attr_input_type'] === 1) {
                    $row['attr_values'] = str_replace("\r", '', $row['attr_values']);
                    $options = explode("\n", $row['attr_values']);

                    $attr_value = [];
                    foreach ($options as $opt) {
                        $attr_value[$opt] = $opt;
                    }
                    $attributes['attr'][] = [
                        'id' => $row['attr_id'],
                        'attr' => $row['attr_name'],
                        'options' => $attr_value,
                        'type' => 3,
                    ];
                } else {
                    $attributes['attr'][] = [
                        'id' => $row['attr_id'],
                        'attr' => $row['attr_name'],
                        'type' => $row['attr_index'],
                    ];
                }
            }
        }

        return $attributes;
    }
}
