@if($full_page)
    @include('admin::pageheader')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('static/admin/js/listtable.js') }}"></script>
    <div class="form-div">
        <form action="javascript:searchSnatch()" name="searchForm">
            <img src="{{ asset('static/admin/images/icon_search.gif') }}" width="26" height="22" border="0"
                 alt="SEARCH"/>
            {{ $lang['snatch_name'] }} <input type="text" name="keyword"/> <input type="submit"
                                                                                  value="{{ $lang['button_search'] }}"
                                                                                  class="button"/>
        </form>
    </div>

    <form method="post" action="" name="listForm">
        <div class="list-div" id="listDiv">
            @endif

            <table cellpadding="3" cellspacing="1">
                <tr>
                    <th><a href="javascript:listTable.sort('act_id'); ">{{ $lang['record_id'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}"/>
                    </th>
                    <th><a href="javascript:listTable.sort('snatch_name'); ">{{ $lang['snatch_name'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}"/>
                    </th>
                    <th><a href="javascript:listTable.sort('goods_name'); ">{{ $lang['goods_name'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}"/>
                    </th>
                    <th><a href="javascript:listTable.sort('start_time'); ">{{ $lang['start_time'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}"/>
                    </th>
                    <th><a href="javascript:listTable.sort('end_time'); ">{{ $lang['end_time'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}"/>
                    </th>
                    <th>{{ $lang['min_price'] }}</a></th>
                    <th>{{ $lang['integral'] }}</a></th>
                    <th>{{ $lang['handler'] }}</th>
                </tr>
                @forelse($snatch_list as $snatch)
                    <tr>
                        <td align="center">{{ $snatch['act_id'] }}</td>
                        <td class="first-cell"><span
                                onclick="listTable.edit(this, 'edit_snatch_name', {{ $snatch['act_id'] }})">{{ $snatch['snatch_name'] }}</span>
                        </td>
                        <td><span>{{ $snatch['goods_name'] }}</span></td>
                        <td align="center">{{ $snatch['start_time'] }}</td>
                        <td align="center">{{ $snatch['end_time'] }}</td>
                        <td align="right">{{ $snatch['start_price'] }}</td>
                        <td align="right">{{ $snatch['cost_points'] }}</td>
                        <td align="center">
                            <a href="snatch.php?act=view&amp;snatch_id={{ $snatch['act_id'] }}"
                               title="{{ $lang['view_detail'] }}"><img
                                    src="{{ asset('static/admin/images/icon_view.gif') }}" border="0" height="16"
                                    width="16"></a>
                            <a href="snatch.php?act=edit&amp;id={{ $snatch['act_id'] }}"
                               title="{{ $lang['edit'] }}"><img
                                    src="{{ asset('static/admin/images/icon_edit.gif') }}" border="0" height="16"
                                    width="16"></a>
                            <a href="javascript:;"
                               onclick="listTable.remove({{ $snatch['act_id'] }},'{{ $lang['drop_confirm'] }}')"
                               title="{{ $lang['remove'] }}"><img src="{{ asset('static/admin/images/icon_drop.gif') }}"
                                                                  border="0" height="16" width="16"></a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="no-records" colspan="10">{{ $lang['no_records'] }}</td>
                    </tr>
                @endforelse
                <tr>
                    <td align="right" nowrap="true" colspan="10">@include('admin::page')</td>
                </tr>
            </table>

@if($full_page)
        </div>
    </form>
    <!-- end article list -->

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
         * 搜索文章
         */
        function searchSnatch() {
            var keyword = Utils.trim(document.forms['searchForm'].elements['keyword'].value);
            listTable.filter.keywords = keyword;
            listTable.filter.page = 1;
            listTable.loadList();
        }
    </script>
    @include('admin::pagefooter')
@endif
