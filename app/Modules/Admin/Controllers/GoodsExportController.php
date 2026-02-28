<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\CommonHelper;
use App\Modules\Admin\Helpers\MainHelper;
use App\Modules\Admin\Libraries\PHPZip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

class GoodsExportController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        if ($action === 'goods_export') {
            $this->admin_priv('goods_export');

            $this->assign('ur_here', lang('14_goods_export'));
            $this->assign('cat_list', CommonHelper::cat_list());
            $this->assign('brand_list', CommonHelper::get_brand_list());
            $this->assign('goods_type_list', MainHelper::goods_type_list(0));
            $goods_fields = $this->my_array_merge(lang('custom'), $this->get_attributes());
            $data_format_array = [
                'phpmall' => lang('export_phpmall'),
                'custom' => lang('export_custom'),
            ];
            $this->assign('data_format', $data_format_array);
            $this->assign('goods_fields', $goods_fields);

            return $this->display('goods_export');
        }

        if ($action === 'act_export_phpmall') {
            $this->admin_priv('goods_export');

            $zip = new PHPZip;

            $where = $this->get_export_where_sql($_POST);

            $res = DB::table('goods as g')
                ->leftJoin('goods_brand as b', 'g.brand_id', '=', 'b.brand_id')
                ->select('g.*', 'b.brand_name as brandname')
                ->whereRaw('1 '.$where)
                ->get()
                ->map(function ($item) {
                    return (array) $item;
                })
                ->toArray();

            // csv文件数组
            $goods_value = [];
            $goods_value['goods_name'] = '""';
            $goods_value['goods_sn'] = '""';
            $goods_value['brand_name'] = '""';
            $goods_value['market_price'] = 0;
            $goods_value['shop_price'] = 0;
            $goods_value['integral'] = 0;
            $goods_value['original_img'] = '""';
            $goods_value['goods_img'] = '""';
            $goods_value['goods_thumb'] = '""';
            $goods_value['keywords'] = '""';
            $goods_value['goods_brief'] = '""';
            $goods_value['goods_desc'] = '""';
            $goods_value['goods_weight'] = 0;
            $goods_value['goods_number'] = 0;
            $goods_value['warn_number'] = 0;
            $goods_value['is_best'] = 0;
            $goods_value['is_new'] = 0;
            $goods_value['is_hot'] = 0;
            $goods_value['is_on_sale'] = 1;
            $goods_value['is_alone_sale'] = 1;
            $goods_value['is_real'] = 1;
            $content = '"'.implode('","', lang('phpmall'))."\"\n";

            foreach ($res as $row) {
                $goods_value['goods_name'] = '"'.$row['goods_name'].'"';
                $goods_value['goods_sn'] = '"'.$row['goods_sn'].'"';
                $goods_value['brand_name'] = '"'.$row['brandname'].'"';
                $goods_value['market_price'] = $row['market_price'];
                $goods_value['shop_price'] = $row['shop_price'];
                $goods_value['integral'] = $row['integral'];
                $goods_value['original_img'] = '"'.$row['original_img'].'"';
                $goods_value['goods_img'] = '"'.$row['goods_img'].'"';
                $goods_value['goods_thumb'] = '"'.$row['goods_thumb'].'"';
                $goods_value['keywords'] = '"'.$row['keywords'].'"';
                $goods_value['goods_brief'] = '"'.$this->replace_special_char($row['goods_brief'], false).'"';
                $goods_value['goods_desc'] = '"'.$this->replace_special_char($row['goods_desc'], false).'"';
                $goods_value['goods_weight'] = $row['goods_weight'];
                $goods_value['goods_number'] = $row['goods_number'];
                $goods_value['warn_number'] = $row['warn_number'];
                $goods_value['is_best'] = $row['is_best'];
                $goods_value['is_new'] = $row['is_new'];
                $goods_value['is_hot'] = $row['is_hot'];
                $goods_value['is_on_sale'] = $row['is_on_sale'];
                $goods_value['is_alone_sale'] = $row['is_alone_sale'];
                $goods_value['is_real'] = $row['is_real'];

                $content .= implode(',', $goods_value)."\n";

                // 压缩图片
                if (! empty($row['goods_img']) && is_file(ROOT_PATH.$row['goods_img'])) {
                    $zip->add_file(file_get_contents(ROOT_PATH.$row['goods_img']), $row['goods_img']);
                }
                if (! empty($row['original_img']) && is_file(ROOT_PATH.$row['original_img'])) {
                    $zip->add_file(file_get_contents(ROOT_PATH.$row['original_img']), $row['original_img']);
                }
                if (! empty($row['goods_thumb']) && is_file(ROOT_PATH.$row['goods_thumb'])) {
                    $zip->add_file(file_get_contents(ROOT_PATH.$row['goods_thumb']), $row['goods_thumb']);
                }
            }
            $charset = empty($_POST['charset']) ? 'UTF8' : trim($_POST['charset']);

            $zip->add_file(BaseHelper::ecs_iconv(EC_CHARSET, $charset, $content), 'goods_list.csv');

            header('Content-Disposition: attachment; filename=goods_list.zip');
            header('Content-Type: application/unknown');
            exit($zip->file());
        }
        // 处理Ajax调用
        if ($action === 'get_goods_fields') {
            $cat_id = isset($_REQUEST['cat_id']) ? intval($_REQUEST['cat_id']) : 0;
            $goods_fields = $this->my_array_merge(lang('custom'), $this->get_attributes($cat_id));

            return $this->make_json_result('', '', $goods_fields);
        }

        if ($action === 'act_export_custom') {
            // 检查输出列
            if (empty($_POST['custom_goods_export'])) {
                return $this->sys_msg(lang('custom_goods_field_not_null'), 1, [], false);
            }

            $this->admin_priv('goods_export');

            $zip = new PHPZip;

            $where = $this->get_export_where_sql($_POST);

            $res = DB::table('goods as g')
                ->leftJoin('goods_brand as b', 'g.brand_id', '=', 'b.brand_id')
                ->select('g.*', 'b.brand_name as brandname')
                ->whereRaw('1 '.$where)
                ->get()
                ->map(function ($item) {
                    return (array) $item;
                })
                ->toArray();

            $goods_fields = explode(',', $_POST['custom_goods_export']);
            $goods_field_name = $this->set_goods_field_name($goods_fields, lang('custom'));

            // csv文件数组
            $goods_field_value = [];
            foreach ($goods_fields as $field) {
                if ($field === 'market_price' || $field === 'shop_price' || $field === 'integral' || $field === 'goods_weight' || $field === 'goods_number' || $field === 'warn_number' || $field === 'is_best' || $field === 'is_new' || $field === 'is_hot') {
                    $goods_field_value[$field] = 0;
                } elseif ($field === 'is_on_sale' || $field === 'is_alone_sale' || $field === 'is_real') {
                    $goods_field_value[$field] = 1;
                } else {
                    $goods_field_value[$field] = '""';
                }
            }

            $content = '"'.implode('","', $goods_field_name)."\"\n";
            foreach ($res as $row) {
                $goods_value = $goods_field_value;
                isset($goods_value['goods_name']) && ($goods_value['goods_name'] = '"'.$row['goods_name'].'"');
                isset($goods_value['goods_sn']) && ($goods_value['goods_sn'] = '"'.$row['goods_sn'].'"');
                isset($goods_value['brand_name']) && ($goods_value['brand_name'] = $row['brandname']);
                isset($goods_value['market_price']) && ($goods_value['market_price'] = $row['market_price']);
                isset($goods_value['shop_price']) && ($goods_value['shop_price'] = $row['shop_price']);
                isset($goods_value['integral']) && ($goods_value['integral'] = $row['integral']);
                isset($goods_value['original_img']) && ($goods_value['original_img'] = '"'.$row['original_img'].'"');
                isset($goods_value['keywords']) && ($goods_value['keywords'] = '"'.$row['keywords'].'"');
                isset($goods_value['goods_brief']) && ($goods_value['goods_brief'] = '"'.$this->replace_special_char($row['goods_brief']).'"');
                isset($goods_value['goods_desc']) && ($goods_value['goods_desc'] = '"'.$this->replace_special_char($row['goods_desc']).'"');
                isset($goods_value['goods_weight']) && ($goods_value['goods_weight'] = $row['goods_weight']);
                isset($goods_value['goods_number']) && ($goods_value['goods_number'] = $row['goods_number']);
                isset($goods_value['warn_number']) && ($goods_value['warn_number'] = $row['warn_number']);
                isset($goods_value['is_best']) && ($goods_value['is_best'] = $row['is_best']);
                isset($goods_value['is_new']) && ($goods_value['is_new'] = $row['is_new']);
                isset($goods_value['is_hot']) && ($goods_value['is_hot'] = $row['is_hot']);
                isset($goods_value['is_on_sale']) && ($goods_value['is_on_sale'] = $row['is_on_sale']);
                isset($goods_value['is_alone_sale']) && ($goods_value['is_alone_sale'] = $row['is_alone_sale']);
                isset($goods_value['is_real']) && ($goods_value['is_real'] = $row['is_real']);

                $query = DB::table('goods_attr')
                    ->select('attr_id', 'attr_value')
                    ->where('goods_id', $row['goods_id'])
                    ->get();
                foreach ($query as $attr) {
                    $attr = (array) $attr;
                    if (in_array($attr['attr_id'], $goods_fields)) {
                        $goods_value[$attr['attr_id']] = '"'.$attr['attr_value'].'"';
                    }
                }

                $content .= implode(',', $goods_value)."\n";

                // 压缩图片
                if (! empty($row['goods_img']) && is_file(ROOT_PATH.$row['goods_img'])) {
                    $zip->add_file(file_get_contents(ROOT_PATH.$row['goods_img']), $row['goods_img']);
                }
            }
            $charset = empty($_POST['charset_custom']) ? 'UTF8' : trim($_POST['charset_custom']);
            $zip->add_file(BaseHelper::ecs_iconv(EC_CHARSET, $charset, $content), 'goods_list.csv');

            header('Content-Disposition: attachment; filename=goods_list.zip');
            header('Content-Type: application/unknown');
            exit($zip->file());
        }

        if ($action === 'get_goods_list') {
            $filters = json_decode($_REQUEST['JSON']);
            $arr = MainHelper::get_goods_list($filters);
            $opt = [];

            foreach ($arr as $key => $val) {
                $opt[] = [
                    'goods_id' => $val['goods_id'],
                    'goods_name' => $val['goods_name'],
                ];
            }

            return $this->make_json_result('', '', $opt);
        }
    }

    private function utf82u2($str): string
    {
        $len = strlen($str);
        $start = 0;
        $result = '';

        if ($len === 0) {
            return $result;
        }

        while ($start < $len) {
            $num = ord($str[$start]);
            if ($num < 127) {
                $result .= chr($num).chr($num >> 8);
                $start += 1;
            } else {
                if ($num < 192) {
                    // 无效字节
                    $start++;
                } elseif ($num < 224) {
                    if ($start + 1 < $len) {
                        $num = (ord($str[$start]) & 0x3F) << 6;
                        $num += ord($str[$start + 1]) & 0x3F;
                        $result .= chr($num & 0xFF).chr($num >> 8);
                    }
                    $start += 2;
                } elseif ($num < 240) {
                    if ($start + 2 < $len) {
                        $num = (ord($str[$start]) & 0x1F) << 12;
                        $num += (ord($str[$start + 1]) & 0x3F) << 6;
                        $num += ord($str[$start + 2]) & 0x3F;

                        $result .= chr($num & 0xFF).chr($num >> 8);
                    }
                    $start += 3;
                } elseif ($num < 248) {
                    if ($start + 3 < $len) {
                        $num = (ord($str[$start]) & 0x0F) << 18;
                        $num += (ord($str[$start + 1]) & 0x3F) << 12;
                        $num += (ord($str[$start + 2]) & 0x3F) << 6;
                        $num += ord($str[$start + 3]) & 0x3F;
                        $result .= chr($num & 0xFF).chr($num >> 8).chr($num >> 16);
                    }
                    $start += 4;
                } elseif ($num < 252) {
                    if ($start + 4 < $len) {
                        // 不做处理
                    }
                    $start += 5;
                } else {
                    if ($start + 5 < $len) {
                        // 不做处理
                    }
                    $start += 6;
                }
            }
        }

        return $result;
    }

    /**
     * @return string
     */
    private function image_path_format($content)
    {
        $prefix = 'http://'.$_SERVER['SERVER_NAME'];
        $pattern = '/(background|src)=[\'|\"]((?!http:\/\/).*?)[\'|\"]/i';
        $replace = "$1='".$prefix."$2'";

        return preg_replace($pattern, $replace, $content);
    }

    /**
     * 获取商品类型属性
     *
     * @param  int  $cat_id  商品类型ID
     * @return array
     */
    private function get_attributes($cat_id = 0)
    {
        $attributes = [];
        $query = DB::table('goods_type_attribute')
            ->select('attr_id', 'cat_id', 'attr_name');

        if (! empty($cat_id)) {
            $query->where('cat_id', intval($cat_id));
        }

        $res = $query->orderBy('cat_id')
            ->orderBy('attr_id')
            ->get();

        foreach ($res as $row) {
            $row = (array) $row;
            $attributes[$row['attr_id']] = $row['attr_name'];
        }

        return $attributes;
    }

    /**
     * 设置导出商品字段名
     *
     * @param  array  $array  字段数组
     * @param  array  $lang  字段名
     * @return array
     */
    private function set_goods_field_name($array, $lang)
    {
        $tmp_fields = $array;
        foreach ($array as $key => $value) {
            if (isset($lang[$value])) {
                $tmp_fields[$key] = $lang[$value];
            } else {
                $tmp_fields[$key] = DB::table('goods_type_attribute')
                    ->where('attr_id', intval($value))
                    ->value('attr_name');
            }
        }

        return $tmp_fields;
    }

    /**
     * 数组合并
     *
     * @param  array  $array1  数组1
     * @param  array  $array2  数组2
     * @return array
     */
    private function my_array_merge($array1, $array2)
    {
        $new_array = $array1;
        foreach ($array2 as $key => $val) {
            $new_array[$key] = $val;
        }

        return $new_array;
    }

    /**
     * 生成商品导出过滤条件
     *
     * @param  array  $filter  过滤条件数组
     * @return string
     */
    private function get_export_where_sql($filter)
    {
        $where = '';
        if (! empty($filter['goods_ids'])) {
            $goods_ids = explode(',', $filter['goods_ids']);
            if (is_array($goods_ids) && ! empty($goods_ids)) {
                $goods_ids = array_unique($goods_ids);
                $goods_ids = "'".implode("','", $goods_ids)."'";
            } else {
                $goods_ids = "'0'";
            }
            $where = ' WHERE g.is_delete = 0 AND g.goods_id IN ('.$goods_ids.') ';
        } else {
            $_filter = new StdClass;
            $_filter->cat_id = $filter['cat_id'];
            $_filter->brand_id = $filter['brand_id'];
            $_filter->keyword = $filter['keyword'];
            $where = MainHelper::get_where_sql($_filter);
        }

        return $where;
    }

    /**
     * 替换影响csv文件的字符
     *
     * @param  $str  string 处理字符串
     */
    private function replace_special_char($str, $replace = true)
    {
        $str = str_replace("\r\n", '', $this->image_path_format($str));
        $str = str_replace("\t", '    ', $str);
        $str = str_replace("\n", '', $str);
        if ($replace === true) {
            $str = '"'.str_replace('"', '""', $str).'"';
        }

        return $str;
    }
}
