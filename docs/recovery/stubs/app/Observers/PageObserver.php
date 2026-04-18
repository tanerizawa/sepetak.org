        if ($page->isDirty('body') && filled($page->body)) {
            $page->body = Purifier::clean($page->body, 'filament_rich_html');
        }