<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    // GET /api/borrowings
    public function index(Request $request)
    {
        $query = Borrowing::with(['member', 'book']);

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('member', function($mq) use ($search) {
                    $mq->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('book', function($bq) use ($search) {
                    $bq->where('title', 'like', "%{$search}%");
                });
            });
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('date_from')) {
            $query->where('borrow_date', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->where('borrow_date', '<=', $request->date_to);
        }

        // Pagination
        $borrowings = $query->orderBy('created_at', 'desc')->paginate(10);

        return response()->json($borrowings);
    }

    // POST /api/borrowings
    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'book_id' => 'required|exists:books,id',
            'borrow_date' => 'required|date',
            'due_date' => 'required|date|after:borrow_date',
            'status' => 'required|in:borrowed,returned,overdue',
        ], [
            'due_date.after' => 'Due date must be after borrow date',
        ]);

        $borrowing = Borrowing::create($validated);

        return response()->json([
            'message' => 'Borrowing created successfully',
            'data' => $borrowing->load(['member', 'book'])
        ], 201);
    }

    // GET /api/borrowings/{id}
    public function show($id)
    {
        $borrowing = Borrowing::with(['member', 'book'])->findOrFail($id);
        return response()->json($borrowing);
    }

    // PUT/PATCH /api/borrowings/{id}
    public function update(Request $request, $id)
    {
        $borrowing = Borrowing::findOrFail($id);

        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'book_id' => 'required|exists:books,id',
            'borrow_date' => 'required|date',
            'due_date' => 'required|date|after:borrow_date',
            'return_date' => 'nullable|date|after_or_equal:borrow_date',
            'status' => 'required|in:borrowed,returned,overdue',
            'fine_amount' => 'nullable|numeric|min:0',
        ]);

        $borrowing->update($validated);

        return response()->json([
            'message' => 'Borrowing updated successfully',
            'data' => $borrowing->load(['member', 'book'])
        ]);
    }

    // DELETE /api/borrowings/{id}
    public function destroy($id)
    {
        $borrowing = Borrowing::findOrFail($id);
        $borrowing->delete();

        return response()->json([
            'message' => 'Borrowing deleted successfully'
        ]);
    }
}