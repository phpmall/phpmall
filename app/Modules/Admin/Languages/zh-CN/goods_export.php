<?php

$_LANG['user_guide'] =
    '<br/>使用说明：'.
    '<ol>'.
    '<li>使用搜索条件每次只允许搜索符合条件的50个商品。</li>'.
    '<li>如果用户需要导出某分类下的所有的商品，在条件中选择分类后，不需点击搜索，直接选择数据格式和编码导出即可。</li>'.
    '</ol>';
$_LANG['good_cat'] = '商品分类';
$_LANG['select_please'] = '请选择要导出的分类';
$_LANG['select_charset'] = '请选择要导出的编码';

$_LANG['goods_class'] = '宝贝栏目ID';
$_LANG['tabobao_shipping'] = '淘宝配送';
$_LANG['post_express'] = '平邮价格';
$_LANG['express'] = '快递价格';
$_LANG['ems'] = 'EMS价格';
$_LANG['notice_goods_class'] = '宝贝栏目ID为淘宝分类的ID，如若不确定，请填写0';

$_LANG['post_express_not_null'] = '平邮价格必须大于0';
$_LANG['express_not_null'] = '快递价格必须大于0';
$_LANG['ems_not_null'] = 'EMS价格必须大于0';

// 批量上传商品的字段
$_LANG['export_phpmall'] = '导出到PHPMall数据格式';
$_LANG['phpmall']['goods_name'] = '商品名称';
$_LANG['phpmall']['goods_sn'] = '商品货号';
$_LANG['phpmall']['brand_name'] = '商品品牌';   // 需要转换成brand_id
$_LANG['phpmall']['market_price'] = '市场售价';
$_LANG['phpmall']['shop_price'] = '本店售价';
$_LANG['phpmall']['integral'] = '积分购买额度';
$_LANG['phpmall']['original_img'] = '商品原始图';
$_LANG['phpmall']['goods_img'] = '商品图片';
$_LANG['phpmall']['goods_thumb'] = '商品缩略图';
$_LANG['phpmall']['keywords'] = '商品关键词';
$_LANG['phpmall']['goods_brief'] = '简单描述';
$_LANG['phpmall']['goods_desc'] = '详细描述';
$_LANG['phpmall']['goods_weight'] = '商品重量（kg）';
$_LANG['phpmall']['goods_number'] = '库存数量';
$_LANG['phpmall']['warn_number'] = '库存警告数量';
$_LANG['phpmall']['is_best'] = '是否精品';
$_LANG['phpmall']['is_new'] = '是否新品';
$_LANG['phpmall']['is_hot'] = '是否热销';
$_LANG['phpmall']['is_on_sale'] = '是否上架';
$_LANG['phpmall']['is_alone_sale'] = '能否作为普通商品销售';
$_LANG['phpmall']['is_real'] = '是否实体商品';

// 自定义导出数据格式
$_LANG['export_custom'] = '导出到自定义数据格式';
$_LANG['custom']['goods_name'] = '商品名称';
$_LANG['custom']['goods_sn'] = '商品货号';
$_LANG['custom']['brand_name'] = '商品品牌';
$_LANG['custom']['market_price'] = '市场售价';
$_LANG['custom']['shop_price'] = '本店售价';
$_LANG['custom']['integral'] = '积分购买额度';
$_LANG['custom']['original_img'] = '商品原始图';
$_LANG['custom']['goods_img'] = '商品图片';
$_LANG['custom']['goods_thumb'] = '商品缩略图';
$_LANG['custom']['keywords'] = '商品关键词';
$_LANG['custom']['goods_brief'] = '简单描述';
$_LANG['custom']['goods_desc'] = '详细描述';
$_LANG['custom']['goods_weight'] = '商品重量（kg）';
$_LANG['custom']['goods_number'] = '库存数量';
$_LANG['custom']['warn_number'] = '库存警告数量';
$_LANG['custom']['is_best'] = '是否精品';
$_LANG['custom']['is_new'] = '是否新品';
$_LANG['custom']['is_hot'] = '是否热销';
$_LANG['custom']['is_on_sale'] = '是否上架';
$_LANG['custom']['is_alone_sale'] = '能否作为普通商品销售';
$_LANG['custom']['is_real'] = '是否实体商品';

$_LANG['custom_keyword'] = '关键字';
$_LANG['custom_goods_cat'] = '所有分类';
$_LANG['custom_goods_brand'] = '所有品牌';
$_LANG['custom_goods_list'] = '选择商品数据列';
$_LANG['custom_goods_type'] = '所有商品类型';
$_LANG['custom_export_list'] = '输出商品数据列';
$_LANG['custom_up'] = '上';
$_LANG['custom_down'] = '下';
$_LANG['custom_goods_search'] = '导出条件';
$_LANG['custom_goods_field_not_null'] = '输出的商品数据列不能为空';

// 导出条件
$_LANG['export_condition'] = '商品数据批量导出';
$_LANG['export_condition_search'] = '搜 索';
$_LANG['export_format'] = '数据格式';

return $_LANG;
