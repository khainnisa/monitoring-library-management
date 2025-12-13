<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * GET /api/books
     * Pagination + Search + Filter
     */
    public function index(Request $request)
    {
        $query = Book::with(['author', 'category']);

        // 🔍 SEARCH (title)
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // 🎯 FILTER by author
        if ($request->filled('author_id')) {
            $query->where('author_id', $request->author_id);
        }

        // 🎯 FILTER by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // 📄 PAGINATION
        $books = $query->paginate(10);

        return response()->json($books, 200);
    }

    /**
     * POST /api/books
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|min:3|max:255|regex:/^[a-zA-Z0-9\s\-\']+$/',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|min:10',
            'stock' => 'required|integer|min:0|max:1000'
        ]);

        $book = Book::create($validated);

        return response()->json([
            'message' => 'Book created successfully',
            'data' => $book
        ], 201);
    }

    /**
     * GET /api/books/{id}
     */
    public function show($id)
    {
        $book = Book::with(['author', 'category'])->findOrFail($id);

        return response()->json(['data' => $book], 200);
    }

    /**
     * PUT /api/books/{id}
     */
    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|string|min:3|max:255|regex:/^[a-zA-Z0-9\s\-\']+$/',
            'author_id' => 'sometimes|exists:authors,id',
            'category_id' => 'sometimes|exists:categories,id',
            'description' => 'sometimes|string|min:10',
            'stock' => 'sometimes|integer|min:0|max:1000'
        ]);

        $book->update($validated);

        return response()->json([
            'message' => 'Book updated successfully',
            'data' => $book
        ], 200);
    }

    /**
     * DELETE /api/books/{id}
     */
    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        return response()->json([
            'message' => 'Book deleted successfully'
        ], 200);
    }
}
