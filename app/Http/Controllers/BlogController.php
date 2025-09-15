<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Contracts\View\View;

class BlogController extends Controller
{
    /**
     * Display a listing of published blog posts.
     */
    public function index(): View
    {
        $posts = BlogPost::published()
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->paginate(9);

        return view('blog.index', compact('posts'));
    }

    /**
     * Display a single blog post.
     */
    public function show(string $slug): View
    {
        $post = BlogPost::published()
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedPosts = BlogPost::published()
            ->where('id', '!=', $post->id)
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->limit(3)
            ->get();

        return view('blog.show', compact('post', 'relatedPosts'));
    }
}
