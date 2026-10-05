<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class StaffController extends Controller
{
    public function index()
    {
        $staff = User::with('branch')->latest()->paginate(15);
        return view('staff.index', compact('staff'));
    }

    public function create()
    {
        $branches = Branch::all();
        return view('staff.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'role'      => ['required', 'string', 'in:admin,staff,accountant'],
            'branch_id' => ['nullable', 'exists:branches,branch_id'],
            'password'  => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'role'      => $request->role,
            'branch_id' => $request->branch_id,
            'password'  => Hash::make($request->password),
        ]);

        return redirect()->route('staff.index')->with('success', 'Staff account created successfully.');
    }

    public function destroy(User $staff)
    {
        if ($staff->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $staff->delete();
        return redirect()->route('staff.index')->with('success', 'Staff account removed successfully.');
    }
}