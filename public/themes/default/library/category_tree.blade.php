<div class="box">
    <div class="box_1">
        <div id="category_tree">
            @foreach($categories as $cat)
                <dl>
                    <dt><a href="{{ $cat['url'] }}">{{ $cat['name'] }}</a></dt>
                    @foreach($cat['cat_id'] as $child)
                        <dd><a href="{{ $child['url'] }}">{{ $child['name'] }}</a></dd>
                        @foreach($child['cat_id'] as $childer)
                            <dd>&nbsp;&nbsp;<a href="{{ $childer['url'] }}">{{ $childer['name'] }}</a></dd>
                        @endforeach
                    @endforeach

                </dl>
            @endforeach
        </div>
    </div>
</div>
<div class="blank5"></div>
