@if($full_page)
    @include('admin::pageheader')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('static/admin/js/listtable.js') }}"></script>

    <div class="list-div" id="listDiv">
        @endif

        <table cellspacing='1' cellpadding='3' id='list-table'>
            <tr>
                <th>{{ $lang['user_name'] }}</th>
                <th>{{ $lang['role_describe'] }}</th>
                <th>{{ $lang['handler'] }}</th>
            </tr>
            @foreach($admin_list as $list)
                <tr>
                    <td class="first-cell">{{ $list['role_name'] }}</td>
                    <td class="first-cell">{{ $list['role_describe'] }}</td>
                    <td align="center">
                        <a href="role.php?act=edit&id={{ $list['role_id'] }}" title="{{ $lang['edit'] }}"><img
                                src="{{ asset('static/admin/images/icon_edit.gif') }}" border="0" height="16"
                                width="16"></a>&nbsp;&nbsp;
                        <a href="javascript:;"
                           onclick="listTable.remove({{ $list['role_id'] }}, '{{ $lang['drop_confirm'] }}')"
                           title="{{ $lang['remove'] }}"><img src="{{ asset('static/admin/images/icon_drop.gif') }}"
                                                              border="0" height="16" width="16"></a>
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
