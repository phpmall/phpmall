@if($full_page)
    @include('admin::pageheader')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('static/admin/js/listtable.js') }}"></script>
    <div class="form-div">
        <form action="javascript:searchArticle()" name="searchForm">
            <img src="{{ asset('static/admin/images/icon_search.gif') }}" width="26" height="22" border="0"
                 alt="SEARCH"/>
            {{ $lang['title'] }} <input type="text" name="keyword" id="keyword"/>
            <input type="submit" value="{{ $lang['button_search'] }}" class="button"/>
        </form>
    </div>

    <form method="POST" action="exchange_goods.php?act=batch_remove" name="listForm">
        <!-- start cat list -->
        <div class="list-div" id="listDiv">
            @endif

            <table cellspacing='1' cellpadding='3' id='list-table'>
                <tr>
                    <th><input onclick='listTable.selectAll(this, "checkboxes")' type="checkbox">
                        <a href="javascript:listTable.sort('goods_id'); ">{{ $lang['goods_id'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}"/>
                    </th>
                    <th><a href="javascript:listTable.sort('goods_name'); ">{{ $lang['goods_name'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}"/>
                    </th>
                    <th><a href="javascript:listTable.sort('exchange_integral'); ">{{ $lang['exchange_integral'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}"/>
                    </th>
                    <th><a href="javascript:listTable.sort('is_exchange'); ">{{ $lang['is_exchange'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}"/>
                    </th>
                    <th><a href="javascript:listTable.sort('is_hot'); ">{{ $lang['is_hot'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}"/>
                    </th>
                    <th>{{ $lang['handler'] }}</th>
                </tr>
                @forelse($goods_list as $list)
                    <tr>
                        <td><span><input name="checkboxes[]" type="checkbox"
                                         value="{{ $list['goods_id'] }}"/>{{ $list['goods_id'] }}</span>
                        </td>
                        <td class="first-cell"><span>{{ $list['goods_name'] }}</span></td>
                        <td align="center"><span
                                onclick="listTable.edit(this, 'edit_exchange_integral', {{ $list['goods_id'] }})">{{ $list['exchange_integral'] }}</span>
                        </td>
                        <td align="center"><img
                                src="{{ asset('static/admin/images/' . ($list['is_exchange'] === 1 ? 'yes' : 'no') . '.gif') }}"
                                onclick="listTable.toggle(this, 'toggle_exchange', {{ $list['goods_id'] }})"/></td>
                        <td align="center"><img
                                src="{{ asset('static/admin/images/' . ($list['is_hot'] === 1 ? 'yes' : 'no') . '.gif') }}"
                                onclick="listTable.toggle(this, 'toggle_hot', {{ $list['goods_id'] }})"/></td>
                        <td align="center" nowrap="true"><span>
                            <a href="../exchange.php?id={{ $list['goods_id'] }}&act=view" target="_blank"
                               title="{{ $lang['view'] }}"><img src="{{ asset('static/admin/images/icon_view.gif') }}"
                                                                border="0" height="16" width="16"/></a>&nbsp;
                            <a href="exchange_goods.php?act=edit&id={{ $list['goods_id'] }}"
                               title="{{ $lang['edit'] }}"><img src="{{ asset('static/admin/images/icon_edit.gif') }}"
                                                                border="0" height="16" width="16"/></a>&nbsp;
                            <a href="javascript:;"
                               onclick="listTable.remove({{ $list['goods_id'] }}, '{{ $lang['drop_confirm'] }}')"
                               title="{{ $lang['remove'] }}"><img src="{{ asset('static/admin/images/icon_drop.gif') }}"
                                                                  border="0" height="16" width="16"></a></span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="no-records" colspan="5">{{ $lang['no_records'] }}</td>
                    </tr>
                @endforelse
                <tr>
                    <td colspan="2"><input type="submit" class="button" id="btnSubmit"
                                           value="{{ $lang['button_remove'] }}"
                                           disabled="true"/></td>
                    <td align="right" nowrap="true" colspan="8">@include('admin::page')</td>
                </tr>
            </table>

@if($full_page)
        </div>
        <!-- end cat list -->
        <script type="text/javascript">
            listTable.recordCount = {{ $record_count }};
            listTable.pageCount = {{ $page_count }};

            @foreach($filter as $item => $key)
                listTable.filter.{{ $key }} = '{{ $item }}';
            @endforeach


            // 搜索文章
            function searchArticle() {
                listTable.filter.keyword = Utils.trim(document.forms['searchForm'].elements['keyword'].value);
                listTable.filter.page = 1;
                listTable.loadList();
            }

        </script>
    @include('admin::pagefooter')
@endif
