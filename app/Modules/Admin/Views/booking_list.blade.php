@if($full_page)
    @include('admin::pageheader')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('static/admin/js/listtable.js') }}"></script>
    <div class="form-div">
        <form action="javascript:searchGoodsname()" name="searchForm">
            <img src="{{ asset('static/admin/images/icon_search.gif') }}" width="26" height="22" border="0"
                 alt="SEARCH"/>
            {{ $lang['goods_name'] }} <input type="text" name="keyword"/> <input type="submit"
                                                                                 value="{{ $lang['button_search'] }}"
                                                                                 class="button"/>
        </form>
    </div>

    <form method="POST" action="" name="listForm">
        <div class="list-div" id="listDiv">
            @endif

            <table cellpadding="3" cellspacing="1">
                <tr>
                    <th><a href="javascript:listTable.sort('rec_id'); ">{{ $lang['record_id'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}"/>
                    </th>
                    <th><a href="javascript:listTable.sort('link_man'); ">{{ $lang['link_man'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}"/>
                    </th>
                    <th><a href="javascript:listTable.sort('goods_name'); ">{{ $lang['goods_name'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}"/>
                    </th>
                    <th><a href="javascript:listTable.sort('goods_number'); ">{{ $lang['number'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}"/>
                    </th>
                    <th><a href="javascript:listTable.sort('booking_time'); ">{{ $lang['booking_time'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}"/>
                    </th>
                    <th><a href="javascript:listTable.sort('is_dispose'); ">{{ $lang['is_dispose'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}"/>
                    </th>
                    <th>{{ $lang['handler'] }}</th>
                </tr>
                @forelse($booking_list as $booking)
                    <tr>
                        <td>{{ $booking['rec_id'] }}</td>
                        <td>{{ $booking['link_man'] }}</td>
                        <td><a href="../goods.php?id={{ $booking['goods_id'] }}" target="_blank"
                               title="{{ $lang['view'] }}">{{ $booking['goods_name'] }}</a></td>
                        <td align="right">{{ $booking['goods_number'] }}</td>
                        <td align="right">{{ $booking['booking_time'] }}</td>
                        <td align="center"><img
                                src="{{ asset('static/admin/images/'.($booking['is_dispose'] ? 'yes' : 'no').'.gif') }}"/>
                        </td>
                        <td align="center">
                            <a href="?act=detail&amp;id={{ $booking['rec_id'] }}" title="{{ $lang['detail'] }}"><img
                                    src="{{ asset('static/admin/images/icon_view.gif') }}" border="0" height="16"
                                    width="16"/></a>
                            <a href="javascript:;"
                               onclick="listTable.remove({{ $booking['rec_id'] }},'{{ $lang['drop_confirm'] }}')"
                               title="{{ $lang['remove'] }}"><img src="{{ asset('static/admin/images/icon_drop.gif') }}"
                                                                  border="0" height="16" width="16"/></a>
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


        /**
         * 搜索标题
         */
        function searchGoodsname() {
            var keyword = Utils.trim(document.forms['searchForm'].elements['keyword'].value);
            listTable.filter['keywords'] = keyword;
            listTable.filter['page'] = 1;
            listTable.loadList("get_bookinglist");
        }
    </script>
    @include('admin::pagefooter')
@endif
