@if($article_categories)
    <div class="box">
        <div class="box_1">
            <h3><span>{{ $lang['article_cat'] }}</span></h3>
            <div class="boxCenterList RelaArticle">
                @foreach($article_categories as $cat)
                    <a href="{{ $cat['url'] }}">{{ $cat['name'] }}</a><br/>
                    @foreach($cat['children'] as $child)
                        <a href="{{ $child['url'] }}" style="background-image:none;">{{ $child['name'] }}</a><br/>
                    @endforeach
                @endforeach
            </div>
        </div>
    </div>
    <div class="blank5"></div>
@endif
