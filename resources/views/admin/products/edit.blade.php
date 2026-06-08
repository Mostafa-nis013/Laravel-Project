@extends('layouts.admin')

@section('title', 'Edit Product')
@section('topbar-title', 'Products / Edit')

@section('content')
<div class="page-header">
    <div>
        <h2>Edit Product</h2>
        <p>Updating: <strong>{{ $product->name }}</strong></p>
    </div>
    <div style="display:flex;gap:10px">
        <a href="{{ route('products.show', $product) }}" class="btn btn-secondary">View</a>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">← Back</a>
    </div>
</div>

<form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="grid-2" style="align-items:start">
        {{-- Left --}}
        <div style="display:flex;flex-direction:column;gap:20px">
            <div class="card">
                <div class="card-header"><span class="card-title">Basic Info</span></div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Product Name <span class="required">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" required>
                        @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>SKU <span class="required">*</span></label>
                        <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" required>
                        @error('sku')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" rows="4">{{ old('description', $product->description) }}</textarea>
                        @error('description')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Category <span class="required">*</span></label>
                        <select name="category_id" required>
                            <option value="">Select category…</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><span class="card-title">Pricing & Inventory</span></div>
                <div class="card-body">
                    <div class="grid-2">
                        <div class="form-group">
                            <label>Price <span class="required">*</span></label>
                            <input type="number" name="price" value="{{ old('price', $product->price) }}" step="0.01" min="0" required>
                            @error('price')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label>Sale Price</label>
                            <input type="number" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" step="0.01" min="0" placeholder="Leave empty to remove">
                            @error('sale_price')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Stock Quantity <span class="required">*</span></label>
                        <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0" required>
                        @error('stock')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Right --}}
        <div style="display:flex;flex-direction:column;gap:20px">
            <div class="card">
                <div class="card-header"><span class="card-title">Product Image</span></div>
                <div class="card-body">
                    <div class="form-group">
                        <div id="image-preview" style="width:100%;height:180px;background:var(--surface2);border:2px dashed var(--border);border-radius:var(--radius);display:flex;align-items:center;justify-content:center;color:var(--muted);margin-bottom:12px;overflow:hidden;cursor:pointer" onclick="document.getElementById('image-input').click()">
                            @if($product->image)
                                <img id="preview-img" src="{{ asset('storage/'.$product->image) }}" style="width:100%;height:100%;object-fit:cover">
                            @else
                                <span id="preview-placeholder">📷 Click to upload</span>
                                <img id="preview-img" style="display:none;width:100%;height:100%;object-fit:cover">
                            @endif
                        </div>
                        <input type="file" id="image-input" name="image" accept="image/*" style="display:none">
                        @if($product->image)
                            <p style="font-size:0.8rem;color:var(--muted)">Upload a new image to replace the current one.</p>
                        @endif
                        @error('image')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><span class="card-title">Visibility</span></div>
                <div class="card-body" style="display:flex;flex-direction:column;gap:16px">
                    <div class="form-check">
                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                        <label for="is_active">Active (visible to customers)</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                        <label for="is_featured">Featured product</label>
                    </div>
                </div>
            </div>

            <div class="card" style="border-color:rgba(224,82,82,0.3)">
                <div class="card-header"><span class="card-title" style="color:var(--danger)">Danger Zone</span></div>
                <div class="card-body">
                    <p style="font-size:0.875rem;color:var(--muted);margin-bottom:14px">Deleting this product is reversible — it will be soft-deleted and can be restored.</p>
                    <form method="POST" action="{{ route('products.destroy', $product) }}">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger" data-confirm="Delete '{{ $product->name }}'?">Delete Product</button>
                    </form>
                </div>
            </div>

            <div style="display:flex;gap:12px;justify-content:flex-end">
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
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
        const placeholder = document.getElementById('preview-placeholder');
        if (placeholder) placeholder.style.display = 'none';
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
