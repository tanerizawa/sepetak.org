@foreach ($posts as $post)
    @php
        $cover = $post->getFirstMediaUrl('cover') ?: null;
        $category = $post->categories->first();
        $dateLabel = $post->published_at ? $post->published_at->translatedFormat('d M Y') : null;
        $reading = $post->readingTimeMinutes().' menit baca';
    @endphp

    <x-rev.article-card
        :href="route('posts.show', $post->slug)"
        :image="$cover"
        :image-alt="$post->title"
        :category="$category?->name"
        :reading-time="$reading"
        :date="$dateLabel"
        :title="$post->title"
        :excerpt="$post->excerpt"
    />
@endforeach

