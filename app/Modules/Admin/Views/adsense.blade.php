@include('admin::pageheader')
<!-- start ads_stats list -->
<div class="list-div" id="listDiv">
    <table width="100%" border="0" cellpadding="3" cellspacing="1">
        <tr>
            <th>{{ $lang['adsense_name'] }}</th>
            <th>{{ $lang['cleck_referer'] }}</th>
            <th>{{ $lang['click_count'] }}</th>
            <th>{{ $lang['confirm_order'] }}</th>
            <th>{{ $lang['gen_order_amount'] }}</th>
        </tr>
        @foreach($ads_stats as $list)
            <tr>
                <td>{{ $list['ad_name'] }}</td>
                <td>{{ $list['referer'] }}</td>
                <td align="right">{{ $list['clicks'] }}</td>
                <td align="right">{{ $list['order_confirm'] }}</td>
                <td align="right">{{ $list['order_num'] }}</td>
            </tr>
        @endforeach
        @forelse($goods_stats as $info)
            <tr>
                <td>{{ $info['ad_name'] }}</td>
                <td>{{ $info['referer'] }}</td>
                <td align="right">{{ $info['clicks'] }}</td>
                <td align="right">{{ $info['order_confirm'] }}</td>
                <td align="right">{{ $info['order_num'] }}</td>
            </tr>
        @empty
            <tr>
                <td class="no-records" colspan="10">{{ $lang['no_records'] }}</td>
            </tr>
        @endforelse
    </table>
</div>
<!-- end ads_stats list -->
<script src="{{ asset('js/utils.js') }}"></script>
<script src="{{ asset('static/admin/js/validator.js') }}"></script>

<script type="text/javascript">
</script>

@include('admin::pagefooter')
