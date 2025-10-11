<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::with(['author', 'category'])->get();
        return response()->json(['data' => $books], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'stock' => 'required|integer|min:0'
        ]);

        $book = Book::create($validated);
        return response()->json(['data' => $book], 201);
    }

    public function show($id)
    {
        $book = Book::with(['author', 'category'])->findOrFail($id);
        return response()->json(['data' => $book], 200);
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'author_id' => 'sometimes|exists:authors,id',
            'category_id' => 'sometimes|exists:categories,id',
            'description' => 'sometimes|string',
            'stock' => 'sometimes|integer|min:0'
        ]);

        $book->update($validated);
        return response()->json(['data' => $book], 200);
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();
        return response()->json(['message' => 'Book deleted successfully'], 200);
    }
}