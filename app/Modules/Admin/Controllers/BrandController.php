<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Libraries\Image;
use App\Modules\Admin\Helpers\MainHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BrandController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        $image = new Image(cfg('bgcolor'));

        /**
         * 品牌列表
         */
        if ($action === 'list') {
            $this->assign('ur_here', lang('06_goods_brand_list'));
            $this->assign('action_link', ['text' => lang('07_brand_add'), 'href' => 'brand.php?act=add']);
            $this->assign('full_page', 1);

            $brand_list = $this->get_brandlist();

            $this->assign('brand_list', $brand_list['brand']);
            $this->assign('filter', $brand_list['filter']);
            $this->assign('record_count', $brand_list['record_count']);
            $this->assign('page_count', $brand_list['page_count']);

            return $this->display('brand_list');
        }

        /**
         * 添加品牌
         */
        if ($action === 'add') {
            // 权限判断
            $this->admin_priv('brand_manage');

            $this->assign('ur_here', lang('07_brand_add'));
            $this->assign('action_link', ['text' => lang('06_goods_brand_list'), 'href' => 'brand.php?act=list']);
            $this->assign('form_action', 'insert');

            $this->assign('brand', ['sort_order' => 50, 'is_show' => 1]);

            return $this->display('brand_info');
        }

        if ($action === 'insert') {
            // 检查品牌名是否重复
            $this->admin_priv('brand_manage');

            $is_show = isset($_REQUEST['is_show']) ? intval($_REQUEST['is_show']) : 0;

            $is_only = ! DB::table('goods_brand')->where('brand_name', $_POST['brand_name'])->exists();

            if (! $is_only) {
                return $this->sys_msg(sprintf(lang('brandname_exist'), stripslashes($_POST['brand_name'])), 1);
            }

            // 对描述处理
            if (! empty($_POST['brand_desc'])) {
                $_POST['brand_desc'] = $_POST['brand_desc'];
            }

            // 处理图片
            $img_name = basename($image->upload_image($_FILES['brand_logo'], 'brandlogo'));

            // 处理URL
            $site_url = MainHelper::sanitize_url($_POST['site_url']);

            // 插入数据
            DB::table('goods_brand')->insert([
                'brand_name' => $_POST['brand_name'],
                'site_url' => $site_url,
                'brand_desc' => $_POST['brand_desc'],
                'brand_logo' => $img_name,
                'is_show' => $is_show,
                'sort_order' => $_POST['sort_order'],
            ]);

            $this->admin_log($_POST['brand_name'], 'add', 'brand');

            // 清除缓存
            $this->clear_cache_files();

            $link[0]['text'] = lang('continue_add');
            $link[0]['href'] = 'brand.php?act=add';

            $link[1]['text'] = lang('back_list');
            $link[1]['href'] = 'brand.php?act=list';

            return $this->sys_msg(lang('brandadd_succed'), 0, $link);
        }

        /**
         * 编辑品牌
         */
        if ($action === 'edit') {
            // 权限判断
            $this->admin_priv('brand_manage');
            $brand = DB::table('goods_brand')
                ->select('brand_id', 'brand_name', 'site_url', 'brand_logo', 'brand_desc', 'is_show', 'sort_order')
                ->where('brand_id', $_REQUEST['id'])
                ->first();
            $brand = $brand ? (array) $brand : [];

            $this->assign('ur_here', lang('brand_edit'));
            $this->assign('action_link', ['text' => lang('06_goods_brand_list'), 'href' => 'brand.php?act=list&'.MainHelper::list_link_postfix()]);
            $this->assign('brand', $brand);
            $this->assign('form_action', 'updata');

            return $this->display('brand_info');
        }

        if ($action === 'updata') {
            $this->admin_priv('brand_manage');
            if ($_POST['brand_name'] != $_POST['old_brandname']) {
                // 检查品牌名是否相同
                $is_only = DB::table('goods_brand')
                    ->where('brand_name', $_POST['brand_name'])
                    ->where('brand_id', '!=', $_POST['id'])
                    ->doesntExist();

                if (! $is_only) {
                    return $this->sys_msg(sprintf(lang('brandname_exist'), stripslashes($_POST['brand_name'])), 1);
                }
            }

            // 对描述处理
            if (! empty($_POST['brand_desc'])) {
                $_POST['brand_desc'] = $_POST['brand_desc'];
            }

            $is_show = isset($_REQUEST['is_show']) ? intval($_REQUEST['is_show']) : 0;
            // 处理URL
            $site_url = MainHelper::sanitize_url($_POST['site_url']);

            // 处理图片
            $img_name = basename($image->upload_image($_FILES['brand_logo'], 'brandlogo'));

            $update_data = [
                'brand_name' => $_POST['brand_name'],
                'site_url' => $site_url,
                'brand_desc' => $_POST['brand_desc'],
                'is_show' => $is_show,
                'sort_order' => $_POST['sort_order'],
            ];

            if (! empty($img_name)) {
                // 有图片上传
                $update_data['brand_logo'] = $img_name;
            }

            if (DB::table('goods_brand')->where('brand_id', $_POST['id'])->update($update_data)) {
                // 清除缓存
                $this->clear_cache_files();

                $this->admin_log($_POST['brand_name'], 'edit', 'brand');

                $link[0]['text'] = lang('back_list');
                $link[0]['href'] = 'brand.php?act=list&'.MainHelper::list_link_postfix();
                $note = vsprintf(lang('brandedit_succed'), $_POST['brand_name']);

                return $this->sys_msg($note, 0, $link);
            } else {
                return $this->sys_msg(lang('edit_failed'), 1);
            }
        }

        /**
         * 编辑品牌名称
         */
        if ($action === 'edit_brand_name') {
            $this->check_authz_json('brand_manage');

            $id = intval($_POST['id']);
            $name = BaseHelper::json_str_iconv(trim($_POST['val']));

            // 检查名称是否重复
            if (DB::table('goods_brand')->where('brand_name', $name)->where('brand_id', '!=', $id)->exists()) {
                return $this->make_json_error(sprintf(lang('brandname_exist'), $name));
            } else {
                if (DB::table('goods_brand')->where('brand_id', $id)->update(['brand_name' => $name])) {
                    $this->admin_log($name, 'edit', 'brand');

                    return $this->make_json_result(stripslashes($name));
                } else {
                    return $this->make_json_result(sprintf(lang('brandedit_fail'), $name));
                }
            }
        }

        if ($action === 'add_brand') {
            $brand = empty($_REQUEST['brand']) ? '' : BaseHelper::json_str_iconv(trim($_REQUEST['brand']));

            if (MainHelper::brand_exists($brand)) {
                return $this->make_json_error(lang('brand_name_exist'));
            } else {
                $brand_id = DB::table('goods_brand')->insertGetId([
                    'brand_name' => $brand,
                ]);

                $arr = ['id' => $brand_id, 'brand' => $brand];

                return $this->make_json_result(json_encode($arr));
            }
        }
        /**
         * 编辑排序序号
         */
        if ($action === 'edit_sort_order') {
            $this->check_authz_json('brand_manage');

            $id = intval($_POST['id']);
            $order = intval($_POST['val']);
            $name = DB::table('goods_brand')->where('brand_id', $id)->value('brand_name');

            if (DB::table('goods_brand')->where('brand_id', $id)->update(['sort_order' => $order])) {
                $this->admin_log(addslashes($name), 'edit', 'brand');

                return $this->make_json_result((string) $order);
            } else {
                return $this->make_json_error(sprintf(lang('brandedit_fail'), $name));
            }
        }

        /**
         * 切换是否显示
         */
        if ($action === 'toggle_show') {
            $this->check_authz_json('brand_manage');

            $id = intval($_POST['id']);
            $val = intval($_POST['val']);

            DB::table('goods_brand')->where('brand_id', $id)->update(['is_show' => $val]);

            return $this->make_json_result((string) $val);
        }

        /**
         * 删除品牌
         */
        if ($action === 'remove') {
            $this->check_authz_json('brand_manage');

            $id = intval($_GET['id']);

            // 删除该品牌的图标
            $logo_name = DB::table('goods_brand')->where('brand_id', $id)->value('brand_logo');
            if (! empty($logo_name)) {
                @unlink(ROOT_PATH.DATA_DIR.'/brandlogo/'.$logo_name);
            }

            DB::table('goods_brand')->where('brand_id', $id)->delete();

            // 更新商品的品牌编号
            DB::table('goods')->where('brand_id', $id)->update(['brand_id' => 0]);

            $url = 'brand.php?act=query&'.str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

            return response()->redirectTo($url);
        }

        /**
         * 删除品牌图片
         */
        if ($action === 'drop_logo') {
            // 权限判断
            $this->admin_priv('brand_manage');
            $brand_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

            // 取得logo名称
            $logo_name = DB::table('goods_brand')->where('brand_id', $brand_id)->value('brand_logo');

            if (! empty($logo_name)) {
                @unlink(ROOT_PATH.DATA_DIR.'/brandlogo/'.$logo_name);
                DB::table('goods_brand')->where('brand_id', $brand_id)->update(['brand_logo' => '']);
            }
            $link = [['text' => lang('brand_edit_lnk'), 'href' => 'brand.php?act=edit&id='.$brand_id], ['text' => lang('brand_list_lnk'), 'href' => 'brand.php?act=list']];

            return $this->sys_msg(lang('drop_brand_logo_success'), 0, $link);
        }

        /**
         * 排序、分页、查询
         */
        if ($action === 'query') {
            $brand_list = $this->get_brandlist();
            $this->assign('brand_list', $brand_list['brand']);
            $this->assign('filter', $brand_list['filter']);
            $this->assign('record_count', $brand_list['record_count']);
            $this->assign('page_count', $brand_list['page_count']);

            return $this->make_json_result(
                $this->fetch('brand_list'),
                '',
                ['filter' => $brand_list['filter'], 'page_count' => $brand_list['page_count']]
            );
        }
    }

    /**
     * 获取品牌列表
     *
     * @return array
     */
    private function get_brandlist()
    {
        $result = MainHelper::get_filter();
        if ($result === false) {
            // 分页大小
            $filter = [];

            $query = DB::table('goods_brand');

            // 记录总数以及页数
            if (isset($_POST['brand_name'])) {
                $query->where('brand_name', $_POST['brand_name']);
            }

            $filter['record_count'] = $query->count();

            $filter = MainHelper::page_and_size($filter);

            // 查询记录
            if (isset($_POST['brand_name'])) {
                if (strtoupper(EC_CHARSET) === 'GBK') {
                    $keyword = iconv('UTF-8', 'gb2312', $_POST['brand_name']);
                } else {
                    $keyword = $_POST['brand_name'];
                }
                $query->where('brand_name', 'like', '%'.$keyword.'%');
            }

            $res = $query->orderBy('sort_order', 'ASC')
                ->offset($filter['start'])
                ->limit($filter['page_size'])
                ->get();

            // MainHelper::set_filter($filter, $sql); // Cannot easily set_filter for Query Builder
        } else {
            $res = DB::select($result['sql']);
            $filter = $result['filter'];
        }

        $arr = [];
        $picflag = asset('static/admin/images/picflag.gif');
        foreach ($res as $rows) {
            $rows = (array) $rows;
            $logo = Storage::publicUrl('data/brandlogo/'.$rows['brand_logo']);
            $brand_logo = empty($rows['brand_logo']) ? '' :
                '<a href="'.$logo.'" target="_brank"><img src="'.$picflag.'" width="16" height="16" border="0" alt='.lang('brand_logo').' /></a>';
            $site_url = empty($rows['site_url']) ? 'N/A' : '<a href="'.$rows['site_url'].'" target="_brank">'.$rows['site_url'].'</a>';

            $rows['brand_logo'] = $brand_logo;
            $rows['site_url'] = $site_url;

            $arr[] = $rows;
        }

        return ['brand' => $arr, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];
    }
}
