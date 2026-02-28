@include('admin::pageheader')
<script src="{{ asset('js/utils.js') }}"></script>
<script src="{{ asset('static/admin/js/validator.js') }}"></script>
<form action="payment.php" method="post">
    <div class="main-div">
        <table cellspacing="1" cellpadding="3" width="100%">
            <tr>
                <td class="label">{{ $lang['payment_name'] }}</td>
                <td><input name="pay_name" type="text" value="{{ $pay['pay_name'] }}" size="40"/></td>
            </tr>
            <tr>
                <td class="label">{{ $lang['payment_desc'] }}</td>
                <td><textarea name="pay_desc" cols="60" rows="8">{{ $pay['pay_desc'] }}</textarea></td>
            </tr>
            @foreach($pay['pay_config'] as $config => $key)
                <tr>
                    <td class="label">
                        @if($config['desc'])
                            <a href="javascript:showNotice('notice{{ $config['name'] }}');"
                               title="{{ $lang['form_notice'] }}"><img
                                    src="{{ asset('static/admin/images/notice.gif') }}"
                                    width="16" height="16" border="0" alt="{{ $lang['form_notice'] }}"></a>
                        @endif
                        <span class="label">{{ $config['label'] }}
                    </td>
                    <td>
                        @if($config['type'] === "text")
                            <input name="cfg_value[]" type="{{ $config['type'] }}" value="{{ $config['value'] }}"
                                   size="40"/>
                        @elseif($config['type'] === "textarea")
                            <textarea name="cfg_value[]" cols="80" rows="5">{{ $config['value'] }}</textarea>
                        @elseif($config['type'] === "select")
                            <select name="cfg_value[]">{html_options options=$config.range
                                selected=$config.value}</select>
                        @endif
                        <input name="cfg_name[]" type="hidden" value="{{ $config['name'] }}"/>
                        <input name="cfg_type[]" type="hidden" value="{{ $config['type'] }}"/>
                        <input name="cfg_lang[]" type="hidden" value="{{ $config['lang'] }}"/>
                        @if($config['desc'])
                            <br/><span class="notice-span"
                                       {{ $help_open ? 'style="display:block" ' : ' style="display:none" ' }} id="notice{{ $config['name'] }}">{{ $config['desc'] }}</span>
                        @endif
                    </td>
                </tr>
            @endforeach
            <tr>
                <td class="label">{{ $lang['pay_fee'] }}</td>
                <td>@if($pay['is_cod'])
                        <input name="pay_fee" type="hidden"
                               value="{{ $pay['pay_fee'] ?? 0 }}"/>{{ $lang['decide_by_ship'] }}
                    @else
                        <input name="pay_fee" type="text" value="{{ $pay['pay_fee'] ?? 0 }}"/>
                    @endif
                </td>

            </tr>
            <tr>
                <td class="label">{{ $lang['payment_is_cod'] }}</td>
                <td>'.($pay['is_cod'] === "1" ? '{{ $lang['yes'] }}' : '{{ $lang['no'] }}').'</td>
            </tr>
            <tr>
                <td class="label">{{ $lang['payment_is_online'] }}</td>
                <td>'.($pay['is_online'] === "1" ? '{{ $lang['yes'] }}' : '{{ $lang['no'] }}').'</td>
            </tr>
            <tr align="center">
                <td colspan="2">
                    <input type="hidden" name="pay_id" value="{{ $pay['pay_id'] }}"/>
                    <input type="hidden" name="pay_code" value="{{ $pay['pay_code'] }}"/>
                    <input type="hidden" name="is_cod" value="{{ $pay['is_cod'] }}"/>
                    <input type="hidden" name="is_online" value="{{ $pay['is_online'] }}"/>
                    <input type="submit" class="button" name="Submit" value="{{ $lang['button_submit'] }}"/>
                    <input type="reset" class="button" name="Reset" value="{{ $lang['button_reset'] }}"/>
                </td>
            </tr>
        </table>
    </div>
</form>
<script type="text/javascript">

</script>
@include('admin::pagefooter')
