@if($full_page)
    @include('admin::pageheader')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('static/admin/js/listtable.js') }}"></script>

    <div class="form-div">
        <form action="javascript:searchActivity()" name="searchForm">
            <img src="{{ asset('static/admin/images/icon_search.gif') }}" width="26" height="22" border="0"
                 alt="SEARCH"/>
            {{ $lang['goods_name'] }} <input type="text" name="keyword" size="30"/>
            <input type="submit" value="{{ $lang['button_search'] }}" class="button"/>
        </form>
    </div>

    <form method="post" action="wholesale.php" name="listForm" onsubmit="return confirm(batch_drop_confirm);">
        <!-- start wholesale list -->
        <div class="list-div" id="listDiv">
            @endif

            <table cellpadding="3" cellspacing="1">
                <tr>
                    <th>
                        <input onclick='listTable.selectAll(this, "checkboxes")' type="checkbox"/>
                        <a href="javascript:listTable.sort('act_id'); ">{{ $lang['record_id'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}"/>
                    </th>
                    <th><a href="javascript:listTable.sort('goods_name'); ">{{ $lang['goods_name'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}"/>
                    </th>
                    <th>{{ $lang['rank_name'] }}</th>
                    <th><a href="javascript:listTable.sort('enabled'); ">{{ $lang['enabled'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}"/>
                    </th>
                    <th>{{ $lang['handler'] }}</th>
                </tr>

                @forelse($wholesale_list as $wholesale)
                    <tr>
                        <td><input value="{{ $wholesale['act_id'] }}" name="checkboxes[]"
                                   type="checkbox">{{ $wholesale['act_id'] }}</td>
                        <td>{{ $wholesale['goods_name'] }}</td>
                        <td>{{ $wholesale['rank_names'] }}</td>
                        <td align="center"><img
                                src="{{ asset('static/admin/images/'.($wholesale['enabled'] ? 'yes' : 'no').'.gif') }}"
                                onclick="listTable.toggle(this, 'toggle_enabled', {{ $wholesale['act_id'] }})"/></td>
                        <td align="center">
                            <a href="wholesale.php?act=edit&amp;id={{ $wholesale['act_id'] }}"
                               title="{{ $lang['edit'] }}"><img src="{{ asset('static/admin/images/icon_edit.gif') }}"
                                                                border="0" height="16" width="16"/></a>
                            <a href="javascript:;"
                               onclick="listTable.remove({{ $wholesale['act_id'] }},'{{ $lang['drop_confirm'] }}')"
                               title="{{ $lang['remove'] }}"><img src="{{ asset('static/admin/images/icon_drop.gif') }}"
                                                                  border="0" height="16" width="16"/></a></td>
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
                               disabled="true"/>
                        <input type="hidden" name="act" value="batch"/></td>
                    <td align="right">@include('admin::page')</td>
                </tr>
            </table>

@if($full_page)
        </div>
        <!-- end wholesale list -->
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
            listTable.filter['page'] = 1;
            listTable.loadList("wholesale_list");
        }
    </script>

    @include('admin::pagefooter')
@endif
