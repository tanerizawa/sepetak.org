# StrReplace fragments for `/home/sepetak.org/app/Services/ArticleGeneratorService.php`

Total edits captured in transcript: **25**

> These fragments are the only surviving traces of edits applied by the previous agent.
> The baseline file existed before the session but is now missing, so the final content cannot be fully reconstructed.
> Use the `new_string` blocks below as guidance when manually rewriting the file.

## Edit #1

### old_string

```
    protected function buildDisclosure(): string
    {
        return '<hr class="article-disclosure-divider">' .
            '<aside class="article-disclosure">' .
            '<p><em>Artikel ini disusun dengan bantuan kecerdasan buatan dan telah ditinjau oleh redaksi SEPETAK. ' .
            'Referensi yang dicantumkan adalah sumber nyata yang dapat diverifikasi. ' .
            'Pandangan dalam artikel ini tidak selalu mencerminkan posisi resmi organisasi.</em></p>' .
            '</aside>';
    }
```

### new_string

```
    protected function buildDisclosure(?ArticlePool $pool = null): string
    {
        $body = $pool && $pool->content_profile === 'member_practical'
            ? 'Materi praktis ini disusun dengan bantuan AI untuk anggota SEPETAK. Verifikasi ke pengurus/dpt desa bila menyangkut langkah hukum atau administrasi resmi.'
            : 'Artikel ini disusun dengan bantuan kecerdasan buatan dan telah ditinjau oleh redaksi SEPETAK. Referensi yang dicantumkan adalah sumber nyata yang dapat diverifikasi. Pandangan dalam artikel ini tidak selalu mencerminkan posisi resmi organisasi.';

        return '<hr class="article-disclosure-divider">' .
            '<aside class="article-disclosure">' .
            '<p><em>'.e($body).'</em></p>' .
            '</aside>';
    }
```

---

## Edit #2

### old_string

```
        $body .= $this->buildDisclosure();
```

### new_string

```
        $body .= $this->buildDisclosure($pool);
```

---

## Edit #3

### old_string

```
        $isValid = $this->validator->validate($response->content);
```

### new_string

```
        $isValid = $this->validator->validate($response->content, $pool?->content_profile);
```

---

## Edit #4

### old_string

```
        $systemPrompt = $this->promptBuilder->getSystemPrompt();
        $userPrompt = $this->promptBuilder->buildUserPrompt($topic);
        $log->update(['prompt_used' => $userPrompt]);
```

### new_string

```
        $recentTitles = [];
        if ($pool && $pool->content_profile === 'member_practical') {
            $recentTitles = Post::query()
                ->where('source_type', 'auto_generated')
                ->where('created_at', '>=', now()->subDays(14))
                ->orderByDesc('id')
                ->limit(22)
                ->pluck('title')
                ->all();
        }

        $systemPrompt = $this->promptBuilder->getSystemPrompt($pool);
        $userPrompt = $this->promptBuilder->buildUserPrompt($topic, $pool, $recentTitles);
        $log->update(['prompt_used' => $userPrompt]);
```

---

## Edit #5

### old_string

```
use App\Models\ArticleGenerationLog;
use App\Models\ArticlePool;
use App\Models\ArticleTopic;
use App\Models\Post;
```

### new_string

```
use App\Models\ArticleGenerationLog;
use App\Models\ArticlePool;
use App\Models\ArticleTopic;
use App\Models\Category;
use App\Models\Post;
```

---

## Edit #6

### old_string

```
    protected function resolveCategoryByType(string $articleType): ?int
    {
        return match ($articleType) {
            'essay' => 5,                 // Opini
            'opinion' => 13,              // Opini & Refleksi
            'scientific_review' => 10,    // Pemikiran Kritis
            'policy_analysis' => 11,      // Analisis Kebijakan
            'thinker_profile' => 10,      // Pemikiran Kritis
            'historical_review' => 12,    // Sejarah Gerakan
            default => 10,               // Pemikiran Kritis
        };
    }
```

### new_string

