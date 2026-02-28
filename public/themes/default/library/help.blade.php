@if($helps)
    @foreach($helps as $help_cat)
        <dl>
            <dt><a href='{{ $help_cat['cat_id'] }}' title="{{ $help_cat['cat_name'] }}">{{ $help_cat['cat_name'] }}</a>
            </dt>
            @foreach($help_cat['article'] as $item)
                <dd><a href="{{ $item['url'] }}" title="{{ $item['title'] }}">{{ $item['short_title'] }}</a></dd>
            @endforeach
        </dl>
    @endforeach
@endif
