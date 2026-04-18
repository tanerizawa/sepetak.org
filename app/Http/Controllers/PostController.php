<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::published()->latest('published_at');

        if ($q = trim((string) $request->input('q', ''))) {
            $like = '%'.str_replace(['%', '_'], ['\\%', '\\_'], $q).'%';
            $driver = DB::connection()->getDriverName();

            $query->where(function ($sub) use ($like, $driver) {
                if ($driver === 'pgsql') {
                    $sub->whereRaw('LOWER(title) LIKE ?', [mb_strtolower($like)])
                        ->orWhereRaw('LOWER(excerpt) LIKE ?', [mb_strtolower($like)])
                        ->orWhereRaw('LOWER(body) LIKE ?', [mb_strtolower($like)]);
                } else {
                    $sub->whereRaw('LOWER(title) LIKE ?', [mb_strtolower($like)])
                        ->orWhereRaw('LOWER(excerpt) LIKE ?', [mb_strtolower($like)])
                        ->orWhereRaw('LOWER(body) LIKE ?', [mb_strtolower($like)]);
                }
            });
        }

        if ($cat = $request->input('category')) {
            $query->whereHas('categories', fn ($s) => $s->where('slug', $cat));
        }

        if ($tag = $request->input('tag')) {
            $query->whereHas('tags', fn ($s) => $s->where('slug', $tag));
        }

        $posts = $query->paginate(9)->withQueryString();
        if ($posts->isEmpty() && $posts->currentPage() > 1) {
            return redirect()->to($posts->url($posts->lastPage()));
        }

        return view('posts.index', [
            'posts' => $posts,
            'q' => $request->input('q'),
            'activeCategory' => $request->input('category'),
            'activeTag' => $request->input('tag'),
            'categories' => Category::orderBy('name')->get(),
            'tags' => Tag::orderBy('name')->get(),
        ]);
    }

    public function show(string $slug)
    {
        $post = Post::published()->with(['categories', 'tags', 'author'])->where('slug', $slug)->firstOrFail();

        // H-4: related articles via category/tag overlap.
        $related = Post::published()
            ->where('id', '!=', $post->id)
            ->where(function ($q) use ($post) {
                $q->whereHas('categories', fn ($s) => $s->whereIn('categories.id', $post->categories->pluck('id')))
                    ->orWhereHas('tags', fn ($s) => $s->whereIn('tags.id', $post->tags->pluck('id')));
            })
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('posts.show', compact('post', 'related'));
    }

    public function category(string $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $posts = $category->posts()
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();
        if ($posts->isEmpty() && $posts->currentPage() > 1) {
            return redirect()->to($posts->url($posts->lastPage()));
        }

        return view('posts.archive', [
            'posts' => $posts,
            'archiveTitle' => 'Kategori: '.$category->name,
            'archiveMeta' => $category->description ?? null,
        ]);
    }

    public function tag(string $slug)
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();
        $posts = $tag->posts()
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();
        if ($posts->isEmpty() && $posts->currentPage() > 1) {
            return redirect()->to($posts->url($posts->lastPage()));
        }

        return view('posts.archive', [
            'posts' => $posts,
            'archiveTitle' => 'Tag: #'.$tag->name,
            'archiveMeta' => null,
        ]);
    }

    public function author(int $id)
    {
        $user = User::findOrFail($id);
        $posts = Post::published()
            ->where('author_id', $user->id)
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();
        if ($posts->isEmpty() && $posts->currentPage() > 1) {
            return redirect()->to($posts->url($posts->lastPage()));
        }

        return view('posts.archive', [
            'posts' => $posts,
            'archiveTitle' => 'Penulis: '.$user->name,
            'archiveMeta' => null,
        ]);
    }
}
