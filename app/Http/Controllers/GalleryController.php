<?php

namespace App\Http\Controllers;

use App\Models\GalleryAlbum;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $albums = GalleryAlbum::published()
            ->withCount('items')
            ->orderByDesc('event_date')
            ->orderByDesc('published_at')
            ->paginate(12)
            ->withQueryString();
        if ($albums->isEmpty() && $albums->currentPage() > 1) {
            return redirect()->to($albums->url($albums->lastPage()));
        }

        return view('gallery.index', compact('albums'));
    }

    public function show(string $slug): View
    {
        $album = GalleryAlbum::published()
            ->where('slug', $slug)
            ->firstOrFail();

        $album->load(['items' => fn ($q) => $q->orderBy('sort_order'), 'items.media']);

        return view('gallery.show', compact('album'));
    }
}
