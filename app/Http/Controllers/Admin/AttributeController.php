<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Attribute;
use App\Models\AttributeValue;

class AttributeController extends Controller
{
    public function index()
    {
        $attributes = Attribute::with('values')->orderBy('sort_order')->get();
        return view('admin.attributes.index', compact('attributes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|in:select,color,button',
            'sort_order' => 'integer|min:0',
        ]);
        $validated['slug'] = Str::slug($validated['name']);

        Attribute::create($validated);
        return back()->with('success', 'Attribute created successfully.');
    }

    public function update(Request $request, Attribute $attribute)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|in:select,color,button',
            'sort_order' => 'integer|min:0',
        ]);
        $validated['slug'] = Str::slug($validated['name']);

        $attribute->update($validated);
        return back()->with('success', 'Attribute updated successfully.');
    }

    public function destroy(Attribute $attribute)
    {
        $attribute->delete();
        return back()->with('success', 'Attribute deleted successfully.');
    }

    public function storeValue(Request $request, Attribute $attribute)
    {
        $validated = $request->validate([
            'value' => 'required|string|max:100',
            'color_code' => 'nullable|string|max:20',
            'sort_order' => 'integer|min:0',
        ]);

        $attribute->values()->create($validated);
        return back()->with('success', 'Attribute value added successfully.');
    }

    public function destroyValue(AttributeValue $attributeValue)
    {
        $attributeValue->delete();
        return back()->with('success', 'Attribute value deleted successfully.');
    }
}
