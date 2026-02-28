@if($full_page)
    @include('admin::pageheader')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('static/admin/js/listtable.js') }}"></script>
    <!-- start bonus_type list -->
    <form method="post" action="" name="listForm">
        <div class="list-div" id="listDiv">
@endif

            <table cellpadding="3" cellspacing="1">
                <tr>
                    <th>
                        <a href="javascript:listTable.sort('type_name'); ">{{ $lang['type_name'] }}</a>{{ $sort_type_name }}
                    </th>
                    <th>
                        <a href="javascript:listTable.sort('send_type'); ">{{ $lang['send_type'] }}</a>{{ $sort_send_type }}
                    </th>
                    <th>
                        <a
                            href="javascript:listTable.sort('type_money'); ">{{ $lang['type_money'] }}</a>{{ $sort_type_money }}
                    </th>
                    <th>
                        <a
                            href="javascript:listTable.sort('min_amount'); ">{{ $lang['min_amount'] }}</a>{{ $sort_min_amount }}
                    </th>
                    <th>{{ $lang['send_count'] }}</th>
                    <th>{{ $lang['use_count'] }}</th>
                    <th>{{ $lang['handler'] }}</th>
                </tr>
                @forelse($type_list as $type)
                    <tr>
                        <td class="first-cell"><span
                                onclick="listTable.edit(this, 'edit_type_name', {{ $type['type_id'] }})">{{ $type['type_name'] }}</span>
                        </td>
                        <td>{{ $type['send_by'] }}</td>
                        <td align="right"><span
                                onclick="listTable.edit(this, 'edit_type_money', {{ $type['type_id'] }})">{{ $type['type_money'] }}</span>
                        </td>
                        <td align="right"><span
                                onclick="listTable.edit(this, 'edit_min_amount', {{ $type['type_id'] }})">{{ $type['min_amount'] }}</span>
                        </td>
                        <td align="right"><span>{{ $type['send_count'] }}</span></td>
                        <td align="right">{{ $type['use_count'] }}</td>
                        <td align="right">
                            @if($type['send_type'] === 3)
                                <a href="bonus.php?act=gen_excel&tid={{ $type['type_id'] }}">{{ $lang['report_form'] }}</a>
                                |
                            @endif
                            @if($type['send_type'] != 2)
                                <a
                                    href="bonus.php?act=send&amp;id={{ $type['type_id'] }}&amp;send_by={{ $type['send_type'] }}">{{ $lang['send'] }}</a>
                                |
                            @endif
                            <a href="bonus.php?act=bonus_list&amp;bonus_type={{ $type['type_id'] }}">{{ $lang['view'] }}</a>
                            |
                            <a href="bonus.php?act=edit&amp;type_id={{ $type['type_id'] }}">{{ $lang['edit'] }}</a> |
                            <a href="javascript:;"
                               onclick="listTable.remove({{ $type['type_id'] }}, '{{ $lang['drop_confirm'] }}')">{{ $lang['remove'] }}</a></span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="no-records" colspan="10">{{ $lang['no_records'] }}</td>
                    </tr>
                @endforelse
                <tr>
                    <td align="right" nowrap="true" colspan="8">@include('admin::page')</td>
                </tr>
            </table>

@if($full_page)
        </div>
    </form>
    <!-- end bonus_type list -->

    <script type="text/javascript">
        listTable.recordCount = {{ $record_count }};
        listTable.pageCount = {{ $page_count }};

        @foreach($filter as $item => $key)
            listTable.filter.{{ $key }} = '{{ $item }}';
        @endforeach
    </script>
    @include('admin::pagefooter')
@endif
