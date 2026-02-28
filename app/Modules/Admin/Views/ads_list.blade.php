@if($full_page)
    @include('admin::pageheader')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('static/admin/js/listtable.js') }}"></script>

    <form method="post" action="" name="listForm">
        <!-- start ads list -->
        <div class="list-div" id="listDiv">
            @endif

            <table cellpadding="3" cellspacing="1">
                <tr>
                    <th><a href="javascript:listTable.sort('ad_name'); ">{{ $lang['ad_name'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}"/>
                    </th>
                    <th><a href="javascript:listTable.sort('position_id'); ">{{ $lang['position_id'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}"/>
                    </th>
                    <th><a href="javascript:listTable.sort('media_type'); ">{{ $lang['media_type'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}"/>
                    </th>
                    <th><a href="javascript:listTable.sort('start_date'); ">{{ $lang['start_date'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}"/>
                    </th>
                    <th><a href="javascript:listTable.sort('end_date'); ">{{ $lang['end_date'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}"/>
                    </th>
                    <th><a href="javascript:listTable.sort('click_count'); ">{{ $lang['click_count'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}"/>
                    </th>
                    <th>{{ $lang['ads_stats'] }}</th>
                    <th>{{ $lang['handler'] }}</th>
                </tr>
                @forelse($ads_list as $list)
                    <tr>
                        <td class="first-cell">
                        <span
                            onclick="javascript:listTable.edit(this, 'edit_ad_name', {{ $list['ad_id'] }})">{{ $list['ad_name'] }}</span>
                        </td>
                        <td align="left">
                            <span>{{ ($list['position_id'] === 0 ? $lang['outside_posit'] : $list['position_name']) }}</span>
                        </td>
                        <td align="left"><span>{{ $list['type'] }}</span></td>
                        <td align="center"><span>{{ $list['start_date'] }}</span></td>
                        <td align="center"><span>{{ $list['end_date'] }}</span></td>
                        <td align="right"><span>{{ $list['click_count'] }}</span></td>
                        <td align="right"><span>{{ $list['ad_stats'] }}</span></td>
                        <td align="right"><span>
                            @if($list['position_id'] === 0)
                                    <a href="ads.php?act=add_js&type={{ $list['media_type'] }}&id={{ $list['ad_id'] }}"
                                       title="{{ $lang['add_js_code'] }}"><img
                                            src="{{ asset('static/admin/images/icon_js.gif') }}"
                                            border="0" height="16" width="16"/></a>
                                @endif
                            <a href="ads.php?act=edit&id={{ $list['ad_id'] }}" title="{{ $lang['edit'] }}"><img
                                    src="{{ asset('static/admin/images/icon_edit.gif') }}" border="0" height="16"
                                    width="16"/></a>
                            <a href="javascript:;"
                               onclick="listTable.remove({{ $list['ad_id'] }}, '{{ $lang['drop_confirm'] }}')"
                               title="{{ $lang['remove'] }}"><img src="{{ asset('static/admin/images/icon_drop.gif') }}"
                                                                  border="0" height="16" width="16"/></a></span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="no-records" colspan="10">{{ $lang['no_ads'] }}</td>
                    </tr>
                @endforelse
                <tr>
                    <td align="right" nowrap="true" colspan="10">@include('admin::page')</td>
                </tr>
            </table>

@if($full_page)
        </div>
        <!-- end ad_position list -->
    </form>

    <script type="text/javascript">
        listTable.recordCount = {{ $record_count }};
        listTable.pageCount = {{ $page_count }};

        @foreach($filter as $item => $key)
            listTable.filter.{{ $key }} = '{{ $item }}';
        @endforeach
    </script>
    @include('admin::pagefooter')
@endif
