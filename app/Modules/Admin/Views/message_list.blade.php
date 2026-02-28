@if($full_page)
    @include('admin::pageheader')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('static/admin/js/listtable.js') }}"></script>

    <div class="form-div">
        <form method="post" action="javascript:searchMessage()" name="theForm">
            {{ $lang['select_msg_type'] }}:
            <select name="msg_type" onchange="javascript:searchMessage()">
                {html_options options=$lang.message_type selected=$msg_type}
            </select>
            <input type="submit" value="{{ $lang['button_submit'] }}" class="button"/>
        </form>
    </div>

    <!-- start admin_message list -->
    <form method="POST" action="message.php?act=drop_msg" name="listForm">
        <div class="list-div" id="listDiv">
            @endif

            <table cellpadding="3" cellspacing="1">
                <tr>
                    <th>
                        <input onclick='listTable.selectAll(this, "checkboxes")' type="checkbox"/>
                        <a href="javascript:listTable.sort('message_id'); ">{{ $lang['record_id'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th><a href="javascript:listTable.sort('title'); ">{{ $lang['title'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th><a href="javascript:listTable.sort('sender_id'); ">{{ $lang['sender_id'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th><a href="javascript:listTable.sort('sent_time'); ">{{ $lang['send_date'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th><a href="javascript:listTable.sort('read_time'); ">{{ $lang['read_date'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th>{{ $lang['handler'] }}</th>
                </tr>
                @forelse($message_list as $msg)
                    <tr>
                        <td><input type="checkbox" name="checkboxes[]"
                                   value="{{ $msg['message_id'] }}"/>{{ $msg['message_id'] }}</td>
                        <td class="first-cell">{$msg.title|escape:html|truncate:35}</td>
                        <td>{{ $msg['user_name'] }}</td>
                        <td align="right">{{ $msg['sent_time'] }}</td>
                        <td align="right">{{ $msg['read_time'] ?? N / A }}</td>
                        <td align="center">
                            <a href="message.php?act=view&id={{ $msg['message_id'] }}"
                               title="{{ $lang['view_msg'] }}">{{ $lang['view'] }}</a>
                            <a href="javascript:;"
                               onclick="listTable.remove({{ $msg['message_id'] }}, '{{ $lang['drop_confirm'] }}')">{{ $lang['remove'] }}</a>
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
                    <td><input type="submit" name="drop" id="btnSubmit" value="{{ $lang['drop'] }}" class="button"
                               disabled="true"/></td>
                    <td align="right">@include('admin::page')</td>
                </tr>
            </table>

@if($full_page)
        </div>
    </form>
    <script type="text/javascript">
        listTable.recordCount = {{ $record_count }};
        listTable.pageCount = {{ $page_count }};

        @foreach($filter as $item => $key)
            listTable.filter.{{ $key }} = '{{ $item }}';
        @endforeach

        /**
         * 查询留言
         */
        function searchMessage() {
            listTable.filter.msg_type = document.forms['theForm'].elements['msg_type'].value;
            listTable.filter.page = 1;
            listTable.loadList();
        }
    </script>

    @include('admin::pagefooter')
@endif
