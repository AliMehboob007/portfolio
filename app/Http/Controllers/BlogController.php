<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;

class BlogController extends Controller
{
    public function index()
    {
        $posts = BlogPost::where('published', true)
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        $categories = BlogPost::where('published', true)
            ->distinct()->pluck('category')->filter()->values();

        return view('blog.index', compact('posts', 'categories'));
    }

    public function show($slug)
    {
        $post = BlogPost::where('slug', $slug)
            ->where('published', true)
            ->firstOrFail();

        $related = BlogPost::where('published', true)
            ->where('category', $post->category)
            ->where('id', '!=', $post->id)
            ->latest()
            ->take(3)
            ->get();

        return view('blog.show', compact('post', 'related'));
    }
}
