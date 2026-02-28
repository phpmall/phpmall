@if($full_page)
    @include('admin::pageheader')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('static/admin/js/listtable.js') }}"></script>
    <div class="list-div" id="listDiv">
        @endif
        <table cellspacing='1' cellpadding='3'>
            <tr>
                <th>{{ $lang['magazine_name'] }}</th>
                <th width="20%">{{ $lang['magazine_last_update'] }}</th>
                <th width="20%">{{ $lang['magazine_last_send'] }}</th>
                <th width="20%">{{ $lang['magazine_addtolist'] }}</th>
                <th width="12%">{{ $lang['handler'] }}</th>
            </tr>
            @forelse($magazinedb as $val)
                <tr>
                    <td>{{ $val['template_subject'] }}</td>
                    <td align="center">{{ $val['last_modify'] }}</td>
                    <td align="center">{{ $val['last_send'] }}</td>
                    <td align="center">
                        <form action="magazine_list.php" method="post" name="hidform">
                            <input type="hidden" name="id" value="{{ $val['template_id'] }}"/>
                            <input type="hidden" name="act" value="addtolist"/>
                            <select name="pri">
                                <option value='0'>{{ $lang['pri']['0'] }}</option>
                                <option value='1'>{{ $lang['pri']['1'] }}</option>
                            </select>
                            <select name="send_rank">
                                {html_options options=$send_rank}
                            </select>
                            <input type="submit" value="{{ $lang['button_submit'] }}" class="button"/>
                        </form>
                    </td>
                    <td align="center"><a
                            href="magazine_list.php?act=edit&id={{ $val['template_id'] }}">{{ $lang['magazine_edit'] }}</a>
                        <a
                            href="magazine_list.php?act=del&id={{ $val['template_id'] }}"
                            onclick="return confirm('{{ $lang['ck_del'] }}');">{{ $lang['magazine_del'] }}</a></td>
                </tr>
            @empty
                <tr>
                    <td class="no-records" colspan="10">{{ $lang['no_records'] }}</td>
                </tr>
            @endforelse
        </table>
        <form method="post" action="" name="listForm">
            <table cellpadding="4" cellspacing="0">
                <tr>
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
    </script>

    @include('admin::pagefooter')
@endif
