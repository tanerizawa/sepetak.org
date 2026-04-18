        if ($post->isDirty('body') && filled($post->body)) {
            $post->body = Purifier::clean($post->body, 'filament_rich_html');
        }