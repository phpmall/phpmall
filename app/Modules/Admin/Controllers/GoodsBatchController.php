<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\CommonHelper;
use App\Helpers\TimeHelper;
use App\Libraries\Image;
use App\Modules\Admin\Helpers\GoodsHelper;
use App\Modules\Admin\Helpers\MainHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GoodsBatchController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        /**
         * 批量上传
         */
        if ($action === 'add') {
            $this->admin_priv('goods_batch');

            // 取得分类列表
            $this->assign('cat_list', CommonHelper::cat_list());

            // 取得可选语言
            $dir = opendir(ROOT_PATH.'languages');
            $lang_list = [
                'UTF8' => lang('charset.utf8'),
                'GB2312' => lang('charset.zh_cn'),
                'BIG5' => lang('charset.zh_tw'),
            ];
            $download_list = [];
            while (@$file = readdir($dir)) {
                if ($file != '.' && $file != '..' && $file != '.svn' && $file != '_svn' && is_dir(ROOT_PATH.'languages/'.$file) === true) {
                    $download_list[$file] = sprintf(lang('download_file'), isset(lang('charset')[$file]) ? lang('charset')[$file] : $file);
                }
            }
            @closedir($dir);
            $data_format_array = [
                'phpmall' => lang('export_phpmall'),
            ];
            $this->assign('data_format', $data_format_array);
            $this->assign('lang_list', $lang_list);
            $this->assign('download_list', $download_list);

            // 参数赋值
            $ur_here = lang('13_batch_add');
            $this->assign('ur_here', $ur_here);

            return $this->display('goods_batch_add');
        }

        /**
         * 批量上传：处理
         */
        if ($action === 'upload') {
            $this->admin_priv('goods_batch');

            // 将文件按行读入数组，逐行进行解析
            $line_number = 0;
            $arr = [];
            $goods_list = [];
            $field_list = array_keys(lang('upload_goods')); // 字段列表
            $data = file($_FILES['file']['tmp_name']);
            if ($_POST['data_cat'] === 'phpmall') {
                foreach ($data as $line) {
                    // 跳过第一行
                    if ($line_number === 0) {
                        $line_number++;

                        continue;
                    }

                    // 转换编码
                    if (($_POST['charset'] != 'UTF8') && (strpos(strtolower(EC_CHARSET), 'utf') === 0)) {
                        $line = BaseHelper::ecs_iconv($_POST['charset'], 'UTF8', $line);
                    }

                    // 初始化
                    $arr = [];
                    $buff = '';
                    $quote = 0;
                    $len = strlen($line);
                    for ($i = 0; $i < $len; $i++) {
                        $char = $line[$i];

                        if ($char === '\\') {
                            $i++;
                            $char = $line[$i];

                            switch ($char) {
                                case '"':
                                    $buff .= '"';
                                    break;
                                case '\'':
                                    $buff .= '\'';
                                    break;
                                case ',':
                                    $buff .= ',';
                                    break;
                                default:
                                    $buff .= '\\'.$char;
                                    break;
                            }
                        } elseif ($char === '"') {
                            if ($quote === 0) {
                                $quote++;
                            } else {
                                $quote = 0;
                            }
                        } elseif ($char === ',') {
                            if ($quote === 0) {
                                if (! isset($field_list[count($arr)])) {
                                    continue;
                                }
                                $field_name = $field_list[count($arr)];
                                $arr[$field_name] = trim($buff);
                                $buff = '';
                                $quote = 0;
                            } else {
                                $buff .= $char;
                            }
                        } else {
                            $buff .= $char;
                        }

                        if ($i === $len - 1) {
                            if (! isset($field_list[count($arr)])) {
                                continue;
                            }
                            $field_name = $field_list[count($arr)];
                            $arr[$field_name] = trim($buff);
                        }
                    }
                    $goods_list[] = $arr;
                }
            } elseif ($_POST['data_cat'] === 'taobao') {
                $id_is = 0;
                foreach ($data as $line) {
                    // 跳过第一行
                    if ($line_number === 0) {
                        $line_number++;

                        continue;
                    }

                    // 初始化
                    $arr = [];
                    $line_list = explode("\t", $line);
                    $arr['goods_name'] = trim($line_list[0], '"');

                    $max_id = DB::table('goods')->max('goods_id') + $id_is;
                    $id_is++;
                    $goods_sn = GoodsHelper::generate_goods_sn($max_id);
                    $arr['goods_sn'] = $goods_sn;
                    $arr['brand_name'] = '';
                    $arr['market_price'] = $line_list[7];
                    $arr['shop_price'] = $line_list[7];
                    $arr['integral'] = 0;
                    $arr['original_img'] = $line_list[25];
                    $arr['keywords'] = '';
                    $arr['goods_brief'] = '';
                    $arr['goods_desc'] = strip_tags($line_list[24]);
                    $arr['goods_desc'] = substr($arr['goods_desc'], 1, -1);
                    $arr['goods_number'] = $line_list[10];
                    $arr['warn_number'] = 1;
                    $arr['is_best'] = 0;
                    $arr['is_new'] = 0;
                    $arr['is_hot'] = 0;
                    $arr['is_on_sale'] = 1;
                    $arr['is_alone_sale'] = 0;
                    $arr['is_real'] = 1;

                    $goods_list[] = $arr;
                }
            } elseif ($_POST['data_cat'] === 'paipai') {
                $id_is = 0;
                foreach ($data as $line) {
                    // 跳过第一行
                    if ($line_number === 0) {
                        $line_number++;

                        continue;
                    }

                    // 初始化
                    $arr = [];
                    $line_list = explode(',', $line);
                    $arr['goods_name'] = trim($line_list[3], '"');

                    $max_id = DB::table('goods')->max('goods_id') + $id_is;
                    $id_is++;
                    $goods_sn = GoodsHelper::generate_goods_sn($max_id);
                    $arr['goods_sn'] = $goods_sn;
                    $arr['brand_name'] = '';
                    $arr['market_price'] = $line_list[13];
                    $arr['shop_price'] = $line_list[13];
                    $arr['integral'] = 0;
                    $arr['original_img'] = $line_list[28];
                    $arr['keywords'] = '';
                    $arr['goods_brief'] = '';
                    $arr['goods_desc'] = strip_tags($line_list[30]);
                    $arr['goods_number'] = 100;
                    $arr['warn_number'] = 1;
                    $arr['is_best'] = 0;
                    $arr['is_new'] = 0;
                    $arr['is_hot'] = 0;
                    $arr['is_on_sale'] = 1;
                    $arr['is_alone_sale'] = 0;
                    $arr['is_real'] = 1;

                    $goods_list[] = $arr;
                }
            } elseif ($_POST['data_cat'] === 'paipai3') {
                $id_is = 0;
                foreach ($data as $line) {
                    // 跳过第一行
                    if ($line_number === 0) {
                        $line_number++;

                        continue;
                    }

                    // 初始化
                    $arr = [];
                    $line_list = explode(',', $line);
                    $arr['goods_name'] = trim($line_list[1], '"');

                    $max_id = DB::table('goods')->max('goods_id') + $id_is;
                    $id_is++;
                    $goods_sn = GoodsHelper::generate_goods_sn($max_id);
                    $arr['goods_sn'] = $goods_sn;
                    $arr['brand_name'] = '';
                    $arr['market_price'] = $line_list[9];
                    $arr['shop_price'] = $line_list[9];
                    $arr['integral'] = 0;
                    $arr['original_img'] = $line_list[23];
                    $arr['keywords'] = '';
                    $arr['goods_brief'] = '';
                    $arr['goods_desc'] = strip_tags($line_list[24]);
                    $arr['goods_number'] = $line_list[5];
                    $arr['warn_number'] = 1;
                    $arr['is_best'] = 0;
                    $arr['is_new'] = 0;
                    $arr['is_hot'] = 0;
                    $arr['is_on_sale'] = 1;
                    $arr['is_alone_sale'] = 0;
                    $arr['is_real'] = 1;

                    $goods_list[] = $arr;
                }
            } elseif ($_POST['data_cat'] === 'taobao46') {
                $id_is = 0;
                foreach ($data as $line) {
                    // 跳过第一行
                    if ($line_number === 0) {
                        $line_number++;

                        continue;
                    }
                    if (($_POST['charset'] === 'UTF8') && (strpos(strtolower(EC_CHARSET), 'utf') === 0)) {
                        $line = BaseHelper::ecs_iconv($_POST['charset'], 'GBK', $line);
                    }
                    // 初始化
                    $arr = [];
                    $line_list = explode("\t", $line);
                    $arr['goods_name'] = trim($line_list[0], '"');

                    $max_id = DB::table('goods')->max('goods_id') + $id_is;
                    $id_is++;
                    $goods_sn = GoodsHelper::generate_goods_sn($max_id);
                    $arr['goods_sn'] = $goods_sn;
                    $arr['brand_name'] = '';
                    $arr['market_price'] = $line_list[7];
                    $arr['shop_price'] = $line_list[7];
                    $arr['integral'] = 0;
                    $arr['original_img'] = str_replace('"', '', $line_list[35]);
                    $arr['keywords'] = '';
                    $arr['goods_brief'] = '';
                    $arr['goods_desc'] = strip_tags($line_list[24]);
                    $arr['goods_desc'] = substr($arr['goods_desc'], 1, -1);
                    $arr['goods_number'] = $line_list[10];
                    $arr['warn_number'] = 1;
                    $arr['is_best'] = 0;
                    $arr['is_new'] = 0;
                    $arr['is_hot'] = 0;
                    $arr['is_on_sale'] = 1;
                    $arr['is_alone_sale'] = 0;
                    $arr['is_real'] = 1;

                    $goods_list[] = $arr;
                }
            }

            $this->assign('goods_class', lang('g_class'));
            $this->assign('goods_list', $goods_list);

            // 字段名称列表
            $this->assign('title_list', lang('upload_goods'));

            // 显示的字段列表
            $this->assign('field_show', ['goods_name' => true, 'goods_sn' => true, 'brand_name' => true, 'market_price' => true, 'shop_price' => true]);

            // 参数赋值
            $this->assign('ur_here', lang('goods_upload_confirm'));

            return $this->display('goods_batch_confirm');
        }

        /**
         * 批量上传：入库
         */
        if ($action === 'insert') {
            $this->admin_priv('goods_batch');

            if (isset($_POST['checked'])) {
                $image = new Image(cfg('bgcolor'));

                // 字段默认值
                $default_value = [
                    'brand_id' => 0,
                    'goods_number' => 0,
                    'goods_weight' => 0,
                    'market_price' => 0,
                    'shop_price' => 0,
                    'warn_number' => 0,
                    'is_real' => 1,
                    'is_on_sale' => 1,
                    'is_alone_sale' => 1,
                    'integral' => 0,
                    'is_best' => 0,
                    'is_new' => 0,
                    'is_hot' => 0,
                    'goods_type' => 0,
                ];

                // 查询品牌列表
                $brand_list = DB::table('goods_brand')->pluck('brand_id', 'brand_name')->toArray();

                // 字段列表
                $field_list = array_keys(lang('upload_goods'));
                $field_list[] = 'goods_class'; // 实体或虚拟商品

                // 获取商品good id
                $max_id = DB::table('goods')->max('goods_id') + 1;

                // 循环插入商品数据
                foreach ($_POST['checked'] as $key => $value) {
                    // 合并
                    $field_arr = [
                        'cat_id' => $_POST['cat'],
                        'add_time' => TimeHelper::gmtime(),
                        'last_update' => TimeHelper::gmtime(),
                    ];

                    foreach ($field_list as $field) {
                        // 转换编码
                        $field_value = isset($_POST[$field][$value]) ? $_POST[$field][$value] : '';

                        // 虚拟商品处理
                        if ($field === 'goods_class') {
                            $field_value = intval($field_value);
                            if ($field_value === G_CARD) {
                                $field_arr['extension_code'] = 'virtual_card';
                            }

                            continue;
                        }

                        // 如果字段值为空，且有默认值，取默认值
                        $field_arr[$field] = ! isset($field_value) && isset($default_value[$field]) ? $default_value[$field] : $field_value;

                        // 特殊处理
                        if (! empty($field_value)) {
                            // 图片路径
                            if (in_array($field, ['original_img', 'goods_img', 'goods_thumb'])) {
                                if (strpos($field_value, '|;') > 0) {
                                    $field_value = explode(':', $field_value);
                                    $field_value = $field_value['0'];
                                    @copy(ROOT_PATH.'images/'.$field_value.'.tbi', ROOT_PATH.'images/'.$field_value.'.jpg');
                                    if (is_file(ROOT_PATH.'images/'.$field_value.'.jpg')) {
                                        $field_arr[$field] = IMAGE_DIR.'/'.$field_value.'.jpg';
                                    }
                                } else {
                                    $field_arr[$field] = IMAGE_DIR.'/'.$field_value;
                                }
                            } // 品牌
                            elseif ($field === 'brand_name') {
                                if (isset($brand_list[$field_value])) {
                                    $field_arr['brand_id'] = $brand_list[$field_value];
                                } else {
                                    $brand_id = DB::table('goods_brand')->insertGetId(['brand_name' => $field_value]);
                                    $brand_list[$field_value] = $brand_id;
                                    $field_arr['brand_id'] = $brand_id;
                                }

                            } // 整数型
                            elseif (in_array($field, ['goods_number', 'warn_number', 'integral'])) {
                                $field_arr[$field] = intval($field_value);
                            } // 数值型
                            elseif (in_array($field, ['goods_weight', 'market_price', 'shop_price'])) {
                                $field_arr[$field] = floatval($field_value);
                            } // bool型
                            elseif (in_array($field, ['is_best', 'is_new', 'is_hot', 'is_on_sale', 'is_alone_sale', 'is_real'])) {
                                $field_arr[$field] = intval($field_value) > 0 ? 1 : 0;
                            }
                        }

                        if ($field === 'is_real') {
                            $field_arr[$field] = intval($_POST['goods_class'][$key]);
                        }
                    }

                    if (empty($field_arr['goods_sn'])) {
                        $field_arr['goods_sn'] = GoodsHelper::generate_goods_sn($max_id);
                    }

                    // 如果是虚拟商品，库存为0
                    if ($field_arr['is_real'] === 0) {
                        $field_arr['goods_number'] = 0;
                    }
                    $new_goods_id = DB::table('goods')->insertGetId($field_arr);

                    $max_id = $new_goods_id + 1;

                    // 如果图片不为空,修改商品图片，插入商品相册
                    if (! empty($field_arr['original_img']) || ! empty($field_arr['goods_img']) || ! empty($field_arr['goods_thumb'])) {
                        $goods_img = '';
                        $goods_thumb = '';
                        $original_img = '';
                        $goods_gallery = [];
                        $goods_gallery['goods_id'] = $new_goods_id;

                        if (! empty($field_arr['original_img'])) {
                            // 设置商品相册原图和商品相册图
                            if (cfg('auto_generate_gallery')) {
                                $ext = substr($field_arr['original_img'], strrpos($field_arr['original_img'], '.'));
                                $img = dirname($field_arr['original_img']).'/'.$image->random_filename().$ext;
                                $gallery_img = dirname($field_arr['original_img']).'/'.$image->random_filename().$ext;
                                @copy(ROOT_PATH.$field_arr['original_img'], ROOT_PATH.$img);
                                @copy(ROOT_PATH.$field_arr['original_img'], ROOT_PATH.$gallery_img);
                                $goods_gallery['img_original'] = GoodsHelper::reformat_image_name('gallery', $goods_gallery['goods_id'], $img, 'source');
                            }
                            // 设置商品原图
                            if (cfg('retain_original_img')) {
                                $original_img = GoodsHelper::reformat_image_name('goods', $goods_gallery['goods_id'], $field_arr['original_img'], 'source');
                            } else {
                                @unlink(ROOT_PATH.$field_arr['original_img']);
                            }
                        }

                        if (! empty($field_arr['goods_img'])) {
                            // 设置商品相册图
                            if (cfg('auto_generate_gallery') && ! empty($gallery_img)) {
                                $goods_gallery['img_url'] = GoodsHelper::reformat_image_name('gallery', $goods_gallery['goods_id'], $gallery_img, 'goods');
                            }
                            // 设置商品图
                            $goods_img = GoodsHelper::reformat_image_name('goods', $goods_gallery['goods_id'], $field_arr['goods_img'], 'goods');
                        }

                        if (! empty($field_arr['goods_thumb'])) {
                            // 设置商品相册缩略图
                            if (cfg('auto_generate_gallery')) {
                                $ext = substr($field_arr['goods_thumb'], strrpos($field_arr['goods_thumb'], '.'));
                                $gallery_thumb = dirname($field_arr['goods_thumb']).'/'.$image->random_filename().$ext;
                                @copy(ROOT_PATH.$field_arr['goods_thumb'], ROOT_PATH.$gallery_thumb);
                                $goods_gallery['thumb_url'] = GoodsHelper::reformat_image_name('gallery_thumb', $goods_gallery['goods_id'], $gallery_thumb, 'thumb');
                            }
                            // 设置商品缩略图
                            $goods_thumb = GoodsHelper::reformat_image_name('goods_thumb', $goods_gallery['goods_id'], $field_arr['goods_thumb'], 'thumb');
                        }

                        // 修改商品图
                        DB::table('goods')->where('goods_id', $goods_gallery['goods_id'])->update([
                            'goods_img' => $goods_img,
                            'goods_thumb' => $goods_thumb,
                            'original_img' => $original_img,
                        ]);

                        // 添加商品相册图
                        if (cfg('auto_generate_gallery')) {
                            DB::table('goods_gallery')->insert($goods_gallery);
                        }

                    }
                }
            }

            // 记录日志
            $this->admin_log('', 'batch_upload', 'goods');

            $link[] = ['href' => 'goods.php?act=list', 'text' => lang('01_goods_list')];

            return $this->sys_msg(lang('batch_upload_ok'), 0, $link);
        }

        /**
         * 批量修改：选择商品
         */
        if ($action === 'select') {
            $this->admin_priv('goods_batch');

            // 取得分类列表
            $this->assign('cat_list', CommonHelper::cat_list());

            // 取得品牌列表
            $this->assign('brand_list', CommonHelper::get_brand_list());

            // 参数赋值
            $ur_here = lang('15_batch_edit');
            $this->assign('ur_here', $ur_here);

            return $this->display('goods_batch_select');
        }

        /**
         * 批量修改：修改
         */
        if ($action === 'edit') {
            $this->admin_priv('goods_batch');

            // 取得商品列表
            if ($_POST['select_method'] === 'cat') {
                $goods_ids = $_POST['goods_ids'];
            } else {
                $goods_sns = explode(',', str_replace("\n", ',', str_replace("\r", '', $_POST['sn_list'])));
                $goods_ids = DB::table('goods')->whereIn('goods_sn', $goods_sns)->distinct()->pluck('goods_id')->toArray();
            }
            $goods_list = DB::table('goods')
                ->whereIn('goods_id', $goods_ids)
                ->distinct()
                ->select('goods_id', 'goods_sn', 'goods_name', 'market_price', 'shop_price', 'goods_number', 'integral', 'give_integral', 'brand_id', 'is_real')
                ->get();
            $goods_list = array_map(function ($item) {
                return (array) $item;
            }, $goods_list->toArray());
            $this->assign('goods_list', $goods_list);

            // 取编辑商品的货品列表
            $product_exists = false;
            $product_list = DB::table('goods_product')
                ->whereIn('goods_id', $goods_ids)
                ->get();
            $product_list = array_map(function ($item) {
                return (array) $item;
            }, $product_list->toArray());

            if (! empty($product_list)) {
                $product_exists = true;
                $_product_list = [];
                foreach ($product_list as $value) {
                    $goods_attr = GoodsHelper::product_goods_attr_list($value['goods_id']);
                    $_goods_attr_array = explode('|', $value['goods_attr']);
                    if (is_array($_goods_attr_array)) {
                        $_temp = [];
                        foreach ($_goods_attr_array as $_goods_attr_value) {
                            if (! $_goods_attr_value) {
                                continue;
                            }
                            $_temp[] = $goods_attr[$_goods_attr_value];
                        }
                        $value['goods_attr'] = implode('，', $_temp);
                    }

                    $_product_list[$value['goods_id']][] = $value;
                }
                $this->assign('product_list', $_product_list);

                // 释放资源
                unset($product_list, $sql, $_product_list);
            }

            $this->assign('product_exists', $product_exists);

            // 取得会员价格
            $member_price_list = [];
            $res = DB::table('goods_member_price')
                ->whereIn('goods_id', $goods_ids)
                ->distinct()
                ->select('goods_id', 'user_rank', 'user_price')
                ->get();
            foreach ($res as $row) {
                $row = (array) $row;
                $member_price_list[$row['goods_id']][$row['user_rank']] = $row['user_price'];
            }
            $this->assign('member_price_list', $member_price_list);

            // 取得会员等级
            $rank_list = DB::table('user_rank')
                ->orderBy('discount', 'DESC')
                ->select('rank_id', 'rank_name', 'discount')
                ->get();
            $rank_list = array_map(function ($item) {
                return (array) $item;
            }, $rank_list->toArray());
            $this->assign('rank_list', $rank_list);

            // 取得品牌列表
            $this->assign('brand_list', CommonHelper::get_brand_list());

            // 赋值编辑方式
            $this->assign('edit_method', $_POST['edit_method']);

            // 参数赋值
            $ur_here = lang('15_batch_edit');
            $this->assign('ur_here', $ur_here);

            return $this->display('goods_batch_edit');
        }

        /**
         * 批量修改：提交
         */
        if ($action === 'update') {
            $this->admin_priv('goods_batch');

            if ($_POST['edit_method'] === 'each') {
                // 循环更新每个商品
                if (! empty($_POST['goods_id'])) {
                    foreach ($_POST['goods_id'] as $goods_id) {
                        // 如果存在货品则处理货品
                        if (! empty($_POST['product_number'][$goods_id])) {
                            $_POST['goods_number'][$goods_id] = 0;
                            foreach ($_POST['product_number'][$goods_id] as $key => $value) {
                                DB::table('goods_product')
                                    ->where('goods_id', $goods_id)
                                    ->where('product_id', $key)
                                    ->update(['product_number' => $value]);

                                $_POST['goods_number'][$goods_id] += $value;
                            }

                        }

                        // 更新商品
                        DB::table('goods')->where('goods_id', $goods_id)->update([
                            'market_price' => floatval($_POST['market_price'][$goods_id]),
                            'shop_price' => floatval($_POST['shop_price'][$goods_id]),
                            'integral' => intval($_POST['integral'][$goods_id]),
                            'give_integral' => intval($_POST['give_integral'][$goods_id]),
                            'goods_number' => intval($_POST['goods_number'][$goods_id]),
                            'brand_id' => intval($_POST['brand_id'][$goods_id]),
                            'last_update' => TimeHelper::gmtime(),
                        ]);

                        // 更新会员价格
                        if (! empty($_POST['rank_id'])) {
                            foreach ($_POST['rank_id'] as $rank_id) {
                                if (trim($_POST['member_price'][$goods_id][$rank_id]) === '') {
                                    // 为空时不做处理
                                    continue;
                                }

                                $rank_data = [
                                    'goods_id' => $goods_id,
                                    'user_rank' => $rank_id,
                                    'user_price' => floatval($_POST['member_price'][$goods_id][$rank_id]),
                                ];

                                if (DB::table('goods_member_price')->where('goods_id', $goods_id)->where('user_rank', $rank_id)->exists()) {
                                    if ($rank_data['user_price'] < 0) {
                                        DB::table('goods_member_price')->where('goods_id', $goods_id)->where('user_rank', $rank_id)->delete();
                                    } else {
                                        DB::table('goods_member_price')->where('goods_id', $goods_id)->where('user_rank', $rank_id)->update(['user_price' => $rank_data['user_price']]);
                                    }
                                } else {
                                    if ($rank_data['user_price'] >= 0) {
                                        DB::table('goods_member_price')->insert($rank_data);
                                    }
                                }

                            }
                        }
                    }
                }
            } else {
                // 循环更新每个商品
                if (! empty($_POST['goods_id'])) {
                    foreach ($_POST['goods_id'] as $goods_id) {
                        // 更新商品
                        $goods = [];
                        if ($_POST['market_price'] !== '') {
                            $goods['market_price'] = floatval($_POST['market_price']);
                        }
                        if (trim($_POST['shop_price']) != '') {
                            $goods['shop_price'] = floatval($_POST['shop_price']);
                        }
                        if (trim($_POST['integral']) != '') {
                            $goods['integral'] = intval($_POST['integral']);
                        }
                        if (trim($_POST['give_integral']) != '') {
                            $goods['give_integral'] = intval($_POST['give_integral']);
                        }
                        if (trim($_POST['goods_number']) != '') {
                            $goods['goods_number'] = intval($_POST['goods_number']);
                        }
                        if ($_POST['brand_id'] > 0) {
                            $goods['brand_id'] = $_POST['brand_id'];
                        }
                        if (! empty($goods)) {
                            $goods['last_update'] = TimeHelper::gmtime();
                            DB::table('goods')->where('goods_id', $goods_id)->update($goods);
                        }

                        // 更新会员价格
                        if (! empty($_POST['rank_id'])) {
                            foreach ($_POST['rank_id'] as $rank_id) {
                                if (trim($_POST['member_price'][$rank_id]) != '') {
                                    $user_price = floatval($_POST['member_price'][$rank_id]);

                                    if (DB::table('goods_member_price')->where('goods_id', $goods_id)->where('user_rank', $rank_id)->exists()) {
                                        if ($user_price < 0) {
                                            DB::table('goods_member_price')->where('goods_id', $goods_id)->where('user_rank', $rank_id)->delete();
                                        } else {
                                            DB::table('goods_member_price')->where('goods_id', $goods_id)->where('user_rank', $rank_id)->update(['user_price' => $user_price]);
                                        }
                                    } else {
                                        if ($user_price >= 0) {
                                            DB::table('goods_member_price')->insert([
                                                'goods_id' => $goods_id,
                                                'user_rank' => $rank_id,
                                                'user_price' => $user_price,
                                            ]);
                                        }
                                    }
                                }

                            }
                        }
                    }
                }
            }

            // 记录日志
            $this->admin_log('', 'batch_edit', 'goods');

            // 提示成功
            $link[] = ['href' => 'goods_batch.php?act=select', 'text' => lang('15_batch_edit')];

            return $this->sys_msg(lang('batch_edit_ok'), 0, $link);
        }

        /**
         * 下载文件
         */
        if ($action === 'download') {
            $this->admin_priv('goods_batch');

            // 文件标签
            header('Content-type: application/vnd.ms-excel; charset=utf-8');
            header('Content-Disposition: attachment; filename=goods_list.csv');

            // 下载
            if ($_GET['charset'] != cfg('lang')) {
                lang([dirname(__DIR__).'/Languages/zh-CN/goods_batch.php']);
            }
            if (lang('upload_goods')) {
                // 创建字符集转换对象
                if ($_GET['charset'] === 'zh_cn' || $_GET['charset'] === 'zh_tw') {
                    $to_charset = $_GET['charset'] === 'zh_cn' ? 'GB2312' : 'BIG5';
                    echo BaseHelper::ecs_iconv(EC_CHARSET, $to_charset, implode(',', lang('upload_goods')));
                } else {
                    echo implode(',', lang('upload_goods'));
                }
            } else {
                echo 'error: $_LANG[upload_goods] not exists';
            }
        }

        /**
         * 取得商品
         */
        if ($action === 'get_goods') {
            $filter = new \stdClass;

            $filter->cat_id = intval($_GET['cat_id']);
            $filter->brand_id = intval($_GET['brand_id']);
            $filter->real_goods = -1;
            $arr = MainHelper::get_goods_list($filter);

            return $this->make_json_result(json_encode($arr));
        }
    }
}
