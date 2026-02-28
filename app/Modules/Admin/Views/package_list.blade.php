@if($full_page)
    @include('admin::pageheader')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('static/admin/js/listtable.js') }}"></script>
    <div class="form-div">
        <form action="javascript:searchPackage()" name="searchForm">
            <img src="{{ asset('static/admin/images/icon_search.gif') }}" width="26" height="22" border="0"
                 alt="SEARCH"/>
            {{ $lang['package_name'] }} <input type="text" name="keyword"/> <input type="submit"
                                                                                   value="{{ $lang['button_search'] }}"
                                                                                   class="button"/>
        </form>
    </div>

    <form method="post" action="" name="listForm">
        <div class="list-div" id="listDiv">
            @endif

            <table cellpadding="3" cellspacing="1">
                <tr>
                    <th><a href="javascript:listTable.sort('act_id'); ">{{ $lang['record_id'] }}</a>{{ $sort_act_id }}
                    </th>
                    <th><a
                            href="javascript:listTable.sort('package_name'); ">{{ $lang['package_name'] }}</a>{{ $sort_package_name }}
                    </th>
                    <th>
                        <a href="javascript:listTable.sort('start_time'); ">{{ $lang['start_time'] }}</a>{{ $sort_start_time }}
                    </th>
                    <th>
                        <a href="javascript:listTable.sort('end_time'); ">{{ $lang['end_time'] }}</a>{{ $sort_end_time }}
                    </th>
                    <th>{{ $lang['handler'] }}</th>
                </tr>
                @forelse($package_list as $package)
                    <tr>
                        <td align="center">{{ $package['act_id'] }}</td>
                        <td class="first-cell"><span
                                onclick="listTable.edit(this, 'edit_package_name', {{ $package['act_id'] }})">{{ $package['package_name'] }}</span>
                        </td>
                        <td align="center">{{ $package['start_time'] }}</td>
                        <td align="center">{{ $package['end_time'] }}</td>
                        <td align="center">
                            <a href="package.php?act=edit&amp;id={{ $package['act_id'] }}"
                               title="{{ $lang['edit'] }}"><img
                                    src="{{ asset('static/admin/images/icon_edit.gif') }}" border="0" height="16"
                                    width="16"></a>
                            <a href="javascript:;"
                               onclick="listTable.remove({{ $package['act_id'] }},'{{ $lang['drop_confirm'] }}')"
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
         * 搜索礼包
         */
        function searchPackage() {
            var keyword = Utils.trim(document.forms['searchForm'].elements['keyword'].value);
            listTable.filter.keywords = keyword;
            listTable.filter.page = 1;
            listTable.loadList();
        }
    </script>
    @include('admin::pagefooter')
@endif
