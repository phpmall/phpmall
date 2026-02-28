@if($full_page)
    @include('admin::pageheader')
    <script type="text/javascript" src="../js/calendar.php?lang={{ $cfg_lang }}"></script>
    <link href="../js/calendar/calendar.css" rel="stylesheet" type="text/css"/>
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('static/admin/js/listtable.js') }}"></script>
    <div class="form-div">
        <form name="TimeInterval" action="javascript:getList()" style="margin:0px">
            {{ $lang['start_date'] }}&nbsp;
            <input name="start_date" type="text" id="start_date" size="15" value='{{ $start_date }}'
                   readonly="readonly"/><input name="selbtn1" type="button" id="selbtn1"
                                               onclick="return showCalendar('start_date', '%Y-%m-%d', false, false, 'selbtn1');"
                                               value="{{ $lang['btn_select'] }}" class="button"/>&nbsp;&nbsp;
            {{ $lang['end_date'] }}&nbsp;
            <input name="end_date" type="text" id="end_date" size="15" value='{{ $end_date }}'
                   readonly="readonly"/><input name="selbtn2" type="button" id="selbtn2"
                                               onclick="return showCalendar('end_date', '%Y-%m-%d', false, false, 'selbtn2');"
                                               value="{{ $lang['btn_select'] }}" class="button"/>&nbsp;&nbsp;
            <input type="submit" name="submit" value="{{ $lang['query'] }}" class="button"/>
        </form>
    </div>
    <form method="POST" action="" name="listForm">
        <div class="list-div" id="listDiv">
            @endif
            <table width="100%" cellspacing="1" cellpadding="3">
                <tr>
                    <th>{{ $lang['order_by'] }}</th>
                    <th>{{ $lang['member_name'] }}</th>
                    <th><a href="javascript:listTable.sort('order_num', 'DESC'); ">{{ $lang['order_amount'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}"/></th>
                    <th><a href="javascript:listTable.sort('turnover', 'DESC'); ">{{ $lang['buy_sum'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}"/></th>
                </tr>
                @forelse($user_orderinfo as $key => $list)
                    <tr align="center">
                        <td align="center">{{ $key+1 }}</td>
                        <td align="left">{{ $list['user_name'] }}</td>
                        <td>{{ $list['order_num'] }}</td>
                        <td>{{ $list['turnover'] }}</td>
                    </tr>
                    @else
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
            listTable.filter['start_date'] = frm.elements['start_date'].value;
            listTable.filter['end_date'] = frm.elements['end_date'].value;
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
                        listTable.filter['start_date'] = frm.elements['start_date'].value;
                        listTable.filter['end_date'] = frm.elements['end_date'].value;
                    }
                    aTags[i].href = "users_order.php?act=download&start_date=" + listTable.filter['start_date'] + "&end_date=" + listTable.filter['end_date'];
                }
            }
        }

    </script>

    @include('admin::pagefooter')
@endif
