@include('admin::pageheader')
<!-- start integrate plugins list -->
<div class="list-div" id="listDiv">
    <table cellspacing='1' cellpadding='3'>
        <tr>
            <th>{{ $lang['plugin_name'] }}</th>
            <th>{{ $lang['plugin_version'] }}</th>
            <th>{{ $lang['plugin_desc'] }}</th>
            <th>{{ $lang['plugin_author'] }}</th>
            <th>{{ $lang['handler'] }}</th>
        </tr>
        @foreach($modules as $module)
            <tr>
                <td class="first-cell" valign="top" nowrap="true">{{ $module['name'] }}</td>
                <td valign="top" nowrap="true">{{ $module['version'] }}</td>
                <td valign="top">{{ $module['desc'] }}</td>
                <td valign="top" nowrap="true"><a href="{{ $module['website'] }}">{{ $module['author'] }}</a></td>
                <td align="center" valign="top" nowrap="true">
                    @if($module['setup'] === 1)
                        <a
                            href="javascript:confirm_redirect('{{ $lang['uninstall_confirm'] }}', 'plugins.php?act=uninstall&code={{ $module['code'] }}')">{{ $lang['uninstall'] }}</a>
                        <a href="plugins.php?act=upgrade&code={{ $module['code'] }}">{{ $lang['upgrade'] }}</a>
                    @elseif($module['setup'] === 0)
                        <a
                            href="javascript:confirm_redirect('{{ $lang['uninstall_confirm'] }}', 'plugins.php?act=uninstall&code={{ $module['code'] }}')">{{ $lang['uninstall'] }}</a>
                    @else
                        <a href="plugins.php?act=install&code={{ $module['code'] }}">{{ $lang['install'] }}</a>
                    @endif
                </td>
            </tr>
        @endforeach
    </table>
</div>
<!-- end integrate plugins list -->

<script type="text/javascript">
</script>

@include('admin::pagefooter')
