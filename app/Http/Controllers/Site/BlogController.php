<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        return $this->listing($request);
    }

    public function category(Request $request, Category $category)
    {
        return $this->listing($request, $category);
    }

    public function show(Post $post)
    {
        abort_unless(Post::published()->whereKey($post->id)->exists(), 404);

        $post->load(['author', 'category', 'tags']);

        return view('site.blog.show', [
            'post' => $post,
            'related' => Post::published()
                ->whereKeyNot($post->id)
                ->when($post->category_id, fn ($q) => $q->where('category_id', $post->category_id))
                ->take(3)->get(),
        ]);
    }

    private function listing(Request $request, ?Category $category = null)
    {
        $search = trim((string) $request->query('busca'));

        $posts = Post::published()
            ->with(['category', 'author'])
            ->when($category, fn ($q) => $q->where('category_id', $category->id))
            ->when($search, fn ($q) => $q->where(fn ($q) => $q
                ->where('title', 'like', "%{$search}%")
                ->orWhere('excerpt', 'like', "%{$search}%")
                ->orWhere('body', 'like', "%{$search}%")))
            ->paginate(9)
            ->withQueryString();

        return view('site.blog.index', [
            'posts' => $posts,
            'category' => $category,
            'search' => $search,
            'categories' => Category::whereHas('posts', fn ($q) => $q->published())->orderBy('name')->get(),
        ]);
    }
}
