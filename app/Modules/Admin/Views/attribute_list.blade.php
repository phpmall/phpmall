@if($full_page)
    @include('admin::pageheader')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('static/admin/js/listtable.js') }}"></script>

    <div class="form-div">
        <form action="" name="searchForm">
            <img src="{{ asset('static/admin/images/icon_search.gif') }}" width="26" height="22" border="0"
                 alt="SEARCH"/>
            {{ $lang['by_goods_type'] }}<select name="goods_type" onchange="searchAttr(this.value)">
                <option value="0">{{ $lang['all_goods_type'] }}</caption>{{ $goods_type_list }}
            </select>
        </form>
    </div>

    <form method="post" action="attribute.php?act=batch" name="listForm">
        <div class="list-div" id="listDiv">
            @endif

            <table cellpadding="3" cellspacing="1">
                <tr>
                    <th>
                        <input onclick='listTable.selectAll(this, "checkboxes[]")' type="checkbox">
                        <a href="javascript:listTable.sort('attr_id'); ">{{ $lang['record_id'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th><a href="javascript:listTable.sort('attr_name'); ">{{ $lang['attr_name'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th><a href="javascript:listTable.sort('cat_id'); ">{{ $lang['cat_id'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th><a href="javascript:listTable.sort('attr_input_type');">{{ $lang['attr_input_type'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th>{{ $lang['attr_values'] }}</th>
                    <th><a href="javascript:listTable.sort('sort_order'); ">{{ $lang['sort_order'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th>{{ $lang['handler'] }}</th>
                </tr>
                @forelse($attr_list as $attr)
                    <tr>
                        <td nowrap="true" valign="top"><span><input value="{{ $attr['attr_id'] }}" name="checkboxes[]"
                                                                    type="checkbox">{{ $attr['attr_id'] }}</span></td>
                        <td class="first-cell" nowrap="true" valign="top"><span
                                onclick="listTable.edit(this, 'edit_attr_name', {{ $attr['attr_id'] }})">{{ $attr['attr_name'] }}</span>
                        </td>
                        <td nowrap="true" valign="top"><span>{{ $attr['cat_name'] }}</span></td>
                        <td nowrap="true" valign="top"><span>{{ $attr['attr_input_type_desc'] }}</span></td>
                        <td valign="top"><span>{{ $attr['attr_values'] }}</span></td>
                        <td align="right" nowrap="true" valign="top"><span
                                onclick="listTable.edit(this, 'edit_sort_order', {{ $attr['attr_id'] }})">{{ $attr['sort_order'] }}</span>
                        </td>
                        <td align="center" nowrap="true" valign="top">
                            <a href="?act=edit&amp;attr_id={{ $attr['attr_id'] }}" title="{{ $lang['edit'] }}"><img
                                    src="{{ asset('static/admin/images/icon_edit.gif') }}" border="0" height="16"
                                    width="16"></a>
                            <a href="javascript:;" onclick="removeRow({{ $attr['attr_id'] }})"
                               title="{{ $lang['remove'] }}"><img src="{{ asset('static/admin/images/icon_drop.gif') }}"
                                                                  border="0" height="16" width="16"></a>
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
                    <td><input type="submit" id="btnSubmit" value="{{ $lang['drop'] }}" class="button" disabled="true"/>
                    </td>
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
         * 查询商品属性
         */
        function searchAttr(goodsType) {
            listTable.filter.goods_type = goodsType;
            listTable.filter.page = 1;
            listTable.loadList();
        }

        function removeRow(attr_id) {
            Ajax.call('attribute.php?act=get_attr_num&attr_id=' + attr_id, '', removeRowResponse, 'GET', 'JSON');
        }

        function removeRowResponse(result) {
            if (result.message.length > 0) {
                alert(result.message);
            }

            if (result.error === 0) {
                listTable.remove(result.content.attr_id, result.content.drop_confirm);
            }
        }
    </script>
    @include('admin::pagefooter')
@endif
