<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user  = Auth::user();
        $stats = [];

        if ($user->hasPermission('view_dashboard')) {
            $articleQuery = Article::query();

            // Editors only see their own articles in stats
            if ($user->isEditor()) {
                $articleQuery->byAuthor($user->id);
            }

            $stats = [
                'total_articles'     => $articleQuery->count(),
                'published_articles' => (clone $articleQuery)->where('status', 'published')->count(),
                'draft_articles'     => (clone $articleQuery)->where('status', 'draft')->count(),
                'total_categories'   => Category::count(),
                'total_users'        => $user->hasRole(['super_admin', 'admin']) ? User::count() : null,
                'active_users'       => $user->hasRole(['super_admin', 'admin']) ? User::where('is_active', true)->count() : null,
            ];

            $recentArticles = (clone $articleQuery)
                ->with(['author', 'category'])
                ->latest()
                ->limit(5)
                ->get();

            $recentUsers = $user->hasRole(['super_admin', 'admin'])
                ? User::latest()->limit(5)->get()
                : collect();
        }

        return view('dashboard.index', compact('stats', 'recentArticles', 'recentUsers'));
    }
}
