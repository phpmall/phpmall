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
                    <th>
                        <a href="javascript:listTable.sort('link_name'); ">{{ $lang['link_name'] }}</a>{{ $sort_link_name }}
                    </th>
                    <th>
                        <a href="javascript:listTable.sort('link_url'); ">{{ $lang['link_url'] }}</a>{{ $sort_link_url }}
                    </th>
                    <th>
                        <a href="javascript:listTable.sort('link_logo'); ">{{ $lang['link_logo'] }}</a>{{ $sort_link_logo }}
                    </th>
                    <th>
                        <a href="javascript:listTable.sort('show_order'); ">{{ $lang['show_order'] }}</a>{{ $sort_show_order }}
                    </th>
                    <th>{{ $lang['handler'] }}</th>
                </tr>
                <tr>
                @forelse($links_list as $link)
                    <tr>
                        <td class="first-cell"><span
                                onclick="listTable.edit(this, 'edit_link_name', {{ $link['link_id'] }})">{{ $link['link_name'] }}</span>
                        </td>
                        <td align="left"><span><a href="{{ $link['link_url'] }}"
                                                  target="_blank">{{ $link['link_url'] }}</a></span></td>
                        <td align="center"><span>{{ $link['link_logo'] }}</span></td>
                        <td align="right"><span
                                onclick="listTable.edit(this, 'edit_show_order', {{ $link['link_id'] }})">{{ $link['show_order'] }}</span>
                        </td>
                        <td align="center"><span>
                <a href="friend_link.php?act=edit&id={{ $link['link_id'] }}" title="{{ $lang['edit'] }}"><img
                        src="{{ asset('static/admin/images/icon_edit.gif') }}" border="0" height="16" width="16"/></a>&nbsp;
                <a href="javascript:;" onclick="listTable.remove({{ $link['link_id'] }}, '{{ $lang['drop_confirm'] }}')"
                   title="{{ $lang['remove'] }}"><img src="{{ asset('static/admin/images/icon_drop.gif') }}" border="0"
                                                      height="16"
                                                      width="16"/></a></span></td>
                    </tr>
                @empty
                    <tr>
                        <td class="no-records" colspan="10">{{ $lang['no_links'] }}</td>
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
