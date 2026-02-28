@include('admin::pageheader')
<!-- comment content list -->
<div class="main-div">
    <table width="100%">
        <tr>
            <td>
                <a href="mailto:{{ $msg['email'] }}"><b>'.($msg['user_name'] ? '{{ $msg['user_name'] }}' :
                        '{{ $lang['anonymous'] }}').'</b></a>&nbsp;{{ $lang['from'] }}
                &nbsp;{{ $msg['add_time'] }}&nbsp;{{ $lang['to'] }}
                &nbsp;<b>{{ $id_value }}</b>&nbsp;{{ $lang['send_comment'] }}
            </td>
        </tr>
        <tr>
            <td>
                <hr color="#dadada" size="1">
            </td>
        </tr>
        <tr>
            <td>
                <div style="overflow:hidden; word-break:break-all;">{{ $msg['content'] }}</div>
                <div align="right"><b>{{ $lang['comment_rank'] }}:</b> {{ $msg['comment_rank'] }}
                    &nbsp;&nbsp;<b>{{ $lang['ip_address'] }}</b>: {{ $msg['ip_address'] }}</div>
            </td>
        </tr>
        <tr>
            <td align="center">
                @if($msg['status'] === "0")
                    <input type="button"
                           onclick="location.href='comment_manage.php?act=check&check=allow&id={{ $msg['comment_id'] }}'"
                           value="{{ $lang['allow'] }}" class="button"/>
                @else
                    <input type="button"
                           onclick="location.href='comment_manage.php?act=check&check=forbid&id={{ $msg['comment_id'] }}'"
                           value="{{ $lang['forbid'] }}" class="button"/>
                @endif
            </td>
        </tr>
    </table>
</div>

@if($reply_info['content'])
    <!-- reply content list -->
    <div class="main-div">
        <table width="100%">
            <tr>
                <td>
                    {{ $lang['admin_user_name'] }}&nbsp;<a
                        href="mailto:{{ $msg['email'] }}"><b>{{ $reply_info['user_name'] }}</b></a>&nbsp;{{ $lang['from'] }}
                    &nbsp;{{ $reply_info['add_time'] }}&nbsp;{{ $lang['reply'] }}
                </td>
            </tr>
            <tr>
                <td>
                    <hr color="#dadada" size="1">
                </td>
            </tr>
            <tr>
                <td>
                    <div style="overflow:hidden; word-break:break-all;">{{ $reply_info['content'] }}</div>
                    <div align="right"><b>{{ $lang['ip_address'] }}</b>: {{ $reply_info['ip_address'] }}</div>
                </td>
            </tr>
        </table>
    </div>
@endif

@if($send_fail)
    <ul style="padding:0; margin: 0; list-style-type:none; color: #CC0000;">
        <li style="border: 1px solid #CC0000; background: #FFFFCC; padding: 10px; margin-bottom: 5px;">
            {{ $lang['mail_send_fail'] }}</li>
    </ul>
@endif

<div class="main-div">
    <form method="post" action="comment_manage.php?act=action" name="theForm" onsubmit="return validate()">
        <table border="0" align="center">
            <tr>
                <th colspan="2">
                    <strong>{{ $lang['reply_comment'] }}</strong>
                </th>
            </tr>
            <tr>
                <td>{{ $lang['user_name'] }}:</td>
                <td><input name="user_name" type="text" '.($reply_info[' user_name']===""
                    ? 'value="{{ $admin_info['user_name'] }}"' : ' value="{{ $reply_info['user_name'] }}"' ).'
                    size="30" readonly="true" />
                </td>
            </tr>
            <tr>
                <td>{{ $lang['email'] }}:</td>
                <td><input name="email" type="text" '.($reply_info[' email']==="" ? 'value="{{ $admin_info['email'] }}"'
                    : ' value="{{ $reply_info['email'] }}"' ).' size="30" readonly="true" />
                </td>
            </tr>
            <tr>
                <td>{{ $lang['reply_content'] }}:</td>
                <td><textarea name="content" cols="50" rows="4" wrap="VIRTUAL">{{ $reply_info['content'] }}</textarea>
                </td>
            </tr>
            @if($reply_info['content'])
                <tr>
                    <td>&nbsp;</td>
                    <td>{{ $lang['have_reply_content'] }}</td>
                </tr>
            @endif
            <tr>
                <td></td>
                <td><input name="send_email_notice" type="checkbox" value='1'/>{{ $lang['send_email_notice'] }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>
                    <input name="submit" type="submit" value="{{ $lang['button_submit'] }}" class="button">
                    <input type="reset" value="{{ $lang['button_reset'] }}" class="button">
                    @if($reply_info['content'])
                        <input type="submit" name="remail" value="{{ $lang['remail'] }}" class="button">
                    @endif
                    <input type="hidden" name="comment_id" value="{{ $msg['comment_id'] }}">
                    <input type="hidden" name="comment_type" value="{{ $msg['comment_type'] }}">
                    <input type="hidden" name="id_value" value="{{ $msg['id_value'] }}">
                </td>
            </tr>
        </table>
    </form>
</div>
<script src="{{ asset('js/utils.js') }}"></script>
<script src="{{ asset('static/admin/js/validator.js') }}"></script>

<script type="text/javascript">
    document.forms['theForm'].elements['content'].focus();

    /**
     * 检查表单输入的数据
     */
    function validate() {
        validator = new Validator("theForm");
        validator.required("content", no_content);
        return validator.passed();
    }
</script>

@include('admin::pagefooter')
