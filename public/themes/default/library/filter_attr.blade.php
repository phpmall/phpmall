@if($filter_attr_list)
    <div class="box">
        <div class="box_1">
            <h3><span>{{ $filter_attr_name }}</span></h3>
            <div class="boxCenterList RelaArticle">
                @foreach($filter_attr_list as $attr)
                    @if($attr['selected'])
                        <a
                            href="{{ $attr['url'] }}">{{ $attr['attr_value'] }}@if($attr['goods_num'])
                                ({{ $attr['goods_num'] }})
                            @endif</a><br/>
                    @else
                        <a
                            href="{{ $attr['url'] }}">{{ $attr['attr_value'] }}@if($attr['goods_num'])
                                ({{ $attr['goods_num'] }})
                            @endif</a><br/>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
    <div class="blank5"></div>
@endif
