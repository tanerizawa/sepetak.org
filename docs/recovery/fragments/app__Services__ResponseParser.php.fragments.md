# StrReplace fragments for `/home/sepetak.org/app/Services/ResponseParser.php`

Total edits captured in transcript: **5**

> These fragments are the only surviving traces of edits applied by the previous agent.
> The baseline file existed before the session but is now missing, so the final content cannot be fully reconstructed.
> Use the `new_string` blocks below as guidance when manually rewriting the file.

## Edit #1

### old_string

```
    public function hasAbstract(string $content): bool
    {
        return (bool) preg_match('/##\s*Abstrak/i', $content);
    }
```

### new_string

```
    public function hasAbstract(string $content): bool
    {
        return (bool) preg_match('/##\s*(Abstrak|Ringkasan(\s+praktis)?)/i', $content);
    }
```

---

## Edit #2

### old_string

```
        if (preg_match('/##\s*Abstrak\s*\n+([\s\S]*?)(?=\n##\s|\z)/i', $content, $matches)) {
```

### new_string

```
        if (preg_match('/##\s*(?:Abstrak|Ringkasan\s+praktis)\s*\n+([\s\S]*?)(?=\n##\s|\z)/i', $content, $matches)) {
```

---

## Edit #3

### old_string

```
        $pattern = '/(<h2[^>]*id="abstrak"[^>]*>.*?<\/h2>)([\s\S]*?)(?=<(?:h2|nav)[\s>])/i';

        return preg_replace_callback($pattern, function ($matches) {
            return '<section class="article-abstract">' . $matches[1] . $matches[2] . '</section>';
        }, $html);
```

### new_string

```
        $pattern = '/(<h2[^>]*id="(?:abstrak|ringkasan-praktis|ringkasan)"[^>]*>.*?<\/h2>)([\s\S]*?)(?=<(?:h2|nav)[\s>])/i';

        return preg_replace_callback($pattern, function ($matches) {
            return '<section class="article-abstract">' . $matches[1] . $matches[2] . '</section>';
        }, $html);
```

---

## Edit #4

### old_string

```
    public function parseTitle(string $content): string
    {
        if (preg_match('/^#\s+(.+)$/m', $content, $matches)) {
            return trim($matches[1]);
        }

        $lines = array_filter(explode("\n", $content), fn ($l) => trim($l) !== '');

        return trim(reset($lines) ?: 'Artikel Tanpa Judul');
    }
```

### new_string

```
    public function parseTitle(string $content): string
    {
        if (preg_match('/^#\s+(.+)$/m', $content, $matches)) {
            return PostSlug::normalizeHeadingText($matches[1]);
        }

        $lines = array_filter(explode("\n", $content), fn ($l) => trim($l) !== '');

        return PostSlug::normalizeHeadingText((string) (reset($lines) ?: 'Artikel Tanpa Judul'));
    }
```

---

## Edit #5

### old_string

```
namespace App\Services;

use Illuminate\Support\Str;
```

### new_string

```
namespace App\Services;

use App\Support\PostSlug;
use Illuminate\Support\Str;
```

---

