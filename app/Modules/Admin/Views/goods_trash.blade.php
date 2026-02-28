@if($full_page)
    @include('admin::pageheader')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('js/transport.js') }}"></script>
    <script src="{{ asset('static/admin/js/listtable.js') }}"></script>

    <!-- 商品搜索 -->
    @include('goods_search')

    <!-- 商品列表 -->
    <form method="post" action="" name="listForm" onsubmit="return confirmSubmit(this)">
        <!-- start goods list -->
        <div class="list-div" id="listDiv">
            @endif
            <table cellpadding="3" cellspacing="1">
                <tr>
                    <th>
                        <input onclick='listTable.selectAll(this, "checkboxes")' type="checkbox"/>
                        <a href="javascript:listTable.sort('goods_id'); ">{{ $lang['record_id'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th><a href="javascript:listTable.sort('goods_name'); ">{{ $lang['goods_name'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th><a href="javascript:listTable.sort('goods_sn'); ">{{ $lang['goods_sn'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th><a href="javascript:listTable.sort('shop_price'); ">{{ $lang['shop_price'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th>{{ $lang['handler'] }}</th>
                <tr>
                @forelse($goods_list as $goods)
                    <tr>
                        <td><input type="checkbox" name="checkboxes[]"
                                   value="{{ $goods['goods_id'] }}"/>{{ $goods['goods_id'] }}</td>
                        <td>{{ $goods['goods_name'] }}</td>
                        <td>{{ $goods['goods_sn'] }}</td>
                        <td align="right">{{ $goods['shop_price'] }}</td>
                        <td align="center">
                            <a href="javascript:;"
                               onclick="listTable.remove({{ $goods['goods_id'] }}, '{{ $lang['restore_goods_confirm'] }}', 'restore_goods')">{{ $lang['restore'] }}</a>
                            |
                            <a href="javascript:;"
                               onclick="listTable.remove({{ $goods['goods_id'] }}, '{{ $lang['drop_goods_confirm'] }}', 'drop_goods')">{{ $lang['drop'] }}</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="no-records" colspan="10">{{ $lang['no_records'] }}</td>
                    </tr>
                @endforelse
            </table>
            <!-- end goods list -->

            <!-- 分页 -->
            <table id="page-table" cellspacing="0">
                <tr>
                    <td>
                        <input type="hidden" name="act" value="batch"/>
                        <select name="type" id="selAction" onchange="changeAction()">
                            <option value="">{{ $lang['select_please'] }}</option>
                            <option value="restore">{{ $lang['restore'] }}</option>
                            <option value="drop">{{ $lang['remove'] }}</option>
                        </select>
                        <select name="target_cat" style="display:none" onchange="checkIsLeaf(this)">
                            <option value="0">{{ $lang['select_please'] }}</caption>{{ $cat_list }}
                        </select>
                        <input type="submit" value="{{ $lang['button_submit'] }}" id="btnSubmit" name="btnSubmit"
                               class="button" disabled="true"/>
                    </td>
                    <td align="right" nowrap="true">
                        @include('admin::page')
                    </td>
                </tr>
            </table>
        </div>

@if($full_page)
    </form>

    <script type="text/javascript">
        listTable.recordCount = {{ $record_count }};
        listTable.pageCount = {{ $page_count }};

        @foreach($filter as $item => $key)
            listTable.filter.{{ $key }} = '{{ $item }}';
        @endforeach
            onload = function () {
            document.forms['listForm'].reset();
        }

        function confirmSubmit(frm, ext) {
            if (frm.elements['type'].value === 'restore') {
                return confirm("{{ $lang['restore_goods_confirm'] }}");

            } else if (frm.elements['type'].value === 'drop') {
                return confirm("{{ $lang['batch_drop_confirm'] }}");

            } else if (frm.elements['type'].value === '') {
                return false;
            } else {
                return true;
            }
        }

        function changeAction() {
            var frm = document.forms['listForm'];

            if (!document.getElementById('btnSubmit').disabled &&
                confirmSubmit(frm, false)) {
                frm.submit();
            }
        }

    </script>
    @include('admin::pagefooter')
@endif
