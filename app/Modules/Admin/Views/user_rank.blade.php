@if($full_page)
    @include('admin::pageheader')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('static/admin/js/listtable.js') }}"></script>

    <form method="post" action="" name="listForm">
        <!-- start ads list -->
        <div class="list-div" id="listDiv">
            @endif

            <table cellspacing='1' id="list-table">
                <tr>
                    <th>{{ $lang['rank_name'] }}</th>
                    <th>{{ $lang['integral_min'] }}</th>
                    <th>{{ $lang['integral_max'] }}</th>
                    <th>{{ $lang['discount'] }}(%)</th>
                    <th>{{ $lang['special_rank'] }}</th>
                    <th>{{ $lang['show_price_short'] }}</th>
                    <th>{{ $lang['handler'] }}</th>
                </tr>
                @foreach($user_ranks as $rank)
                    <tr>
                        <td class="first-cell"><span
                                onclick="listTable.edit(this,'edit_name', {{ $rank['rank_id'] }})">{{ $rank['rank_name'] }}</span>
                        </td>
                        <td align="right"><span
                                @if($rank['special_rank'] != 1) onclick="listTable.edit(this, 'edit_min_points', {{ $rank['rank_id'] }})" @endif >{{ $rank['min_points'] }}</span>
                        </td>
                        <td align="right"><span
                                @if($rank['special_rank'] != 1) onclick="listTable.edit(this, 'edit_max_points', {{ $rank['rank_id'] }})" @endif >{{ $rank['max_points'] }}</span>
                        </td>
                        <td align="right"><span
                                onclick="listTable.edit(this, 'edit_discount', {{ $rank['rank_id'] }})">{{ $rank['discount'] }}</span>
                        </td>
                        <td align="center"><img
                                src="{{ asset('static/admin/images/'.($rank['special_rank'] ? 'yes' : 'no').'.gif') }}"
                                onclick="listTable.toggle(this, 'toggle_special', {{ $rank['rank_id'] }})"/></td>
                        <td align="center"><img
                                src="{{ asset('static/admin/images/'.($rank['show_price'] ? 'yes' : 'no').'.gif') }}"
                                onclick="listTable.toggle(this, 'toggle_showprice', {{ $rank['rank_id'] }})"/></td>
                        <td align="center">
                            <a href="javascript:;"
                               onclick="listTable.remove({{ $rank['rank_id'] }}, '{{ $lang['drop_confirm'] }}')"
                               title="{{ $lang['remove'] }}"><img src="{{ asset('static/admin/images/icon_drop.gif') }}"
                                                                  border="0" height="16" width="16"></a></td>
                    </tr>
                @endforeach
            </table>

@if($full_page)
        </div>
        <!-- end user ranks list -->
    </form>
    <script type="text/javascript">
    </script>
    @include('admin::pagefooter')
@endif
