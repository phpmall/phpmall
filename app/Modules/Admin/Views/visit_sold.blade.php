@include('admin::pageheader')
<div class="form-div">
    <form name="TimeInterval" action="visit_sold.php?act=list" method="post" style="margin:0px">
        {{ $lang['goods_cat'] }}
        <select name="cat_id">
            <option value="0">{{ $lang['select_please'] }}</option>{{ $cat_list }}
        </select>
        {{ $lang['goods_brand'] }}
        <select name="brand_id">
            <option value="0">{{ $lang['select_please'] }}</caption>
                {html_options options=$brand_list selected=$brand_id}
        </select>
        {{ $lang['show_num'] }}
        <input name="show_num" type="text" size="8" value="{{ $show_num }}"/>
        <input type="hidden" name="order_type" value="{{ $order_type }}"/>
        <input type="submit" name="submit" value="{{ $lang['query'] }}" class="button"/>
    </form>
</div>
<div class="list-div">
    <table width="100%" cellpadding="3" cellspacing="1">
        <tr>
            <th>{{ $lang['order_by'] }}</th>
            <th>{{ $lang['goods_name'] }}</th>
            <th>{{ $lang['fav_exponential'] }}</th>
            <th>{{ $lang['buy_times'] }}</th>
            <th>{{ $lang['visit_buy'] }}</th>
        </tr>
        @forelse($click_sold_info as $Key => $list)
            <tr align="right">
                <td align="center">{$Key+1}</td>
                <td align="left"><a href="../goods.php?id={{ $list['goods_id'] }}"
                                    target="_blank">{{ $list['goods_name'] }}</a></td>
                <td>{{ $list['click_count'] }}</td>
                <td>{{ $list['sold_times'] }}</td>
                <td>{{ $list['scale'] }}</td>
            </tr>
        @empty
            <tr>
                <td class="no-records" colspan="10">{{ $lang['no_records'] }}</td>
            </tr>
        @endforelse
    </table>
</div>

<script type="text/javascript">

</script>
@include('admin::pagefooter')
