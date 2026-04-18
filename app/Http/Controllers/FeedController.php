<?php

namespace App\Http\Controllers;

use App\Models\GalleryAlbum;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Http\Response;

class FeedController extends Controller
{
    public function sitemap(): Response
    {
        $urls = [
            ['loc' => route('beranda'), 'priority' => '1.0', 'changefreq' => 'weekly'],
            ['loc' => route('posts.index'), 'priority' => '0.9', 'changefreq' => 'daily'],
            ['loc' => route('events.index'), 'priority' => '0.7', 'changefreq' => 'weekly'],
            ['loc' => route('gallery.index'), 'priority' => '0.7', 'changefreq' => 'weekly'],
            ['loc' => route('agrarian-cases.index'), 'priority' => '0.65', 'changefreq' => 'weekly'],
            ['loc' => route('advocacy-programs.index'), 'priority' => '0.65', 'changefreq' => 'weekly'],
            ['loc' => route('member-registration.create'), 'priority' => '0.7', 'changefreq' => 'monthly'],
        ];

        foreach (Post::published()->latest('published_at')->get() as $post) {
            $urls[] = [
                'loc'        => route('posts.show', $post->slug),
                'lastmod'    => optional($post->updated_at)->toAtomString(),
                'priority'   => '0.8',
                'changefreq' => 'weekly',
            ];
        }

        foreach (Page::published()->get() as $page) {
            $urls[] = [
                'loc'        => route('pages.show', $page->slug),
                'lastmod'    => optional($page->updated_at)->toAtomString(),
                'priority'   => '0.6',
                'changefreq' => 'monthly',
            ];
        }

        foreach (GalleryAlbum::published()->get() as $album) {
            $urls[] = [
                'loc'        => route('gallery.show', $album->slug),
                'lastmod'    => optional($album->updated_at)->toAtomString(),
                'priority'   => '0.55',
                'changefreq' => 'weekly',
            ];
        }

        return response()
            ->view('feeds.sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml; charset=utf-8');
    }

    public function rss(): Response
    {
        $posts = Post::published()->latest('published_at')->limit(20)->get();

        return response()
            ->view('feeds.rss', ['posts' => $posts])
            ->header('Content-Type', 'application/rss+xml; charset=utf-8');
    }

    public function robots(): Response
    {
        $content = implode("\n", [
            'User-agent: *',
            'Disallow: /admin',
            'Disallow: /livewire',
            '',
            'Sitemap: ' . url('/sitemap.xml'),
            '',
        ]);

        return response($content, 200)
            ->header('Content-Type', 'text/plain; charset=utf-8');
    }
}
