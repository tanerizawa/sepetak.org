<?php

/**
 * Re-attach cover images using the improved ArticleImageService.
 * Removes old covers and fetches new ones from Wikimedia Commons → Pexels → Unsplash.
 *
 * Usage: php artisan tinker scripts/attach-covers.php
 */

use App\Models\Post;
use App\Services\ArticleImageService;

$imageService = app(ArticleImageService::class);

$posts = Post::where('source_type', 'auto_generated')->get();

foreach ($posts as $post) {
    echo "Post [{$post->id}] {$post->title}\n";

    // Remove existing cover
    $existing = $post->getFirstMedia('cover');
    if ($existing) {
        $existing->delete();
        echo "  Removed old cover: {$existing->file_name}\n";
    }

    echo "  Searching for cover image...\n";

    try {
        $attached = $imageService->attachCoverImage($post);

        if ($attached) {
            // Reload to get fresh media
            $post->load('media');
            $media = $post->getFirstMedia('cover');
            if ($media) {
                echo "  OK: {$media->file_name} (".number_format($media->size / 1024)." KB)\n";
                echo '  Source: '.$media->getCustomProperty('source', '?')."\n";
                echo '  Credit: '.$media->getCustomProperty('photographer', '?')."\n";
            } else {
                echo "  OK: Cover attached (media reload pending)\n";
            }
        } else {
            echo "  FAIL: No image found\n";
        }
    } catch (Throwable $e) {
        echo "  ERROR: {$e->getMessage()}\n";
    }

    echo "\n";
}

echo "Done.\n";
