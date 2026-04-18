# StrReplace fragments for `/home/sepetak.org/app/Observers/PostObserver.php`

Total edits captured in transcript: **6**

> These fragments are the only surviving traces of edits applied by the previous agent.
> The baseline file existed before the session but is now missing, so the final content cannot be fully reconstructed.
> Use the `new_string` blocks below as guidance when manually rewriting the file.

## Edit #1

### old_string

```
        if ($post->isDirty('body') && filled($post->body)) {
            $post->body = Purifier::clean($post->body);
        }
```

### new_string

```
        if ($post->isDirty('body') && filled($post->body)) {
            $post->body = Purifier::clean($post->body, 'filament_rich_html');
        }
```

---

## Edit #2

### old_string

```
    /**
     * Sanitize body HTML on save to mitigate stored XSS (S-1 / H-6).
     * Auto-generated articles are trusted (produced by server-side pipeline) and skipped
     * to preserve custom classes used by the pillar article renderer (TOC, callouts, bibliography).
     */
```

### new_string

```
    /**
     * Sanitize body HTML on save to mitigate stored XSS (S-1 / H-6).
     * Profil `filament_rich_html` mempertahankan heading dan blok TipTap/Filament.
     * Auto-generated articles are trusted (produced by server-side pipeline) and skipped
     * to preserve custom classes used by the pillar article renderer (TOC, callouts, bibliography).
     */
```

---

## Edit #3

### old_string

```
        } catch (\Throwable $e) {
            Log::warning('PostObserver: Cover image attachment failed (non-fatal)', [
                'post_id' => $post->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
```

### new_string

```
        } catch (\Throwable $e) {
            Log::warning('PostObserver: Cover image attachment failed (non-fatal)', [
                'post_id' => $post->id,
                'error' => $e->getMessage(),
            ]);
        }

        if ($post->wasChanged('status') && $post->status === 'published') {
            NotifyMembersPostPublishedWhatsAppJob::dispatch($post->id);
        }
    }
}
```

---

## Edit #4

### old_string

```
namespace App\Observers;

use App\Models\Post;
use App\Services\ArticleImageService;
use Illuminate\Support\Facades\Log;
```

### new_string

```
namespace App\Observers;

use App\Jobs\NotifyMembersPostPublishedWhatsAppJob;
use App\Models\Post;
use App\Services\ArticleImageService;
use Illuminate\Support\Facades\Log;
```

---

## Edit #5

### old_string

```
    public function updated(Post $post): void
    {
        // Only trigger when status changed to 'published'
        if (! $post->wasChanged('status') || $post->status !== 'published') {
            return;
        }

        // Skip if already has a cover image
        if ($post->getFirstMediaUrl('cover')) {
            return;
        }

        // Only auto-attach for auto-generated articles (manual articles may have intentional no-cover)
        if ($post->source_type !== 'auto_generated') {
            return;
        }

        Log::info('PostObserver: Auto-attaching cover image on publish', ['post_id' => $post->id]);

        try {
            $attached = $this->imageService->attachCoverImage($post);
            if ($attached) {
                Log::info('PostObserver: Cover image attached successfully', ['post_id' => $post->id]);
            } else {
                Log::info('PostObserver: No suitable cover image found', ['post_id' => $post->id]);
            }
        } catch (\Throwable $e) {
            Log::warning('PostObserver: Cover image attachment failed (non-fatal)', [
                'post_id' => $post->id,
                'error' => $e->getMessage(),
            ]);
        }

        if ($post->wasChanged('status') && $post->status === 'published') {
            NotifyMembersPostPublishedWhatsAppJob::dispatch($post->id);
        }
    }
}
```

### new_string

```
    public function updated(Post $post): void
    {
        $becamePublished = $post->wasChanged('status') && $post->status === 'published';

        if ($becamePublished
            && $post->source_type === 'auto_generated'
            && ! $post->getFirstMediaUrl('cover')) {
            Log::info('PostObserver: Auto-attaching cover image on publish', ['post_id' => $post->id]);

            try {
                $attached = $this->imageService->attachCoverImage($post);
                if ($attached) {
                    Log::info('PostObserver: Cover image attached successfully', ['post_id' => $post->id]);
                } else {
                    Log::info('PostObserver: No suitable cover image found', ['post_id' => $post->id]);
                }
            } catch (\Throwable $e) {
                Log::warning('PostObserver: Cover image attachment failed (non-fatal)', [
                    'post_id' => $post->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        if ($becamePublished) {
            NotifyMembersPostPublishedWhatsAppJob::dispatch($post->id);
        }
    }
}
```

---

## Edit #6

### old_string

```
    public function saving(Post $post): void
    {
        if ($post->source_type === 'auto_generated') {
            return;
        }

        if ($post->isDirty('body') && filled($post->body)) {
            $post->body = Purifier::clean($post->body, 'filament_rich_html');
        }
    }

    /**
     * When a post is updated (e.g. status changed to 'published'),
     * auto-attach a Wikimedia cover image if none exists.
     */
    public function updated(Post $post): void
```

### new_string

```
    public function saving(Post $post): void
    {
        if ($post->source_type === 'auto_generated') {
            return;
        }

        if ($post->isDirty('body') && filled($post->body)) {
            $post->body = Purifier::clean($post->body, 'filament_rich_html');
        }
    }

    public function created(Post $post): void
    {
        if ($post->status === 'published') {
            NotifyMembersPostPublishedWhatsAppJob::dispatch($post->id);
        }
    }

    /**
     * When a post is updated (e.g. status changed to 'published'),
     * auto-attach a Wikimedia cover image if none exists.
     */
    public function updated(Post $post): void
```

---

