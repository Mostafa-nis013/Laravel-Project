@extends('layouts.app')
@section('title', 'Categories')
@section('page-title', 'Categories')

@section('content')
<div class="categories-layout">

    {{-- Create form --}}
    <div class="card">
        <h3 class="card-title">New Category</h3>
        <form id="categoryForm" method="POST" action="{{ route('categories.store') }}" novalidate>
            @csrf
            <div class="category-form-row">
                <div class="form-group flex-1">
                    <label class="form-label" for="name">Name <span class="required">*</span></label>
                    <input id="name" type="text" name="name" class="form-input @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" placeholder="Category name…" />
                    @error('name')<span class="form-error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group flex-1">
                    <label class="form-label" for="description">Description</label>
                    <input id="description" type="text" name="description" class="form-input"
                           value="{{ old('description') }}" placeholder="Optional description…" />
                </div>
                <div class="form-group color-group">
                    <label class="form-label" for="color">Color</label>
                    <input id="color" type="color" name="color" class="form-color"
                           value="{{ old('color', '#6366f1') }}" />
                </div>
                <div class="form-group btn-group-end">
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </div>
        </form>
    </div>

    {{-- Categories grid --}}
    <div class="categories-grid">
        @forelse($categories as $cat)
        <div class="category-card card" style="border-top: 3px solid {{ $cat->color }}">
            <div class="category-card-header">
                <div class="category-color-dot" style="background: {{ $cat->color }}"></div>
                <h4>{{ $cat->name }}</h4>
                <span class="muted">{{ $cat->articles_count }} articles</span>
            </div>
            @if($cat->description)
                <p class="category-desc">{{ $cat->description }}</p>
            @endif
            <div class="category-card-footer">
                <span class="muted">by {{ $cat->creator->name }}</span>
                <div class="action-btns">
                    <button class="btn btn-xs btn-secondary" onclick="openEditModal({{ $cat->id }}, '{{ addslashes($cat->name) }}', '{{ addslashes($cat->description) }}', '{{ $cat->color }}')">
                        ✏️ Edit
                    </button>
                    <button class="btn btn-xs btn-danger delete-btn"
                        data-url="{{ route('categories.destroy', $cat) }}"
                        data-name="{{ $cat->name }}">🗑</button>
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state">No categories yet. Create your first one above.</div>
        @endforelse
    </div>

    <div class="pagination-wrapper">{{ $categories->links() }}</div>
</div>

{{-- Edit Modal --}}
<div class="modal-backdrop" id="editCategoryModal" style="display:none">
    <div class="modal">
        <div class="modal-header">
            <h3>Edit Category</h3>
            <button class="modal-close" onclick="closeEditModal()">✕</button>
        </div>
        <form id="editCategoryForm" method="POST" novalidate>
            @csrf @method('PUT')
            <div class="form-group">
                <label class="form-label">Name</label>
                <input type="text" name="name" id="editName" class="form-input" required />
            </div>
            <div class="form-group">
                <label class="form-label">Description</label>
                <input type="text" name="description" id="editDescription" class="form-input" />
            </div>
            <div class="form-group">
                <label class="form-label">Color</label>
                <input type="color" name="color" id="editColor" class="form-color" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
const baseUrl = '{{ url("/categories") }}';

function openEditModal(id, name, description, color) {
    document.getElementById('editName').value = name;
    document.getElementById('editDescription').value = description;
    document.getElementById('editColor').value = color;
    document.getElementById('editCategoryForm').action = `${baseUrl}/${id}`;
    document.getElementById('editCategoryModal').style.display = 'flex';
}

function closeEditModal() {
    document.getElementById('editCategoryModal').style.display = 'none';
}

document.getElementById('editCategoryModal').addEventListener('click', function(e) {
    if (e.target === this) closeEditModal();
});
</script>
@endpush
