@include('admin::pageheader')
<!-- start templates list -->
<div class="list-div">
    <table width="100%" cellpadding="3" cellspacing="1">
        <tr>
            <th>{{ $lang['cur_setting_template'] }}</th>
        </tr>
        @if($files)
            <tr>
                <td>
                    <form action="template.php" method="post">
                        <table>
                            <tr>
                                <td colspan="2"><input type="checkbox" name="chkall"
                                                       onclick="checkall(this.form, 'files[]')"><strong>{{ $lang['select_all'] }}
                                        <strong></td>
                            </tr>
                            <tr>
                                @foreach($files as $key => $file)
                                    @if($loop->template . iteration > 1 && ($loop->template . iteration - 1) % 3 === 0)
                            </tr>
                            <tr>
                                @endif
                                <td><input type="checkbox" name="files[]" value="{{ $key }}">{{ $file }}</td>
                                @if($loop->template . last)
                                    @if($loop->template . iteration % 3 === 0)
                            </tr>
                            @elseif($loop->template . iteration % 3 === 1)
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
            </tr>
        @else
            <td>&nbsp;</td>
            </tr>
        @endif
        @endif
        @endforeach
        <tr>
            <td colspan="3">{{ $lang['remarks'] }}:&nbsp;&nbsp;<input type="text" name="remarks" size="40"/></td>
        </tr>
        <tr>
            <td colspan="3"/>
            <input type="hidden" name="act" value="act_backup_setting"/><input type="submit"
                                                                               value="{{ $lang['backup_setting'] }}"
                                                                               class="button"/>
            <td>
        </tr>
    </table>
    </form>
    </td>
    </tr>
    @else
        <tr>
            <td colspan="2" align="center">{{ $lang['no_setting_template'] }}</td>
        </tr>
    @endif
    <tr>
        <th>{{ $lang['cur_backup'] }}</th>
    </tr>
    @forelse($list as $remarks)
        <tr>
            <td><span style="float:right"><a
                        href="template.php?act=restore_backup&remarks={{ $remarks['url'] }}">{{ $lang['restore'] }}</a>&nbsp;&nbsp;<a
                        href="template.php?act=del_backup&remarks={{ $remarks['url'] }}">{{ $lang['remove'] }}</a></span>{{ $remarks['content'] }}
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="2" align="center">{{ $lang['no_backup'] }}</td>
        </tr>
        @endforelse
        </table>
</div>
<!-- end templates list -->

<script type="text/javascript">
    function checkall(frm, chk) {
        for (i = 0; i < frm.elements.length; i++) {
            if (frm.elements[i].name === chk) {
                frm.elements[i].checked = frm.elements['chkall'].checked;
            }
        }
    }
</script>
@include('admin::pagefooter')
