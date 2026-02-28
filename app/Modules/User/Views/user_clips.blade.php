<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="Keywords" content="{{ $keywords }}"/>
    <meta name="Description" content="{{ $description }}"/>
    <title>{{ $page_title }}</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}"/>
    <link href="{{ $ecs_css_path }}" rel="stylesheet" type="text/css"/>

    <script type="text/javascript" src="{{ asset('js/transport.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/common.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/user.js') }}"></script>
</head>
<body>
@include('web::library/page_header')
<!--当前位置 start-->
<div class="block box">
    <div id="ur_here">
        @include('web::library/ur_here')
    </div>
</div>
<!--当前位置 end-->
<div class="blank"></div>
<div class="block clearfix">
    <!--left start-->
    <div class="AreaL">
        <div class="box">
            <div class="box_1">
                <div class="userCenterBox">
                    @include('web::library/user_menu')
                </div>
            </div>
        </div>
    </div>
    <!--left end-->
    <!--right start-->
    <div class="AreaR">
        <div class="box">
            <div class="box_1">
                <div class="userCenterBox boxCenterList clearfix" style="_height:1%;">
                    <!-- *用户中心默认显示页面 start-->
                    @if($action == 'default')
                        <font class="f5"><b
                                class="f4">{{ $info['username'] }}</b> {{ $lang['welcome_to'] }} {{ $info['shop_name'] }}
                            ！</font><br/>
                        <div class="blank"></div>
                        {{ $lang['last_time'] }}: {{ $info['last_time'] }}<br/>
                        <div class="blank5"></div>
                        {{ $rank_name }} {{ $next_rank_name }}<br/>
                        <div class="blank5"></div>
                        @if($info['is_validate'] == 0)
                            {{ $lang['not_validated'] }} <a href="javascript:sendHashMail()"
                                                            style="color:#006bd0;">{{ $lang['resend_hash_mail'] }}</a>
                            <br/>
                        @endif
                        <div
                            style="margin:5px 0; border:1px solid #a1675a;padding:10px 20px; background-color:#e8d1c9;">
                            <img src="images/note.gif" alt="note"/>&nbsp;{{ $user_notice }}
                        </div>
                        <br/><br/>
                        <div class="f_l" style="width:350px;">
                            <h5><span>{{ $lang['your_account'] }}</span></h5>
                            <div class="blank"></div>
                            {{ $lang['your_surplus'] }}:<a href="user.php?act=account_log"
                                                           style="color:#006bd0;">{{ $info['surplus'] }}</a><br/>
                            <!-- @if($info['credit_line'] > 0)
                                如果有信用额度 -->
                                {{ $lang['credit_line'] }}:{{ $info['formated_credit_line'] }}<br/>
                            @endif
                            {{ $lang['your_bonus'] }}:<a href="user.php?act=bonus"
                                                         style="color:#006bd0;">{{ $info['bonus'] }}</a><br/>
                            {{ $lang['your_integral'] }}:{{ $info['integral'] }}<br/>
                        </div>
                        <div class="f_r" style="width:350px;">
                            <h5><span>{{ $lang['your_notice'] }}</span></h5>
                            <div class="blank"></div>
                            @foreach($prompt as $item)
                                {{ $item['text'] }}<br/>
                            @endforeach
                            {{ $lang['last_month_order'] }}{{ $info['order_count'] }}{{ $lang['order_unit'] }}<br/>
                            @if($info['shipped_order'])
                                {{ $lang['please_received'] }}<br/>
                                @foreach($info['shipped_order'] as $item)
                                    <a href="user.php?act=order_detail&order_id={{ $item['order_id'] }}"
                                       style="color:#006bd0;">{{ $item['order_sn'] }}</a>
                                @endforeach
                            @endif
                        </div>
                    @endif
                    <!-- #用户中心默认显示页面 end-->
                    <!-- *我的留言 start-->
                    @if($action == 'message_list')
                        <h5><span>{{ $lang['label_message'] }}</span></h5>
                        <div class="blank"></div>
                        @foreach($message_list as $message => $key)
                            <div class="f_l">
                                <b>{{ $message['msg_type'] }}:</b>&nbsp;&nbsp;<font
                                    class="f4">{{ $message['msg_title'] }}</font> ({{ $message['msg_time'] }})
                            </div>
                            <div class="f_r">
                                <a href="user.php?act=del_msg&amp;id={{ $key }}&amp;order_id={{ $message['order_id'] }}"
                                   title="{{ $lang['drop'] }}"
                                   onclick="if (!confirm('{{ $lang['confirm_remove_msg'] }}')) return false;"
                                   class="f6">{{ $lang['drop'] }}</a>
                            </div>
                            <div class="msgBottomBorder">
                                {{ $message['msg_content'] }}
                                <!-- @if($message['message_img'])
                                    如果上传了图片-->
                                    <div align="right">
                                        <a href="data/feedbackimg/{{ $message['message_img'] }}" target="_bank"
                                           class="f6">{{ $lang['view_upload_file'] }}</a>
                                    </div>
                                @endif
                                <br/>
                                @if($message['re_msg_content'])
                                    <a href="mailto:{{ $message['re_user_email'] }}"
                                       class="f6">{{ $lang['shopman_reply'] }}</a> ({{ $message['re_msg_time'] }})<br/>
                                    {{ $message['re_msg_content'] }}
                                @endif
                            </div>
                        @endforeach
                        @if($message_list)
                            <div class="f_r">
                                @include('web::library/pages')
                            </div>
                        @endif
                        <div class="blank"></div>
                        <form action="user.php" method="post" enctype="multipart/form-data" name="formMsg"
                              onSubmit="return submitMsg()">
                            <table width="100%" border="0" cellpadding="3">
                                @if($order_info)
                                    <tr>
                                        <td align="right">{{ $lang['order_number'] }}</td>
                                        <td>
                                            <a href="{{ $order_info['url'] }}"><img
                                                    src="images/note.gif"/>{{ $order_info['order_sn'] }}</a>
                                            <input name="msg_type" type="hidden" value="5"/>
                                            <input name="order_id" type="hidden" value="{{ $order_info['order_id'] }}"
                                                   class="inputBg"/>
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td align="right">{{ $lang['message_type'] }}：</td>
                                        <td><input name="msg_type" type="radio" value="0" checked="checked"/>
                                            {$lang.type[0]}
                                            <input type="radio" name="msg_type" value="1"/>
                                            {$lang.type[1]}
                                            <input type="radio" name="msg_type" value="2"/>
                                            {$lang.type[2]}
                                            <input type="radio" name="msg_type" value="3"/>
                                            {$lang.type[3]}
                                            <input type="radio" name="msg_type" value="4"/>
                                            {$lang.type[4]}
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td align="right">{{ $lang['message_title'] }}：</td>
                                    <td><input name="msg_title" type="text" size="30" class="inputBg"/></td>
                                </tr>
                                <tr>
                                    <td align="right" valign="top">{{ $lang['message_content'] }}：</td>
                                    <td><textarea name="msg_content" cols="50" rows="4" wrap="virtual"
                                                  class="B_blue"></textarea></td>
                                </tr>
                                <tr>
                                    <td align="right">{{ $lang['upload_img'] }}：</td>
                                    <td><input type="file" name="message_img" size="45" class="inputBg"/></td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td><input type="hidden" name="act" value="act_add_message"/>
                                        <input type="submit" value="{{ $lang['submit'] }}" class="bnt_bonus"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>
                                        {{ $lang['img_type_tips'] }}<br/>
                                        {{ $lang['img_type_list'] }}
                                    </td>
                                </tr>
                            </table>
                        </form>
                    @endif
                    <!--#我的留言 end-->
                    <!-- *我的评论 start-->
                    @if($action == 'comment_list')
                        <h5><span>{{ $lang['label_comment'] }}</span></h5>
                        <div class="blank"></div>
                        @foreach($comment_list as $comment)
                            <div class="f_l">
                                <b>'.($comment['comment_type'] == '0' ? '{{ $lang['goods_comment'] }}' :
                                    '{{ $lang['article_comment'] }}').': </b><font
                                    class="f4">{{ $comment['cmt_name'] }}</font>&nbsp;&nbsp;({{ $comment['formated_add_time'] }}
                                )
                            </div>
                            <div class="f_r">
                                <a href="user.php?act=del_cmt&amp;id={{ $comment['comment_id'] }}"
                                   title="{{ $lang['drop'] }}"
                                   onclick="if (!confirm('{{ $lang['confirm_remove_msg'] }}')) return false;"
                                   class="f6">{{ $lang['drop'] }}</a>
                            </div>
                            <div class="msgBottomBorder">
                                {{ $comment['content'] }}<br/>
                                @if($comment['reply_content'])
                                    <b>{{ $lang['reply_comment'] }}：</b><br/>
                                    {{ $comment['reply_content'] }}
                                @endif
                            </div>
                        @endforeach
                        @if($comment_list)
                            @include('web::library/pages')
                        @else
                            {{ $lang['no_comments'] }}
                        @endif
                    @endif
                    <!--#我的评论 end-->
                    <!--#我的标签 start-->
                    @if($action == 'tag_list')
                        <h5><span>{{ $lang['label_tag'] }}</span></h5>
                        <div class="blank"></div>
                        @if($tags)
                            <!-- 标签云开始 @foreach($tags as $tag)-->
                            <a href="search.php?keywords={{ urlencode($tag['tag_words']) }}"
                               class="f6">{{ $tag['tag_words'] }}</a> <a
                                href="user.php?act=act_del_tag&amp;tag_words={{ urlencode($tag['tag_words']) }}"
                                onclick="if (!confirm('{{ $lang['confirm_drop_tag'] }}')) return false;"
                                title="{{ $lang['drop'] }}"><img src="images/drop.gif" alt="{{ $lang['drop'] }}"/></a>
                            &nbsp;&nbsp;
                            <!-- 标签云结束 @endforeach-->
                            @else
                                <span
                                    style="margin:2px 10px; font-size:14px; line-height:36px;">{{ $lang['no_tag'] }}</span>
                            @endif
                            @endif
                            <!--#我的标签 end-->
                            <!--*收藏商品列表页面 start-->
                            @if($action == 'collection_list')
                                <script type="text/javascript" src="{{ asset('js/transport.js') }}"></script>
                                <script type="text/javascript" src="{{ asset('js/utils.js') }}"></script>
                                <h5><span>{{ $lang['label_collection'] }}</span></h5>
                                <div class="blank"></div>
                                <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
                                    <tr align="center">
                                        <th width="35%" bgcolor="#ffffff">{{ $lang['goods_name'] }}</th>
                                        <th width="30%" bgcolor="#ffffff">{{ $lang['price'] }}</th>
                                        <th width="35%" bgcolor="#ffffff">{{ $lang['handle'] }}</th>
                                    </tr>
                                    @foreach($goods_list as $goods)
                                        <tr>
                                            <td bgcolor="#ffffff"><a href="{{ $goods['url'] }}"
                                                                     class="f6">{{ $goods['goods_name'] }}</a></td>
                                            <td bgcolor="#ffffff">@if($goods['promote_price'] != "")
                                                    {{ $lang['promote_price'] }}<span
                                                        class="goods-price">{{ $goods['promote_price'] }}</span>
                                                @else
                                                    {{ $lang['shop_price'] }}<span
                                                        class="goods-price">{{ $goods['shop_price'] }}</span>
                                                @endif        </td>
                                            <td align="center" bgcolor="#ffffff">
                                                @if($goods['is_attention'])
                                                    <a href="javascript:if (confirm('{{ $lang['del_attention'] }}')) location.href='user.php?act=del_attention&rec_id={{ $goods['rec_id'] }}'"
                                                       class="f6">{{ $lang['no_attention'] }}</a>
                                                @else
                                                    <a href="javascript:if (confirm('{{ $lang['add_to_attention'] }}')) location.href='user.php?act=add_to_attention&rec_id={{ $goods['rec_id'] }}'"
                                                       class="f6">{{ $lang['attention'] }}</a>
                                                @endif
                                                <a href="javascript:addToCart({{ $goods['goods_id'] }})"
                                                   class="f6">{{ $lang['add_to_cart'] }}</a> <a
                                                    href="javascript:if (confirm('{{ $lang['remove_collection_confirm'] }}')) location.href='user.php?act=delete_collection&collection_id={{ $goods['rec_id'] }}'"
                                                    class="f6">{{ $lang['drop'] }}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                                @include('web::library/pages')
                                <div class="blank5"></div>

                                <h5><span>{{ $lang['label_affiliate'] }}</span></h5>
                                <div class="blank"></div>
                                <form name="theForm" method="post" action="">
                                    <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
                                        <tr>
                                            <td align="right" bgcolor="#ffffff">{{ $lang['label_need_image'] }}</td>
                                            <td bgcolor="#ffffff">
                                                <select name="need_image" id="need_image" class="inputBg">
                                                    <option value="true" selected>{{ $lang['need'] }}</option>
                                                    <option value="false">{{ $lang['need_not'] }}</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="right" bgcolor="#ffffff">{{ $lang['label_goods_num'] }}</td>
                                            <td bgcolor="#ffffff"><input name="goods_num" type="text" id="goods_num"
                                                                         value="6" class="inputBg"/></td>
                                        </tr>
                                        <tr>
                                            <td align="right" bgcolor="#ffffff">{{ $lang['label_arrange'] }}</td>
                                            <td bgcolor="#ffffff"><select name="arrange" id="arrange" class="inputBg">
                                                    <option value="h" selected>{{ $lang['horizontal'] }}</option>
                                                    <option value="v">{{ $lang['verticle'] }}</option>
                                                </select></td>
                                        </tr>
                                        <tr>
                                            <td align="right" bgcolor="#ffffff">{{ $lang['label_rows_num'] }}</td>
                                            <td bgcolor="#ffffff"><input name="rows_num" type="text" id="rows_num"
                                                                         value="1" class="inputBg"/></td>
                                        </tr>
                                        <tr>
                                            <td align="right" bgcolor="#ffffff">{{ $lang['label_charset'] }}</td>
                                            <td bgcolor="#ffffff"><select name="charset" id="charset">
                                                    {html_options options=$lang_list}
                                                </select></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" align="center" bgcolor="#ffffff"><input type="button"
                                                                                                    name="gen_code"
                                                                                                    value="{{ $lang['generate'] }}"
                                                                                                    onclick="genCode()"
                                                                                                    class="bnt_blue_1"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" align="center" bgcolor="#ffffff"><textarea name="code"
                                                                                                       cols="80"
                                                                                                       rows="5"
                                                                                                       id="code"
                                                                                                       class="B_blue"></textarea>
                                            </td>
                                        </tr>
                                    </table>
                                </form>
                                <script type="text/javascript">
                                    var elements = document.forms['theForm'].elements;
                                    var url = '{{ $url }}';
                                    var u = '{{ $user_id }}';

                                    /**
                                     * 生成代码
                                     */
                                    function genCode() {
                                        // 检查输入
                                        if (isNaN(parseInt(elements['goods_num'].value))) {
                                            alert('{{ $lang['goods_num_must_be_int'] }}');
                                            return;
                                        }
                                        if (elements['goods_num'].value < 1) {
                                            alert('{{ $lang['goods_num_must_over_0'] }}');
                                            return;
                                        }
                                        if (isNaN(parseInt(elements['rows_num'].value))) {
                                            alert('{{ $lang['rows_num_must_be_int'] }}');
                                            return;
                                        }
                                        if (elements['rows_num'].value < 1) {
                                            alert('{{ $lang['rows_num_must_over_0'] }}');
                                            return;
                                        }

                                        // 生成代码
                                        var code = '\<script src=\"' + url + 'goods_script.php?';
                                        code += 'need_image=' + elements['need_image'].value + '&';
                                        code += 'goods_num=' + elements['goods_num'].value + '&';
                                        code += 'arrange=' + elements['arrange'].value + '&';
                                        code += 'rows_num=' + elements['rows_num'].value + '&';
                                        code += 'charset=' + elements['charset'].value + '&u=' + u;
                                        code += '\"\>\</script\>';
                                        elements['code'].value = code;
                                        elements['code'].select();
                                        if (Browser.isIE) {
                                            window.clipboardData.setData("Text", code);
                                        }
                                    }

                                    var compare_no_goods = "{{ $lang['compare_no_goods'] }}";
                                    var btn_buy = "{{ $lang['btn_buy'] }}";
                                    var is_cancel = "{{ $lang['is_cancel'] }}";
                                    var select_spe = "{{ $lang['select_spe'] }}";
                                </script>
                            @endif
                            <!--#收藏商品列表页面 end-->
                            <!--*缺货登记列表页面 start-->
                            @if($action == 'booking_list')
                                <h5><span>{{ $lang['label_booking'] }}</span></h5>
                                <div class="blank"></div>
                                <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
                                    <tr align="center">
                                        <td width="20%" bgcolor="#ffffff">{{ $lang['booking_goods_name'] }}</td>
                                        <td width="10%" bgcolor="#ffffff">{{ $lang['booking_amount'] }}</td>
                                        <td width="20%" bgcolor="#ffffff">{{ $lang['booking_time'] }}</td>
                                        <td width="35%" bgcolor="#ffffff">{{ $lang['process_desc'] }}</td>
                                        <td width="15%" bgcolor="#ffffff">{{ $lang['handle'] }}</td>
                                    </tr>
                                    @foreach($booking_list as $item)
                                        <tr>
                                            <td align="left" bgcolor="#ffffff"><a href="{{ $item['url'] }}"
                                                                                  target="_blank"
                                                                                  class="f6">{{ $item['goods_name'] }}</a>
                                            </td>
                                            <td align="center" bgcolor="#ffffff">{{ $item['goods_number'] }}</td>
                                            <td align="center" bgcolor="#ffffff">{{ $item['booking_time'] }}</td>
                                            <td align="left" bgcolor="#ffffff">{{ $item['dispose_note'] }}</td>
                                            <td align="center" bgcolor="#ffffff"><a
                                                    href="javascript:if (confirm('{{ $lang['confirm_remove_account'] }}')) location.href='user.php?act=act_del_booking&id={{ $item['rec_id'] }}'"
                                                    class="f6">{{ $lang['drop'] }}</a></td>
                                        </tr>
                                    @endforeach
                                </table>
                            @endif
                            <div class="blank5"></div>
                            <!--#缺货登记列表页面 -->
                            @if($action == 'add_booking')
                                <script type="text/javascript" src="{{ asset('js/utils.js') }}"></script>
                                <script type="text/javascript">
                                    @foreach($lang['booking_js'] as $item => $key)
                                    var {{ $key }} = "{{ $item }}";
                                    @endforeach
                                </script>
                                <h5><span>{{ $lang['add'] }}{{ $lang['label_booking'] }}</span></h5>
                                <div class="blank"></div>
                                <form action="user.php" method="post" name="formBooking"
                                      onsubmit="return addBooking();">
                                    <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
                                        <tr>
                                            <td align="right" bgcolor="#ffffff">{{ $lang['booking_goods_name'] }}</td>
                                            <td bgcolor="#ffffff">{{ $info['goods_name'] }}</td>
                                        </tr>
                                        <tr>
                                            <td align="right" bgcolor="#ffffff">{{ $lang['booking_amount'] }}:</td>
                                            <td bgcolor="#ffffff"><input name="number" type="text"
                                                                         value="{{ $info['goods_number'] }}"
                                                                         class="inputBg"/></td>
                                        </tr>
                                        <tr>
                                            <td align="right" bgcolor="#ffffff">{{ $lang['describe'] }}:</td>
                                            <td bgcolor="#ffffff"><textarea name="desc" cols="50" rows="5"
                                                                            wrap="virtual"
                                                                            class="B_blue">{{ $goods_attr }}{{ $info['goods_desc'] }}</textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="right" bgcolor="#ffffff">{{ $lang['contact_username'] }}:</td>
                                            <td bgcolor="#ffffff"><input name="linkman" type="text"
                                                                         value="{{ $info['consignee'] }}" size="25"
                                                                         class="inputBg"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="right" bgcolor="#ffffff">{{ $lang['email_address'] }}:</td>
                                            <td bgcolor="#ffffff"><input name="email" type="text"
                                                                         value="{{ $info['email'] }}" size="25"
                                                                         class="inputBg"/></td>
                                        </tr>
                                        <tr>
                                            <td align="right" bgcolor="#ffffff">{{ $lang['contact_phone'] }}:</td>
                                            <td bgcolor="#ffffff"><input name="tel" type="text"
                                                                         value="{{ $info['tel'] }}" size="25"
                                                                         class="inputBg"/></td>
                                        </tr>
                                        <tr>
                                            <td align="right" bgcolor="#ffffff">&nbsp;</td>
                                            <td bgcolor="#ffffff"><input name="act" type="hidden"
                                                                         value="act_add_booking"/>
                                                <input name="id" type="hidden" value="{{ $info['id'] }}"/>
                                                <input name="rec_id" type="hidden" value="{{ $info['rec_id'] }}"/>
                                                <input type="submit" name="submit" class="submit"
                                                       value="{{ $lang['submit_booking_goods'] }}"/>
                                                <input type="reset" name="reset" class="reset"
                                                       value="{{ $lang['button_reset'] }}"/>
                                            </td>
                                        </tr>
                                    </table>
                                </form>
                            @endif
                            <!-- *我的推荐 -->
                            @if($affiliate['on'] == 1)
                                @if($action == 'affiliate')
                                    @if(!$goodsid || $goodsid == 0)
                                        <h5><span>{{ $lang['affiliate_detail'] }}</span></h5>
                                        <div class="blank"></div>
                                        {{ $affiliate_intro }}
                                        @if($affiliate['config']['separate_by'] == 0)
                                            <!-- 下线人数、分成 -->
                                            <div class="blank"></div>
                                            <h5><span><a name="myrecommend">{{ $lang['affiliate_member'] }}</a></span>
                                            </h5>
                                            <div class="blank"></div>
                                            <table width="100%" border="0" cellpadding="5" cellspacing="1"
                                                   bgcolor="#dddddd">
                                                <tr align="center">
                                                    <td bgcolor="#ffffff">{{ $lang['affiliate_lever'] }}</td>
                                                    <td bgcolor="#ffffff">{{ $lang['affiliate_num'] }}</td>
                                                    <td bgcolor="#ffffff">{{ $lang['level_point'] }}</td>
                                                    <td bgcolor="#ffffff">{{ $lang['level_money'] }}</td>
                                                </tr>
                                                @foreach($affdb as $level => $val)
                                                    <tr align="center">
                                                        <td bgcolor="#ffffff">{{ $level }}</td>
                                                        <td bgcolor="#ffffff">{{ $val['num'] }}</td>
                                                        <td bgcolor="#ffffff">{{ $val['point'] }}</td>
                                                        <td bgcolor="#ffffff">{{ $val['money'] }}</td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                            <!-- /下线人数、分成 -->
                                        @else
                                            <!-- 介绍订单数、分成 -->
                                            <!-- /介绍订单数、分成 -->
                                        @endif
                                        <!-- 我的推荐清单 -->
                                        <div class="blank"></div>
                                        <h5><span>分成规则</span></h5>
                                        <div class="blank"></div>
                                        <table width="100%" border="0" cellpadding="5" cellspacing="1"
                                               bgcolor="#dddddd">
                                            <tr align="center">
                                                <td bgcolor="#ffffff">{{ $lang['order_number'] }}</td>
                                                <td bgcolor="#ffffff">{{ $lang['affiliate_money'] }}</td>
                                                <td bgcolor="#ffffff">{{ $lang['affiliate_point'] }}</td>
                                                <td bgcolor="#ffffff">{{ $lang['affiliate_mode'] }}</td>
                                                <td bgcolor="#ffffff">{{ $lang['affiliate_status'] }}</td>
                                            </tr>
                                            @forelse($logdb as $val)
                                                <tr align="center">
                                                    <td bgcolor="#ffffff">{{ $val['order_sn'] }}</td>
                                                    <td bgcolor="#ffffff">{{ $val['money'] }}</td>
                                                    <td bgcolor="#ffffff">{{ $val['point'] }}</td>
                                                    <td bgcolor="#ffffff">'.($val['separate_type'] == 1 ||
                                                        $val['separate_type'] === 0 ?
                                                        '{$lang.affiliate_type.$val.separate_type}' :
                                                        '{$lang.affiliate_type.$affiliate_type}').'
                                                    </td>
                                                    <td bgcolor="#ffffff">{$lang.affiliate_stats[$val.is_separate]}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" align="center"
                                                        bgcolor="#ffffff">{{ $lang['no_records'] }}</td>
                                                </tr>
                                            @endforelse
                                            @if($logdb)
                                                <tr>
                                                    <td colspan="5" bgcolor="#ffffff">
                                                        <form action="{{ $smarty['server']['PHP_SELF'] }}" method="get">
                                                            <div
                                                                id="pager"> {{ $lang['pager_1'] }}{{ $pager['record_count'] }}{{ $lang['pager_2'] }}{{ $lang['pager_3'] }}{{ $pager['page_count'] }}{{ $lang['pager_4'] }}
                                                                <span> <a
                                                                        href="{{ $pager['page_first'] }}">{{ $lang['page_first'] }}</a> <a
                                                                        href="{{ $pager['page_prev'] }}">{{ $lang['page_prev'] }}</a> <a
                                                                        href="{{ $pager['page_next'] }}">{{ $lang['page_next'] }}</a> <a
                                                                        href="{{ $pager['page_last'] }}">{{ $lang['page_last'] }}</a> </span>
                                                                <select name="page" id="page"
                                                                        onchange="selectPage(this)">
                                                                    {html_options options=$pager.array
                                                                    selected=$pager.page}
                                                                </select>
                                                                <input type="hidden" name="act" value="affiliate"/>
                                                            </div>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endif
                                        </table>
                                        <script type="text/javascript">
                                            <!--

