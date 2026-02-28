@if($full_page)
    @include('admin::pageheader')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('static/admin/js/listtable.js') }}"></script>
    <div class="form-div">
        <form action="javascript:searchMsg()" name="searchForm">
            <img src="{{ asset('static/admin/images/icon_search.gif') }}" width="26" height="22" border="0"
                 alt="SEARCH"/>
            {{ $lang['msg_type'] }}:
            <select name="msg_type">
                <option value="-1">{{ $lang['select_please'] }}</option>
                <option value="0">{$lang.type[0]}</option>
                <option value="1">{$lang.type[1]}</option>
                <option value="2">{$lang.type[2]}</option>
                <option value="3">{$lang.type[3]}</option>
                <option value="4">{$lang.type[4]}</option>
                <option value="5">{$lang.type[5]}</option>
            </select>
            {{ $lang['msg_title'] }}: <input type="text" name="keyword"/> <input type="submit" class="button"
                                                                                 value="{{ $lang['button_search'] }}"/>
        </form>
    </div>
    <form method="POST" action="user_msg.php?act=batch_drop" name="listForm" onsubmit="return confirm_bath()">
        <!-- start article list -->
        <div class="list-div" id="listDiv">
            @endif
            <table cellpadding="3" cellspacing="1">
                <tr>
                    <th>
                        <input onclick='listTable.selectAll(this, "checkboxes")' type="checkbox"/>
                        <a href="javascript:listTable.sort('msg_id'); ">{{ $lang['msg_id'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}"/>
                    </th>
                    <th><a href="javascript:listTable.sort('user_name'); ">{{ $lang['user_name'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}"/>
                    </th>
                    <th><a href="javascript:listTable.sort('msg_title'); ">{{ $lang['msg_title'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}"/>
                    </th>
                    <th><a href="javascript:listTable.sort('msg_type'); ">{{ $lang['msg_type'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}"/>
                    </th>
                    <th><a href="javascript:listTable.sort('msg_time'); ">{{ $lang['msg_time'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}"/>
                    </th>
                    <th><a href="javascript:listTable.sort('msg_status'); ">{{ $lang['msg_status'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}"/>
                    </th>
                    <th><a href="javascript:listTable.sort('reply'); ">{{ $lang['reply'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}"/>
                    </th>
                    <th>{{ $lang['handler'] }}</th>
                </tr>
                @forelse($msg_list as $msg)
                    <tr>
                        <td><input type="checkbox" name="checkboxes[]"
                                   value="{{ $msg['msg_id'] }}"/>{{ $msg['msg_id'] }}</td>
                        <td align="center">{{ $msg['user_name'] }}</td>
                        <td align="left">{$msg.msg_title|truncate:40|escape:html}</td>
                        <td align="center">{{ $msg['msg_type'] }}@if($msg['order_id'])<br><a
                                href="order.php?act=info&order_id={{ $msg['order_id'] }}">{{ $msg['order_sn'] }}@endif</a>
                        </td>
                        <td align="center" nowrap="nowrap">{{ $msg['msg_time'] }}</td>
                        @if($msg['msg_area'] === 0)
                            <td align="center">{{ $lang['display'] }}</td>
                        @else
                            <td align="center">'.($msg['msg_status'] === 0 ? '{{ $lang['hidden'] }}' :
                                '{{ $lang['display'] }}').'
                            </td>
                        @endif
                        <td align="center">'.($msg['reply'] === 0 ? '{{ $lang['unreplyed'] }}' : '{{ $lang['replyed'] }}
                            ').'
                        </td>
                        <td align="center">
                            <a href="user_msg.php?act=view&id={{ $msg['msg_id'] }}" title="{{ $lang['view'] }}">
                                <img src="{{ asset('static/admin/images/icon_view.gif') }}" border="0" height="16"
                                     width="16"/>
                            </a>
                            <a href="javascript:;"
                               onclick="listTable.remove({{ $msg['msg_id'] }}, '{{ $lang['drop_confirm'] }}')"
                               title="{{ $lang['remove'] }}">
                                <img src="{{ asset('static/admin/images/icon_drop.gif') }}" border="0" height="16"
                                     width="16">
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="no-records" colspan="7">{{ $lang['no_records'] }}</td>
                    </tr>
                @endforelse
            </table>
            <table id="page-table" cellspacing="0">
                <tr>
                    <td>
                        <div>
                            <select name="sel_action">
                                <option value="">{{ $lang['select_please'] }}</option>
                                <option value="remove">{{ $lang['delete'] }}</option>
                                <option value="allow">{{ $lang['allow'] }}</option>
                                <option value="deny">{{ $lang['forbid'] }}</option>
                            </select>
                            <input type="hidden" name="act" value="batch"/>
                            <input type="submit" name="drop" id="btnSubmit" value="{{ $lang['button_submit'] }}"
                                   class="button" disabled="true"/>
                        </div>
                    </td>
                    <td align="right" nowrap="true">
                        @include('admin::page')
                    </td>
                </tr>
            </table>
@if($full_page)
        </div>
        <!-- end article list -->
    </form>
    <script type="text/javascript">
        listTable.recordCount = {{ $record_count }};
        listTable.pageCount = {{ $page_count }};
        cfm = new Object();
        cfm['allow'] = '{{ $lang['cfm_allow'] }}';
        cfm['remove'] = '{{ $lang['cfm_remove'] }}';
        cfm['deny'] = '{{ $lang['cfm_deny'] }}';
        @foreach($filter as $item => $key)
            listTable.filter.{{ $key }} = '{{ $item }}';
        @endforeach

            onload = function () {
            document.forms['searchForm'].elements['keyword'].focus();
        }

        /**
         * 搜索标题
         */
        function searchMsg() {
            var keyword = Utils.trim(document.forms['searchForm'].elements['keyword'].value);
            var msgType = document.forms['searchForm'].elements['msg_type'].value;

            listTable.filter['keywords'] = Utils.trim(document.forms['searchForm'].elements['keyword'].value);
            listTable.filter['msg_type'] = document.forms['searchForm'].elements['msg_type'].value;
            listTable.filter['page'] = 1;
            listTable.loadList();
        }

        function confirm_bath() {
            var action = document.forms['listForm'].elements['sel_action'].value;
            if (action === 'allow' || action === 'remove' || action === 'deny') {
                return confirm(cfm[action]);
            }
        }

    </script>

    @include('admin::pagefooter')
@endif
