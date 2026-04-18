# StrReplace fragments for `/home/sepetak.org/app/Observers/PageObserver.php`

Total edits captured in transcript: **3**

> These fragments are the only surviving traces of edits applied by the previous agent.
> The baseline file existed before the session but is now missing, so the final content cannot be fully reconstructed.
> Use the `new_string` blocks below as guidance when manually rewriting the file.

## Edit #1

### old_string

```
        if ($page->isDirty('body') && filled($page->body)) {
            $page->body = Purifier::clean($page->body);
        }
```

### new_string

```
        if ($page->isDirty('body') && filled($page->body)) {
            $page->body = Purifier::clean($page->body, 'filament_rich_html');
        }
```

---

## Edit #2

### old_string

```
    /**
     * Sanitize body HTML on save (S-1 / H-6).
     */
```

### new_string

```
    /**
     * Sanitize body HTML on save (S-1 / H-6).
     * Memakai profil {@see config('purifier.settings.filament_rich_html')} agar heading
     * dan struktur TipTap tidak dihapus oleh profil `default` (yang tidak mengizinkan h1–h6).
     */
```

---

## Edit #3

### old_string

```
     * Memakai profil {@see config('purifier.settings.filament_rich_html')} agar heading
```

### new_string

```
     * Memakai profil Purifier `filament_rich_html` (config/purifier.php) agar heading
```

---

