<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::with('parent', 'products');

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $categories = $query->orderBy('sort_order')->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $parents = Category::whereNull('parent_id')->active()->get();
        return view('admin.categories.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'parent_id'   => 'nullable|exists:categories,id',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_active'   => 'boolean',
            'sort_order'  => 'nullable|integer|min:0',
        ]);

        $validated['slug']       = Str::slug($request->name);
        $validated['is_active']  = $request->boolean('is_active', true);
        $validated['sort_order'] = $request->input('sort_order', 0);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        $category = Category::create($validated);

        return redirect()
            ->route('categories.index')
            ->with('success', "Category \"{$category->name}\" created successfully.");
    }

    public function show(Category $category)
    {
        $category->load('products', 'parent', 'children');
        return view('admin.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        $parents = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->active()
            ->get();

        return view('admin.categories.edit', compact('category', 'parents'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'parent_id'   => 'nullable|exists:categories,id',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_active'   => 'boolean',
            'sort_order'  => 'nullable|integer|min:0',
        ]);

        $validated['slug']       = Str::slug($request->name);
        $validated['is_active']  = $request->boolean('is_active');
        $validated['sort_order'] = $request->input('sort_order', 0);

        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($validated);

        return redirect()
            ->route('categories.index')
            ->with('success', "Category \"{$category->name}\" updated successfully.");
    }

    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return redirect()
                ->route('categories.index')
                ->with('error', "Cannot delete category with existing products. Reassign or delete them first.");
        }

        $name = $category->name;
        $category->delete();

        return redirect()
            ->route('categories.index')
            ->with('success', "Category \"{$name}\" deleted successfully.");
    }
}
