<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MemberController extends Controller
{
    // GET /api/members
    public function index(Request $request)
    {
        $query = Member::query();

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('membership_number', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Pagination
        $members = $query->orderBy('created_at', 'desc')->paginate(10);

        return response()->json($members);
    }

    // POST /api/members
    public function store(Request $request)
    {
        $validated = $request->validate([
            'membership_number' => 'required|unique:members|regex:/^MEM-\d{4}$/',
            'name' => 'required|string|min:3|max:100',
            'email' => 'required|email|unique:members',
            'phone' => 'required|regex:/^(08)[0-9]{9,11}$/',
            'address' => 'required|string|min:10',
            'join_date' => 'required|date',
            'status' => 'required|in:active,inactive',
        ], [
            'membership_number.regex' => 'Format: MEM-0001',
            'phone.regex' => 'Format: 08xxxxxxxxxx',
        ]);

        $member = Member::create($validated);

        return response()->json([
            'message' => 'Member created successfully',
            'data' => $member
        ], 201);
    }

    // GET /api/members/{id}
    public function show($id)
    {
        $member = Member::with('borrowings')->findOrFail($id);
        return response()->json($member);
    }

    // PUT/PATCH /api/members/{id}
    public function update(Request $request, $id)
    {
        $member = Member::findOrFail($id);

        $validated = $request->validate([
            'membership_number' => [
                'required',
                'regex:/^MEM-\d{4}$/',
                Rule::unique('members')->ignore($member->id)
            ],
            'name' => 'required|string|min:3|max:100',
            'email' => [
                'required',
                'email',
                Rule::unique('members')->ignore($member->id)
            ],
            'phone' => 'required|regex:/^(08)[0-9]{9,11}$/',
            'address' => 'required|string|min:10',
            'join_date' => 'required|date',
            'status' => 'required|in:active,inactive',
        ]);

        $member->update($validated);

        return response()->json([
            'message' => 'Member updated successfully',
            'data' => $member
        ]);
    }

    // DELETE /api/members/{id}
    public function destroy($id)
    {
        $member = Member::findOrFail($id);
        $member->delete();

        return response()->json([
            'message' => 'Member deleted successfully'
        ]);
    }
}