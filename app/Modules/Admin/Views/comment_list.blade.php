@if($full_page)
    @include('admin::pageheader')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('static/admin/js/listtable.js') }}"></script>
    <div class="form-div">
        <form action="javascript:searchComment()" name="searchForm">
            <img src="{{ asset('static/admin/images/icon_search.gif') }}" width="26" height="22" border="0"
                 alt="SEARCH"/>
            {{ $lang['search_comment'] }} <input type="text" name="keyword"/> <input type="submit" class="Button"
                                                                                     value="{{ $lang['button_search'] }}"/>
        </form>
    </div>

    <form method="POST" action="comment_manage.php?act=batch_drop" name="listForm" onsubmit="return confirm_bath()">

        <!-- start comment list -->
        <div class="list-div" id="listDiv">
            @endif

            <table cellpadding="3" cellspacing="1">
                <tr>
                    <th>
                        <input onclick='listTable.selectAll(this, "checkboxes")' type="checkbox">
                        <a href="javascript:listTable.sort('comment_id'); ">{{ $lang['record_id'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th><a href="javascript:listTable.sort('user_name'); ">{{ $lang['user_name'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th><a href="javascript:listTable.sort('comment_type'); ">{{ $lang['comment_type'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th><a href="javascript:listTable.sort('id_value'); ">{{ $lang['comment_obj'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th><a href="javascript:listTable.sort('ip_address'); ">{{ $lang['ip_address'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th><a href="javascript:listTable.sort('add_time'); ">{{ $lang['comment_time'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th>{{ $lang['comment_flag'] }}</th>
                    <th>{{ $lang['handler'] }}</th>
                </tr>
                @forelse($comment_list as $comment)
                    <tr>
                        <td><input value="{{ $comment['comment_id'] }}" name="checkboxes[]"
                                   type="checkbox">{{ $comment['comment_id'] }}</td>
                        <td>'.($comment['user_name'] ? '{{ $comment['user_name'] }}' : '{{ $lang['anonymous'] }}').'
                        </td>
                        <td>{$lang.type[$comment.comment_type]}</td>
                        <td>
                            <a href="../'.($comment['comment_type'] === '0' ? 'goods' : 'article').'.php?id={{ $comment['id_value'] }}"
                               target="_blank">{{ $comment['title'] }}</td>
                        <td>{{ $comment['ip_address'] }}</td>
                        <td align="center">{{ $comment['add_time'] }}</td>
                        <td align="center">'.($comment['status'] === 0 ? '{{ $lang['hidden'] }}' :
                            '{{ $lang['display'] }}').'
                        </td>
                        <td align="center">
                            <a href="comment_manage.php?act=reply&amp;id={{ $comment['comment_id'] }}">{{ $lang['view_content'] }}</a>
                            |
                            <a href="javascript:"
                               onclick="listTable.remove({{ $comment['comment_id'] }}, '{{ $lang['drop_confirm'] }}')">{{ $lang['remove'] }}</a>
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
                    <td>
                        <div>
                            <select name="sel_action">
                                <option value="remove">{{ $lang['drop_select'] }}</option>
                                <option value="allow">{{ $lang['allow'] }}</option>
                                <option value="deny">{{ $lang['forbid'] }}</option>
                            </select>
                            <input type="hidden" name="act" value="batch"/>
                            <input type="submit" name="drop" id="btnSubmit" value="{{ $lang['button_submit'] }}"
                                   class="button" disabled="true"/></div>
                    </td>
                    <td align="right">@include('admin::page')</td>
                </tr>
            </table>

@if($full_page)
        </div>
        <!-- end comment list -->

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
         * 搜索评论
         */
        function searchComment() {
            var keyword = Utils.trim(document.forms['searchForm'].elements['keyword'].value);
            if (keyword.length > 0) {
                listTable.filter['keywords'] = keyword;
                listTable.filter.page = 1;
                listTable.loadList();
            } else {
                document.forms['searchForm'].elements['keyword'].focus();
            }
        }
        function confirm_bath() {
            var action = document.forms['listForm'].elements['sel_action'].value;

            return confirm(cfm[action]);
        }

    </script>
    @include('admin::pagefooter')
@endif
