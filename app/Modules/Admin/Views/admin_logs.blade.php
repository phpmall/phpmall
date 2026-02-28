@if($full_page)
    @include('admin::pageheader')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('static/admin/js/listtable.js') }}"></script>

    <div class="form-div">
        <table>
            <tr>
                <td width="50%">
                    <form name="theForm" method="POST" action="admin_logs.php">
                        {{ $lang['view_ip'] }}
                        <select name="ip">
                            <option value='0'>{{ $lang['select_ip'] }}</option>
                            {html_options options=$ip_list selected=$ip}
                        </select>
                        <input type="submit" value="{{ $lang['comfrom'] }}" class="button"/>
                    </form>
                </td>
                <td>
                    <form name="Form2" action="admin_logs.php?act=batch_drop" method="POST">
                        {{ $lang['drop_logs'] }}
                        <select name="log_date">
                            <option value='0'>{{ $lang['select_date'] }}</option>
                            <option value='1'>{{ $lang['week_date'] }}</option>
                            <option value='2'>{{ $lang['month_date'] }}</option>
                            <option value='3'>{{ $lang['three_month'] }}</option>
                            <option value='4'>{{ $lang['six_month'] }}</option>
                            <option value='5'>{{ $lang['a_yaer'] }}</option>
                        </select>
                        <input name="drop_type_date" type="submit" value="{{ $lang['comfrom'] }}" class="button"/>
                    </form>
                </td>
            </tr>
        </table>
    </div>

    <form method="POST" action="admin_logs.php?act=batch_drop" name="listForm">
        <!-- start admin_logs list -->
        <div class="list-div" id="listDiv">
            @endif

            <table cellpadding="3" cellspacing="1">
                <tr>
                    <th><input onclick='listTable.selectAll(this, "checkboxes")' type="checkbox">
                        <a href="javascript:listTable.sort('log_id'); ">{{ $lang['log_id'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}"/>
                    </th>
                    <th><a href="javascript:listTable.sort('user_id'); ">{{ $lang['user_id'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}"/>
                    </th>
                    <th><a href="javascript:listTable.sort('log_time'); ">{{ $lang['log_time'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}"/>
                    </th>
                    <th><a href="javascript:listTable.sort('ip_address'); ">{{ $lang['ip_address'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}"/>
                    </th>
                    <th>{{ $lang['log_info'] }}</th>
                </tr>
                @foreach($log_list as $list)
                    <tr>
                        <td width="10%"><span><input name="checkboxes[]" type="checkbox"
                                                     value="{{ $list['log_id'] }}"/>{{ $list['log_id'] }}</span></td>
                        <td width="15%" class="first-cell"><span>{{ $list['user_name'] }}</span></td>
                        <td width="20%" align="center"><span>{{ $list['log_time'] }}</span></td>
                        <td width="15%" align="left"><span>{{ $list['ip_address'] }}</span></td>
                        <td width="40%" align="left"><span>{{ $list['log_info'] }}</span></td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="2"><input name="drop_type_id" type="submit" id="btnSubmit"
                                           value="{{ $lang['drop_logs'] }}"
                                           disabled="true" class="button"/></td>
                    <td align="right" nowrap="true" colspan="10">@include('admin::page')</td>
                </tr>
            </table>

@if($full_page)
        </div>
        <!-- end ad_position list -->

        <script type="text/javascript">
            listTable.recordCount = {{ $record_count }};
            listTable.pageCount = {{ $page_count }};

            @foreach($filter as $item => $key)
                listTable.filter.{{ $key }} = '{{ $item }}';
            @endforeach
        </script>
    @include('admin::pagefooter')
@endif
