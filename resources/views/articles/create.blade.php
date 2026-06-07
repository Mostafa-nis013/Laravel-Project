@extends('layouts.app')
@section('title','New Article')
@section('page-title','New Article')

@section('content')
<div class="form-page">
<form id="articleForm" method="POST" action="{{ route('articles.store') }}" novalidate>
    @csrf

    <div class="form-layout">
        {{-- Main column --}}
        <div class="form-main">
            <div class="card">
                <div class="form-group">
                    <label class="form-label" for="title">Title <span class="required">*</span></label>
                    <input id="title" type="text" name="title" class="form-input @error('title') is-invalid @enderror"
                           value="{{ old('title') }}" placeholder="Article title…" />
                    @error('title')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="excerpt">Excerpt</label>
                    <textarea id="excerpt" name="excerpt" class="form-input" rows="2"
                              placeholder="Short summary shown in listings…">{{ old('excerpt') }}</textarea>
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
                              rows="16" placeholder="Write your article content…">{{ old('content') }}</textarea>
                    @error('content')<span class="form-error">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        {{-- Side column --}}
        <div class="form-sidebar">
            <div class="card">
                <h4 class="sidebar-section-title">Publish</h4>

                <div class="form-group">
                    <label class="form-label" for="status">Status <span class="required">*</span></label>
                    <select id="status" name="status" class="form-input">
                        <option value="draft"     {{ old('status','draft')=='draft'?'selected':'' }}>📋 Draft</option>
                        @if(auth()->user()->hasPermission('publish_articles'))
                        <option value="published" {{ old('status')=='published'?'selected':'' }}>✅ Published</option>
                        @endif
                        <option value="archived"  {{ old('status')=='archived'?'selected':'' }}>📦 Archived</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="category_id">Category</label>
                    <select id="category_id" name="category_id" class="form-input">
                        <option value="">— None —</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id')==$cat->id?'selected':'' }}>
                            {{ $cat->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-full">Publish Article</button>
                    <a href="{{ route('articles.index') }}" class="btn btn-ghost btn-full">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Character counter
    const excerpt = document.getElementById('excerpt');
    const counter = document.querySelector('[data-target="excerpt"]');
    if (excerpt && counter) {
        const update = () => counter.textContent = excerpt.value.length + ' / 500';
        excerpt.addEventListener('input', update);
        update();
    }

    // Simple editor toolbar
    document.querySelectorAll('.editor-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const action = btn.dataset.action;
            const ta = document.getElementById('content');
            const start = ta.selectionStart, end = ta.selectionEnd;
            const sel = ta.value.substring(start, end);
            const map = {
                bold: `**${sel || 'bold text'}**`,
                italic: `_${sel || 'italic text'}_`,
                h2: `\n## ${sel || 'Heading'}`,
                h3: `\n### ${sel || 'Heading'}`,
                ul: `\n- ${sel || 'List item'}`,
                ol: `\n1. ${sel || 'List item'}`,
                link: `[${sel || 'Link text'}](https://)`,
            };
            const insert = map[action] || sel;
            ta.value = ta.value.substring(0, start) + insert + ta.value.substring(end);
            ta.focus();
        });
    });
});
</script>
@endpush
