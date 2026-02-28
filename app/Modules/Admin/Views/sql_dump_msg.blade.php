@include('admin::pageheader')
<div class="list-div">
    <div style="background:#FFF; padding: 20px 50px; margin: 2px;">
        <table align="center" width="400">
            <tr>
                <td width="100%" valign="top">
                    <img src="{{ asset('static/admin/images/information.gif') }}" width="32" height="32" border="0"
                         alt="information"/>
                    <span style="font-size: 14px; font-weight: bold">{{ $title }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    @if($auto_redirect)
                        <a href="{{ $auto_link }}">{{ $lang['backup_notice'] }}</a>
                        <script
                            type="text/javascript">setTimeout("window.location.replace('{{ $auto_link }}');", 1250);</script>
                    @else
                        <ul>
                            @foreach($list as $file)
                                <li><a href="{{ $file['href'] }}">{{ $file['name'] }}</li>
                            @endforeach
                        </ul>
                    @endif
                </td>
            </tr>
        </table>
    </div>
</div>
@include('admin::pagefooter')
