<?php

namespace App\Http\Controllers;

use App\Models\IncomeHead;
use Illuminate\Http\Request;

class IncomeHeadController extends Controller
{
    public function index()
    {
        $heads = IncomeHead::withCount('incomes')->latest()->paginate(15);
        return view('income_heads.index', compact('heads'));
    }

    public function store(Request $request)
{
    $request->validate([
        'head_name' => 'required|string|max:255|unique:income_heads,head_name',
    ]);

    IncomeHead::create([
        'head_name' => $request->input('head_name'),
    ]);

    return redirect()->back()->with('success', 'Income head created successfully.');
}
    public function update(Request $request, $id)
    {
        $head = IncomeHead::findOrFail($id);

        $validated = $request->validate([
            'head_name'   => 'required|string|max:100|unique:income_heads,head_name,' . $id . ',head_id',
            'description' => 'nullable|string|max:255',
        ]);

        $head->update($validated);

        return redirect()->route('income-heads.index')->with('success', 'Revenue category updated successfully.');
    }

    public function destroy($id)
    {
        $head = IncomeHead::withCount('incomes')->findOrFail($id);

        if ($head->incomes_count > 0) {
            return redirect()->route('income-heads.index')->with('error', 'Cannot delete category: transactions are already attached to it.');
        }

        $head->delete();

        return redirect()->route('income-heads.index')->with('success', 'Revenue category deleted.');
    }

public function toggleStatus($id)
    {
        $head = IncomeHead::findOrFail($id);
        $head->is_active = !$head->is_active;
        $head->save();

        $status = $head->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Revenue head '{$head->head_name}' {$status}.");
    }
}