@if($full_page)
    @include('admin::pageheader')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('static/admin/js/listtable.js') }}"></script>
    <!-- start goods list -->
    <form method="post" action="" name="listForm">
        <div class="list-div" id="listDiv">
            @endif
            <table cellpadding="3" cellspacing="1">
                <tr>
                    <th>
                        <a href="javascript:listTable.sort('pack_name'); ">{{ $lang['pack_name'] }}</a>{{ $sort_pack_name }}
                    </th>
                    <th>
                        <a href="javascript:listTable.sort('pack_fee'); ">{{ $lang['pack_fee'] }}</a>{{ $sort_pack_fee }}
                    </th>
                    <th>
                        <a href="javascript:listTable.sort('free_money');">{{ $lang['free_money'] }}</a>{{ $sort_free_money }}
                    </th>
                    <th>{{ $lang['pack_desc'] }}</th>
                    <th>{{ $lang['handler'] }}</th>
                </tr>
                @forelse($packs_list as $pack)
                    <tr>
                        <td class="first-cell">
                            @if(($pack['pack_img']))
                                <a href="../data/packimg/{{ $pack['pack_img'] }}" target="_brank"><img
                                        src="{{ asset('static/admin/images/picflag.gif') }}" width="16" height="16"
                                        border="0" alt=""/></a>
                            @else
                                <img
                                    src="{{ asset('static/admin/images/picnoflag.gif') }}" width="16" height="16"
                                    border="0" alt=""/>
                            @endif
                            <span
                                onclick="listTable.edit(this, 'edit_name', {{ $pack['pack_id'] }})">{$pack.pack_name|escape:"html"}</span>
                        </td>
                        <td align="right"><span
                                onclick="listTable.edit(this, 'edit_pack_fee', {{ $pack['pack_id'] }})">{{ $pack['pack_fee'] }}</span>
                        </td>
                        <td align="right"><span
                                onclick="listTable.edit(this, 'edit_free_money', {{ $pack['pack_id'] }})">{{ $pack['free_money'] }}</span>
                        </td>
                        <td>{$pack.pack_desc|truncate:50|escape:"html"}</td>
                        <td align="center" nowrap="true" valign="top">
                            <a href="?act=edit&amp;id={{ $pack['pack_id'] }}" title="{{ $lang['edit'] }}"><img
                                    src="{{ asset('static/admin/images/icon_edit.gif') }}"
                                    border="0" height="16" width="16"></a>
                            <a href="javascript:;"
                               onclick="listTable.remove({{ $pack['pack_id'] }}, '{{ $lang['drop_confirm'] }}')"
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
                    <td align="right">@include('admin::page')</td>
                </tr>
            </table>
@if($full_page)
        </div>
        <!-- end goods list -->
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
