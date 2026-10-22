<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\ActivityLog;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with(['parent', 'children'])->withCount('products')->orderBy('sort_order')->get();
        $parentCategories = Category::whereNull('parent_id')->orderBy('name')->get();
        return view('admin.categories.index', compact('categories', 'parentCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
            'image' => 'nullable|image|max:2048',
        ]);

        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(4);
        $validated['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', 'public');
            $validated['image'] = 'storage/' . $path;
        }

        $category = Category::create($validated);
        ActivityLog::log('category.create', "Created category {$category->name}", $category);

        return back()->with('success', 'Category created successfully.');
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
            'image' => 'nullable|image|max:2048',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', 'public');
            $validated['image'] = 'storage/' . $path;
        }

        $category->update($validated);
        ActivityLog::log('category.update', "Updated category {$category->name}", $category);

        return back()->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        ActivityLog::log('category.delete', "Deleted category {$category->name}", $category);

        return back()->with('success', 'Category deleted successfully.');
    }
}
