@if($full_page)
    @include('admin::pageheader')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('static/admin/js/listtable.js') }}"></script>

    <form method="post" action="" name="listForm" onsubmit="return confirm(batch_drop_confirm);">
        <div class="list-div" id="listDiv">
            @endif

            <table cellpadding="3" cellspacing="1">
                <tr>
                    <th><input onclick='listTable.selectAll(this, "checkboxes")' type="checkbox"/>
                        <a href="javascript:listTable.sort('agency_id'); ">{{ $lang['record_id'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th><a href="javascript:listTable.sort('agency_name'); ">{{ $lang['agency_name'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th>{{ $lang['agency_desc'] }}</th>
                    <th>{{ $lang['handler'] }}</th>
                </tr>
                @forelse($agency_list as $agency)
                    <tr>
                        <td><input type="checkbox" name="checkboxes[]" value="{{ $agency['agency_id'] }}"/>
                            {{ $agency['agency_id'] }}</td>
                        <td class="first-cell">
            <span
                onclick="javascript:listTable.edit(this, 'edit_agency_name', {{ $agency['agency_id'] }})">{{ $agency['agency_name'] }}
            </span>
                        </td>
                        <td>{$agency.agency_desc|nl2br}</td>
                        <td align="center">
                            <a href="agency.php?act=edit&id={{ $agency['agency_id'] }}"
                               title="{{ $lang['edit'] }}">{{ $lang['edit'] }}</a> |
                            <a href="javascript:;"
                               onclick="listTable.remove({{ $agency['agency_id'] }}, '{{ $lang['drop_confirm'] }}')"
                               title="{{ $lang['remove'] }}">{{ $lang['remove'] }}</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="no-records" colspan="4">{{ $lang['no_records'] }}</td>
                    </tr>
                @endforelse
            </table>
            <table id="page-table" cellspacing="0">
                <tr>
                    <td>
                        <input name="remove" type="submit" id="btnSubmit" value="{{ $lang['drop'] }}" class="button"
                               disabled="true"/>
                        <input name="act" type="hidden" value="batch"/>
                    </td>
                    <td align="right" nowrap="true">
                        @include('admin::page')
                    </td>
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
