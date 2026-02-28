@if($full_page)
    @include('admin::pageheader')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('static/admin/js/listtable.js') }}"></script>
    <div class="list-div">
        <table width="100%" cellpadding="3" cellspacing="1">
            <tr>
                <th colspan="8"><strong>{{ $info['snatch_name'] }} </strong></th>
            </tr>
            <tr>
                <td><strong>{{ $lang['start_time'] }}: </strong></td>
                <td colspan="3">{{ $info['start_time'] }}</td>
                <td><strong>{{ $lang['end_time'] }}: </strong></td>
                <td colspan="3">{{ $info['end_time'] }}</td>
            </tr>
            @if($result)
                <tr>
                    <td align="left"><strong>{{ $lang['bid_user'] }}: </strong></td>
                    <td>{{ $result['user_name'] }}</td>
                    <td><strong>{{ $lang['email'] }}: </strong></td>
                    <td>{{ $result['email'] }}</td>
                    <td><strong>{{ $lang['bid_time'] }}: </strong></td>
                    <td>{{ $result['bid_time'] }}</td>
                    <td><strong>{{ $lang['min_union_price'] }}: </strong></td>
                    <td>{{ $result['bid_price'] }}</td>
                </tr>
            @endif
        </table>
    </div>

    <form method="post" action="" name="listForm">
        <div class="list-div" id="listDiv">
            @endif

            <table cellpadding="3" cellspacing="1">
                <tr>
                    <th><a href="javascript:listTable.sort('log_id');">{{ $lang['record_id'] }}</a>{{ $sort_snatch_id }}
                    </th>
                    <th>
                        <a href="javascript:listTable.sort('user_name');">{{ $lang['bid_user'] }}</a>{{ $sort_user_name }}
                    </th>
                    <th><a href="javascript:listTable.sort('bid_time');">{{ $lang['bid_time'] }}</a>{{ $sort_bid_time }}
                    </th>
                    <th>
                        <a href="javascript:listTable.sort('bid_price');">{{ $lang['bid_price'] }}</a>{{ $sort_bid_price }}
                    </th>
                </tr>
                @forelse($bid_list as $bid)
                    <tr>
                        <td align="center">{{ $bid['log_id'] }}</td>
                        <td class="first-cell">{{ $bid['user_name'] }}</td>
                        <td align="right">{{ $bid['bid_time'] }}</td>
                        <td align="right">{{ $bid['bid_price'] }}</td>
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

    <script type="text/javascript">
        listTable.recordCount = {{ $record_count }};
        listTable.pageCount = {{ $page_count }};
        listTable.query = "query_bid";

        @foreach($filter as $item => $key)
            listTable.filter.{{ $key }} = '{{ $item }}';
        @endforeach
    </script>
    @include('admin::pagefooter')
@endif
