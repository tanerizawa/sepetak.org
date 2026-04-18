<?php

namespace App\Providers;

use App\Contracts\ArticleAiProvider;
use App\Models\Page;
use App\Models\Post;
use App\Observers\PageObserver;
use App\Observers\PostObserver;
use App\Services\AiProviders\OpenRouterProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ArticleAiProvider::class, function (): ArticleAiProvider {
            return OpenRouterProvider::fromConfig();
        });
    }

    public function boot(): void
    {
        Post::observe(PostObserver::class);
        Page::observe(PageObserver::class);

        if (str_starts_with((string) config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }
    }
}
