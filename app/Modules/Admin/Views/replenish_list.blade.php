@if($full_page)
    @include('admin::pageheader')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('static/admin/js/listtable.js') }}"></script>

    <div class="form-div">
        <form action="javascript:searchSnatch()" name="searchForm">
            <img src="{{ asset('static/admin/images/icon_search.gif') }}" width="26" height="22" border="0"
                 alt="SEARCH"/>
            <select name="searchType">
                <option value="card_sn">{{ $lang['lab_card_sn'] }}</option>
                <option value="order_sn">{{ $lang['lab_order_sn'] }}</option>
            </select>
            <input type="text" name="keyword"/> <input type="submit" value="{{ $lang['button_search'] }}"
                                                       class="button"/>
        </form>
    </div>

    <form method="POST" action="virtual_card.php?act=batch_drop_card&goods_id={{ $goods_id }}" name="listForm">
        <!-- start card list -->
        <div class="list-div" id="listDiv">
            @endif

            <table cellpadding="3" cellspacing="1">
                <tr>
                    <th>
                        <input onclick='listTable.selectAll(this, "checkboxes")' type="checkbox">
                        <a href="javascript:listTable.sort('card_id'); ">{{ $lang['record_id'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th><a href="javascript:listTable.sort('card_sn'); ">{{ $lang['lab_card_sn'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th><a href="javascript:listTable.sort('card_password'); ">{{ $lang['lab_card_password'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th><a href="javascript:listTable.sort('end_date'); ">{{ $lang['lab_end_date'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th><a href="javascript:listTable.sort('is_saled'); ">{{ $lang['lab_is_saled'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th><a href="javascript:listTable.sort('order_sn'); ">{{ $lang['lab_order_sn'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th>{{ $lang['handler'] }}</th>
                </tr>
                @forelse($card_list as $card)
                    <tr>
                        <td><input value="{{ $card['card_id'] }}" name="checkboxes[]"
                                   type="checkbox">{{ $card['card_id'] }}</td>
                        <td><span>{{ $card['card_sn'] }}</span></td>
                        <td><span>{{ $card['card_password'] }}</span></td>
                        <td align="right"><span>{{ $card['end_date'] }}</span></td>
                        <td align="center"><img
                                src="{{ asset('static/admin/images/'.($card['is_saled'] ? 'yes' : 'no').'.gif') }}"/></span>
                        </td>
                        <td>{{ $card['order_sn'] }}</td>
                        <td align="center">
                            <a href="virtual_card.php?act=edit_replenish&amp;card_id={{ $card['card_id'] }}"
                               title="{{ $lang['edit'] }}"><img src="{{ asset('static/admin/images/icon_edit.gif') }}"
                                                                border="0" height="16" width="16"/></a>
                            <a href="javascript:;"
                               onclick="listTable.remove({{ $card['card_id'] }}, '{{ $lang['drop_confirm'] }}', 'remove_card')"
                               title="{{ $lang['drop'] }}"><img src="{{ asset('static/admin/images/icon_drop.gif') }}"
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
                    <td><input type="submit" name="drop" id="btnSubmit" value="{{ $lang['drop'] }}" class="button"
                               disabled="true"/></td>
                    <td align="right">@include('admin::page')</td>
                </tr>
            </table>

@if($full_page)
        </div>
        <!-- end card_list list -->
    </form>

    <script type="text/javascript">
        listTable.recordCount = {{ $record_count }};
        listTable.pageCount = {{ $page_count }};
        listTable.query = "query_card";

        @foreach($filter as $item => $key)
            listTable.filter.{{ $key }} = '{{ $item }}';
        @endforeach

            onload = function () {
            document.forms['searchForm'].elements['keyword'].focus();
        }

        /**
         * 搜索团购商品
         */
        function searchSnatch() {
            var keyword = Utils.trim(document.forms['searchForm'].elements['keyword'].value);
            var type = document.forms['searchForm'].elements['searchType'].value;

            if (keyword.length > 0) {
                listTable.filter['search_type'] = type;
                listTable.filter['keyword'] = keyword;
                listTable.loadList();
            } else {
                document.forms['searchForm'].elements['keyword'].focus();
            }
        }

    </script>
    @include('admin::pagefooter')
@endif
