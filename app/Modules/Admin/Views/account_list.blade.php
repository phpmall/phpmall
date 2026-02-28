@if($full_page)
    @include('admin::pageheader')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('static/admin/js/listtable.js') }}"></script>

    <div class="form-div">
        <form method="post" action="account_log.php?act=list&user_id={{ $smarty['get']['user_id'] }}" name="searchForm">
            <select name="account_type" onchange="document.forms['searchForm'].submit()">
                <option value="" @if($account_type === '')selected="selected"@endif>{{ $lang['all_account'] }}</option>
                <option value="user_money"
                        @if($account_type === 'user_money')selected="selected"@endif>{{ $lang['user_money'] }}</option>
                <option value="frozen_money"
                        @if($account_type === 'frozen_money')selected="selected"@endif>{{ $lang['frozen_money'] }}</option>
                <option value="rank_points"
                        @if($account_type === 'rank_points')selected="selected"@endif>{{ $lang['rank_points'] }}</option>
                <option value="pay_points"
                        @if($account_type === 'pay_points')selected="selected"@endif>{{ $lang['pay_points'] }}</option>
            </select>
            <strong>{{ $lang['label_user_name'] }}</strong>{{ $user['user_name'] }}
            <strong>{{ $lang['label_user_money'] }}</strong>{{ $user['formated_user_money'] }}
            <strong>{{ $lang['label_frozen_money'] }}</strong>{{ $user['formated_frozen_money'] }}
            <strong>{{ $lang['label_rank_points'] }}</strong>{{ $user['rank_points'] }}
            <strong>{{ $lang['label_pay_points'] }}</strong>{{ $user['pay_points'] }}
        </form>
    </div>

    <form method="post" action="" name="listForm">
        <div class="list-div" id="listDiv">
            @endif

            <table cellpadding="3" cellspacing="1">
                <tr>
                    <th width="20%">{{ $lang['change_time'] }}</th>
                    <th width="30%">{{ $lang['change_desc'] }}</th>
                    <th>{{ $lang['user_money'] }}</th>
                    <th>{{ $lang['frozen_money'] }}</th>
                    <th>{{ $lang['rank_points'] }}</th>
                    <th>{{ $lang['pay_points'] }}</th>
                </tr>
                @forelse($account_list as $account)
                    <tr>
                        <td>{{ $account['change_time'] }}</td>
                        <td>{{ $account['change_desc'] }}</td>
                        <td align="right">
                            @if($account['user_money'] > 0)
                                <span style="color:#0000FF">+{{ $account['user_money'] }}</span>
                            @elseif($account['user_money'] < 0)
                                <span style="color:#FF0000">{{ $account['user_money'] }}</span>
                            @else
                                {{ $account['user_money'] }}
                            @endif
                        </td>
                        <td align="right">
                            @if($account['frozen_money'] > 0)
                                <span style="color:#0000FF">+{{ $account['frozen_money'] }}</span>
                            @elseif($account['frozen_money'] < 0)
                                <span style="color:#FF0000">{{ $account['frozen_money'] }}</span>
                            @else
                                {{ $account['frozen_money'] }}
                            @endif
                        </td>
                        <td align="right">
                            @if($account['rank_points'] > 0)
                                <span style="color:#0000FF">+{{ $account['rank_points'] }}</span>
                            @elseif($account['rank_points'] < 0)
                                <span style="color:#FF0000">{{ $account['rank_points'] }}</span>
                            @else
                                {{ $account['rank_points'] }}
                            @endif
                        </td>
                        <td align="right">
                            @if($account['pay_points'] > 0)
                                <span style="color:#0000FF">+{{ $account['pay_points'] }}</span>
                            @elseif($account['pay_points'] < 0)
                                <span style="color:#FF0000">{{ $account['pay_points'] }}</span>
                            @else
                                {{ $account['pay_points'] }}
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="no-records" colspan="6">{{ $lang['no_records'] }}</td>
                    </tr>
                @endforelse
            </table>
            <table id="page-table" cellspacing="0">
                <tr>
                    <td align="right" nowrap="true">
                        @include('admin::page')
                    </td>
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
