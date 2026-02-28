@include('admin::pageheader')
<script src="{{ asset('js/utils.js') }}"></script>
<script src="{{ asset('static/admin/js/listtable.js') }}"></script>
<!-- start payment list -->
<div class="list-div" id="listDiv">
    <table cellspacing='1' cellpadding='3'>
        <tr>
            <th width="13%">{{ $lang['cron_name'] }}</th>
            <th>{{ $lang['cron_desc'] }}</th>
            <th width="5%">{{ $lang['version'] }}</th>
            <th width="13%">{{ $lang['cron_author'] }}</th>
            <th width="16%">{{ $lang['cron_this'] }}</th>
            <th width="16%">{{ $lang['cron_next'] }}</th>
            <th width="3%">{{ $lang['if_open'] }}</th>
            <th width="12%">{{ $lang['handler'] }}</th>
        </tr>
        @foreach($modules as $module)
            <tr>
                <td class="first-cell" valign="top">
                    @if($module['install'] === 1)
                        {{ $module['name'] }}
                    @else
                        {{ $module['name'] }}
                    @endif
                </td>
                <td>{$module.desc|nl2br}</td>
                <td valign="top">{{ $module['version'] }}</td>
                <td valign="top"><a href="{{ $module['website'] }}" target="_blank">{{ $module['author'] }}</a></td>
                <td align="center">{{ $module['thistime'] }}</td>
                <td align="center">{{ $module['nextime'] }}</td>

                <td align="center">
                    @if($module['install'] === "1")
                        <img
                            src="{{ asset('static/admin/images/' . ($module['enable'] === 1 ? 'yes' : 'no') . '.gif') }}"
                            onclick="listTable.toggle(this, 'toggle_show', '{{ $module['code'] }}')"/>
                    @else
                        －
                    @endif
                </td>
                <td align="center" valign="top">
                    @if($module['install'] === "1")
                        <a
                            href="javascript:confirm_redirect(lang_removeconfirm, 'cron.php?act=uninstall&code={{ $module['code'] }}')">{{ $lang['uninstall'] }}</a>
                        |<a href="cron.php?act=edit&code={{ $module['code'] }}">{{ $lang['edit'] }}</a>|<a
                            href="cron.php?act=do&code={{ $module['code'] }}">{{ $lang['cron_do'] }}</a>
                    @else
                        <a href="cron.php?act=install&code={{ $module['code'] }}">{{ $lang['install'] }}</a>
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
