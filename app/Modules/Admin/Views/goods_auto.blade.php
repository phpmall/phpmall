@if($full_page)
    @include('admin::pageheader')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('static/admin/js/listtable.js') }}"></script>
    <script type="text/javascript" src="../js/calendar.php?lang={{ $cfg_lang }}"></script>
    <script type="text/javascript">
        var thisfile = '{{ $thisfile }}';
        var deleteck = '{{ $lang['deleteck'] }}';
        var deleteid = '{{ $lang['delete'] }}';
    </script>
    <link href="../js/calendar/calendar.css" rel="stylesheet" type="text/css"/>
    <div class="form-div">
        @if(!$crons_enable)
            <ul style="padding:0; margin: 0; list-style-type:none; color: #CC0000;">
                <li style="border: 1px solid #CC0000; background: #FFFFCC; padding: 10px; margin-bottom: 5px;">
                    {{ $lang['enable_notice'] }}
                </li>
            </ul>
        @endif
        <form action="{{ $thisfile }}" method="post">
            {{ $lang['goods_name'] }}
            <input type="hidden" name="act" value="list"/>
            <input name="goods_name" type="text" size="25"/> <input type="submit" value="{{ $lang['button_search'] }}"
                                                                    class="button"/>
        </form>
    </div>
    <form method="post" action="" name="listForm">
        <div class="list-div" id="listDiv">
            @endif

            <table cellspacing='1' cellpadding='3'>
                <tr>
                    <th width="5%"><input onclick='listTable.selectAll(this, "checkboxes")'
                                          type="checkbox">{{ $lang['id'] }}</th>
                    <th>{{ $lang['goods_name'] }}</th>
                    <th width="25%">{{ $lang['starttime'] }}</th>
                    <th width="25%">{{ $lang['endtime'] }}</th>
                    <th width="10%">{{ $lang['handler'] }}</th>
                </tr>
                @forelse($goodsdb as $val)
                    <tr>
                        <td><input name="checkboxes[]" type="checkbox"
                                   value="{{ $val['goods_id'] }}"/>{{ $val['goods_id'] }}
                        </td>
                        <td>{{ $val['goods_name'] }}</td>
                        <td align="center">
                        <span
                            onclick="listTable.edit(this, 'edit_starttime', '{{ $val['goods_id'] }}');showCalendar(this.firstChild, '%Y-%m-%d', false, false, this.firstChild)">'.($val['starttime']
                            ? '{{ $val['starttime'] }}' : '0000-00-00').'</span>
                        </td>
                        <td align="center">
                        <span
                            onclick="listTable.edit(this, 'edit_endtime', '{{ $val['goods_id'] }}');showCalendar(this.firstChild, '%Y-%m-%d', false, false, this.firstChild)">'.($val['endtime']
                            ? '{{ $val['endtime'] }}' : '0000-00-00').'</span>
                        </td>
                        <td align="center"><span id="del{{ $val['goods_id'] }}">
                            @if($val['endtime'] || $val['starttime'])
                                    <a href="{{ $thisfile }}?goods_id={{ $val['goods_id'] }}&act=del"
                                       onclick="return confirm('{{ $lang['deleteck'] }}');">{{ $lang['delete'] }}</a>
                                @else
                                    -
                                @endif</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="no-records" colspan="10">{{ $lang['no_records'] }}</td>
                    </tr>
                @endforelse
            </table>
@if($full_page)
            @endif
            <table id="page-table" cellspacing="0">
                <tr>
                    <td>
                        <input type="hidden" name="act" value=""/>
                        <input name="date" type="text" id="date" size="10" value='0000-00-00'
                               readonly="readonly"/><input
                            name="selbtn1" type="button" id="selbtn1"
                            onclick="return showCalendar('date', '%Y-%m-%d', false, false, 'selbtn1');"
                            value="{{ $lang['btn_select'] }}" class="button"/>
                        <input type="button" id="btnSubmit1" value="{{ $lang['button_start'] }}" disabled="true"
                               class="button" onClick="return validate('batch_start')"/>
                        <input type="button" id="btnSubmit2" value="{{ $lang['button_end'] }}" disabled="true"
                               class="button" onClick="return validate('batch_end')"/>
                    </td>
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

        function validate(name) {
            if (document.listForm.elements["date"].value === "0000-00-00") {
                alert('{{ $lang['select_time'] }}');
                return;
            } else {
                document.listForm.act.value = name;
                document.listForm.submit();
            }
        }

    </script>
    @include('admin::pagefooter')
@endif
