@if($full_page)
    @include('admin::pageheader')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('static/admin/js/listtable.js') }}"></script>

    <div class="list-div" id="listDiv">
        @endif

        <table cellspacing='1' cellpadding='3' id='list-table'>
            <tr>
                <th>{{ $lang['user_name'] }}</th>
                <th>{{ $lang['email'] }}</th>
                <th>{{ $lang['join_time'] }}</th>
                <th>{{ $lang['last_time'] }}</th>
                <th>{{ $lang['handler'] }}</th>
            </tr>
            @foreach($admin_list as $list)
                <tr>
                    <td class="first-cell">{{ $list['user_name'] }}</td>
                    <td align="left">{{ $list['email'] }}</td>
                    <td align="center">{{ $list['add_time'] }}</td>
                    <td align="center">{{ $list['last_login'] ?? N / A }}</td>
                    <td align="center">
                        <a href="privilege.php?act=allot&id={{ $list['user_id'] }}&user={{ $list['user_name'] }}"
                           title="{{ $lang['allot_priv'] }}"><img src="{{ asset('static/admin/images/icon_priv.gif') }}"
                                                                  border="0"
                                                                  height="16" width="16"></a>&nbsp;&nbsp;
                        <a href="admin_logs.php?act=list&id={{ $list['user_id'] }}" title="{{ $lang['view_log'] }}"><img
                                src="{{ asset('static/admin/images/icon_view.gif') }}" border="0" height="16"
                                width="16"></a>&nbsp;&nbsp;
                        <a href="privilege.php?act=edit&id={{ $list['user_id'] }}" title="{{ $lang['edit'] }}"><img
                                src="{{ asset('static/admin/images/icon_edit.gif') }}" border="0" height="16"
                                width="16"></a>&nbsp;&nbsp;
                        <a href="javascript:;"
                           onclick="listTable.remove({{ $list['user_id'] }}, '{{ $lang['drop_confirm'] }}')"
                           title="{{ $lang['remove'] }}"><img src="{{ asset('static/admin/images/icon_drop.gif') }}"
                                                              border="0"
                                                              height="16" width="16"></a>
                    </td>
                </tr>
            @endforeach
        </table>

@if($full_page)
    </div>
    <script type="text/javascript">
    </script>
    @include('admin::pagefooter')
@endif
