@props([
    'url' => null,
    'title' => '',
])

@php
    $url = $url ?: (request()->url());
    $encUrl = urlencode($url);
    $encTitle = urlencode($title);
@endphp

<div class="share-buttons flex flex-wrap items-center gap-3" role="group" aria-label="Bagikan artikel">
    <span class="meta-stamp text-ink-700">Bagikan:</span>

    <a href="https://api.whatsapp.com/send?text={{ $encTitle }}%20{{ $encUrl }}"
       target="_blank" rel="noopener"
       class="share-btn border-2 border-ink-900 bg-paper-50 text-ink-900 hover:bg-flag-500 hover:text-paper-50 w-10 h-10 inline-flex items-center justify-center"
       aria-label="Bagikan ke WhatsApp"
       title="WhatsApp">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20.5 3.5A11 11 0 0 0 3.2 17.7L2 22l4.4-1.2A11 11 0 1 0 20.5 3.5Zm-8.5 17a9 9 0 0 1-4.6-1.3l-.3-.2-2.6.7.7-2.5-.2-.3a9 9 0 1 1 7 3.6Zm5-6.7c-.3-.1-1.7-.8-2-.9s-.4-.1-.6.1-.7.9-.8 1-.3.2-.6 0a7.3 7.3 0 0 1-3.6-3.1c-.3-.5.3-.4.8-1.3.1-.2 0-.3 0-.5s-.6-1.5-.9-2c-.2-.5-.4-.4-.6-.4h-.5c-.2 0-.5.1-.7.3a2.7 2.7 0 0 0-.8 2c0 1.2.8 2.3 1 2.5.1.2 1.8 2.8 4.4 3.9.6.3 1.1.5 1.5.6.6.2 1.2.2 1.7.1.5-.1 1.7-.7 1.9-1.3.2-.7.2-1.2.2-1.3 0-.1-.2-.2-.5-.3Z"/></svg>
    </a>

    <a href="https://twitter.com/intent/tweet?text={{ $encTitle }}&url={{ $encUrl }}"
       target="_blank" rel="noopener"
       class="share-btn border-2 border-ink-900 bg-paper-50 text-ink-900 hover:bg-flag-500 hover:text-paper-50 w-10 h-10 inline-flex items-center justify-center"
       aria-label="Bagikan ke X (Twitter)"
       title="X / Twitter">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.3 2H21l-6.5 7.5L22 22h-6l-4.7-6-5.4 6H3.4l7-7.8L2 2h6.1l4.3 5.6L18.3 2Zm-1 18h1.6L7 3.9H5.3L17.3 20Z"/></svg>
    </a>

    <a href="https://www.facebook.com/sharer/sharer.php?u={{ $encUrl }}"
       target="_blank" rel="noopener"
       class="share-btn border-2 border-ink-900 bg-paper-50 text-ink-900 hover:bg-flag-500 hover:text-paper-50 w-10 h-10 inline-flex items-center justify-center"
       aria-label="Bagikan ke Facebook"
       title="Facebook">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M13 22v-8h3l1-4h-4V7.5c0-1 .3-1.7 1.8-1.7H17V2.2A26 26 0 0 0 14.2 2C11.6 2 10 3.6 10 6.7V10H7v4h3v8h3Z"/></svg>
    </a>

    <a href="https://t.me/share/url?url={{ $encUrl }}&text={{ $encTitle }}"
       target="_blank" rel="noopener"
       class="share-btn border-2 border-ink-900 bg-paper-50 text-ink-900 hover:bg-flag-500 hover:text-paper-50 w-10 h-10 inline-flex items-center justify-center"
       aria-label="Bagikan ke Telegram"
       title="Telegram">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="m21.9 4.3-3.2 15.2c-.2 1.1-.9 1.3-1.8.8l-5-3.7-2.4 2.3c-.3.3-.5.5-1 .5l.3-4.9 8.8-8c.4-.3-.1-.5-.6-.2l-10.9 6.8L1.5 12c-1-.3-1-1 .2-1.5l19-7.3c.9-.3 1.7.2 1.4 1.2Z"/></svg>
    </a>

    <button type="button"
            class="share-btn share-copy border-2 border-ink-900 bg-paper-50 text-ink-900 hover:bg-flag-500 hover:text-paper-50 w-10 h-10 inline-flex items-center justify-center"
            data-url="{{ $url }}"
            aria-label="Salin tautan"
            title="Salin tautan">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M10 13a5 5 0 0 0 7 0l3-3a5 5 0 0 0-7-7l-1 1"/><path d="M14 11a5 5 0 0 0-7 0l-3 3a5 5 0 0 0 7 7l1-1"/></svg>
    </button>
</div>

@pushOnce('scripts')
<script>
document.addEventListener('click', function (e) {
    var btn = e.target.closest('.share-copy');
    if (!btn) return;
    e.preventDefault();
    var url = btn.dataset.url;
    var done = function () {
        var original = btn.innerHTML;
        btn.innerHTML = '<span class="font-mono text-[10px]">OK</span>';
        setTimeout(function () { btn.innerHTML = original; }, 1500);
    };
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(url).then(done).catch(function () {
            window.prompt('Salin tautan:', url);
        });
    } else {
        window.prompt('Salin tautan:', url);
    }
});
</script>
@endPushOnce
