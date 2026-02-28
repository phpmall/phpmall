@if($full_page)
    @include('admin::pageheader')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('static/admin/js/listtable.js') }}"></script>
    <script type="text/javascript" src="../js/calendar.php?lang={{ $cfg_lang }}"></script>
    <link href="../js/calendar/calendar.css" rel="stylesheet" type="text/css"/>
    <div class="form-div">
        <form action="attention_list.php" method="post">
            {{ $lang['goods_name'] }}
            <input type="hidden" name="act" value="list"/>
            <input name="goods_name" type="text" size="25"/> <input type="submit" value="{{ $lang['button_search'] }}"
                                                                    class="button"/>
        </form>
    </div>
    <div class="form-div">
        <form action="attention_list.php" method="post">
            {{ $lang['batch_note'] }}
            <input type="hidden" name="act" value="batch_addtolist"/>
            <input name="date" type="text" id="date" size="10" value='' readonly="readonly"/><input name="selbtn1"
                                                                                                    type="button"
                                                                                                    id="selbtn1"
                                                                                                    onclick="return showCalendar('date', '%Y-%m-%d', false, false, 'selbtn1');"
                                                                                                    value="{{ $lang['btn_select'] }}"
                                                                                                    class="button"/>
            <select name="pri" id="pri">
                <option value='0'>{{ $lang['pri']['0'] }}</option>
                <option value='1'>{{ $lang['pri']['1'] }}</option>
            </select>
            <input type="submit" value="{{ $lang['attention_addtolist'] }}" class="button"/>
        </form>
    </div>
    <div class="list-div" id="listDiv">
        <form method="post" action="" name="listForm">
            @endif
            <table cellspacing='1' cellpadding='3'>
                <tr>
                    <th>{{ $lang['goods_name'] }}</th>
                    <th width="15%">{{ $lang['goods_last_update'] }}</th>
                    <th width="15%">{{ $lang['attention_addtolist'] }}</th>
                </tr>
                @forelse($goodsdb as $val)
                    <tr>
                        <td><a href="../goods.php?id={{ $val['goods_id'] }}"
                               target="_blank">{{ $val['goods_name'] }}</a></td>
                        <td align="center">{{ $val['last_update'] }}</td>
                        <td align="center">
                            <form action="attention_list.php" method="post" name="form">
                                <input type="hidden" name="id" value="{{ $val['goods_id'] }}"/>
                                <input type="hidden" name="act" value="addtolist"/>
                                <select name="pri" id="pri">
                                    <option value='0'>{{ $lang['pri']['0'] }}</option>
                                    <option value='1'>{{ $lang['pri']['1'] }}</option>
                                </select>
                                <input type="submit" value="{{ $lang['button_submit'] }}" class="button"/>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="no-records" colspan="10">{{ $lang['no_records'] }}</td>
                    </tr>
                @endforelse
            </table>
            <table id="page-table" cellspacing="0">
                <tr>
                    <td align="right" nowrap="true">
                        @include('admin::page')
                    </td>
                </tr>
            </table>
@if($full_page)
        </form>
    </div>

    <script type="text/javascript">
        listTable.recordCount = {{ $record_count }};
        listTable.pageCount = {{ $page_count }};
        @foreach($filter as $item => $key)
            listTable.filter.{{ $key }} = '{{ $item }}';
        @endforeach
    </script>
    @include('admin::pagefooter')
@endif
