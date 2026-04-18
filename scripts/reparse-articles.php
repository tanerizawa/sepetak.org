<?php

/**
 * Re-parse all auto-generated articles through the current ResponseParser.
 * Converts raw Markdown (stored in generation logs) to the latest HTML format.
 *
 * Usage: php artisan tinker scripts/reparse-articles.php
 */

use App\Models\ArticleGenerationLog;
use App\Models\Post;
use App\Services\ResponseParser;

$parser = app(ResponseParser::class);

$disclosure = '<hr class="article-disclosure-divider">'
    .'<aside class="article-disclosure">'
    .'<p><em>Artikel ini disusun dengan bantuan kecerdasan buatan dan telah ditinjau oleh redaksi SEPETAK. '
    .'Referensi yang dicantumkan adalah sumber nyata yang dapat diverifikasi. '
    .'Pandangan dalam artikel ini tidak selalu mencerminkan posisi resmi organisasi.</em></p>'
    .'</aside>';

$posts = Post::where('source_type', 'auto_generated')->get();

foreach ($posts as $post) {
    $log = ArticleGenerationLog::where('post_id', $post->id)->first();

    if (! $log || ! $log->raw_response) {
        echo "Post [{$post->id}] — no raw_response, SKIPPED\n";

        continue;
    }

    $raw = $log->raw_response;
    $body = $parser->parseBody($raw);
    $excerpt = $parser->parseExcerpt($raw);

    // Add disclosure if not already present
    if (! str_contains($body, 'article-disclosure')) {
        $body .= $disclosure;
    }

    $post->update([
        'body' => $body,
        'excerpt' => $excerpt,
    ]);

    $checks = [
        'toc' => str_contains($body, 'article-toc'),
        'meta' => str_contains($body, 'article-meta'),
        'abstract' => str_contains($body, 'article-abstract'),
        'biblio' => str_contains($body, 'article-bibliography'),
        'anchors' => (bool) preg_match('/<h2[^>]*id=/', $body),
        'back2top' => str_contains($body, 'back-to-top-link'),
        'disclosure' => str_contains($body, 'article-disclosure'),
        'takeaway' => str_contains($body, 'article-key-takeaway'),
    ];

    $summary = implode(' ', array_map(
        fn ($k, $v) => $k.':'.($v ? 'YES' : 'no'),
        array_keys($checks),
        array_values($checks)
    ));

    echo "Post [{$post->id}] UPDATED — ".mb_strlen($body)." chars | {$summary}\n";
}

echo "\nDone.\n";
