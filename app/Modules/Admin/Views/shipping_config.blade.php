@include('admin::pageheader')
<div class="form-div">
    <form method="post" action="shipping.php">
        <table cellspacing='1' cellpadding='3'>
            @foreach($modules as $module)
                <tr>
                    <td class="first-cell"><a href="{{ $module['website'] }}" target="_blank">{{ $module['name'] }}</a>
                    </td>
                    <td>{{ $module['desc'] }}</td>
                    <td><a href="mailto:{{ $module['email'] }}">{{ $module['author'] }}</a></td>
                    <td align="center">{{ $module['act'] }}</td>
                </tr>
            @endforeach
        </table>
        <input type="hidden" name="act" value="$form_action"/>
    </form>
</div>

<script type="text/javascript">
</script>

@include('admin::pagefooter')
