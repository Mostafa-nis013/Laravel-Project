@extends('layouts.app')
@section('title', $article->title)
@section('page-title', 'View Article')

@section('topbar-actions')
    @if(auth()->user()->hasPermission('edit_any_article') || (auth()->user()->hasPermission('edit_own_article') && $article->author_id === auth()->id()))
        <a href="{{ route('articles.edit', $article) }}" class="btn btn-secondary">✏️ Edit</a>
    @endif
    <a href="{{ route('articles.index') }}" class="btn btn-ghost">← Back</a>
@endsection

@section('content')
<div class="article-view">
    <div class="article-header-card card">
        <div class="article-meta-row">
            @if($article->category)
                <span class="category-pill" style="background:{{ $article->category->color }}20; color:{{ $article->category->color }}">
                    {{ $article->category->name }}
                </span>
            @endif
            <span class="badge {{ $article->getStatusBadgeColor() }}">{{ ucfirst($article->status) }}</span>
            <span class="muted">{{ $article->getReadingTime() }} min read</span>
            <span class="muted">👁 {{ number_format($article->views) }} views</span>
        </div>
        <h1 class="article-view-title">{{ $article->title }}</h1>
        @if($article->excerpt)
            <p class="article-view-excerpt">{{ $article->excerpt }}</p>
        @endif
        <div class="article-author-row">
            <div class="mini-avatar">{{ strtoupper(substr($article->author->name, 0, 1)) }}</div>
            <div>
                <div class="author-name">{{ $article->author->name }}</div>
                <div class="muted">{{ $article->created_at->format('F d, Y') }}</div>
            </div>
        </div>
    </div>

    <div class="card article-content-card">
        <div class="article-body">{!! nl2br(e($article->content)) !!}</div>
    </div>
</div>
@endsection
