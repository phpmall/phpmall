@if($full_page)
    @include('admin::pageheader')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('static/admin/js/listtable.js') }}"></script>
    <!-- start cat list -->
    <div class="list-div" id="listDiv">
        @endif

        <table cellspacing='1' cellpadding='3' id='list-table'>
            <tr>
                <th>{{ $lang['id'] }}</th>
                <th>{{ $lang['title'] }}</th>
                <th>{{ $lang['add_time'] }}</th>
                <th>{{ $lang['handler'] }}</th>
            </tr>
            @foreach($list as $item)
                <tr>
                    <td align="center" width="40px">{{ $item['article_id'] }}</td>
                    <td class="first-cell"><span
                            onclick="javascript:listTable.edit(this, 'edit_title', {{ $item['article_id'] }})">{{ $item['title'] }}</span>
                    </td>
                    <td align="center">{{ $item['add_time'] }}</td>
                    <td align="center">
                        <a href="shopinfo.php?act=edit&id={{ $item['article_id'] }}" title="{{ $lang['edit'] }}"><img
                                src="{{ asset('static/admin/images/icon_edit.gif') }}" border="0" height="16"
                                width="16"></a>&nbsp;&nbsp;
                        <a href="javascript:;"
                           onclick="listTable.remove({{ $item['article_id'] }}, '{{ $lang['drop_confirm'] }}')"
                           title="{{ $lang['remove'] }}"><img src="{{ asset('static/admin/images/icon_drop.gif') }}"
                                                              border="0" height="16" width="16"></a>
                    </td>
                </tr>
            @endforeach
        </table>

@if($full_page)
    </div>
    <!-- end cat list -->
    <script type="text/javascript">
    </script>
    @include('admin::pagefooter')
@endif
