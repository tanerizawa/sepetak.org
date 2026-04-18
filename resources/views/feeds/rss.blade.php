<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title>{{ \App\Models\SiteSetting::getValue('site_name', 'SEPETAK') }}</title>
        <link>{{ url('/') }}</link>
        <atom:link href="{{ url('/feed.xml') }}" rel="self" type="application/rss+xml" />
        <description>{{ \App\Models\SiteSetting::getValue('site_description', 'Berita dan advokasi SEPETAK') }}</description>
        <language>id</language>
        <lastBuildDate>{{ now()->toRssString() }}</lastBuildDate>
@foreach($posts as $post)
        <item>
            <title>{{ $post->title }}</title>
            <link>{{ route('posts.show', $post->slug) }}</link>
            <guid isPermaLink="true">{{ route('posts.show', $post->slug) }}</guid>
            @if($post->published_at)<pubDate>{{ $post->published_at->toRssString() }}</pubDate>@endif
            <description><![CDATA[{{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->body), 300) }}]]></description>
        </item>
@endforeach
    </channel>
</rss>
