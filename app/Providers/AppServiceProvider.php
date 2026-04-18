<?php

namespace App\Providers;

use App\Models\Page;
use App\Models\Post;
use App\Observers\PageObserver;
use App\Observers\PostObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Post::observe(PostObserver::class);
        Page::observe(PageObserver::class);
    }
}
