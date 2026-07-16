<?php

namespace App\Http\Controllers;

use App\Models\DiningTable;
use Illuminate\Http\Request;

class DiningTableController extends Controller
{
    public function index(Request $request)
    {
        $query = DiningTable::query();
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        $tables = $query->orderBy('name')->paginate(12)->withQueryString();

        return view('tables.index', compact('tables'));
    }

    public function create()
    {
        return view('tables.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:50',
            'capacity' => 'required|integer|min:1|max:20',
            'zone' => 'nullable|string|max:100',
            'status' => 'required|in:available,occupied,reserved,maintenance',
        ]);
        DiningTable::create($data);

        return redirect()->route('tables.index')->with('success', 'Table created.');
    }

    public function edit(DiningTable $table)
    {
        return view('tables.edit', compact('table'));
    }

    public function update(Request $request, DiningTable $table)
    {
        $data = $request->validate([
            'name' => 'required|string|max:50',
            'capacity' => 'required|integer|min:1|max:20',
            'zone' => 'nullable|string|max:100',
            'status' => 'required|in:available,occupied,reserved,maintenance',
        ]);
        $table->update($data);

        return redirect()->route('tables.index')->with('success', 'Table updated.');
    }

    public function destroy(DiningTable $table)
    {
        $table->delete();

        return redirect()->route('tables.index')->with('success', 'Table deleted.');
    }
}
