@if($full_page)
    @include('admin::pageheader')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('static/admin/js/listtable.js') }}"></script>
    <div class="form-div">
        <form name="TimeInterval" action="javascript:getList()" style="margin:0px">
            {{ $lang['start_date'] }}&nbsp;{html_select_date field_order="YMD" prefix="start_date" time=$start_date
            start_year="-10" end_year="+1" display_days=true month_format="%m"}&nbsp;&nbsp;
            {{ $lang['end_date'] }}&nbsp;{html_select_date field_order="YMD" prefix="end_date" time=$end_date
            start_year="-10" end_year="+1" display_days=true month_format="%m"}
            <input type="submit" name="submit" value="{{ $lang['query'] }}" class="button"/>
        </form>
    </div>
    <form method="POST" action="" name="listForm">
        <div class="list-div" id="listDiv">
            @endif
            <table width="100%" cellspacing="1" cellpadding="3">
                <tr>
                    <th>{{ $lang['order_by'] }}</th>
                    <th>{{ $lang['goods_name'] }}</th>
                    <th>{{ $lang['goods_sn'] }}</th>
                    <th>
                        <a
                            href="javascript:listTable.sort('goods_num', 'DESC'); ">{{ $lang['sell_amount'] }}</a>{{ $sort_goods_num }}
                    </th>
                    <th>
                        <a
                            href="javascript:listTable.sort('turnover', 'DESC'); ">{{ $lang['sell_sum'] }}</a>{{ $sort_turnover }}
                    </th>
                    <th>{{ $lang['percent_count'] }}</th>
                </tr>
                @forelse($goods_order_data as $list)
                    <tr align="center">
                        <td>{{ $smarty['foreach']['val']['iteration'] }}</td>
                        <td align="left"><a href="../goods.php?id={{ $list['goods_id'] }}"
                                            target="_blank">{{ $list['goods_name'] }}</a></td>
                        <td>{{ $list['goods_sn'] }}</td>
                        <td align="right">{{ $list['goods_num'] }}</td>
                        <td align="right">{{ $list['turnover'] }}</td>
                        <td align="right">{{ $list['wvera_price'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td class="no-records" colspan="10">{{ $lang['no_records'] }}</td>
                    </tr>
                @endforelse
            </table>
            <table id="page-table" cellspacing="0">
                <tr>
                    <td>&nbsp;</td>
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

            onload = function () {
            getDownUrl();
        }

        function getList() {
            var frm = document.forms['TimeInterval'];
            listTable.filter['start_date'] = frm.elements['start_dateYear'].value + '-' + frm.elements['start_dateMonth'].value + '-' + frm.elements['start_dateDay'].value;
            listTable.filter['end_date'] = frm.elements['end_dateYear'].value + '-' + frm.elements['end_dateMonth'].value + '-' + frm.elements['end_dateDay'].value;
            listTable.filter['page'] = 1;
            listTable.loadList();
            getDownUrl();
        }

        function getDownUrl() {
            var aTags = document.getElementsByTagName('A');
            for (var i = 0; i < aTags.length; i++) {
                if (aTags[i].href.indexOf('download') >= 0) {
                    if (listTable.filter['start_date'] === "") {
                        var frm = document.forms['TimeInterval'];
                        listTable.filter['start_date'] = frm.elements['start_dateYear'].value + '-' + frm.elements['start_dateMonth'].value + '-' + frm.elements['start_dateDay'].value;
                        listTable.filter['end_date'] = frm.elements['end_dateYear'].value + '-' + frm.elements['end_dateMonth'].value + '-' + frm.elements['end_dateDay'].value;
                    }
                    aTags[i].href = "sale_order.php?act=download&start_date=" + listTable.filter['start_date'] + "&end_date=" + listTable.filter['end_date'];
                }
            }
        }

    </script>

    @include('admin::pagefooter')
@endif
