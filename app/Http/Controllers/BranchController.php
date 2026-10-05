<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::withCount(['incomes', 'expenses'])->latest()->paginate(10);
        return view('branches.index', compact('branches'));
    }

    public function create()
    {
        return view('branches.create');
    }

public function store(Request $request)
{
    $validated = $request->validate([
        'branch_code' => ['nullable', 'string', 'max:50', 'unique:branches,branch_code'],
        'branch_name' => ['required', 'string', 'max:255'],
        'location'    => ['nullable', 'string', 'max:255'],
        'phone'       => ['nullable', 'string', 'max:50'],
    ]);

    // Automatically generate branch_code if not supplied
    if (empty($validated['branch_code'])) {
        $validated['branch_code'] = 'BR-' . strtoupper(Str::random(6));
    }

    Branch::create($validated);

    return redirect()->route('branches.index')->with('success', 'Hospital branch added successfully.');
}
    
        
    public function edit(Branch $branch)
    {
        return view('branches.edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'branch_code' => ['nullable', 'string', 'max:50', 'unique:branches,branch_code,' . $branch->getKey() . ',branch_id'],
            'branch_name' => ['required', 'string', 'max:255'],
            'location'    => ['nullable', 'string', 'max:255'],
            'phone'       => ['nullable', 'string', 'max:50'],
        ]);

        $branch->update($validated);

        return redirect()->route('branches.index')->with('success', 'Branch details updated successfully.');
    }

    public function destroy(Branch $branch)
    {
        if ($branch->incomes()->count() > 0 || $branch->expenses()->count() > 0) {
            return redirect()->route('branches.index')
                ->with('error', 'Cannot delete branch because it contains recorded receipts or expenses.');
        }

        $branch->delete();
        return redirect()->route('branches.index')->with('success', 'Branch removed successfully.');
    }
}