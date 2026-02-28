@if($full_page)
    @include('admin::pageheader')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('static/admin/js/listtable.js') }}"></script>
    <form method="post" action="" name="listForm">
        <div class="list-div" id="listDiv">
            @endif
            <table cellspacing='1' cellpadding='3' id='list-table'>
                <tr>
                    <th>{{ $lang['item_name'] }}</th>
                    <th>{{ $lang['item_ifshow'] }}</th>
                    <th>{{ $lang['item_opennew'] }}</th>
                    <th>{{ $lang['item_vieworder'] }}</th>
                    <th>{{ $lang['item_type'] }}</th>
                    <th width="60px">{{ $lang['handler'] }}</th>
                </tr>
                @forelse($navdb as $val)
                    <tr>
                        <td align="center">'.($val['id'] ? '{{ $val['name'] }}' : '&nbsp;').'</td>
                        <td align="center">
                            @if($val['id'])
                                <img
                                    src="{{ asset('static/admin/images/' . ($val['ifshow'] === '1' ? 'yes' : 'no') . '.gif') }}"
                                    onclick="listTable.toggle(this, 'toggle_ifshow', {{ $val['id'] }})"/>
                            @endif
                        </td>
                        <td align="center">
                            @if($val['id'])
                                <img
                                    src="{{ asset('static/admin/images/' . ($val['opennew'] === '1' ? 'yes' : 'no') . '.gif') }}"
                                    onclick="listTable.toggle(this, 'toggle_opennew', {{ $val['id'] }})"/>
                            @endif
                        </td>
                        <td align="center">@if($val['id'])
                                <span
                                    onclick="listTable.edit(this, 'edit_sort_order', {{ $val['id'] }})">{{ $val['vieworder'] }}</span>
                            @endif
                        </td>
                        <td align="center">@if($val['id'])
                                {$lang[$val.type]}
                            @endif</td>
                        <td align="center">@if($val['id'])<a href="navigator.php?act=edit&id={{ $val['id'] }}"
                                                             title="{{ $lang['edit'] }}"><img
                                    src="{{ asset('static/admin/images/icon_edit.gif') }}"
                                    width="16" height="16" border="0"/></a>
                            <a href="navigator.php?act=del&id={{ $val['id'] }}"
                               onclick="return confirm('{{ $lang['ckdel'] }}');" title="{{ $lang['ckdel'] }}"><img
                                    src="{{ asset('static/admin/images/no.gif') }}" width="16" height="16"
                                    border="0"/>@endif</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="no-records" colspan="10">{{ $lang['no_records'] }}</td>
                    </tr>
                @endforelse
            </table>

            <table cellpadding="4" cellspacing="0">
                <tr>
                    <td align="right">@include('admin::page')</td>
                </tr>
            </table>
@if($full_page)
        </div>
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
