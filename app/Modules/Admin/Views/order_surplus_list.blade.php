@if($full_page)
    @include('admin::pageheader')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('static/admin/js/listtable.js') }}"></script>

    <div class="form-div">
        <form action="javascript:searchUser()" name="searchForm">
            <img src="{{ asset('static/admin/images/icon_search.gif') }}" width="26" height="22" border="0"
                 alt="SEARCH"/>
            &nbsp;{{ $lang['label_user_name'] }} &nbsp;<input type="text" name="keyword"/> <input type="submi
  t     "
                                                                                                  value="{{ $lang['button_search'] }}"/>
        </form>
    </div>

    <form method="POST" action="" name="listForm">
        <!-- start users list -->
        <div class="list-div" id="listDiv">
            @endif
            <!--用户列表部分-->
            <table cellpadding="3" cellspacing="1">
                <tr>
                    <th>
                        <a href="javascript:listTable.sort('user_name'); ">{{ $lang['username'] }}</a>{{ $sort_user_name }}
                    </th>
                    <th>
                        <a href="javascript:listTable.sort('order_sn'); ">{{ $lang['order_sn'] }}</a>{{ $sort_order_sn }}
                    </th>
                    <th>{{ $lang['surplus'] }}</th>
                    <th>{{ $lang['integral_money'] }}</th>
                    <th>
                        <a href="javascript:listTable.sort('add_time'); ">{{ $lang['add_time'] }}</a>{{ $sort_add_time }}
                    </th>
                    <th>{{ $lang['handler'] }}</th>
                <tr>
                @forelse($order_list as $order)
                    <tr align="center">
                        <td class="first-cell">{{ $order['user_name'] }}</td>
                        <td>{{ $order['order_sn'] }}</td>
                        <td>{{ $order['surplus'] }}</td>
                        <td>{{ $order['integral_money'] }}</td>
                        <td align="center">{{ $order['add_time'] }}</td>
                        <td align="center">

                            <a href="order.php?act=info&order_id={{ $order['order_id'] }}"
                               title="{{ $lang['view_order'] }}">{{ $lang['view'] }}</a>
                        </td>

                    </tr>

                @empty
                    <tr>
                        <td class="no-records" colspan="10">{{ $lang['no_records'] }}</td>
                    </tr>
                @endforelse

                <tr>
                    <td colspan="2">
                        <input type="hidden" name="act" value="batch_remove"/>
                        <input type="submit" id="btnSubmit" value="{{ $lang['button_remove'] }}" disabled="true"
                               class="button"/>
                    </td>
                    <td align="right" nowrap="true" colspan="8">
                        @include('admin::page')
                    </td>
                </tr>
            </table>

@if($full_page)
        </div>
        <!-- end users list -->
    </form>
    <script type="text/javascript">
        listTable.recordCount = {{ $record_count }};
        listTable.pageCount = {{ $page_count }};

        @foreach($filter as $item => $key)
            listTable.filter.{{ $key }} = '{{ $item }}';
        @endforeach
            onload = function () {
            document.forms['searchForm'].elements['keyword'].focus();
        }

        /**
         * 搜索用户
         */
        function searchUser() {
            listTable.filter['keywords'] = Utils.trim(document.forms['searchForm'].elements['keyword'].value);
            listTable.filter['page'] = 1;
            listTable.loadList();
        }

    </script>

    @include('admin::pagefooter')
@endif
