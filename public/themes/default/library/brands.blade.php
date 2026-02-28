@if($brand_list)
    @foreach($brand_list as $brand)
        @if($loop->brand_foreach.index <= 11)
            @if($brand['brand_logo'])
                <a href="{{ $brand['url'] }}"><img src="data/brandlogo/{{ $brand['brand_logo'] }}"
                                                   alt="{{ $brand['brand_name'] }} ({{ $brand['goods_num'] }})"/></a>
            @else
                <a href="{{ $brand['url'] }}">{{ $brand['brand_name'] }} @if($brand['goods_num'])
                        ({{ $brand['goods_num'] }})
                    @endif</a>
            @endif
        @endif
    @endforeach
    <div class="brandsMore"><a href="../brand.php"><img src="images/moreBrands.gif"/></a></div>
@endif
