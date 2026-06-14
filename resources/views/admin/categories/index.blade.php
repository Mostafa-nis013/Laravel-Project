@extends('layouts.admin')

@section('title', 'Categories')
@section('topbar-title', 'Categories')
@section('breadcrumb')
    <span style="color:var(--muted)">Catalog</span>
    <span class="crumb-sep">›</span>
    <span class="crumb-current">Categories</span>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Categories</h2>
        <p>{{ $categories->total() }} categories in your store</p>
    </div>
    <a href="{{ route('categories.create') }}" class="btn btn-primary">+ Add Category</a>
</div>

<form method="GET" action="{{ route('categories.index') }}">
    <div class="filters-bar">
        <input type="text" name="search" placeholder="Search categories…" value="{{ request('search') }}">
        <button type="submit" class="btn btn-secondary">Search</button>
        @if(request('search'))
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Clear</a>
        @endif
    </div>
</form>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Parent</th>
                    <th>Products</th>
                    <th>Sort</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:12px">
                            <div class="product-thumb">
                                @if($category->image)
                                    <img src="{{ asset('storage/'.$category->image) }}" alt="{{ $category->name }}">
                                @else
                                    ◉
                                @endif
                            </div>
                            <div>
                                <div style="font-weight:500">{{ $category->name }}</div>
                                <div style="font-size:0.78rem;color:var(--muted)">{{ Str::limit($category->description, 40) }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="color:var(--muted)">{{ $category->parent->name ?? '—' }}</td>
                    <td>
                        <span class="badge badge-blue">{{ $category->products->count() }}</span>
                    </td>
                    <td style="color:var(--muted)">{{ $category->sort_order }}</td>
                    <td>
                        <span class="badge {{ $category->is_active ? 'badge-green' : 'badge-gray' }}">
                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="{{ route('categories.show', $category) }}" class="btn btn-secondary btn-sm">View</a>
                            <a href="{{ route('categories.edit', $category) }}" class="btn btn-secondary btn-sm">Edit</a>
                            <form method="POST" action="{{ route('categories.destroy', $category) }}">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" data-confirm="Delete '{{ $category->name }}'? This will fail if it has products.">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:40px;color:var(--muted)">
                        No categories yet. <a href="{{ route('categories.create') }}" style="color:var(--accent)">Create one?</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($categories->hasPages())
    <div class="pagination">
        @if($categories->onFirstPage())
            <span class="page-link" style="opacity:0.3">←</span>
        @else
            <a href="{{ $categories->previousPageUrl() }}" class="page-link">←</a>
        @endif
        @foreach($categories->getUrlRange(max(1,$categories->currentPage()-2), min($categories->lastPage(),$categories->currentPage()+2)) as $page => $url)
            <a href="{{ $url }}" class="page-link {{ $page == $categories->currentPage() ? 'active' : '' }}">{{ $page }}</a>
        @endforeach
        @if($categories->hasMorePages())
            <a href="{{ $categories->nextPageUrl() }}" class="page-link">→</a>
        @else
            <span class="page-link" style="opacity:0.3">→</span>
        @endif
        <span style="margin-left:auto;color:var(--muted);font-size:0.85rem">
            Showing {{ $categories->firstItem() }}–{{ $categories->lastItem() }} of {{ $categories->total() }}
        </span>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('[data-confirm]').forEach(el => {
    el.addEventListener('click', e => { if (!confirm(el.dataset.confirm)) e.preventDefault(); });
});
</script>
@endpush
