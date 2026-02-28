<div class="box">
    <div class="box_1">
        <h3><span>&nbsp;</span></h3>
        <div class="boxCenterList">
            {{ $info }}<br>
            @if($member)
                @foreach($member as $val)
                    {{ $val['user_name'] }}
                @endforeach
            @endif
        </div>
    </div>
</div>
<div class="blank5"></div>
