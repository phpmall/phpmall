<?php

declare(strict_types=1);

namespace App\Modules\Admin\Helpers;

use App\Helpers\BaseHelper;
use App\Helpers\CommonHelper;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class MainHelper
{
    /**
     * 获得所有模块的名称以及链接地址
     *
     * @param  string  $directory  插件存放的目录
     * @return array
     */
    public static function read_modules($directory = '.')
    {
        $dir = @opendir($directory);
        $set_modules = true;
        $modules = [];

        while (false !== ($file = @readdir($dir))) {
            if (preg_match("/^.*?\.php$/", $file)) {
                include_once $directory.'/'.$file;
            }
        }
        @closedir($dir);
        unset($set_modules);

        foreach ($modules as $key => $value) {
            ksort($modules[$key]);
        }
        ksort($modules);

        return $modules;
    }

    /**
     * 将通过表单提交过来的年月日变量合成为"2004-05-10"的格式。
     *
     * 此函数适用于通过smarty函数html_select_date生成的下拉日期。
     *
     * @param  string  $prefix  年月日变量的共同的前缀。
     * @return date 日期变量。
     */
    public static function sys_joindate($prefix)
    {
        // 返回年-月-日的日期格式
        $year = empty($_POST[$prefix.'Year']) ? '0' : $_POST[$prefix.'Year'];
        $month = empty($_POST[$prefix.'Month']) ? '0' : $_POST[$prefix.'Month'];
        $day = empty($_POST[$prefix.'Day']) ? '0' : $_POST[$prefix.'Day'];

        return $year.'-'.$month.'-'.$day;
    }

    /**
     * 设置管理员的session内容
     *
     * @param  int  $user_id  管理员编号
     * @param  string  $username  管理员姓名
     * @param  string  $action_list  权限列表
     * @param  string  $last_time  最后登录时间
     * @return void
     */
    public static function set_admin_session($user_id, $username, $action_list, $last_time)
    {
        Session::put('admin_id', $user_id);
        Session::put('admin_name', $username);
        Session::put('action_list', $action_list);
        Session::put('last_check', $last_time); // 用于保存最后一次检查订单的时间
    }

    /**
     * 插入一个配置信息
     *
     * @param  string  $parent  分组的code
     * @param  string  $code  该配置信息的唯一标识
     * @param  string  $value  该配置信息值
     * @return void
     */
    public static function insert_config($parent, $code, $value)
    {
        $parent_id = DB::table('shop_config')
            ->where('code', $parent)
            ->where('type', 1)
            ->value('id');

        DB::table('shop_config')->insert([
            'parent_id' => $parent_id,
            'code' => $code,
            'value' => $value,
        ]);
    }

    /**
     * 取得红包类型数组（用于生成下拉列表）
     *
     * @return array 分类数组 bonus_typeid => bonus_type_name
     */
    public static function get_bonus_type()
    {
        $bonus = [];
        $res = DB::table('activity_bonus')
            ->select('type_id', 'type_name', 'type_money')
            ->where('send_type', 3)
            ->get();

        foreach ($res as $row) {
            $bonus[$row->type_id] = $row->type_name.' ['.sprintf(cfg('currency_format'), $row->type_money).']';
        }

        return $bonus;
    }

    /**
     * 取得用户等级数组,按用户级别排序
     *
     * @param  bool  $is_special  是否只显示特殊会员组
     * @return array rank_id=>rank_name
     */
    public static function get_rank_list($is_special = false)
    {
        $rank_list = [];
        $query = DB::table('user_rank')->select('rank_id', 'rank_name', 'min_points');

        if ($is_special) {
            $query->where('special_rank', 1);
        }

        $res = $query->orderBy('min_points')->get();

        foreach ($res as $row) {
            $rank_list[$row->rank_id] = $row->rank_name;
        }

        return $rank_list;
    }

    /**
     * 按等级取得用户列表（用于生成下拉列表）
     *
     * @return array 分类数组 user_id => user_name
     */
    public static function get_user_rank($rankid, $where)
    {
        $user_list = [];
        $res = DB::table('user')
            ->whereRaw(ltrim($where, ' WHERE'))
            ->select('user_id', 'user_name')
            ->orderBy('user_id', 'DESC')
            ->get();

        foreach ($res as $row) {
            $user_list[$row->user_id] = $row->user_name;
        }

        return $user_list;
    }

    /**
     * 取得广告位置数组（用于生成下拉列表）
     *
     * @return array 分类数组 position_id => position_name
     */
    public static function get_position_list()
    {
        $position_list = [];
        $res = DB::table('ad_position')
            ->select('position_id', 'position_name', 'ad_width', 'ad_height')
            ->get();

        foreach ($res as $row) {
            $position_list[$row->position_id] = addslashes($row->position_name).' ['.$row->ad_width.'x'.$row->ad_height.']';
        }

        return $position_list;
    }

    /**
     * 生成编辑器
     *
     * @param string  input_name  输入框名称
     * @param string  input_value 输入框值
     */
    public static function create_html_editor($input_name, $input_value = '')
    {
        //        $editor = new FCKeditor($input_name);
        //        $editor->BasePath = '../includes/fckeditor/';
        //        $editor->ToolbarSet = 'Normal';
        //        $editor->Width = '100%';
        //        $editor->Height = '320';
        //        $editor->Value = $input_value;
        //        $FCKeditor = $editor->CreateHtml();
        //        $this->assign('FCKeditor', $FCKeditor);
        return ''; // TODO
    }

    /**
     * 取得商品列表：用于把商品添加到组合、关联类、赠品类
     *
     * @param  object  $filters  过滤条件
     */
    public static function get_goods_list($filter)
    {
        $filter->keyword = BaseHelper::json_str_iconv($filter->keyword);
        $where = MainHelper::get_where_sql($filter); // 取得过滤条件

        return DB::table('goods as g')
            ->whereRaw(ltrim($where, ' WHERE'))
            ->select('goods_id', 'goods_name', 'shop_price')
            ->limit(50)
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();
    }

    /**
     * 取得文章列表：用于商品关联文章
     *
     * @param  object  $filters  过滤条件
     */
    public static function get_article_list($filter)
    {
        // 创建数据容器对象
        $ol = new OptionList;

        $query = DB::table('article as a')
            ->join('article_cat as c', 'a.cat_id', '=', 'c.cat_id')
            ->where('c.cat_type', 1);

        if (isset($filter->title)) {
            $query->where('a.title', 'like', '%'.BaseHelper::mysql_like_quote($filter->title).'%');
        }

        $res = $query->select('a.article_id', 'a.title')->get();

        foreach ($res as $row) {
            $ol->add_option((string) $row->article_id, $row->title);
        }

        // 生成列表
        $ol->build_select();
    }

    /**
     * 返回是否
     *
     * @param  int  $var  变量 1, 0
     */
    public static function get_yes_no($var)
    {
        return empty($var) ? '<img src="'.asset('static/admin/images/no.gif').'" border="0" />' :
            '<img src="'.asset('static/admin/images/yes.gif').'" border="0" />';
    }

    /**
     * 生成过滤条件：用于 get_goodslist 和 get_goods_list
     *
     * @param  object  $filter
     * @return string
     */
    public static function get_where_sql($filter)
    {
        $time = date('Y-m-d');

        $where = isset($filter->is_delete) && $filter->is_delete === '1' ?
            ' WHERE is_delete = 1 ' : ' WHERE is_delete = 0 ';
        $where .= (isset($filter->real_goods) && ($filter->real_goods > -1)) ? ' AND is_real = '.intval($filter->real_goods) : '';
        $where .= isset($filter->cat_id) && $filter->cat_id > 0 ? ' AND '.CommonHelper::get_children($filter->cat_id) : '';
        $where .= isset($filter->brand_id) && $filter->brand_id > 0 ? " AND brand_id = '".$filter->brand_id."'" : '';
        $where .= isset($filter->intro_type) && $filter->intro_type != '0' ? ' AND '.$filter->intro_type." = '1'" : '';
        $where .= isset($filter->intro_type) && $filter->intro_type === 'is_promote' ?
            " AND promote_start_date <= '$time' AND promote_end_date >= '$time' " : '';
        $where .= isset($filter->keyword) && trim($filter->keyword) != '' ?
            " AND (goods_name LIKE '%".BaseHelper::mysql_like_quote($filter->keyword)."%' OR goods_sn LIKE '%".BaseHelper::mysql_like_quote($filter->keyword)."%' OR goods_id LIKE '%".BaseHelper::mysql_like_quote($filter->keyword)."%') " : '';
        $where .= isset($filter->suppliers_id) && trim($filter->suppliers_id) != '' ?
            " AND (suppliers_id = '".$filter->suppliers_id."') " : '';

        $where .= isset($filter->in_ids) ? ' AND goods_id '.db_create_in($filter->in_ids) : '';
        $where .= isset($filter->exclude) ? ' AND goods_id NOT '.db_create_in($filter->exclude) : '';
        $where .= isset($filter->stock_warning) ? ' AND goods_number <= warn_number' : '';

        return $where;
    }

    /**
     * 获取地区列表的函数。
     *
     * @param  int  $region_id  上级地区id
     * @return void
     */
    public static function area_list($region_id)
    {
        $res = DB::table('shop_region')
            ->where('parent_id', $region_id)
            ->orderBy('region_id')
            ->get();

        $area_arr = [];
        foreach ($res as $row) {
            $row_array = (array) $row;
            $row_array['type'] = ($row->region_type === 0) ? lang('country') : '';
            $row_array['type'] .= ($row->region_type === 1) ? lang('province') : '';
            $row_array['type'] .= ($row->region_type === 2) ? lang('city') : '';
            $row_array['type'] .= ($row->region_type === 3) ? lang('cantonal') : '';

            $area_arr[] = $row_array;
        }

        return $area_arr;
    }

    /**
     * 取得图表颜色
     *
     * @param  int  $n  颜色顺序
     * @return void
     */
    public static function chart_color($n)
    {
        // 随机显示颜色代码
        $arr = ['33FF66', 'FF6600', '3399FF', '009966', 'CC3399', 'FFCC33', '6699CC', 'CC3366', '33FF66', 'FF6600', '3399FF'];

        if ($n > 8) {
            $n = $n % 8;
        }

        return $arr[$n];
    }

    /**
     * 获得商品类型的列表
     *
     * @param  int  $selected  选定的类型编号
     * @return string
     */
    public static function goods_type_list($selected)
    {
        $res = DB::table('goods_type')
            ->where('enabled', 1)
            ->select('cat_id', 'cat_name')
            ->get();

        $lst = '';
        foreach ($res as $row) {
            $lst .= "<option value='$row->cat_id'";
            $lst .= ($selected === $row->cat_id) ? ' selected="true"' : '';
            $lst .= '>'.htmlspecialchars($row->cat_name).'</option>';
        }

        return $lst;
    }

    /**
     * 取得货到付款和非货到付款的支付方式
     *
     * @return array('is_cod' => '', 'is_not_cod' => '')
     */
    public static function get_pay_ids()
    {
        $ids = ['is_cod' => '0', 'is_not_cod' => '0'];
        $res = DB::table('payment')
            ->where('enabled', 1)
            ->select('pay_id', 'is_cod')
            ->get();

        foreach ($res as $row) {
            if ($row->is_cod) {
                $ids['is_cod'] .= ','.$row->pay_id;
            } else {
                $ids['is_not_cod'] .= ','.$row->pay_id;
            }
        }

        return $ids;
    }

    /**
     * 清空表数据
     *
     * @param  string  $table_name  表名称
     */
    public static function truncate_table($table_name)
    {
        return DB::table($table_name)->truncate();
    }

    /**
     *  返回字符集列表数组
     *
     *
     * @return void
     */
    public static function get_charset_list()
    {
        return [
            'UTF8' => 'UTF-8',
            'GB2312' => 'GB2312/GBK',
            'BIG5' => 'BIG5',
        ];
    }

    /**
     * 根据过滤条件获得排序的标记
     *
     * @param  array  $filter
     * @return array
     */
    public static function sort_flag($filter)
    {
        $sort = asset('static/admin/images/'.($filter['sort_order'] === 'DESC' ? 'sort_desc.gif' : 'sort_asc.gif'));
        $flag['tag'] = 'sort_'.preg_replace('/^.*\./', '', $filter['sort_by']);
        $flag['img'] = '<img src="'.$sort.'"/>';

        return $flag;
    }

    /**
     * 分页的信息加入条件的数组
     *
     * @return array
     */
    public static function page_and_size($filter)
    {
        if (isset($_REQUEST['page_size']) && intval($_REQUEST['page_size']) > 0) {
            $filter['page_size'] = intval($_REQUEST['page_size']);
        } else {
            $ecscpCookie = Cookie::get('ECSCP');
            $pageSize = is_array($ecscpCookie) ? ($ecscpCookie['page_size'] ?? '') : '';
            $filter['page_size'] = isset($pageSize) && intval($pageSize) > 0 ? intval($pageSize) : 15;
        }

        // 每页显示
        $filter['page'] = (empty($_REQUEST['page']) || intval($_REQUEST['page']) <= 0) ? 1 : intval($_REQUEST['page']);

        // page 总数
        $filter['page_count'] = (! empty($filter['record_count']) && $filter['record_count'] > 0) ? ceil($filter['record_count'] / $filter['page_size']) : 1;

        // 边界处理
        if ($filter['page'] > $filter['page_count']) {
            $filter['page'] = $filter['page_count'];
        }

        $filter['start'] = ($filter['page'] - 1) * $filter['page_size'];

        return $filter;
    }

    /**
     *  将含有单位的数字转成字节
     *
     * @param  string  $val  带单位的数字
     * @return int $val
     */
    public static function return_bytes($val)
    {
        $val = trim($val);
        $v = (int) mb_substr($val, 0, strlen($val) - 1);
        $last = strtolower($val[strlen($val) - 1]);
        switch ($last) {
            case 'g':
                $v *= 1024;
            case 'm':
                $v *= 1024;
            case 'k':
                $v *= 1024;
        }

        return $v;
    }

    /**
     * 获得指定的商品类型下所有的属性分组
     *
     * @param  int  $cat_id  商品类型ID
     * @return array
     */
    public static function get_attr_groups($cat_id)
    {
        $grp = DB::table('goods_type')
            ->where('cat_id', $cat_id)
            ->value('attr_group');

        $grp = str_replace("\r", '', (string) $grp);

        if ($grp) {
            return explode("\n", $grp);
        } else {
            return [];
        }
    }

    /**
     * 生成链接后缀
     */
    public static function list_link_postfix()
    {
        return 'uselastfilter=1';
    }

    /**
     * 保存过滤条件
     *
     * @param  array  $filter  过滤条件
     * @param  string  $sql  查询语句
     * @param  string  $param_str  参数字符串，由list函数的参数组成
     */
    public static function set_filter($filter, $sql, $param_str = '')
    {
        $filterfile = basename(request()->path(), '.php');
        if ($param_str) {
            $filterfile .= $param_str;
        }
        Cookie::queue('ECSCP[lastfilterfile]', sprintf('%X', crc32($filterfile)), 600);
        Cookie::queue('ECSCP[lastfilter]', urlencode(serialize($filter)), 600);
        Cookie::queue('ECSCP[lastfiltersql]', base64_encode($sql), 600);
    }

    /**
     * 取得上次的过滤条件
     *
     * @param  string  $param_str  参数字符串，由list函数的参数组成
     * @return 如果有，返回array('filter' => $filter, 'sql' => $sql)；否则返回false
     */
    public static function get_filter($param_str = '')
    {
        $filterfile = basename(request()->path(), '.php');
        if ($param_str) {
            $filterfile .= $param_str;
        }
        $ecscpCookie = Cookie::get('ECSCP');
        $lastfilterfile = is_array($ecscpCookie) ? ($ecscpCookie['lastfilterfile'] ?? '') : '';
        if (
            isset($_GET['uselastfilter']) && $lastfilterfile
            && $lastfilterfile === sprintf('%X', crc32($filterfile))
        ) {
            $lastfilter = is_array($ecscpCookie) ? ($ecscpCookie['lastfilter'] ?? '') : '';
            $lastfiltersql = is_array($ecscpCookie) ? ($ecscpCookie['lastfiltersql'] ?? '') : '';
            return [
                'filter' => unserialize(urldecode($lastfilter)),
                'sql' => base64_decode($lastfiltersql),
            ];
        } else {
            return false;
        }
    }

    /**
     * URL过滤
     *
     * @param  string  $url  参数字符串，一个urld地址,对url地址进行校正
     * @return 返回校正过的url;
     */
    public static function sanitize_url($url, $check = 'http://')
    {
        if (strpos($url, $check) === false) {
            $url = $check.$url;
        }

        return $url;
    }

    /**
     * 检查分类是否已经存在
     *
     * @param  string  $cat_name  分类名称
     * @param  int  $parent_cat  上级分类
     * @param  int  $exclude  排除的分类ID
     * @return bool
     */
    public static function cat_exists($cat_name, $parent_cat, $exclude = 0)
    {
        return DB::table('goods_category')
            ->where('parent_id', $parent_cat)
            ->where('cat_name', $cat_name)
            ->where('cat_id', '<>', $exclude)
            ->exists();
    }

    public static function brand_exists($brand_name)
    {
        return DB::table('goods_brand')
            ->where('brand_name', $brand_name)
            ->exists();
    }

    /**
     * 获取当前管理员信息
     *
     *
     * @return array
     */
    public static function admin_info()
    {
        $admin_id = session('admin_id', 0);
        $admin_info = DB::table('admin_user')
            ->where('user_id', $admin_id)
            ->first();

        return $admin_info ? (array) $admin_info : [];
    }

    /**
     * 供货商列表信息
     *
     * @param  string  $conditions
     * @return array
     */
    public static function suppliers_list_info($conditions = '')
    {
        $query = DB::table('supplier')->select('suppliers_id', 'suppliers_name', 'suppliers_desc');

        if (! empty($conditions)) {
            $query->whereRaw(ltrim($conditions, ' WHERE'));
        }

        return $query->get()->map(fn ($item) => (array) $item)->all();
    }

    /**
     * 供货商名
     *
     * @return array
     */
    public static function suppliers_list_name()
    {
        // 查询
        $suppliers_list = MainHelper::suppliers_list_info(' is_check = 1 ');

        // 供货商名字
        $suppliers_name = [];
        if (count($suppliers_list) > 0) {
            foreach ($suppliers_list as $suppliers) {
                $suppliers_name[$suppliers['suppliers_id']] = $suppliers['suppliers_name'];
            }
        }

        return $suppliers_name;
    }
}
