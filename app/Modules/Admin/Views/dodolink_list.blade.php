@if($full_page)
    @include('admin::pageheader')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('static/admin/js/listtable.js') }}"></script>
    <form method="POST" action="" name="listForm">
        <!-- start user_bonus list -->
        <div class="list-div" id="listDiv">
            @endif

            <table cellpadding="3" cellspacing="1">
                <tr>
                    <th width="13%">
                        <input onclick='listTable.selectAll(this, "checkboxes")' type="checkbox">
                        <a href="javascript:listTable.sort('log_id'); ">{{ $lang['record_id'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}"/>
                    </th>
                    <th width="26%"><a href="javascript:listTable.sort('sn');">{{ $lang['sn'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}"/>
                    </th>
                    <th width="10%"><a href="javascript:listTable.sort('count');">{{ $lang['count'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}"/>
                    </th>
                    <th width="15%"><a href="javascript:listTable.sort('end_time');">{{ $lang['end_time'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}"/>
                    </th>
                    <th width="15%"><a href="javascript:listTable.sort('user_name');">{{ $lang['user'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}"/>
                    </th>
                    <th width="31%">{{ $lang['handler'] }}</th>
                </tr>
                @forelse($items as $item)
                    <tr>
                        <td><span><input value="{{ $topic['topic_id'] }}" name="checkboxes[]"
                                         type="checkbox">{{ $item['log_id'] }}</span></td>

                        <td>{{ $item['sn'] }}</td>
                        <td>{{ $item['count'] }}</td>
                        <td>{{ $item['end_time'] }}</td>
                        <td>{{ $item['user_name'] }}</td>
                        <td align="center">
                            <a href="javascript:;" title="{{ $lang['drop'] }}"
                               onclick="listTable.remove({{ $item['log_id'] }}, delete_topic_confirm, 'delete');">{{ $lang['drop'] }}</a>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td class="no-records" colspan="11">{{ $lang['no_records'] }}</td>
                    </tr>
                @endforelse
            </table>

            <table cellpadding="4" cellspacing="0">
                <tr>
                    <td><input type="submit" name="drop" id="btnSubmit" value="{{ $lang['drop'] }}" class="button"
                               disabled="true"/>
                    </td>
                    <td align="right">@include('admin::page')</td>
                </tr>
            </table>

@if($full_page)
        </div>
        <!-- end user_bonus list -->
    </form>

    <script type="text/javascript">
        listTable.recordCount = {{ $record_count }};
        listTable.pageCount = {{ $page_count }};
        listTable.query = "query";

        @foreach($filter as $item => $key)
            listTable.filter.{{ $key }} = '{{ $item }}';
        @endforeach

            onload = function () {
            document.forms['listForm'].reset();
        }

        document.getElementById("btnSubmit").onclick = function () {
            if (confirm(delete_topic_confirm)) {
                document.forms["listForm"].action = "topic.php?act=delete";
                return;
            } else {
                return false;
            }
        }

    </script>
    @include('admin::pagefooter')
@endif
