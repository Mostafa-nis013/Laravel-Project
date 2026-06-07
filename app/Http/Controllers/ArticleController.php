<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    // ── Index ──────────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $user  = Auth::user();
        $query = Article::with(['author', 'category'])->latest();

        // Editors only see their own articles
        if ($user->isEditor()) {
            $query->byAuthor($user->id);
        }

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('excerpt', 'like', "%{$request->search}%");
            });
        }

        $articles   = $query->paginate(10)->withQueryString();
        $categories = Category::all();

        return view('articles.index', compact('articles', 'categories'));
    }

    // ── Create ─────────────────────────────────────────────────────────────────
    public function create()
    {
        $this->authorize_permission('create_articles');
        $categories = Category::all();
        return view('articles.create', compact('categories'));
    }

    // ── Store ──────────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $this->authorize_permission('create_articles');

        $data = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'excerpt'     => ['nullable', 'string', 'max:500'],
            'content'     => ['required', 'string'],
            'status'      => ['required', 'in:draft,published,archived'],
            'category_id' => ['nullable', 'exists:categories,id'],
        ]);

        // Only admins/super_admins can publish directly
        if ($data['status'] === 'published' && ! Auth::user()->hasPermission('publish_articles')) {
            $data['status'] = 'draft';
        }

        $article = Article::create([
            ...$data,
            'author_id'    => Auth::id(),
            'slug'         => Article::generateSlug($data['title']),
            'published_at' => $data['status'] === 'published' ? now() : null,
        ]);

        return redirect()->route('articles.index')
            ->with('success', "Article \"{$article->title}\" created successfully.");
    }

    // ── Show ───────────────────────────────────────────────────────────────────
    public function show(Article $article)
    {
        $this->authorize_article_access($article);
        $article->increment('views');
        return view('articles.show', compact('article'));
    }

    // ── Edit ───────────────────────────────────────────────────────────────────
    public function edit(Article $article)
    {
        $this->authorize_article_edit($article);
        $categories = Category::all();
        return view('articles.edit', compact('article', 'categories'));
    }

    // ── Update ─────────────────────────────────────────────────────────────────
    public function update(Request $request, Article $article)
    {
        $this->authorize_article_edit($article);

        $data = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'excerpt'     => ['nullable', 'string', 'max:500'],
            'content'     => ['required', 'string'],
            'status'      => ['required', 'in:draft,published,archived'],
            'category_id' => ['nullable', 'exists:categories,id'],
        ]);

        if ($data['status'] === 'published' && ! Auth::user()->hasPermission('publish_articles')) {
            $data['status'] = 'draft';
        }

        if ($data['status'] === 'published' && $article->status !== 'published') {
            $data['published_at'] = now();
        }

        $article->update($data);

        return redirect()->route('articles.index')
            ->with('success', "Article \"{$article->title}\" updated successfully.");
    }

    // ── Destroy ────────────────────────────────────────────────────────────────
    public function destroy(Article $article)
    {
        $this->authorize_article_delete($article);
        $title = $article->title;
        $article->delete();
        return redirect()->route('articles.index')
            ->with('success', "Article \"{$title}\" deleted.");
    }

    // ── Private helpers ────────────────────────────────────────────────────────
    private function authorize_permission(string $permission): void
    {
        if (! Auth::user()->hasPermission($permission)) {
            abort(403);
        }
    }

    private function authorize_article_access(Article $article): void
    {
        $user = Auth::user();
        if ($user->isUser() && $article->status !== 'published') {
            abort(403);
        }
    }

    private function authorize_article_edit(Article $article): void
    {
        $user = Auth::user();
        if ($user->hasPermission('edit_any_article')) return;
        if ($user->hasPermission('edit_own_article') && $article->author_id === $user->id) return;
        abort(403);
    }

    private function authorize_article_delete(Article $article): void
    {
        $user = Auth::user();
        if ($user->hasPermission('delete_any_article')) return;
        if ($user->hasPermission('delete_own_article') && $article->author_id === $user->id) return;
        abort(403);
    }
}
