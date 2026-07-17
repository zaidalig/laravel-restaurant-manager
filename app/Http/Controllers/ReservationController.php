<?php

namespace App\Http\Controllers;

use App\Models\DiningTable;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $query = Reservation::with(['table', 'user']);
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('date')) {
            $query->whereDate('reservation_date', $request->input('date'));
        }
        [$sort, $direction] = $this->tableSort($request, ['created_at', 'reservation_date', 'customer_name', 'status'], 'reservation_date');
        $reservations = $query->orderBy($sort, $direction)->paginate($this->tablePerPage($request))->withQueryString();

        return view('reservations.index', compact('reservations'));
    }

    public function create()
    {
        $tables = DiningTable::orderBy('name')->get();

        return view('reservations.create', compact('tables'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'dining_table_id' => 'nullable|exists:dining_tables,id',
            'customer_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email',
            'party_size' => 'required|integer|min:1|max:20',
            'reservation_date' => 'required|date',
            'reservation_time' => 'required',
            'status' => 'required|in:pending,confirmed,seated,completed,cancelled',
            'notes' => 'nullable|string',
        ]);
        $data['user_id'] = auth()->id();
        Reservation::create($data);

        return redirect()->route('reservations.index')->with('success', 'Reservation created.');
    }

    public function edit(Reservation $reservation)
    {
        $tables = DiningTable::orderBy('name')->get();

        return view('reservations.edit', compact('reservation', 'tables'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        $data = $request->validate([
            'dining_table_id' => 'nullable|exists:dining_tables,id',
            'customer_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email',
            'party_size' => 'required|integer|min:1|max:20',
            'reservation_date' => 'required|date',
            'reservation_time' => 'required',
            'status' => 'required|in:pending,confirmed,seated,completed,cancelled',
            'notes' => 'nullable|string',
        ]);
        $reservation->update($data);

        return redirect()->route('reservations.index')->with('success', 'Reservation updated.');
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();

        return redirect()->route('reservations.index')->with('success', 'Reservation deleted.');
    }
}