```
    protected function resolveCategoryByType(string $articleType): ?int
    {
        if ($articleType === 'member_guide') {
            return Category::query()->where('slug', 'panduan-tips-anggota')->value('id');
        }

        return match ($articleType) {
            'essay' => 5,                 // Opini
            'opinion' => 13,              // Opini & Refleksi
            'scientific_review' => 10,    // Pemikiran Kritis
            'policy_analysis' => 11,      // Analisis Kebijakan
            'thinker_profile' => 10,      // Pemikiran Kritis
            'historical_review' => 12,    // Sejarah Gerakan
            default => 10,               // Pemikiran Kritis
        };
    }
```

---

## Edit #7

### old_string

```
        $body = $pool && $pool->content_profile === 'member_practical'
```

### new_string

```
        $body = ContentProfile::fromPool($pool)->isMemberPractical()
```

---

## Edit #8

### old_string

```
        $isValid = $this->validator->validate($response->content, $pool?->content_profile);
```

### new_string

```
        $isValid = $this->validator->validate(
            $response->content,
            ContentProfile::fromPool($pool)->value
        );
```

---

## Edit #9

### old_string

```
        $recentTitles = [];
        if ($pool && $pool->content_profile === 'member_practical') {
            $recentTitles = Post::query()
                ->where('source_type', 'auto_generated')
                ->where('created_at', '>=', now()->subDays(14))
                ->orderByDesc('id')
                ->limit(22)
                ->pluck('title')
                ->all();
        }

        $systemPrompt = $this->promptBuilder->getSystemPrompt($pool);
        $userPrompt = $this->promptBuilder->buildUserPrompt($topic, $pool, $recentTitles);
```

### new_string

```
        $recentTitles = [];
        if (ContentProfile::fromPool($pool)->isMemberPractical()) {
            $recentTitles = Post::query()
                ->where('source_type', 'auto_generated')
                ->where('created_at', '>=', now()->subDays(14))
                ->orderByDesc('id')
                ->limit(22)
                ->pluck('title')
                ->all();
        }

        $systemPrompt = $this->promptComposer->systemPrompt($pool);
        $userPrompt = $this->promptComposer->buildUserPrompt($pool, $topic, $recentTitles);
```

---

## Edit #10

### old_string

```
    public function __construct(
        protected ArticleAiProvider $aiProvider,
        protected PromptBuilder $promptBuilder,
        protected ResponseParser $parser,
        protected ArticleQualityValidator $validator,
        protected ArticleImageService $imageService,
    ) {}
```

### new_string

```
    public function __construct(
        protected ArticleAiProvider $aiProvider,
        protected PromptComposer $promptComposer,
        protected ResponseParser $parser,
        protected ArticleQualityValidator $validator,
        protected ArticleImageService $imageService,
    ) {}
```

---

## Edit #11

### old_string

```
use App\Contracts\ArticleAiProvider;
use App\Models\ArticleGenerationLog;
use App\Models\ArticlePool;
use App\Models\ArticleTopic;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
```

### new_string

```
use App\Contracts\ArticleAiProvider;
use App\Models\ArticleGenerationLog;
use App\Models\ArticlePool;
use App\Models\ArticleTopic;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use App\Services\ArticleGeneration\ContentProfile;
use App\Services\ArticleGeneration\PromptComposer;
```

---

## Edit #12

### old_string

```
    protected function uniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        if (empty($base)) {
            $base = 'artikel-'.Str::random(8);
        }

        $slug = $base;
        $counter = 1;
        while (Post::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
```

### new_string

```
    protected function normalizeArticleTitle(string $title): string
    {
        return mb_substr(PostSlug::normalizeHeadingText($title), 0, 500);
    }
```

---

## Edit #13

### old_string

```
        // Generate unique slug
        $slug = $this->uniqueSlug($title);
```

### new_string

```
        // Slug unik, panjang aman untuk kolom DB (hindari silent truncate / mismatch URL)
        $slug = PostSlug::uniqueFromTitle($title);
```

---

## Edit #14

### old_string

```
        $title = $this->parser->parseTitle($response->content);
        $excerpt = $this->parser->parseExcerpt($response->content);
        $body = $this->parser->parseBody($response->content);
```

### new_string

```
        $title = $this->normalizeArticleTitle($this->parser->parseTitle($response->content));
        $excerpt = $this->parser->parseExcerpt($response->content);
        $body = $this->parser->parseBody($response->content);
```

