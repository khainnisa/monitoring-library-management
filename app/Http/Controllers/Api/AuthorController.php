<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index()
    {
        $authors = Author::with('books')->get();
        return response()->json(['data' => $authors], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:authors,email',
            'bio' => 'nullable|string'
        ]);

        $author = Author::create($validated);
        return response()->json(['data' => $author], 201);
    }

    public function show($id)
    {
        $author = Author::with('books')->findOrFail($id);
        return response()->json(['data' => $author], 200);
    }

    public function update(Request $request, $id)
    {
        $author = Author::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:authors,email,' . $author->id,
            'bio' => 'nullable|string'
        ]);

        $author->update($validated);
        return response()->json(['data' => $author], 200);
    }

    public function destroy($id)
    {
        $author = Author::findOrFail($id);
        $author->delete();
        return response()->json(['message' => 'Author deleted successfully'], 200);
    }
}
