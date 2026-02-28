@include('admin::pageheader')
<!-- start form -->
<div class="form-div">
    <form method="post" action="database.php" name="theForm">
        {{ $lang['chip_count'] }}:{{ $num }}
        <input type="submit" value="{{ $lang['start_optimize'] }}" class="button"/>
        <input type="hidden" name="num" value="{{ $num }}">
        <input type="hidden" name="act" value="run_optimize">
    </form>
</div>
<!-- end form -->
<!-- start list -->
<div class="list-div" id="listDiv">
    <table cellspacing='1' cellpadding='3' id='listTable'>
        <tr>
            <th>{{ $lang['table'] }}</th>
            <th>{{ $lang['type'] }}</th>
            <th>{{ $lang['rec_num'] }}</th>
            <th>{{ $lang['rec_size'] }}</th>
            <th>{{ $lang['rec_chip'] }}</th>
            <th>{{ $lang['charset'] }}</th>
            <th>{{ $lang['status'] }}</th>
        </tr>
        @foreach($list as $item)
            <tr>
                <td class="first-cell">{{ $item['table'] }}</td>
                <td align="left">{{ $item['type'] }}</td>
                <td align="right">{{ $item['rec_num'] }}</td>
                <td align="right">{{ $item['rec_size'] }}</td>
                <td align="right">{{ $item['rec_chip'] }}</td>
                <td align="left">{{ $item['charset'] }}</td>
                <td align="left">{{ $item['status'] }}</td>
            </tr>
        @endforeach
    </table>
</div>
<!-- end  list -->

<script type="text/javascript">
</script>

@include('admin::pagefooter')
