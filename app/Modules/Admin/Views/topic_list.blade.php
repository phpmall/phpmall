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
                        <a href="javascript:listTable.sort('topic_id'); ">{{ $lang['record_id'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th width="26%"><a href="javascript:listTable.sort('title'); ">{{ $lang['topic_title'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th width="13%"><a
                            href="javascript:listTable.sort('start_time'); ">{{ $lang['start_time'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th width="13%"><a
                            href="javascript:listTable.sort('end_time'); ">{{ $lang['end_time'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th width="">{{ $lang['handler'] }}</th>
                </tr>
                @forelse($topic_list as $topic)
                    <tr>
                        <td><span><input value="{{ $topic['topic_id'] }}" name="checkboxs[]"
                                         type="checkbox">{{ $topic['topic_id'] }}</span></td>

                        <td>{{ $topic['title'] }}</td>

                        <td>{{ $topic['start_time'] }}</td>
                        <td>{{ $topic['end_time'] }}</td>
                        <td align="center"><a href="../topic.php?topic_id={{ $topic['topic_id'] }}"
                                              title="{{ $lang['view'] }}"
                                              target="_blank">{{ $lang['view'] }}</a> <a
                                href="topic.php?act=edit&topic_id={{ $topic['topic_id'] }}"
                                title="{{ $lang['edit'] }}">{{ $lang['edit'] }}</a>
                            <a href="javascript:;" on title="{{ $lang['drop'] }}"
                               onclick="listTable.remove({{ $topic['topic_id'] }},delete_topic_confirm,'delete');">{{ $lang['drop'] }}</a>
                            <a
                                href="ads.php?act=add&ad_name={{ $topic['title'] }}&ad_link={{ $topic['url'] }}">{{ $lang['publish_to_ads'] }}</a>
                            <a href="flashplay.php?act=add&ad_link={{ $topic['url'] }}"
                               title="{{ $lang['publish_to_player'] }}">{{ $lang['publish_to_player'] }}</a>
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
