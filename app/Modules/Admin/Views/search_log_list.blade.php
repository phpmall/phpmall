@if($full_page)
    @include('admin::pageheader')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('static/admin/js/listtable.js') }}"></script>
    <div class="form-div">
        <form action="search_log.php" method="post" style="margin:0px">
            {{ $lang['start_date'] }}&nbsp;{html_select_date field_order="YMD" prefix="start_date" time=$start_date
            start_year="-10" end_year="+1" display_days=true month_format="%m"}&nbsp;&nbsp;
            {{ $lang['end_date'] }}&nbsp;{html_select_date field_order="YMD" prefix="end_date" time=$end_date
            start_year="-10"
            end_year="+1" display_days=true month_format="%m"}&nbsp;&nbsp;
            <input type="hidden" name="act" value="list"/>
            <input type="submit" name="submit" value="{{ $lang['query'] }}" class="button"/>
        </form>
    </div>
    <div class="list-div" id="listDiv">
        @endif
        <table cellspacing='1' cellpadding='3'>
            <tr>
                <th>{{ $lang['keywords'] }}</th>
                <th width="25%">{{ $lang['date'] }}</th>
                <th width="20%">{{ $lang['hits'] }}</th>
            </tr>
            @foreach($logdb as $val)
                <tr>
                    <td>{{ $val['keyword'] }}</td>
                    <td align="center">{{ $val['date'] }}</td>
                    <td align="center">{{ $val['count'] }}</td>
                </tr>
            @endforeach
        </table>
        <table id="page-table" cellspacing="0">
            <tr>
                <td align="right" nowrap="true">
                    @include('admin::page')
                </td>
            </tr>
        </table>
@if($full_page)
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
