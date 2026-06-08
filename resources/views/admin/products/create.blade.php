@extends('layouts.admin')

@section('title', 'Add Product')
@section('topbar-title', 'Products / Add New')

@section('content')
<div class="page-header">
    <div>
        <h2>Add Product</h2>
        <p>Fill in the details to create a new product.</p>
    </div>
    <a href="{{ route('products.index') }}" class="btn btn-secondary">← Back</a>
</div>

<form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="grid-2" style="align-items:start">
        {{-- Left column --}}
        <div style="display:flex;flex-direction:column;gap:20px">
            <div class="card">
                <div class="card-header"><span class="card-title">Basic Info</span></div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Product Name <span class="required">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Wireless Headphones">
                        @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>SKU <span class="required">*</span></label>
                        <input type="text" name="sku" value="{{ old('sku') }}" required placeholder="e.g. ELEC-001">
                        @error('sku')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" rows="4" placeholder="Product description…">{{ old('description') }}</textarea>
                        @error('description')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Category <span class="required">*</span></label>
                        <select name="category_id" required>
                            <option value="">Select category…</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
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
                            <input type="number" name="price" value="{{ old('price') }}" step="0.01" min="0" required placeholder="0.00">
                            @error('price')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label>Sale Price <span style="color:var(--muted);font-size:0.8rem">(optional)</span></label>
                            <input type="number" name="sale_price" value="{{ old('sale_price') }}" step="0.01" min="0" placeholder="0.00">
                            @error('sale_price')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Stock Quantity <span class="required">*</span></label>
                        <input type="number" name="stock" value="{{ old('stock', 0) }}" min="0" required>
                        @error('stock')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Right column --}}
        <div style="display:flex;flex-direction:column;gap:20px">
            <div class="card">
                <div class="card-header"><span class="card-title">Product Image</span></div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Upload Image</label>
                        <div id="image-preview" style="width:100%;height:180px;background:var(--surface2);border:2px dashed var(--border);border-radius:var(--radius);display:flex;align-items:center;justify-content:center;color:var(--muted);font-size:2rem;margin-bottom:12px;overflow:hidden;cursor:pointer" onclick="document.getElementById('image-input').click()">
                            <span id="preview-placeholder">📷 Click to upload</span>
                            <img id="preview-img" style="display:none;width:100%;height:100%;object-fit:cover">
                        </div>
                        <input type="file" id="image-input" name="image" accept="image/*" style="display:none">
                        @error('image')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><span class="card-title">Visibility</span></div>
                <div class="card-body" style="display:flex;flex-direction:column;gap:16px">
                    <div class="form-check">
                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label for="is_active">Active (visible to customers)</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                        <label for="is_featured">Featured product</label>
                    </div>
                </div>
            </div>

            <div style="display:flex;gap:12px;justify-content:flex-end">
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Create Product</button>
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
