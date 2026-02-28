<div id="append_parent"></div>

@if($user_info)
    <font style="position:relative; top:10px;">
        {{ $lang['hello'] }}，<font class="f4_b">{{ $user_info['username'] }}</font>, {{ $lang['welcome_return'] }}！
        <a href="user.php">{{ $lang['user_center'] }}</a>|
        <a href="user.php?act=logout">{{ $lang['user_logout'] }}</a>
    </font>
@else
    {{ $lang['welcome'] }}&nbsp;&nbsp;&nbsp;&nbsp;
    <a href="user.php"><img src="images/bnt_log.gif"/></a>
    <a href="user.php?act=register"><img src="images/bnt_reg.gif"/></a>
@endif
