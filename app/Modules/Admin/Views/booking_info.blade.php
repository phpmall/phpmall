@include('admin::pageheader')
<div class="list-div">
    <table width="100%" cellpadding="3" cellspacing="1">
        <tr>
            <th colspan="4">{{ $lang['booking'] }}</th>
        <tr>
        <tr>
            <td align="right" class="first-cell">{{ $lang['user_name'] }}：</td>
            <td>{{ $booking['user_name'] }}</td>
            <td align="right" class="first-cell">{{ $lang['booking_time'] }}：</td>
            <td>{{ $booking['booking_time'] }}</td>
        </tr>
        <tr>
            <td align="right" class="first-cell">{{ $lang['goods_name'] }}：</td>
            <td><a href="../goods.php?id={{ $booking['goods_id'] }}" target="_blank"
                   title="{{ $lang['view'] }}">{{ $booking['goods_name'] }}</a></td>
            <td align="right" class="first-cell">{{ $lang['number'] }}：</td>
            <td>{{ $booking['goods_number'] }}</td>
        </tr>
        <tr>
            <td align="right" class="first-cell" valign="top">{{ $lang['desc'] }}：</td>
            <td colspan="3">{$booking.goods_desc|escape|nl2br}</td>
        </tr>
        <tr>
            <td align="right" class="first-cell">{{ $lang['link_man'] }}：</td>
            <td>{{ $booking['link_man'] }} @if($booking['email'])
                    <{{ $booking['email'] }}>
                @endif </td>
            <td align="right" class="first-cell">{{ $lang['tel'] }}：</td>
            <td>{{ $booking['tel'] }}</td>
        </tr>
        @if($booking['is_dispose'])
            <tr>
                <th colspan="4">{{ $lang['dispose_info'] }}</th>
            </tr>
            <tr>
                <td align="right" class="first-cell">{{ $lang['dispose_user'] }}：</td>
                <td>{{ $booking['dispose_user'] }}</td>
                <td align="right" class="first-cell">{{ $lang['dispose_time'] }}：</td>
                <td>{{ $booking['dispose_time'] }}</td>
            </tr>
            <tr>
                <td align="right" class="first-cell">{{ $lang['note'] }}：</td>
                <td colspan="3">{{ $booking['dispose_note'] }}</td>
            </tr>
        @endif
    </table>
</div>
<br/>

@if($send_fail)
    <ul style="padding:0; margin: 0; list-style-type:none; color: #CC0000;">
        <li style="border: 1px solid #CC0000; background: #FFFFCC; padding: 10px; margin-bottom: 5px;">{{ $lang['mail_send_fail'] }}</li>
    </ul>
@endif

<div class="form-div">
    <form name='theForm' method="post" action="goods_booking.php" onsubmit="return validate()">
        {{ $lang['note'] }}:&nbsp;&nbsp;<input type="text" name="dispose_note" size="55"
                                               value="{{ $booking['dispose_note'] }}"/>&nbsp;<input type="hidden"
                                                                                                    name="act"
                                                                                                    value="update"/><input
            type="hidden" name="rec_id" value="{{ $booking['rec_id'] }}"><input name="send_email_notice" type="checkbox"
                                                                                value='1'/>{{ $lang['send_email_notice'] }}
        <input type="submit" value="{{ $lang['i_dispose'] }}" class="button">
        @if($booking['is_dispose'])
            <input type="submit" name="remail" value="{{ $lang['remail'] }}" class="button">
        @endif

    </form>
</div>

<script src="{{ asset('js/utils.js') }}"></script>
<script src="{{ asset('static/admin/js/validator.js') }}"></script>

<script type="text/javascript">
    document.forms['theForm'].elements['dispose_note'].focus();
    /**
     * 检查表单输入的数据
     */
    function validate() {
        validator = new Validator("theForm");
        validator.required("dispose_note", no_note);
        return validator.passed();
    }

</script>

@include('admin::pagefooter')