---

## Edit #15

### old_string

```
use App\Models\User;
use App\Services\ArticleGeneration\ContentProfile;
use App\Services\ArticleGeneration\PromptComposer;
```

### new_string

```
use App\Models\User;
use App\Services\ArticleGeneration\ContentProfile;
use App\Services\ArticleGeneration\PromptComposer;
use App\Support\PostSlug;
```

---

## Edit #16

### old_string

```
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
```

### new_string

```
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
```

---

## Edit #17

### old_string

```
        $body = ContentProfile::fromPool($pool)->isMemberPractical()
```

### new_string

```
        $body = ContentProfile::forArticleGeneration($pool, $topic)->isMemberPractical()
```

---

## Edit #18

### old_string

```
        $isValid = $this->validator->validate(
            $response->content,
            ContentProfile::fromPool($pool)->value
        );
```

### new_string

```
        $isValid = $this->validator->validate(
            $response->content,
            ContentProfile::forArticleGeneration($pool, $topic)->value
        );
```

---

## Edit #19

### old_string

```
        $systemPrompt = $this->promptComposer->systemPrompt($pool);
```

### new_string

```
        $systemPrompt = $this->promptComposer->systemPrompt($pool, $topic);
```

---

## Edit #20

### old_string

```
        if (ContentProfile::fromPool($pool)->isMemberPractical()) {
```

### new_string

```
        if (ContentProfile::forArticleGeneration($pool, $topic)->isMemberPractical()) {
```

---

## Edit #21

### old_string

```
    protected function buildDisclosure(?ArticlePool $pool = null): string
    {
        $body = ContentProfile::forArticleGeneration($pool, $topic)->isMemberPractical()
```

### new_string

```
    protected function buildDisclosure(?ArticlePool $pool, ArticleTopic $topic): string
    {
        $body = ContentProfile::forArticleGeneration($pool, $topic)->isMemberPractical()
```

---

## Edit #22

### old_string

```
        $body .= $this->buildDisclosure($pool);
```

### new_string

```
        $body .= $this->buildDisclosure($pool, $topic);
```

---

## Edit #23

### old_string

```
        // Parse content (Markdown → HTML conversion happens here)
        $title = $this->parser->parseTitle($response->content);
        $excerpt = $this->parser->parseExcerpt($response->content);
        $body = $this->parser->parseBody($response->content);
```

### new_string

```
        // Parse content (Markdown → HTML conversion happens here)
        $title = $this->normalizeArticleTitle($this->parser->parseTitle($response->content));
        $excerpt = $this->parser->parseExcerpt($response->content);
        $body = $this->parser->parseBody($response->content);
```

---

## Edit #24

### old_string

```
        $recentTitles = [];
        if (ContentProfile::forArticleGeneration($pool, $topic)->isMemberPractical()) {
            $recentTitles = Post::query()
                ->where('source_type', 'auto_generated')
                ->where('created_at', '>=', now()->subDays(14))
                ->orderByDesc('id')
                ->limit(22)
                ->pluck('title')
                ->all();
        }
```

### new_string

```
        $recentTitles = [];
        if (ContentProfile::forArticleGeneration($pool, $topic)->isMemberPractical()) {
            $promptCfg = config('article-generator.member_practical_prompt', []);
            $lookbackDays = (int) ($promptCfg['recent_title_lookback_days'] ?? 14);
            $maxTitles = (int) ($promptCfg['recent_title_max'] ?? 18);
            $maxChars = (int) ($promptCfg['recent_title_max_chars'] ?? 0);

            $recentTitles = Post::query()
                ->where('source_type', 'auto_generated')
                ->where('created_at', '>=', now()->subDays(max(1, $lookbackDays)))
                ->orderByDesc('id')
                ->limit(max(1, min($maxTitles, 50)))
                ->pluck('title')
                ->map(function (mixed $title) use ($maxChars): string {
                    $t = (string) $title;

                    return $maxChars > 0 ? Str::limit($t, $maxChars, '…') : $t;
                })
                ->all();
        }
```

---

## Edit #25

### old_string

```
use App\Support\PostSlug;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
```

### new_string

```
use App\Support\PostSlug;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
```

---

