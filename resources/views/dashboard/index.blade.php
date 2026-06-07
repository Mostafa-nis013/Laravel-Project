@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="dashboard-grid">

    {{-- Stat cards --}}
    <div class="stats-row">
        <div class="stat-card stat-indigo">
            <div class="stat-icon">📝</div>
            <div class="stat-info">
                <div class="stat-value">{{ $stats['total_articles'] ?? 0 }}</div>
                <div class="stat-label">Total Articles</div>
            </div>
        </div>
        <div class="stat-card stat-green">
            <div class="stat-icon">✅</div>
            <div class="stat-info">
                <div class="stat-value">{{ $stats['published_articles'] ?? 0 }}</div>
                <div class="stat-label">Published</div>
            </div>
        </div>
        <div class="stat-card stat-yellow">
            <div class="stat-icon">📋</div>
            <div class="stat-info">
                <div class="stat-value">{{ $stats['draft_articles'] ?? 0 }}</div>
                <div class="stat-label">Drafts</div>
            </div>
        </div>
        <div class="stat-card stat-pink">
            <div class="stat-icon">🏷️</div>
            <div class="stat-info">
                <div class="stat-value">{{ $stats['total_categories'] ?? 0 }}</div>
                <div class="stat-label">Categories</div>
            </div>
        </div>
        @if(isset($stats['total_users']))
        <div class="stat-card stat-blue">
            <div class="stat-icon">👥</div>
            <div class="stat-info">
                <div class="stat-value">{{ $stats['total_users'] }}</div>
                <div class="stat-label">Total Users</div>
            </div>
        </div>
        <div class="stat-card stat-teal">
            <div class="stat-icon">🟢</div>
            <div class="stat-info">
                <div class="stat-value">{{ $stats['active_users'] }}</div>
                <div class="stat-label">Active Users</div>
            </div>
        </div>
        @endif
    </div>

    {{-- Recent articles --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Recent Articles</h3>
            <a href="{{ route('articles.index') }}" class="btn btn-sm btn-secondary">View All</a>
        </div>
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr><th>Title</th><th>Author</th><th>Category</th><th>Status</th><th>Date</th></tr>
                </thead>
                <tbody>
                    @forelse($recentArticles as $article)
                    <tr>
                        <td><a href="{{ route('articles.edit', $article) }}" class="table-link">{{ $article->title }}</a></td>
                        <td>{{ $article->author->name }}</td>
                        <td>
                            @if($article->category)
                                <span class="category-dot" style="background:{{ $article->category->color }}"></span>
                                {{ $article->category->name }}
                            @else — @endif
                        </td>
                        <td><span class="badge {{ $article->getStatusBadgeColor() }}">{{ ucfirst($article->status) }}</span></td>
                        <td>{{ $article->created_at->format('M d, Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="empty-row">No articles yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Recent users (admin+) --}}
    @if($recentUsers->isNotEmpty())
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Recent Users</h3>
            <a href="{{ route('users.index') }}" class="btn btn-sm btn-secondary">View All</a>
        </div>
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Joined</th></tr>
                </thead>
                <tbody>
                    @foreach($recentUsers as $u)
                    <tr>
                        <td>
                            <div class="user-cell">
                                <div class="mini-avatar">{{ strtoupper(substr($u->name,0,1)) }}</div>
                                {{ $u->name }}
                            </div>
                        </td>
                        <td>{{ $u->email }}</td>
                        <td><span class="badge {{ $u->getRoleBadgeColor() }}">{{ $u->getRoleLabel() }}</span></td>
                        <td>
                            <span class="status-dot {{ $u->is_active ? 'active' : 'inactive' }}">
                                {{ $u->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>{{ $u->created_at->format('M d, Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Permissions info card --}}
    <div class="card permissions-card">
        <div class="card-header"><h3 class="card-title">Your Permissions</h3></div>
        <div class="permissions-list">
            @foreach(auth()->user()->getPermissions() as $perm)
            <span class="permission-tag">{{ str_replace('_', ' ', $perm) }}</span>
            @endforeach
        </div>
    </div>

</div>
@endsection
