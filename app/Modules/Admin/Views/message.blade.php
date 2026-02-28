@include('admin::pageheader')
<div class="list-div">
    <div style="background:#FFF; padding: 20px 50px; margin: 2px;">
        <table align="center" width="400">
            <tr>
                <td width="50" valign="top">
                    @if($msg_type==0)
                        <img src="{{ asset('static/admin/images/information.gif') }}" width="32" height="32" border="0"
                             alt="information"/>
                    @elseif($msg_type === 1)
                        <img src="{{ asset('static/admin/images/warning.gif') }}" width="32" height="32" border="0"
                             alt="warning"/>
                    @else
                        <img src="{{ asset('static/admin/images/confirm.gif') }}" width="32" height="32" border="0"
                             alt="confirm"/>
                    @endif
                </td>
                <td style="font-size: 14px; font-weight: bold">{{ $msg_detail }}</td>
            </tr>
            <tr>
                <td></td>
                <td id="redirectionMsg">
                    @if($auto_redirect)
                        {!! $lang['auto_redirection'] !!}
                    @endif
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <ul style="margin:0; padding:0 10px" class="msg-link">
                        @foreach($links as $link)
                            <li><a href="{{ $link['href'] }}"
                                   @if(isset($link['target']))
                                       target="{{ $link['target'] }}"
                                    @endif
                                >{{ $link['text'] }}</a></li>
                        @endforeach
                    </ul>
                </td>
            </tr>
        </table>
    </div>
</div>
@if($auto_redirect)
    <script type="text/javascript">
        var seconds = 3;
        var defaultUrl = "{{ $default_url }}";
        onload = function () {
            if (defaultUrl === 'javascript:history.go(-1)' && window.history.length === 0) {
                document.getElementById('redirectionMsg').innerHTML = '';
                return;
            }

            window.setInterval(redirection, 1000);
        }

        function redirection() {
            if (seconds <= 0) {
                window.clearInterval();
                return;
            }

            seconds--;
            document.getElementById('spanSeconds').innerHTML = seconds;

            if (seconds === 0) {
                window.clearInterval();
                location.href = defaultUrl;
            }
        }

    </script>

@endif
@include('admin::pagefooter')