function selectPage(sel) {
    sel.form.submit();
}


                                        </script>
                                        <!-- /我的推荐清单 -->
                                        <div class="blank"></div>
                                        <h5><span>{{ $lang['affiliate_code'] }}</span></h5>
                                        <div class="blank"></div>
                                        <table width="100%" border="0" cellpadding="5" cellspacing="1"
                                               bgcolor="#dddddd">
                                            <tr>
                                                <td width="30%" bgcolor="#ffffff"><a
                                                        href="{{ $shopurl }}?u={{ $userid }}" target="_blank"
                                                        class="f6">{{ $shopname }}</a></td>
                                                <td bgcolor="#ffffff"><input size="40" onclick="this.select();"
                                                                             type="text"
                                                                             value="&lt;a href=&quot;{{ $shopurl }}?u={{ $userid }}&quot; target=&quot;_blank&quot;&gt;{{ $shopname }}&lt;/a&gt;"
                                                                             style="border:1px solid #ccc;"/> {{ $lang['recommend_webcode'] }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td bgcolor="#ffffff"><a href="{{ $shopurl }}?u={{ $userid }}"
                                                                         target="_blank" title="{{ $shopname }}"
                                                                         class="f6"><img
                                                            src="{{ $shopurl }}{{ $logosrc }}"/></a></td>
                                                <td bgcolor="#ffffff"><input size="40" onclick="this.select();"
                                                                             type="text"
                                                                             value="&lt;a href=&quot;{{ $shopurl }}?u={{ $userid }}&quot; target=&quot;_blank&quot; title=&quot;{{ $shopname }}&quot;&gt;&lt;img src=&quot;{{ $shopurl }}{{ $logosrc }}&quot; /&gt;&lt;/a&gt;"
                                                                             style="border:1px solid #ccc;"/> {{ $lang['recommend_webcode'] }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td bgcolor="#ffffff"><a href="{{ $shopurl }}?u={{ $userid }}"
                                                                         target="_blank" class="f6">{{ $shopname }}</a>
                                                </td>
                                                <td bgcolor="#ffffff"><input size="40" onclick="this.select();"
                                                                             type="text"
                                                                             value="[url={{ $shopurl }}?u={{ $userid }}]{{ $shopname }}[/url]"
                                                                             style="border:1px solid #ccc;"/> {{ $lang['recommend_bbscode'] }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td bgcolor="#ffffff"><a href="{{ $shopurl }}?u={{ $userid }}"
                                                                         target="_blank" title="{{ $shopname }}"
                                                                         class="f6"><img
                                                            src="{{ $shopurl }}{{ $logosrc }}"/></a></td>
                                                <td bgcolor="#ffffff"><input size="40" onclick="this.select();"
                                                                             type="text"
                                                                             value="[url={{ $shopurl }}?u={{ $userid }}][img]{{ $shopurl }}{{ $logosrc }}[/img][/url]"
                                                                             style="border:1px solid #ccc;"/> {{ $lang['recommend_bbscode'] }}
                                                </td>
                                            </tr>
                                        </table>

                                    @else
                                        <!-- 单个商品推荐 -->
                                        <style type="text/css">
                                            .types a {
                                                text-decoration: none;
                                                color: #006bd0;
                                            }
                                        </style>
                                        <h5><span>{{ $lang['affiliate_code'] }}</span></h5>
                                        <div class="blank"></div>
                                        <table width="100%" border="0" cellpadding="5" cellspacing="1"
                                               bgcolor="#dddddd">
                                            <tr align="center">
                                                <td bgcolor="#ffffff">{{ $lang['affiliate_view'] }}</td>
                                                <td bgcolor="#ffffff">{{ $lang['affiliate_code'] }}</td>
                                            </tr>
                                            @foreach($types as $val)
                                                <tr align="center">
                                                    <td bgcolor="#ffffff" class="types">
                                                        <script
                                                            src="{{ $shopurl }}affiliate.php?charset={{ $ecs_charset }}&gid={{ $goodsid }}&u={{ $userid }}&type={{ $val }}"></script>
                                                    </td>
                                                    <td bgcolor="#ffffff">javascript {{ $lang['affiliate_codetype'] }}
                                                        <br>
                                                        <textarea cols=30 rows=2
                                                                  id="txt{{ $smarty['foreach']['types']['iteration'] }}"
                                                                  style="border:1px solid #ccc;"><script src="{{ $shopurl }}affiliate.php?charset={{ $ecs_charset }}&gid={{ $goodsid }}&u={{ $userid }}&type={{ $val }}"></script></textarea>[<a
                                                            href="#" title="Copy To Clipboard"
                                                            onClick="Javascript:copyToClipboard(document.getElementById('txt{{ $smarty['foreach']['types']['iteration'] }}').value);alert('{{ $lang['copy_to_clipboard'] }}');"
                                                            class="f6">{{ $lang['code_copy'] }}</a>]
                                                        <br>iframe {{ $lang['affiliate_codetype'] }}<br><textarea
                                                            cols=30 rows=2
                                                            id="txt{{ $smarty['foreach']['types']['iteration'] }}_iframe"
                                                            style="border:1px solid #ccc;"><iframe width="250" height="270" src="{{ $shopurl }}affiliate.php?charset={{ $ecs_charset }}&gid={{ $goodsid }}&u={{ $userid }}&type={{ $val }}&display_mode=iframe" frameborder="0" scrolling="no"></iframe></textarea>[<a
                                                            href="#" title="Copy To Clipboard"
                                                            onClick="Javascript:copyToClipboard(document.getElementById('txt{{ $smarty['foreach']['types']['iteration'] }}_iframe').value);alert('{{ $lang['copy_to_clipboard'] }}');"
                                                            class="f6">{{ $lang['code_copy'] }}</a>]
                                                        <br/>{{ $lang['bbs'] }}UBB {{ $lang['affiliate_codetype'] }}
                                                        <br/><textarea cols=30 rows=2
                                                                       id="txt{{ $smarty['foreach']['types']['iteration'] }}_ubb"
                                                                       style="border:1px solid #ccc;">'.($val != 5 ? '[url={{ $shopurl }}goods.php?id={{ $goodsid }}&u={{ $userid }}][img]@if($val < 3)
                                                                {{ $goods['goods_thumb'] }}' :
                                                                '{{ $goods['goods_img'] }}').'[/img][/url]
                                                            @endif

[url={{ $shopurl }}goods.php?id={{ $goodsid }}&u={{ $userid }}][b]{{ $goods['goods_name'] }}[/b][/url]
@if($val != 1 && $val != 3)
                                                                [s]{{ $goods['market_price'] }}[/s]
                                                            @endif [color=red]{{ $goods['shop_price'] }}[/color]</textarea>[<a
                                                            href="#" title="Copy To Clipboard"
                                                            onClick="Javascript:copyToClipboard(document.getElementById('txt{{ $smarty['foreach']['types']['iteration'] }}_ubb').value);alert('{{ $lang['copy_to_clipboard'] }}');"
                                                            class="f6">{{ $lang['code_copy'] }}</a>]
                                                        @if($val == 5)
                                                            <br/>{{ $lang['im_code'] }} {{ $lang['affiliate_codetype'] }}
                                                            <br/><textarea cols=30 rows=2
                                                                           id="txt{{ $smarty['foreach']['types']['iteration'] }}_txt"
                                                                           style="border:1px solid #ccc;">{{ $lang['show_good_to_you'] }} {{ $goods['goods_name'] }}

                                                                {{ $shopurl }}goods.php?id={{ $goodsid }}&u={{ $userid }}</textarea>
                                                            [<a href="#" title="Copy To Clipboard"
                                                                onClick="Javascript:copyToClipboard(document.getElementById('txt{{ $smarty['foreach']['types']['iteration'] }}_txt').value);alert('{{ $lang['copy_to_clipboard'] }}');"
                                                                class="f6">{{ $lang['code_copy'] }}</a>]
                                                        @endif</td>
                                                </tr>
                                            @endforeach
                                        </table>
                                        <script type="text/javascript">
                                            copyToClipboard = function (txt) {
                                                if (window.clipboardData) {
                                                    window.clipboardData.clearData();
                                                    window.clipboardData.setData("Text", txt);
                                                } else if (navigator.userAgent.indexOf("Opera") != -1) {
                                                    //暂时无方法:-(
                                                } else if (window.netscape) {
                                                    try {
                                                        netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
                                                    } catch (e) {
                                                        alert("{{ $lang['firefox_copy_alert'] }}");
                                                        return false;
                                                    }
                                                    var clip = Components.classes['@mozilla.org/widget/clipboard;1'].createInstance(Components.interfaces.nsIClipboard);
                                                    if (!clip)
                                                        return;
                                                    var trans = Components.classes['@mozilla.org/widget/transferable;1'].createInstance(Components.interfaces.nsITransferable);
                                                    if (!trans)
                                                        return;
                                                    trans.addDataFlavor('text/unicode');
                                                    var str = new Object();
                                                    var len = new Object();
                                                    var str = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);
                                                    var copytext = txt;
                                                    str.data = copytext;
                                                    trans.setTransferData("text/unicode", str, copytext.length * 2);
                                                    var clipid = Components.interfaces.nsIClipboard;
                                                    if (!clip)
                                                        return false;
                                                    clip.setData(trans, null, clipid.kGlobalClipboard);
                                                }
                                            }
                                        </script>
                                        <!-- /单个商品推荐 -->
                                    @endif
                                @endif

                            @endif

                            <!-- /我的推荐 -->
                </div>
            </div>
        </div>
    </div>
    <!--right end-->
</div>
<div class="blank"></div>
@include('web::library/page_footer')
</body>
<script type="text/javascript">
    @foreach($lang['clips_js'] as $item => $key)
    var {{ $key }} = "{{ $item }}";
    @endforeach
</script>
</html>
