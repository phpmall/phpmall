<!--购买记录 START-->
<div class="box">
    <div class="box_1">
        <h3><span class="text">{{ $lang['bought_notes'] }}</span>({{ $lang['later_bought_amounts'] }}<font
                class="f1">{{ $pager['record_count'] }}</font>)</h3>
        <div class="boxCenterList">
            @if($notes)
                <table width="100%" cellpadding="4">
                    <tr style="background:url(images/lineBg.gif) repeat-x left bottom;text-align:center; color:#006bd0; font-weight:bold;">
                        <td width="25%" align="left" style="padding-left:20px">{{ $lang['username'] }}</td>
                        <td width="10%">{{ $lang['number'] }}</td>
                        <td width="45%">{{ $lang['bought_time'] }}</td>
                        <td width="20%">{{ $lang['order_status'] }}</td>
                    </tr>
                    @foreach($notes as $note)
                        <tr align="center">
                            <td align="left" style="padding-left:20px">'.($note['user_name'] ? '{{ $note['user_name'] }}
                                ' : '{{ $lang['anonymous'] }}').'
                            </td>
                            <td>{{ $note['goods_number'] }}</td>
                            <td>{{ $note['add_time'] }}</td>
                            <td>'.($note['order_status'] ? '{{ $lang['turnover'] }}' : '{{ $lang['is_cancel'] }}').'
                            </td>
                        </tr>
                    @endforeach
                </table>
            @else
                {{ $lang['no_notes'] }}
            @endif
            <!--翻页 start-->
            <div id="buy_pagebar" class="f_r" style="margin-top:10px">
                <form name="selectPageForm" action="{{ $smarty['server']['PHP_SELF'] }}" method="get">
                    @if($pager['styleid'] == 0 )
                        <div id="buy_pager">
                            {{ $lang['pager_1'] }}{{ $pager['record_count'] }}{{ $lang['pager_2'] }}{{ $lang['pager_3'] }}{{ $pager['page_count'] }}{{ $lang['pager_4'] }}
                            <span> <a href="{{ $pager['page_first'] }}">{{ $lang['page_first'] }}</a> <a
                                    href="{{ $pager['page_prev'] }}">{{ $lang['page_prev'] }}</a> <a
                                    href="{{ $pager['page_next'] }}">{{ $lang['page_next'] }}</a> <a
                                    href="{{ $pager['page_last'] }}">{{ $lang['page_last'] }}</a> </span>
                            @foreach($pager['search'] as $key => $item)
                                <input type="hidden" name="{{ $key }}" value="{{ $item }}"/>
                            @endforeach
                        </div>
                    @else

                        <!--翻页 start-->
                        <div id="buy_pager" class="pagebar">
                            <span class="f_l f6"
                                  style="margin-right:10px;">{{ $lang['total'] }} <b>{{ $pager['record_count'] }}</b> {{ $lang['user_comment_num'] }}</span>
                            @if($pager['page_first'])
                                <a href="{{ $pager['page_first'] }}">1 ...</a>
                            @endif
                            @if($pager['page_prev'])
                                <a class="prev" href="{{ $pager['page_prev'] }}">{{ $lang['page_prev'] }}</a>
                            @endif
                            @foreach($pager['page_number'] as $key => $item)
                                @if($pager['page'] == $key)
                                    <span class="page_now">{{ $key }}</span>
                                @else
                                    <a href="{{ $item }}">[{{ $key }}]</a>
                                @endif
                            @endforeach

                            @if($pager['page_next'])
                                <a class="next" href="{{ $pager['page_next'] }}">{{ $lang['page_next'] }}</a>
                            @endif
                            @if($pager['page_last'])
                                <a class="last" href="{{ $pager['page_last'] }}">...{{ $pager['page_count'] }}</a>
                            @endif
                            @if($pager['page_kbd'])
                                @foreach($pager['search'] as $key => $item)
                                    <input type="hidden" name="{{ $key }}" value="{{ $item }}"/>
                                @endforeach
                                <kbd style="float:left; margin-left:8px; position:relative; bottom:3px;"><input
                                        type="text" name="page" onkeydown="if(event.keyCode==13)selectPage(this)"
                                        size="3" class="B_blue"/></kbd>
                            @endif
                        </div>
                        <!--翻页 END-->

                    @endif
                </form>
                <script type="text/javascript">
                    <!--

        function selectPage(sel) {
            sel.form.submit();
        }


                </script>
            </div>
            <!--翻页 END-->
            <div class="blank5"></div>
        </div>
    </div>
</div>
<div class="blank5"></div>
<!--购买记录 END-->
