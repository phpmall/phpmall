@include('admin::pageheader')
<div class="form-div">
    <strong>{{ $lang['label_act_name'] }}</strong>{{ $auction['act_name'] }}
    <strong>{{ $lang['label_goods_name'] }}</strong>{{ $auction['goods_name'] }}
    <a href="auction.php?act=edit&id={{ $auction['act_id'] }}"> [ {{ $lang['view'] }} ] </a>
</div>

<div class="list-div" id="listDiv">
    <table cellpadding="3" cellspacing="1">
        <tr>
            <th>{{ $lang['bid_user'] }}</th>
            <th>{{ $lang['bid_price'] }}</th>
            <th width="30%">{{ $lang['bid_time'] }}</th>
            <th>{{ $lang['status'] }}</th>
        </tr>
        @forelse($auction_log as $log)
            <tr>
                <td>{{ $log['user_name'] }}</td>
                <td align="right">{{ $log['formated_bid_price'] }}</td>
                <td align="center">{{ $log['bid_time'] }}</td>
                <td align="center">'.($loop->fe_log.first ? 'OK' : '&nbsp;').'</td>
            </tr>
        @empty
            <tr>
                <td class="no-records" colspan="4">{{ $lang['no_records'] }}</td>
            </tr>
        @endforelse
    </table>
</div>
@include('admin::pagefooter')
