<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query();

        // 🔍 SEARCH (case-insensitive)
        if ($request->filled('search')) {
            $search = strtolower($request->search);

            $query->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"]);
        }

        // 📄 PAGINATION
        $categories = $query
            ->withCount('books')
            ->paginate($request->get('per_page', 10));

        return response()->json($categories, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'unique:categories,name',
                'regex:/^[a-zA-Z0-9\s]+$/'
            ],
            'description' => 'nullable|string|min:5'
        ]);

        $category = Category::create($validated);

        return response()->json(['data' => $category], 201);
    }

    public function show($id)
    {
        $category = Category::with('books')->findOrFail($id);
        return response()->json(['data' => $category], 200);
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => [
                'sometimes',
                'string',
                'min:3',
                'max:255',
                'unique:categories,name,' . $category->id,
                'regex:/^[a-zA-Z0-9\s]+$/'
            ],
            'description' => 'nullable|string|min:5'
        ]);

        $category->update($validated);

        return response()->json(['data' => $category], 200);
    }

    public function destroy($id)
    {
        Category::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Category deleted successfully'
        ], 200);
    }
}
