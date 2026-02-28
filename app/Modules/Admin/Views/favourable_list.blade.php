@if($full_page)
    @include('admin::pageheader')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('static/admin/js/listtable.js') }}"></script>

    <div class="form-div">
        <form action="javascript:searchActivity()" name="searchForm">
            <img src="{{ asset('static/admin/images/icon_search.gif') }}" width="26" height="22" border="0"
                 alt="SEARCH"/>
            {{ $lang['goods_name'] }} <input type="text" name="keyword" size="30"/>
            <input name="is_going" type="checkbox" id="is_going" value="1"/>
            {{ $lang['act_is_going'] }}
            <input type="submit" value="{{ $lang['button_search'] }}" class="button"/>
        </form>
    </div>

    <form method="post" action="favourable.php" name="listForm" onsubmit="return confirm(batch_drop_confirm);">
        <!-- start favourable list -->
        <div class="list-div" id="listDiv">
            @endif

            <table cellpadding="3" cellspacing="1">
                <tr>
                    <th>
                        <input onclick='listTable.selectAll(this, "checkboxes")' type="checkbox"/>
                        <a href="javascript:listTable.sort('act_id'); ">{{ $lang['record_id'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th><a href="javascript:listTable.sort('act_name'); ">{{ $lang['act_name'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th><a href="javascript:listTable.sort('start_time'); ">{{ $lang['start_time'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th><a href="javascript:listTable.sort('end_time'); ">{{ $lang['end_time'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th>{{ $lang['min_amount'] }}</th>
                    <th>{{ $lang['max_amount'] }}</th>
                    <th><a href="javascript:listTable.sort('sort_order'); ">{{ $lang['sort_order'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th>{{ $lang['handler'] }}</th>
                </tr>

                @forelse($favourable_list as $favourable)
                    <tr>
                        <td><input value="{{ $favourable['act_id'] }}" name="checkboxes[]"
                                   type="checkbox">{{ $favourable['act_id'] }}
                        </td>
                        <td>{{ $favourable['act_name'] }}</td>
                        <td align="right">{{ $favourable['start_time'] }}</td>
                        <td align="right">{{ $favourable['end_time'] }}</td>
                        <td align="right">{{ $favourable['min_amount'] }}</td>
                        <td align="right">{{ $favourable['max_amount'] }}</td>
                        <td align="center"><span
                                onclick="listTable.edit(this, 'edit_sort_order', {{ $favourable['act_id'] }})">{{ $favourable['sort_order'] }}</span>
                        </td>
                        <td align="center">
                            <a href="favourable.php?act=edit&amp;id={{ $favourable['act_id'] }}"
                               title="{{ $lang['edit'] }}"><img
                                    src="{{ asset('static/admin/images/icon_edit.gif') }}" border="0" height="16"
                                    width="16"/></a>
                            <a href="javascript:;"
                               onclick="listTable.remove({{ $favourable['act_id'] }},'{{ $lang['drop_confirm'] }}')"
                               title="{{ $lang['remove'] }}"><img src="{{ asset('static/admin/images/icon_drop.gif') }}"
                                                                  border="0" height="16" width="16"/></a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="no-records" colspan="13">{{ $lang['no_records'] }}</td>
                    </tr>
                @endforelse
            </table>

            <table cellpadding="4" cellspacing="0">
                <tr>
                    <td><input type="submit" name="drop" id="btnSubmit" value="{{ $lang['drop'] }}" class="button"
                               disabled="true"/>
                        <input type="hidden" name="act" value="batch"/>
                    </td>
                    <td align="right">@include('admin::page')</td>
                </tr>
            </table>

@if($full_page)
        </div>
        <!-- end favourable list -->
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
         * 搜索团购活动
         */
        function searchActivity() {
            var keyword = Utils.trim(document.forms['searchForm'].elements['keyword'].value);
            listTable.filter['keyword'] = keyword;
            if (document.forms['searchForm'].elements['is_going'].checked) {
                listTable.filter['is_going'] = 1;
            } else {
                listTable.filter['is_going'] = 0;
            }
            listTable.filter['page'] = 1;
            listTable.loadList("favourable_list");
        }
    </script>

    @include('admin::pagefooter')
@endif
