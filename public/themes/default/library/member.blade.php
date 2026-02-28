<script type="text/javascript" src="{{ asset('js/transport.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/utils.js') }}"></script>
<div><img src="../images/memeber_login.jpg" alt="Login" width="170" height="40"/></div>
<table width="100%" border="0" cellpadding="0" cellspacing="0" style="background:url(../images/login_bg.gif);">
    <tr>
        <td id="ECS_MEMBERZONE">{* 提醒您：根据用户id来调用member_info.lbi显示不同的界面 *}{insert
            name='member_info'}
        </td>
        <td width="9" valign="top"><img src="../images/login_right.gif" alt="shadow" width="9" height="131"/></td>
    </tr>
</table>
