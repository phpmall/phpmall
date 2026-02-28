@if($cat_list)
    <div class="box">
        <div class="box_1">
            <div id="category_tree">
                @foreach($cat_list as $cat)
                    <dl>
                        <dt><a href="{{ $cat['url'] }}">{{ $cat['cat_name'] }} @if($cat['goods_num'])
                                    ({{ $cat['goods_num'] }})
                                @endif</a>
                        </dt>
                    </dl>
                @endforeach
            </div>
        </div>
    </div>
    <div class="blank5"></div>
@endif
