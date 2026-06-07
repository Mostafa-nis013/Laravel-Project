@extends('layouts.app')
@section('title','Articles')
@section('page-title','Articles')

@section('topbar-actions')
    @if(auth()->user()->hasPermission('create_articles'))
        <a href="{{ route('articles.create') }}" class="btn btn-primary">+ New Article</a>
    @endif
@endsection

@section('content')
{{-- Filters --}}
<div class="card filter-card">
    <form method="GET" action="{{ route('articles.index') }}" class="filter-form" id="filterForm">
        <input type="text" name="search" class="form-input" placeholder="Search articles…" value="{{ request('search') }}" />
        <select name="status" class="form-input">
            <option value="">All statuses</option>
            <option value="published" {{ request('status')=='published'?'selected':'' }}>Published</option>
            <option value="draft"     {{ request('status')=='draft'?'selected':'' }}>Draft</option>
            <option value="archived"  {{ request('status')=='archived'?'selected':'' }}>Archived</option>
        </select>
        <select name="category" class="form-input">
            <option value="">All categories</option>
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category')==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-secondary">Filter</button>
        @if(request()->hasAny(['search','status','category']))
            <a href="{{ route('articles.index') }}" class="btn btn-ghost">Clear</a>
        @endif
    </form>
</div>

{{-- Table --}}
<div class="card">
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Views</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($articles as $article)
                <tr>
                    <td>
                        <div class="article-title-cell">
                            <a href="{{ route('articles.show', $article) }}" class="table-link">{{ $article->title }}</a>
                            <span class="reading-time">{{ $article->getReadingTime() }} min read</span>
                        </div>
                    </td>
                    <td>{{ $article->author->name }}</td>
                    <td>
                        @if($article->category)
                            <span class="category-pill" style="background:{{ $article->category->color }}20; color:{{ $article->category->color }}; border:1px solid {{ $article->category->color }}40">
                                {{ $article->category->name }}
                            </span>
                        @else <span class="muted">—</span> @endif
                    </td>
                    <td><span class="badge {{ $article->getStatusBadgeColor() }}">{{ ucfirst($article->status) }}</span></td>
                    <td>{{ number_format($article->views) }}</td>
                    <td>{{ $article->created_at->format('M d, Y') }}</td>
                    <td>
                        <div class="action-btns">
                            <a href="{{ route('articles.show', $article) }}" class="btn btn-xs btn-ghost" title="View">👁</a>
                            @can_edit_article($article)
                                <a href="{{ route('articles.edit', $article) }}" class="btn btn-xs btn-secondary" title="Edit">✏️</a>
                                <button class="btn btn-xs btn-danger delete-btn"
                                    data-url="{{ route('articles.destroy', $article) }}"
                                    data-name="{{ $article->title }}" title="Delete">🗑</button>
                            @end_can_edit
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="empty-row">No articles found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrapper">
        {{ $articles->links() }}
    </div>
</div>
@endsection
