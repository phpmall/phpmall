@if($full_page)
    @include('admin::pageheader')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('static/admin/js/listtable.js') }}"></script>
    <!-- start goods list -->
    <form method="post" action="" name="listForm">
        <input type="hidden" name="goods_id" value="{{ $goods_id }}"/>
        <div class="list-div" id="listDiv">
            @endif
            <table cellpadding="3" cellspacing="1">
                <tr>
                    <th>&nbsp</th>
                    <th>
                        <a href="javascript:listTable.sort('card_name');">{{ $lang['card_name'] }}</a>{{ $sort_card_name }}
                    </th>
                    <th><a href="javascript:listTable.sort('card_fee');">{{ $lang['card_fee'] }}</a>{{ $sort_card_fee }}
                    </th>
                    <th>
                        <a href="javascript:listTable.sort('free_money');">{{ $lang['free_money'] }}</a>{{ $sort_free_money }}
                    </th>
                    <th>{{ $lang['card_desc'] }}</th>
                    <th>{{ $lang['handler'] }}</th>
                </tr>
                @forelse($card_list as $card)
                    <tr>
                        <td align="center">
                            @if($card['card_img'])
                                <a href="../data/cardimg/{{ $card['card_img'] }}" target="_brank"><img
                                        src="{{ asset('static/admin/images/picflag.gif') }}" width="16" height="16"
                                        border="0" alt=""/></a>
                            @else
                                <img src="{{ asset('static/admin/images/picnoflag.gif') }}" width="16" height="16"
                                     border="0" alt=""/>
                            @endif
                        </td>
                        <td align="left"><span onclick="listTable.edit(this, 'edit_card_name', {{ $card['card_id'] }})">{$card.card_name|escape:"html"}</a>
                        </td>
                        <td align="right"><span
                                onclick="listTable.edit(this, 'edit_card_fee', {{ $card['card_id'] }})">{{ $card['card_fee'] }}</a>
                        </td>
                        <td align="right"><span
                                onclick="listTable.edit(this, 'edit_free_money', {{ $card['card_id'] }})">{{ $card['free_money'] }}</a>
                        </td>
                        <td align="left">'.($card['card_desc'] ? '{$card.card_desc|truncate:50|escape:"html"}' :
                            'N/A').'
                        </td>
                        <td align="center" nowrap="true" valign="top">
                            <a href="?act=edit&amp;id={{ $card['card_id'] }}" title="{{ $lang['edit'] }}"><img
                                    src="{{ asset('static/admin/images/icon_edit.gif') }}" border="0" height="16"
                                    width="16"></a>
                            <a href="javascript:;"
                               onclick="listTable.remove({{ $card['card_id'] }}, '{{ $lang['drop_confirm'] }}')"
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
    </form>
    <!-- end goods list -->
    <script type="text/javascript">
        listTable.recordCount = {{ $record_count }};
        listTable.pageCount = {{ $page_count }};
        @foreach($filter as $item => $key)
            listTable.filter.{{ $key }} = '{{ $item }}';
        @endforeach

    </script>
    @include('admin::pagefooter')
@endif
