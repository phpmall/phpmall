@if($full_page)
    @include('admin::pageheader')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('static/admin/js/listtable.js') }}"></script>
    <div class="form-div">
        <input type="button" name="export" value="{{ $lang['export'] }}"
               onclick="location.href='email_list.php?act=export';"
               class="button"/>
    </div>
    <form method="post" action="email_list.php" name="listForm">
        <div class="list-div" id="listDiv">
            @endif
            <table cellspacing='1' cellpadding='3'>
                <tr>
                    <th width="5%"><input onclick='listTable.selectAll(this, "checkboxes")' type="checkbox"><a
                            href="javascript:listTable.sort('id'); ">{{ $lang['id'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th><a href="javascript:listTable.sort('email'); ">{{ $lang['email_val'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th width="15%"><a href="javascript:listTable.sort('stat'); ">{{ $lang['stat']['name'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                </tr>
                @forelse($emaildb as $val)
                    <tr>
                        <td><input name="checkboxes[]" type="checkbox" value="{{ $val['id'] }}"/>{{ $val['id'] }}</td>
                        <td>{{ $val['email'] }}</td>
                        <td align="center">{$lang.stat[$val.stat]}</td>
                    </tr>
                @empty
                    <tr>
                        <td class="no-records" colspan="10">{{ $lang['no_records'] }}</td>
                    </tr>
                @endforelse
            </table>
            <table id="page-table" cellspacing="0">
                <tr>
                    <td>
                        <input type="hidden" name="act" value=""/>
                        <input type="button" id="btnSubmit1" value="{{ $lang['button_exit'] }}" disabled="true"
                               class="button"
                               onClick="javascript:document.listForm.act.value='batch_exit';document.listForm.submit();"/>
                        <input type="button" id="btnSubmit2" value="{{ $lang['button_remove'] }}" disabled="true"
                               class="button"
                               onClick="javascript:document.listForm.act.value='batch_remove';document.listForm.submit();"/>
                        <input type="button" id="btnSubmit3" value="{{ $lang['button_unremove'] }}" disabled="true"
                               class="button"
                               onClick="javascript:document.listForm.act.value='batch_unremove';document.listForm.submit();"/>
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
