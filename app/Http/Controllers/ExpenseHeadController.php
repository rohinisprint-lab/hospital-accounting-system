<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExpenseHead;

class ExpenseHeadController extends Controller
{
    public function index()
    {
        $heads = ExpenseHead::withCount('expenses')->latest()->paginate(15);
        return view('expense_heads.index', compact('heads'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'head_name'   => 'required|string|max:100|unique:expense_heads,head_name',
            'description' => 'nullable|string|max:255',
        ]);

        ExpenseHead::create($validated);

        return redirect()->route('expense-heads.index')->with('success', 'Cost center added successfully.');
    }

    public function update(Request $request, $id)
    {
        $head = ExpenseHead::findOrFail($id);

        $validated = $request->validate([
            'head_name'   => 'required|string|max:100|unique:expense_heads,head_name,' . $id . ',head_id',
            'description' => 'nullable|string|max:255',
        ]);

        $head->update($validated);

        return redirect()->route('expense-heads.index')->with('success', 'Cost center updated successfully.');
    }

    public function destroy($id)
    {
        $head = ExpenseHead::withCount('expenses')->findOrFail($id);

        if ($head->expenses_count > 0) {
            return redirect()->route('expense-heads.index')->with('error', 'Cannot delete cost center: expense vouchers are already attached to it.');
        }

        $head->delete();

        return redirect()->route('expense-heads.index')->with('success', 'Cost center deleted.');
    }
public function toggleStatus($id)
    {
        $head = ExpenseHead::findOrFail($id);
        $head->is_active = !$head->is_active;
        $head->save();

        $status = $head->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Cost center '{$head->head_name}' {$status}.");
    }
}