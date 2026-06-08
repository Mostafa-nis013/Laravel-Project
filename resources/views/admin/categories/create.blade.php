@extends('layouts.admin')

@section('title', 'Add Category')
@section('topbar-title', 'Categories / Add New')

@section('content')
<div class="page-header">
    <div>
        <h2>Add Category</h2>
        <p>Create a new product category.</p>
    </div>
    <a href="{{ route('categories.index') }}" class="btn btn-secondary">← Back</a>
</div>

<form method="POST" action="{{ route('categories.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="grid-2" style="align-items:start">
        <div style="display:flex;flex-direction:column;gap:20px">
            <div class="card">
                <div class="card-header"><span class="card-title">Category Details</span></div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Name <span class="required">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Electronics">
                        @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" rows="3" placeholder="Brief description of this category…">{{ old('description') }}</textarea>
                        @error('description')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Parent Category</label>
                        <select name="parent_id">
                            <option value="">— None (Top-level) —</option>
                            @foreach($parents as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                            @endforeach
                        </select>
                        @error('parent_id')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Sort Order</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
                        @error('sort_order')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div style="display:flex;flex-direction:column;gap:20px">
            <div class="card">
                <div class="card-header"><span class="card-title">Image</span></div>
                <div class="card-body">
                    <div id="image-preview" style="width:100%;height:160px;background:var(--surface2);border:2px dashed var(--border);border-radius:var(--radius);display:flex;align-items:center;justify-content:center;color:var(--muted);margin-bottom:12px;overflow:hidden;cursor:pointer" onclick="document.getElementById('image-input').click()">
                        <span id="preview-placeholder">🖼 Click to upload</span>
                        <img id="preview-img" style="display:none;width:100%;height:100%;object-fit:cover">
                    </div>
                    <input type="file" id="image-input" name="image" accept="image/*" style="display:none">
                    @error('image')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="card">
                <div class="card-header"><span class="card-title">Visibility</span></div>
                <div class="card-body">
                    <div class="form-check">
                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label for="is_active">Active (show in store)</label>
                    </div>
                </div>
            </div>

            <div style="display:flex;gap:12px;justify-content:flex-end">
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Create Category</button>
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
        document.getElementById('preview-placeholder').style.display = 'none';
        const img = document.getElementById('preview-img');
        img.src = e.target.result;
        img.style.display = 'block';
    };
    reader.readAsDataURL(file);
});
</script>
@endpush
