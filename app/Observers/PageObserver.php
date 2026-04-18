<?php

namespace App\Observers;

use App\Models\Page;
use Mews\Purifier\Facades\Purifier;

class PageObserver
{
    public function saving(Page $page): void
    {
        // Gunakan preset filament_rich_html (lihat config/purifier.php) agar heading
        // H2/H3 dari RichEditor tetap terpelihara.
        if ($page->isDirty('body') && filled($page->body)) {
            $page->body = Purifier::clean($page->body, 'filament_rich_html');
        }
    }
}
