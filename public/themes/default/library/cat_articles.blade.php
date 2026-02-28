<div class="box">
    <div class="box_1">
        <h3>
            <span><a href="{{ $articles_cat['url'] }}">{{ $articles_cat['name'] }}</a></span>
            <a href="{{ $articles_cat['url'] }}"><img src="../images/more.gif" alt="more"/></a>
        </h3>
        <div class="boxCenterList RelaArticle">
            @foreach($articles as $article_item)
                <a href="{{ $article_item['url'] }}"
                   title="{{ $article_item['title'] }}">{{ $article_item['short_title'] }}</a>
                {{ $article_item['add_time'] }}<br/>
            @endforeach
        </div>
    </div>
</div>
<div class="blank5"></div>
