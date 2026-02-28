@if($full_page)
    @include('admin::pageheader')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('static/admin/js/listtable.js') }}"></script>
    <!-- 订单搜索 -->
    <div class="form-div">
        <form action="javascript:searchOrder()" name="searchForm">
            <img src="{{ asset('static/admin/images/icon_search.gif') }}" width="26" height="22" border="0"
                 alt="SEARCH"/>
            {{ $lang['label_delivery_sn'] }}<input name="delivery_sn" type="text" id="delivery_sn" size="15">
            {{ $lang['order_sn'] }}<input name="order_sn" type="text" id="order_sn" size="15">
            {{ $lang['consignee'] }}<input name="consignee" type="text" id="consignee" size="15">
            <input type="submit" value="{{ $lang['button_search'] }}" class="button"/>
        </form>
    </div>

    <!-- 订单列表 -->
    <form method="post" action="order.php?act=operate" name="listForm" onsubmit="return check()">
        <div class="list-div" id="listDiv">
            @endif

            <table cellpadding="3" cellspacing="1">
                <tr>
                    <th>
                        <input onclick='listTable.selectAll(this, "back_id")' type="checkbox"/><a
                            href="javascript:listTable.sort('delivery_sn', 'DESC'); ">{{ $lang['label_delivery_sn'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th><a href="javascript:listTable.sort('order_sn', 'DESC'); ">{{ $lang['order_sn'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th><a href="javascript:listTable.sort('add_time', 'DESC'); ">{{ $lang['label_add_time'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th><a href="javascript:listTable.sort('consignee', 'DESC'); ">{{ $lang['consignee'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th>
                        <a href="javascript:listTable.sort('update_time', 'DESC'); ">{{ $lang['label_update_time'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th>{{ $lang['label_return_time'] }}</th>
                    <th>{{ $lang['operator'] }}</th>
                    <th>{{ $lang['handler'] }}</th>
                <tr>
                @foreach($back_list as $back => $dkey)
                    <tr>
                        <td valign="top" nowrap="nowrap"><input type="checkbox" name="back_id[]"
                                                                value="{{ $back['back_id'] }}"/>{{ $back['delivery_sn'] }}
                        </td>
                        <td>{{ $back['order_sn'] }}<br/></td>
                        <td align="center" valign="top" nowrap="nowrap">{{ $back['add_time'] }}</td>
                        <td align="left" valign="top"><a
                                href="mailto:{{ $back['email'] }}"> {{ $back['consignee'] }}</a></td>
                        <td align="center" valign="top" nowrap="nowrap">{{ $back['update_time'] }}</td>
                        <td align="center" valign="top" nowrap="nowrap">{{ $back['return_time'] }}</td>
                        <td align="center" valign="top" nowrap="nowrap">{{ $back['action_user'] }}</td>
                        <td align="center" valign="top" nowrap="nowrap">
                            <a href="order.php?act=back_info&back_id={{ $back['back_id'] }}">{{ $lang['detail'] }}</a>
                            <a onclick="{if(confirm('{{ $lang['confirm_delete'] }}')){return true;}return false;}"
                               href="order.php?act=operate&remove_back=1&back_id={{ $back['back_id'] }}">{{ $lang['remove'] }}</a>
                        </td>
                    </tr>
                @endforeach
            </table>

            <!-- 分页 -->
            <table id="page-table" cellspacing="0">
                <tr>
                    <td align="right" nowrap="true">
                        @include('admin::page')
                    </td>
                </tr>
            </table>

@if($full_page)
        </div>
        <div>
            <input name="remove_back" type="submit" id="btnSubmit3" value="{{ $lang['remove'] }}" class="button"
                   disabled="true" onclick="{if(confirm('{{ $lang['confirm_delete'] }}')){return true;}return false;}"/>
        </div>
    </form>
    <script type="text/javascript">
        listTable.recordCount = {{ $record_count }};
        listTable.pageCount = {{ $page_count }};

        @foreach($filter as $item => $key)
            listTable.filter.{{ $key }} = '{{ $item }}';
        @endforeach
            onload = function () {
            listTable.query = "back_query";
        }

        /**
         * 搜索订单
         */
        function searchOrder() {
            listTable.filter['order_sn'] = Utils.trim(document.forms['searchForm'].elements['order_sn'].value);
            listTable.filter['consignee'] = Utils.trim(document.forms['searchForm'].elements['consignee'].value);
            listTable.filter['delivery_sn'] = document.forms['searchForm'].elements['delivery_sn'].value;
            listTable.filter['page'] = 1;
            listTable.query = "back_query";
            listTable.loadList();
        }

        function check() {
            var snArray = new Array();
            var eles = document.forms['listForm'].elements;
            for (var i = 0; i < eles.length; i++) {
                if (eles[i].tagName === 'INPUT' && eles[i].type === 'checkbox' && eles[i].checked && eles[i].value != 'on') {
                    snArray.push(eles[i].value);
                }
            }
            if (snArray.length === 0) {
                return false;
            } else {
                eles['order_id'].value = snArray.toString();
                return true;
            }
        }
    </script>
    @include('admin::pagefooter')
@endif
