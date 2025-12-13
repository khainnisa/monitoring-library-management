<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource with pagination and search
     */
    public function index(Request $request)
    {
        $query = Author::query();

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('bio', 'like', "%{$search}%");
            });
        }

        // Optional: Filter by specific field
        if ($request->has('name')) {
            $query->where('name', 'like', "%{$request->name}%");
        }

        if ($request->has('email')) {
            $query->where('email', 'like', "%{$request->email}%");
        }

        // Pagination (default 10 per page)
        $perPage = $request->get('per_page', 10);
        $authors = $query->orderBy('created_at', 'desc')
                        ->paginate($perPage);

        return response()->json($authors, 200);
    }

    /**
     * Store a newly created resource in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'regex:/^[a-zA-Z\s]+$/' // Only letters and spaces
            ],
            'email' => [
                'required',
                'email',
                'unique:authors,email',
                'max:255'
            ],
            'bio' => [
                'nullable',
                'string',
                'min:10',
                'max:1000'
            ]
        ], [
            'name.required' => 'Nama author wajib diisi',
            'name.min' => 'Nama author minimal 3 karakter',
            'name.max' => 'Nama author maksimal 255 karakter',
            'name.regex' => 'Nama author hanya boleh berisi huruf dan spasi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'bio.min' => 'Bio minimal 10 karakter',
            'bio.max' => 'Bio maksimal 1000 karakter'
        ]);

        $author = Author::create($validated);
        
        return response()->json([
            'message' => 'Author berhasil ditambahkan',
            'data' => $author
        ], 201);
    }

    /**
     * Display the specified resource
     */
    public function show($id)
    {
        $author = Author::with('books')->findOrFail($id);
        
        return response()->json([
            'data' => $author
        ], 200);
    }

    /**
     * Update the specified resource in storage
     */
    public function update(Request $request, $id)
    {
        $author = Author::findOrFail($id);

        $validated = $request->validate([
            'name' => [
                'sometimes',
                'required',
                'string',
                'min:3',
                'max:255',
                'regex:/^[a-zA-Z\s]+$/'
            ],
            'email' => [
                'sometimes',
                'required',
                'email',
                'max:255',
                Rule::unique('authors')->ignore($author->id)
            ],
            'bio' => [
                'nullable',
                'string',
                'min:10',
                'max:1000'
            ]
        ], [
            'name.required' => 'Nama author wajib diisi',
            'name.min' => 'Nama author minimal 3 karakter',
            'name.max' => 'Nama author maksimal 255 karakter',
            'name.regex' => 'Nama author hanya boleh berisi huruf dan spasi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'bio.min' => 'Bio minimal 10 karakter',
            'bio.max' => 'Bio maksimal 1000 karakter'
        ]);

        $author->update($validated);
        
        return response()->json([
            'message' => 'Author berhasil diupdate',
            'data' => $author
        ], 200);
    }

    /**
     * Remove the specified resource from storage
     */
    public function destroy($id)
    {
        $author = Author::findOrFail($id);
        
        // Optional: Check if author has books
        if ($author->books()->count() > 0) {
            return response()->json([
                'message' => 'Author tidak dapat dihapus karena masih memiliki buku'
            ], 422);
        }
        
        $author->delete();
        
        return response()->json([
            'message' => 'Author berhasil dihapus'
        ], 200);
    }
}