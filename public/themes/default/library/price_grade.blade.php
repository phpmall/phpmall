@if($price_grade)
    <div class="box">
        <div class="box_1">
            <h3><span>{{ $lang['price_grade'] }}</span></h3>
            <div class="boxCenterList RelaArticle">
                @foreach($price_grade as $grade)
                    @if($grade['selected'])
                        <img src="../images/alone.gif" style=" margin-right:8px;">
                        <font class="f1 f5">{{ $grade['start'] }} - {{ $grade['end'] }}
                            @if($grade['goods_num'])
                                ({{ $grade['goods_num'] }})
                            @endif</font><br/>
                    @else
                        <a href="{{ $grade['url'] }}">{{ $grade['start'] }} - {{ $grade['end'] }}</a>
                        @if($grade['goods_num'])
                            ({{ $grade['goods_num'] }})
                        @endif<br/>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
    <div class="blank5"></div>
@endif
