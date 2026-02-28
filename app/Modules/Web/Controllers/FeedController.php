<?php

declare(strict_types=1);

namespace App\Modules\Web\Controllers;

use App\Helpers\CommonHelper;
use App\Helpers\TimeHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeedController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        define('INIT_NO_USERS', true);
        define('INIT_NO_SMARTY', true);

        header('Content-Type: application/xml; charset='.EC_CHARSET);
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Fri, 14 Mar 1980 20:53:00 GMT');
        header('Last-Modified: '.date('r'));
        header('Pragma: no-cache');

        $ver = isset($_REQUEST['ver']) ? $_REQUEST['ver'] : '2.00';
        $cat = isset($_REQUEST['cat']) ? ' AND '.CommonHelper::get_children(intval($_REQUEST['cat'])) : '';
        $brd = isset($_REQUEST['brand']) ? ' AND g.brand_id='.intval($_REQUEST['brand']).' ' : '';

        $uri = ecs()->url();

        $rss = new RSSBuilder(EC_CHARSET, $uri, htmlspecialchars(cfg('shop_name')), htmlspecialchars(cfg('shop_desc')), $uri.'favicon.ico');
        $rss->addDCdata('', 'http://www.phpmall.net', date('r'));

        if (isset($_REQUEST['type'])) {
            if ($_REQUEST['type'] === 'group_buy') {
                $now = TimeHelper::gmtime();
                $res = DB::table('goods_activity')
                    ->select('act_id', 'act_name', 'act_desc', 'start_time')
                    ->where('act_type', GAT_GROUP_BUY)
                    ->where('start_time', '<=', $now)
                    ->where('is_finished', '<', 3)
                    ->orderByDesc('start_time')
                    ->get()
                    ->map(fn ($item) => (array) $item)
                    ->all();

                if ($res !== false) {
                    foreach ($res as $row) {
                        $item_url = build_uri('group_buy', ['gbid' => $row['act_id']], $row['act_name']);
                        $separator = (strpos($item_url, '?') === false) ? '?' : '&amp;';
                        $about = $uri.$item_url;
                        $title = htmlspecialchars($row['act_name']);
                        $link = $uri.$item_url.$separator.'from=rss';
                        $desc = htmlspecialchars($row['act_desc']);
                        $subject = lang('group_buy');
                        $date = TimeHelper::local_date('r', $row['start_time']);

                        $rss->addItem($about, $title, $link, $desc, $subject, $date);
                    }

                    $rss->outputRSS($ver);
                }
            } elseif ($_REQUEST['type'] === 'snatch') {
                $now = TimeHelper::gmtime();
                $res = DB::table('goods_activity')
                    ->select('act_id', 'act_name', 'act_desc', 'start_time')
                    ->where('act_type', GAT_SNATCH)
                    ->where('start_time', '<=', $now)
                    ->where('is_finished', '<', 3)
                    ->orderByDesc('start_time')
                    ->get()
                    ->map(fn ($item) => (array) $item)
                    ->all();

                if ($res !== false) {
                    foreach ($res as $row) {
                        $item_url = build_uri('snatch', ['sid' => $row['act_id']], $row['act_name']);
                        $separator = (strpos($item_url, '?') === false) ? '?' : '&amp;';
                        $about = $uri.$item_url;
                        $title = htmlspecialchars($row['act_name']);
                        $link = $uri.$item_url.$separator.'from=rss';
                        $desc = htmlspecialchars($row['act_desc']);
                        $subject = lang('snatch');
                        $date = TimeHelper::local_date('r', $row['start_time']);

                        $rss->addItem($about, $title, $link, $desc, $subject, $date);
                    }

                    $rss->outputRSS($ver);
                }
            } elseif ($_REQUEST['type'] === 'auction') {
                $now = TimeHelper::gmtime();
                $res = DB::table('goods_activity')
                    ->select('act_id', 'act_name', 'act_desc', 'start_time')
                    ->where('act_type', GAT_AUCTION)
                    ->where('start_time', '<=', $now)
                    ->where('is_finished', '<', 3)
                    ->orderByDesc('start_time')
                    ->get()
                    ->map(fn ($item) => (array) $item)
                    ->all();

                if ($res !== false) {
                    foreach ($res as $row) {
                        $item_url = build_uri('auction', ['auid' => $row['act_id']], $row['act_name']);
                        $separator = (strpos($item_url, '?') === false) ? '?' : '&amp;';
                        $about = $uri.$item_url;
                        $title = htmlspecialchars($row['act_name']);
                        $link = $uri.$item_url.$separator.'from=rss';
                        $desc = htmlspecialchars($row['act_desc']);
                        $subject = lang('auction');
                        $date = TimeHelper::local_date('r', $row['start_time']);

                        $rss->addItem($about, $title, $link, $desc, $subject, $date);
                    }

                    $rss->outputRSS($ver);
                }
            } elseif ($_REQUEST['type'] === 'exchange') {
                $res = DB::table('activity_exchange as eg')
                    ->select('g.goods_id', 'g.goods_name', 'g.goods_brief', 'g.last_update')
                    ->join('goods as g', 'eg.goods_id', '=', 'g.goods_id')
                    ->get()
                    ->map(fn ($item) => (array) $item)
                    ->all();

                if ($res !== false) {
                    foreach ($res as $row) {
                        $item_url = build_uri('exchange_goods', ['gid' => $row['goods_id']], $row['goods_name']);
                        $separator = (strpos($item_url, '?') === false) ? '?' : '&amp;';
                        $about = $uri.$item_url;
                        $title = htmlspecialchars($row['goods_name']);
                        $link = $uri.$item_url.$separator.'from=rss';
                        $desc = htmlspecialchars($row['goods_brief']);
                        $subject = lang('exchange');
                        $date = TimeHelper::local_date('r', $row['last_update']);

                        $rss->addItem($about, $title, $link, $desc, $subject, $date);
                    }

                    $rss->outputRSS($ver);
                }
            } elseif ($_REQUEST['type'] === 'activity') {
                $now = TimeHelper::gmtime();
                $res = DB::table('activity')
                    ->select('act_id', 'act_name', 'start_time')
                    ->where('start_time', '<=', $now)
                    ->where('end_time', '>=', $now)
                    ->get()
                    ->map(fn ($item) => (array) $item)
                    ->all();

                if ($res !== false) {
                    foreach ($res as $row) {
                        $item_url = 'activity.php';
                        $separator = (strpos($item_url, '?') === false) ? '?' : '&amp;';
                        $about = $uri.$item_url;
                        $title = htmlspecialchars($row['act_name']);
                        $link = $uri.$item_url.$separator.'from=rss';
                        $desc = '';
                        $subject = lang('favourable');
                        $date = TimeHelper::local_date('r', $row['start_time']);

                        $rss->addItem($about, $title, $link, $desc, $subject, $date);
                    }

                    $rss->outputRSS($ver);
                }
            } elseif ($_REQUEST['type'] === 'package') {
                $now = TimeHelper::gmtime();
                $res = DB::table('goods_activity')
                    ->select('act_id', 'act_name', 'act_desc', 'start_time')
                    ->where('act_type', GAT_PACKAGE)
                    ->where('start_time', '<=', $now)
                    ->where('is_finished', '<', 3)
                    ->orderByDesc('start_time')
                    ->get()
                    ->map(fn ($item) => (array) $item)
                    ->all();

                if ($res !== false) {
                    foreach ($res as $row) {
                        $item_url = 'package.php';
                        $separator = (strpos($item_url, '?') === false) ? '?' : '&amp;';
                        $about = $uri.$item_url;
                        $title = htmlspecialchars($row['act_name']);
                        $link = $uri.$item_url.$separator.'from=rss';
                        $desc = htmlspecialchars($row['act_desc']);
                        $subject = lang('remark_package');
                        $date = TimeHelper::local_date('r', $row['start_time']);

                        $rss->addItem($about, $title, $link, $desc, $subject, $date);
                    }

                    $rss->outputRSS($ver);
                }
            } elseif (substr($_REQUEST['type'], 0, 11) === 'article_cat') {
                $res = DB::table('article')
                    ->select('article_id', 'title', 'author', 'add_time')
                    ->where('is_open', 1)
                    ->whereRaw(CommonHelper::get_article_children(substr($_REQUEST['type'], 11)))
                    ->orderByDesc('add_time')
                    ->limit(intval(cfg('article_page_size')))
                    ->get()
                    ->map(fn ($item) => (array) $item)
                    ->all();

                if ($res !== false) {
                    foreach ($res as $row) {
                        $item_url = build_uri('article', ['aid' => $row['article_id']], $row['title']);
                        $separator = (strpos($item_url, '?') === false) ? '?' : '&amp;';
                        $about = $uri.$item_url;
                        $title = htmlspecialchars($row['title']);
                        $link = $uri.$item_url.$separator.'from=rss';
                        $desc = '';
                        $subject = htmlspecialchars($row['author']);
                        $date = TimeHelper::local_date('r', $row['add_time']);

                        $rss->addItem($about, $title, $link, $desc, $subject, $date);
                    }

                    $rss->outputRSS($ver);
                }
            }
        } else {
            $in_cat = $cat > 0 ? ' AND '.CommonHelper::get_children($cat) : '';

            $res = DB::table('goods_category as c')
                ->select('c.cat_name', 'g.goods_id', 'g.goods_name', 'g.goods_brief', 'g.last_update')
                ->join('goods as g', 'c.cat_id', '=', 'g.cat_id')
                ->where('g.is_delete', 0)
                ->where('g.is_alone_sale', 1)
                ->whereRaw('1 '.$brd.$cat)
                ->orderByDesc('g.last_update')
                ->get()
                ->map(fn ($item) => (array) $item)
                ->all();

            if ($res !== false) {
                foreach ($res as $row) {
                    $item_url = build_uri('goods', ['gid' => $row['goods_id']], $row['goods_name']);
                    $separator = (strpos($item_url, '?') === false) ? '?' : '&amp;';
                    $about = $uri.$item_url;
                    $title = htmlspecialchars($row['goods_name']);
                    $link = $uri.$item_url.$separator.'from=rss';
                    $desc = htmlspecialchars($row['goods_brief']);
                    $subject = htmlspecialchars($row['cat_name']);
                    $date = TimeHelper::local_date('r', $row['last_update']);

                    $rss->addItem($about, $title, $link, $desc, $subject, $date);
                }

                $rss->outputRSS($ver);
            }
        }
    }
}
