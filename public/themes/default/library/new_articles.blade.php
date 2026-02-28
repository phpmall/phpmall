<ul>
    @foreach($new_articles as $article)
        <li>
            [<a href="{{ $article['cat_url'] }}">{{ $article['cat_name'] }}</a>] <a href="{{ $article['url'] }}"
                                                                                    title="{{ $article['title'] }}">{$article.short_title|truncate:10:"...":true}</a>
        </li>
    @endforeach
</ul>
