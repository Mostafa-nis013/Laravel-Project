@extends('layouts.admin')

@section('title', 'Edit Category')
@section('topbar-title', 'Categories / Edit')
@section('breadcrumb')
    <span style="color:var(--muted)">Catalog</span>
    <span class="crumb-sep">›</span>
    <span class="crumb-current">Categories / Edit</span>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Edit Category</h2>
        <p>Updating: <strong>{{ $category->name }}</strong></p>
    </div>
    <a href="{{ route('categories.index') }}" class="btn btn-secondary">← Back</a>
</div>

<form method="POST" action="{{ route('categories.update', $category) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="grid-2" style="align-items:start">
        <div style="display:flex;flex-direction:column;gap:20px">
            <div class="card">
                <div class="card-header"><span class="card-title">Category Details</span></div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Name <span class="required">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $category->name) }}" required>
                        @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" rows="3">{{ old('description', $category->description) }}</textarea>
                        @error('description')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Parent Category</label>
                        <select name="parent_id">
                            <option value="">— None (Top-level) —</option>
                            @foreach($parents as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                            @endforeach
                        </select>
                        @error('parent_id')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Sort Order</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}" min="0">
                    </div>
                </div>
            </div>
        </div>

        <div style="display:flex;flex-direction:column;gap:20px">
            <div class="card">
                <div class="card-header"><span class="card-title">Image</span></div>
                <div class="card-body">
                    <div id="image-preview" style="width:100%;height:160px;background:var(--surface2);border:2px dashed var(--border);border-radius:var(--radius);display:flex;align-items:center;justify-content:center;color:var(--muted);margin-bottom:12px;overflow:hidden;cursor:pointer" onclick="document.getElementById('image-input').click()">
                        @if($category->image)
                            <img id="preview-img" src="{{ asset('storage/'.$category->image) }}" style="width:100%;height:100%;object-fit:cover">
                        @else
                            <span id="preview-placeholder">🖼 Click to upload</span>
                            <img id="preview-img" style="display:none;width:100%;height:100%;object-fit:cover">
                        @endif
                    </div>
                    <input type="file" id="image-input" name="image" accept="image/*" style="display:none">
                    @error('image')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="card">
                <div class="card-header"><span class="card-title">Visibility</span></div>
                <div class="card-body">
                    <div class="form-check">
                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                        <label for="is_active">Active (show in store)</label>
                    </div>
                </div>
            </div>

            <div class="card" style="border-color:rgba(224,82,82,0.3)">
                <div class="card-header"><span class="card-title" style="color:var(--danger)">Danger Zone</span></div>
                <div class="card-body">
                    <p style="font-size:0.875rem;color:var(--muted);margin-bottom:14px">Cannot delete a category that has products assigned to it.</p>
                    <form method="POST" action="{{ route('categories.destroy', $category) }}">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger" data-confirm="Delete '{{ $category->name }}'?">Delete Category</button>
                    </form>
                </div>
            </div>

            <div style="display:flex;gap:12px;justify-content:flex-end">
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
document.getElementById('image-input').addEventListener('change', function() {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        const ph = document.getElementById('preview-placeholder');
        if (ph) ph.style.display = 'none';
        const img = document.getElementById('preview-img');
        img.src = e.target.result;
        img.style.display = 'block';
    };
    reader.readAsDataURL(file);
});
document.querySelectorAll('[data-confirm]').forEach(el => {
    el.addEventListener('click', e => { if (!confirm(el.dataset.confirm)) e.preventDefault(); });
});
</script>
@endpush
