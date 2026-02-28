<div class="box">
    <div class="box_1">
        <h3><span>{{ $lang['message_board'] }}</span></h3>
        <div class="boxCenterList">
            @foreach($msg_lists as $key => $msg)
                <div class="f_l" style="width:100%; position:relative;">
                    [<b>{{ $msg['msg_type'] }}</b>]&nbsp;{{ $msg['user_name'] }}@if($msg['user_name'] == '')
                        {{ $lang['anonymous'] }}
                    @endif：@if($msg['id_value'] > 0)
                        {{ $lang['feed_user_comment'] }}<b><a class="f3" href="{{ $msg['goods_url'] }}" target="_blank"
                                                              title="{{ $msg['goods_name'] }}">{{ $msg['goods_name'] }}</a></b>
                    @endif<font class="f4">{{ $msg['msg_title'] }}</font> ({{ $msg['msg_time'] }}
                    )@if($msg['comment_rank'] > 0)
                        <img style="position:absolute; right:0px;" src="../images/stars{{ $msg['comment_rank'] }}.gif"
                             alt="{{ $msg['comment_rank'] }}"/>
                    @endif
                </div>
                <div class="msgBottomBorder word">
                    {{ $msg['msg_content'] }}<br>
                    @if($msg['re_content'])
                        <font class="f2">{{ $lang['shopman_reply'] }}</font><br/>
                        {{ $msg['re_content'] }}
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>
<div class="blank5"></div>




