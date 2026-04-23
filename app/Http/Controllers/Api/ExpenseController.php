<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\ExpenseStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();
        $query = Expense::with('user');

        if (!$user->isAdmin() && !$user->isManager()) {
            $query->where('user_id', $user->id);
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $expenses = $query->latest()->paginate(15);

        return response()->json($expenses);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'category' => ['required', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
        ]);

        $expense = Auth::user()->expenses()->create($validated);
        $expense->load('user');

        return response()->json($expense, 201);
    }

    public function show(Expense $expense): JsonResponse
    {
        $expense->load('user', 'approvalLogs.user');

        return response()->json($expense);
    }

    public function update(Request $request, Expense $expense): JsonResponse
    {
        if ($expense->status !== ExpenseStatus::Draft) {
            return response()->json(['message' => 'Only draft expenses can be edited'], 422);
        }

        if ($expense->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'amount' => ['sometimes', 'required', 'numeric', 'min:0.01'],
            'category' => ['sometimes', 'required', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
        ]);

        $expense->update($validated);
        $expense->load('user');

        return response()->json($expense);
    }

    public function destroy(Expense $expense): JsonResponse
    {
        if ($expense->status !== ExpenseStatus::Draft) {
            return response()->json(['message' => 'Only draft expenses can be deleted'], 422);
        }

        if ($expense->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $expense->delete();

        return response()->json(['message' => 'Expense deleted']);
    }
}
