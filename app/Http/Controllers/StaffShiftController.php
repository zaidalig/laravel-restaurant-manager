<?php

namespace App\Http\Controllers;

use App\Models\StaffShift;
use App\Models\User;
use Illuminate\Http\Request;

class StaffShiftController extends Controller
{
    public function index(Request $request)
    {
        $query = StaffShift::with('user');
        if ($request->filled('date')) {
            $query->whereDate('shift_date', $request->input('date'));
        }
        [$sort, $direction] = $this->tableSort($request, ['created_at', 'shift_date', 'status'], 'created_at');
        $shifts = $query->orderBy($sort, $direction)->paginate($this->tablePerPage($request))->withQueryString();

        return view('shifts.index', compact('shifts'));
    }

    public function create()
    {
        $users = User::where('status', 'active')->orderBy('name')->get();

        return view('shifts.create', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'shift_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'position' => 'nullable|string|max:100',
            'status' => 'required|in:scheduled,completed,cancelled',
        ]);
        StaffShift::create($data);

        return redirect()->route('shifts.index')->with('success', 'Shift scheduled.');
    }

    public function edit(StaffShift $shift)
    {
        $users = User::where('status', 'active')->orderBy('name')->get();

        return view('shifts.edit', compact('shift', 'users'));
    }

    public function update(Request $request, StaffShift $shift)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'shift_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'position' => 'nullable|string|max:100',
            'status' => 'required|in:scheduled,completed,cancelled',
        ]);
        $shift->update($data);

        return redirect()->route('shifts.index')->with('success', 'Shift updated.');
    }

    public function destroy(StaffShift $shift)
    {
        $shift->delete();

        return redirect()->route('shifts.index')->with('success', 'Shift deleted.');
    }
}
