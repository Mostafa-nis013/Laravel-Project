@extends('layouts.app')
@section('title','Edit Article')
@section('page-title','Edit Article')

@section('content')
<div class="form-page">
<form id="articleForm" method="POST" action="{{ route('articles.update', $article) }}" novalidate>
    @csrf @method('PUT')

    <div class="form-layout">
        <div class="form-main">
            <div class="card">
                <div class="form-group">
                    <label class="form-label" for="title">Title <span class="required">*</span></label>
                    <input id="title" type="text" name="title" class="form-input @error('title') is-invalid @enderror"
                           value="{{ old('title', $article->title) }}" />
                    @error('title')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="excerpt">Excerpt</label>
                    <textarea id="excerpt" name="excerpt" class="form-input" rows="2">{{ old('excerpt', $article->excerpt) }}</textarea>
                    <span class="char-counter" data-max="500" data-target="excerpt">0 / 500</span>
                </div>

                <div class="form-group">
                    <label class="form-label" for="content">Content <span class="required">*</span></label>
                    <div class="editor-toolbar">
                        <button type="button" class="editor-btn" data-action="bold"><b>B</b></button>
                        <button type="button" class="editor-btn" data-action="italic"><i>I</i></button>
                        <button type="button" class="editor-btn" data-action="h2">H2</button>
                        <button type="button" class="editor-btn" data-action="h3">H3</button>
                        <button type="button" class="editor-btn" data-action="ul">• List</button>
                        <button type="button" class="editor-btn" data-action="ol">1. List</button>
                        <button type="button" class="editor-btn" data-action="link">🔗</button>
                    </div>
                    <textarea id="content" name="content" class="form-input editor-area @error('content') is-invalid @enderror"
                              rows="16">{{ old('content', $article->content) }}</textarea>
                    @error('content')<span class="form-error">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <div class="form-sidebar">
            <div class="card">
                <h4 class="sidebar-section-title">Publish</h4>

                <div class="article-meta-info">
                    <div class="meta-item"><span>Views:</span> <strong>{{ number_format($article->views) }}</strong></div>
                    <div class="meta-item"><span>Reading time:</span> <strong>{{ $article->getReadingTime() }} min</strong></div>
                    @if($article->published_at)
                    <div class="meta-item"><span>Published:</span> <strong>{{ $article->published_at->format('M d, Y') }}</strong></div>
                    @endif
                </div>

                <div class="form-group">
                    <label class="form-label" for="status">Status</label>
                    <select id="status" name="status" class="form-input">
                        <option value="draft"     {{ old('status',$article->status)=='draft'?'selected':'' }}>📋 Draft</option>
                        @if(auth()->user()->hasPermission('publish_articles'))
                        <option value="published" {{ old('status',$article->status)=='published'?'selected':'' }}>✅ Published</option>
                        @endif
                        <option value="archived"  {{ old('status',$article->status)=='archived'?'selected':'' }}>📦 Archived</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="category_id">Category</label>
                    <select id="category_id" name="category_id" class="form-input">
                        <option value="">— None —</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id',$article->category_id)==$cat->id?'selected':'' }}>
                            {{ $cat->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-full">Update Article</button>
                    <a href="{{ route('articles.index') }}" class="btn btn-ghost btn-full">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</form>
</div>
@endsection
