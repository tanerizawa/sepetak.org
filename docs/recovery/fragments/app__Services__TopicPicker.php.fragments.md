# StrReplace fragments for `/home/sepetak.org/app/Services/TopicPicker.php`

Total edits captured in transcript: **5**

> These fragments are the only surviving traces of edits applied by the previous agent.
> The baseline file existed before the session but is now missing, so the final content cannot be fully reconstructed.
> Use the `new_string` blocks below as guidance when manually rewriting the file.

## Edit #1

### old_string

```
            ->filter(function (ArticleTopic $t) use ($cooldownHours) {
                if ($cooldownHours <= 0) {
                    return true;
                }
                $lastLog = $t->generationLogs()
                    ->where('status', 'completed')
                    ->latest()
                    ->first();

                return ! $lastLog || $lastLog->created_at->diffInHours(now()) >= $cooldownHours;
            });

        if ($topics->isEmpty()) {
            return null;
        }
```

### new_string

```
            ->filter(function (ArticleTopic $t) use ($cooldownHours) {
                if ($cooldownHours <= 0) {
                    return true;
                }
                $lastLog = $t->generationLogs()
                    ->where('status', 'completed')
                    ->latest()
                    ->first();

                return ! $lastLog || $lastLog->created_at->diffInHours(now()) >= $cooldownHours;
            });

        if ($pool->content_profile === 'member_practical') {
            $tz = config('article-generator.schedule_timezone', config('app.timezone'));
            $today = now()->timezone($tz)->toDateString();
            $usedToday = ArticleGenerationLog::query()
                ->where('article_pool_id', $pool->id)
                ->whereDate('created_at', $today)
                ->where('status', 'completed')
                ->pluck('article_topic_id')
                ->unique()
                ->all();
            $topics = $topics->reject(fn (ArticleTopic $t) => in_array($t->id, $usedToday, true));
        }

        if ($topics->isEmpty()) {
            return null;
        }
```

---

## Edit #2

### old_string

```
namespace App\Services;

use App\Models\ArticlePool;
use App\Models\ArticleTopic;
use Illuminate\Support\Collection;
```

### new_string

```
namespace App\Services;

use App\Models\ArticleGenerationLog;
use App\Models\ArticlePool;
use App\Models\ArticleTopic;
use Illuminate\Support\Collection;
```

---

## Edit #3

### old_string

```
        if ($pool->content_profile === 'member_practical') {
```

### new_string

```
        if (($pool->content_profile ?? 'pillar') === 'member_practical') {
```

---

## Edit #4

### old_string

```
        if (($pool->content_profile ?? 'pillar') === 'member_practical') {
```

### new_string

```
        if (ContentProfile::fromPool($pool)->isMemberPractical()) {
```

---

## Edit #5

### old_string

```
namespace App\Services;

use App\Models\ArticleGenerationLog;
use App\Models\ArticlePool;
use App\Models\ArticleTopic;
```

### new_string

```
namespace App\Services;

use App\Models\ArticleGenerationLog;
use App\Models\ArticlePool;
use App\Models\ArticleTopic;
use App\Services\ArticleGeneration\ContentProfile;
```

---

