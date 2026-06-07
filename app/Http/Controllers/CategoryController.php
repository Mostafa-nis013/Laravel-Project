<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index()
    {
        $this->gate();
        $categories = Category::withCount('articles')->with('creator')->latest()->paginate(12);
        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->gate();
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:80', 'unique:categories,name'],
            'description' => ['nullable', 'string', 'max:300'],
            'color'       => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ]);

        Category::create([
            ...$data,
            'slug'       => Category::generateSlug($data['name']),
            'created_by' => Auth::id(),
        ]);

        return back()->with('success', "Category \"{$data['name']}\" created.");
    }

    public function update(Request $request, Category $category)
    {
        $this->gate();
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:80', 'unique:categories,name,' . $category->id],
            'description' => ['nullable', 'string', 'max:300'],
            'color'       => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ]);

        $category->update($data);
        return back()->with('success', 'Category updated.');
    }

    public function destroy(Category $category)
    {
        $this->gate();
        $name = $category->name;
        $category->delete();
        return back()->with('success', "Category \"{$name}\" deleted.");
    }

    private function gate(): void
    {
        if (! Auth::user()->hasPermission('manage_categories')) abort(403);
    }
}
