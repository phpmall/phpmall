@include('admin::pageheader')
<script src="{{ asset('js/utils.js') }}"></script>
<script src="{{ asset('static/admin/js/listtable.js') }}"></script>
<!-- start payment list -->
<div class="list-div" id="listDiv">
    <table cellspacing='1' cellpadding='3'>
        <tr>
            <th>{{ $lang['shipping_name'] }}</th>
            <th>{{ $lang['shipping_desc'] }}</th>
            <th nowrap="true">{{ $lang['insure'] }}</th>
            <th nowrap="true">{{ $lang['support_cod'] }}</th>
            <th nowrap="true">{{ $lang['shipping_version'] }}</th>
            <th>{{ $lang['shipping_author'] }}</th>
            <th>{{ $lang['sort_order'] }}</th>
            <th>{{ $lang['handler'] }}</th>
        </tr>
        @foreach($modules as $module)
            <tr>
                <td class="first-cell" nowrap="true">
                    @if($module['install'] === 1)
                        <span
                            onclick="listTable.edit(this, 'edit_name', '{{ $module['code'] }}'); return false;">{{ $module['name'] }}</span>
                    @else
                        {{ $module['name'] }}
                    @endif
                </td>
                <td>
                    @if($module['install'] === 1)
                        <span
                            onclick="listTable.edit(this, 'edit_desc', '{{ $module['code'] }}'); return false;">{{ $module['desc'] }}</span>
                    @else
                        {{ $module['desc'] }}
                    @endif
                </td>
                <td align="right">
                    @if($module['install'] === 1 && $module['is_insure'] != 0)
                        <span
                            onclick="listTable.edit(this, 'edit_insure', '{{ $module['code'] }}'); return false;">{{ $module['insure_fee'] }}</span>
                    @else
                        {{ $module['insure_fee'] }}
                    @endif
                </td>
                <td align='center'>'.($module['cod'] === 1 ? '{{ $lang['yes'] }}' : '{{ $lang['no'] }}').'</td>
                <td nowrap="true">{{ $module['version'] }}</td>
                <td nowrap="true"><a href="{{ $module['website'] }}" target="_blank">{{ $module['author'] }}</a></td>
                <td align="right" valign="top"> @if($module['install'] === 1)
                        <span
                            onclick="listTable.edit(this, 'edit_order', '{{ $module['code'] }}'); return false;">{{ $module['shipping_order'] }}</span>
                    @else
                        &nbsp;
                    @endif
                </td>
                <td align="center" nowrap="true">
                    @if($module['install'] === 1)
                        <a
                            href="javascript:confirm_redirect(lang_removeconfirm,'shipping.php?act=uninstall&code={{ $module['code'] }}')">{{ $lang['uninstall'] }}</a>
                        <a href="shipping_area.php?act=list&shipping={{ $module['id'] }}">{{ $lang['shipping_area'] }}</a>
                        <a
                            href="shipping.php?act=edit_print_template&shipping={{ $module['id'] }}">{{ $lang['shipping_print_edit'] }}</a>
                    @else
                        <a href="shipping.php?act=install&code={{ $module['code'] }}">{{ $lang['install'] }}</a>
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
