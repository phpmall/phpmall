@include('admin::pageheader')
<script src="{{ asset('js/utils.js') }}"></script>
<script src="{{ asset('static/admin/js/listtable.js') }}"></script>
<!-- start payment list -->
<div class="list-div" id="listDiv">
    <table cellspacing='1' cellpadding='3'>
        <tr>
            <th>{{ $lang['payment_name'] }}</th>
            <th width="40%">{{ $lang['payment_desc'] }}</th>
            <th>{{ $lang['version'] }}</th>
            <th>{{ $lang['payment_author'] }}</th>
            <th>{{ $lang['short_pay_fee'] }}</th>
            <th>{{ $lang['sort_order'] }}</th>
            <th>{{ $lang['handler'] }}</th>
        </tr>
        @foreach($modules as $module)
            <tr>
                <td class="first-cell" valign="top">
                    @if($module['install'] === 1)
                        <span
                            onclick="listTable.edit(this, 'edit_name', '{{ $module['code'] }}'); return false;">{{ $module['name'] }}</span>
                    @else
                        {{ $module['name'] }}
                    @endif
                </td>
                <td>{$module.desc|nl2br}</td>
                <td valign="top">{{ $module['version'] }}</td>
                <td valign="top"><a href="{{ $module['website'] }}">{{ $module['author'] }}</a></td>
                <td valign="top" align="right">
                    @if($module['is_cod'])
                        {{ $lang['decide_by_ship'] }}
                    @else
                        @if($module['install'] === 1)
                            <span
                                onclick="listTable.edit(this, 'edit_pay_fee', '{{ $module['code'] }}'); return false;">{{ $module['pay_fee'] }}</span>
                        @else
                            {{ $module['pay_fee'] }}
                        @endif
                    @endif
                </td>
                <td align="right" valign="top"> @if($module['install'] === 1)
                        <span
                            onclick="listTable.edit(this, 'edit_order', '{{ $module['code'] }}'); return false;">{{ $module['pay_order'] }}</span>
                    @else
                        &nbsp;
                    @endif
                </td>
                <td align="center" valign="top">
                    @if($module['install'] === "1")
                        @if($module['code'] === "tenpay")
                            <a
                                href="javascript:confirm_redirect(lang_removeconfirm, 'payment.php?act=uninstall&code={{ $module['code'] }}')">{{ $lang['uninstall'] }}{{ $lang['tenpay'] }}</a>
                            <a href="payment.php?act=edit&code={{ $module['code'] }}">{{ $lang['edit'] }}</a><br/>
                            @if($tenpayc2c['install'] === "1")
                                <a
                                    href="javascript:confirm_redirect(lang_removeconfirm, 'payment.php?act=uninstall&code=tenpayc2c')">{{ $lang['uninstall'] }}{{ $lang['tenpayc2c'] }}</a>
                                <a href="payment.php?act=edit&code=tenpayc2c">{{ $lang['edit'] }}</a>
                            @else
                                <a href="payment.php?act=install&code=tenpayc2c">{{ $lang['install'] }}{{ $lang['tenpayc2c'] }}</a>
                            @endif
                        @else
                            <a
                                href="javascript:confirm_redirect(lang_removeconfirm, 'payment.php?act=uninstall&code={{ $module['code'] }}')">{{ $lang['uninstall'] }}</a>
                            <a href="payment.php?act=edit&code={{ $module['code'] }}">{{ $lang['edit'] }}</a>
                        @endif

                    @else
                        @if($module['code'] === "tenpay")
                            <a href="payment.php?act=install&code={{ $module['code'] }}">{{ $lang['install'] }}{{ $lang['tenpay'] }}
                            </a><br/>
                            @if($tenpayc2c['install'] === "1")
                                <a
                                    href="javascript:confirm_redirect(lang_removeconfirm, 'payment.php?act=uninstall&code=tenpayc2c')">{{ $lang['uninstall'] }}{{ $lang['tenpayc2c'] }}</a>
                                <a href="payment.php?act=edit&code=tenpayc2c">{{ $lang['edit'] }}</a>

                            @else
                                <a href="payment.php?act=install&code=tenpayc2c">{{ $lang['install'] }}{{ $lang['tenpayc2c'] }}</a>
                            @endif

                        @else
                            <a href="payment.php?act=install&code={{ $module['code'] }}">{{ $lang['install'] }}</a>
                        @endif

                    @endif
                </td>
            </tr>
        @endforeach
    </table>
</div>
<!-- end payment list -->
<script type="text/javascript">

</script>
@include('admin::pagefooter')
