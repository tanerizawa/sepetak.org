<?php

/**
 * Regenerate a specific article that was truncated during initial generation.
 * Deletes old post + log and generates fresh from the same topic.
 *
 * Usage: php artisan tinker scripts/regenerate-article.php
 * Set $postId below before running.
 */

use App\Models\ArticleGenerationLog;
use App\Models\Post;
use App\Services\ArticleGeneratorService;

$postId = 44; // <-- Change this to the post ID to regenerate

$post = Post::find($postId);
if (! $post) {
    echo "Post {$postId} not found.\n";

    return;
}

$topic = $post->articleTopic;
if (! $topic) {
    echo "No topic linked to Post {$postId}.\n";

    return;
}

echo "Regenerating Post [{$postId}] {$post->title}\n";
echo "Topic: {$topic->title}\n";
echo "Type: {$topic->article_type} | Framework: {$topic->thinking_framework}\n";
echo 'Max tokens: '.config('article-generator.openrouter.max_tokens')."\n\n";

// Remove old cover media
$oldCover = $post->getFirstMedia('cover');
if ($oldCover) {
    $oldCover->delete();
    echo "Removed old cover image.\n";
}

// Delete old post and log (force-delete if using SoftDeletes)
$oldSlug = $post->slug;
$oldLogId = $post->generation_log_id;
$post->categories()->detach();
$post->tags()->detach();
$post->forceDelete();
echo "Deleted old Post [{$postId}].\n";

if ($oldLogId) {
    ArticleGenerationLog::where('id', $oldLogId)->delete();
    echo "Deleted old generation log.\n";
}

// Reset topic cooldown so it can be regenerated
$topic->update(['last_generated_at' => null]);
echo "Reset topic cooldown.\n\n";

// Regenerate
echo "Calling ArticleGeneratorService::generate()...\n";
$generator = app(ArticleGeneratorService::class);

try {
    $newPost = $generator->generate($topic, null, 'manual_regenerate');

    if ($newPost) {
        echo "\nSUCCESS: New Post [{$newPost->id}] created.\n";
        echo "Title: {$newPost->title}\n";
        echo "Slug: {$newPost->slug}\n";
        echo "Status: {$newPost->status}\n";
        echo 'Body length: '.mb_strlen($newPost->body)." chars\n";

        $hasBiblio = str_contains($newPost->body, 'article-bibliography');
        echo 'Has bibliography: '.($hasBiblio ? 'YES' : 'NO')."\n";

        $cover = $newPost->fresh()->getFirstMedia('cover');
        echo 'Has cover: '.($cover ? "YES ({$cover->getCustomProperty('source', '?')})" : 'NO')."\n";
    } else {
        echo "\nFAILED: Generator returned null.\n";
        echo "Check storage/logs/laravel.log for details.\n";
    }
} catch (Throwable $e) {
    echo "\nERROR: {$e->getMessage()}\n";
    echo "File: {$e->getFile()}:{$e->getLine()}\n";
}

echo "\nDone.\n";
