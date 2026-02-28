@if($full_page)
    @include('admin::pageheader')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('static/admin/js/listtable.js') }}"></script>

    <form method="post" action="" name="listForm">
        <!-- start vote list -->
        <div class="list-div" id="listDiv">
            @endif

            <table cellpadding="3" cellspacing="1">
                <tr>
                    <th>{{ $lang['vote_name'] }}</th>
                    <th>{{ $lang['begin_date'] }}</th>
                    <th>{{ $lang['end_date'] }}</th>
                    <th>{{ $lang['vote_count'] }}</th>
                    <th>{{ $lang['handler'] }}</th>
                </tr>
                @forelse($list as $list)
                    <tr>
                        <td class="first-cell">
            <span
                onclick="javascript:listTable.edit(this, 'edit_vote_name', {{ $list['vote_id'] }})">{{ $list['vote_name'] }}</span>
                        </td>
                        <td align="center"><span>{{ $list['begin_date'] }}</span></td>
                        <td align="center"><span>{{ $list['end_date'] }}</span></td>
                        <td align="right"><span>{{ $list['vote_count'] }}</span></td>
                        <td align="center">
                            <a href="vote.php?act=option&id={{ $list['vote_id'] }}"
                               title="{{ $lang['vote_option'] }}"><img
                                    src="{{ asset('static/admin/images/icon_view.gif') }}" border="0" height="16"
                                    width="16"/></a>&nbsp;
                            <a href="vote.php?act=edit&id={{ $list['vote_id'] }}" title="{{ $lang['edit'] }}"><img
                                    src="{{ asset('static/admin/images/icon_edit.gif') }}" border="0" height="16"
                                    width="16"/></a>&nbsp;
                            <a href="javascript:;"
                               onclick="listTable.remove({{ $list['vote_id'] }}, '{{ $lang['drop_confirm'] }}')"
                               title="{{ $lang['remove'] }}"><img src="{{ asset('static/admin/images/icon_drop.gif') }}"
                                                                  border="0" height="16" width="16"/></a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="no-records" colspan="10">{{ $lang['no_vote_name'] }}</td>
                    </tr>
                @endforelse
                <tr>
                    <td align="right" nowrap="true" colspan="10">@include('admin::page')</td>
                </tr>
            </table>

@if($full_page)
        </div>
        <!-- end ad_position list -->
    </form>

    <script type="text/javascript">
        listTable.recordCount = {{ $record_count }};
        listTable.pageCount = {{ $page_count }};

        @foreach($filter as $item => $key)
            listTable.filter.{{ $key }} = '{{ $item }}';
        @endforeach
    </script>
    @include('admin::pagefooter')
@endif
