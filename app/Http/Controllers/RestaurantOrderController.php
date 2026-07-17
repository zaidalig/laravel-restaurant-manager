<?php

namespace App\Http\Controllers;

use App\Models\DiningTable;
use App\Models\MenuItem;
use App\Models\Reservation;
use App\Models\RestaurantOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RestaurantOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = RestaurantOrder::with(['table', 'waiter']);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('search')) {
            $query->where('order_number', 'like', '%'.$request->input('search').'%');
        }

        [$sort, $direction] = $this->tableSort($request, ['created_at', 'order_number', 'status', 'total']);
        $orders = $query->orderBy($sort, $direction)->paginate($this->tablePerPage($request))->withQueryString();

        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $tables = DiningTable::orderBy('name')->get();
        $menuItems = MenuItem::where('status', 'active')->with('category')->orderBy('name')->get();
        $reservations = Reservation::whereIn('status', ['confirmed', 'seated'])->latest()->limit(20)->get();

        return view('orders.create', compact('tables', 'menuItems', 'reservations'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'dining_table_id' => 'nullable|exists:dining_tables,id',
            'reservation_id' => 'nullable|exists:reservations,id',
            'tax' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.menu_item_id' => 'required|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($data) {
            $subtotal = 0;
            $orderNumber = 'ORD-'.str_pad((string) (RestaurantOrder::count() + 1), 5, '0', STR_PAD_LEFT);

            $order = RestaurantOrder::create([
                'order_number' => $orderNumber,
                'dining_table_id' => $data['dining_table_id'] ?? null,
                'reservation_id' => $data['reservation_id'] ?? null,
                'status' => 'open',
                'user_id' => auth()->id(),
            ]);

            foreach ($data['items'] as $item) {
                $menuItem = MenuItem::findOrFail($item['menu_item_id']);
                $line = $menuItem->price * $item['quantity'];
                $subtotal += $line;

                $order->items()->create([
                    'menu_item_id' => $menuItem->id,
                    'item_name' => $menuItem->name,
                    'quantity' => $item['quantity'],
                    'unit_price' => $menuItem->price,
                    'subtotal' => $line,
                ]);
            }

            $tax = $data['tax'] ?? 0;
            $order->update([
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $subtotal + $tax,
            ]);

            if ($order->dining_table_id) {
                DiningTable::where('id', $order->dining_table_id)->update(['status' => 'occupied']);
            }
        });

        return redirect()->route('orders.index')->with('success', 'Order created.');
    }

    public function show(RestaurantOrder $order)
    {
        $order->load(['table', 'waiter', 'items', 'reservation']);

        return view('orders.show', compact('order'));
    }

    public function updateStatus(Request $request, RestaurantOrder $order)
    {
        $data = $request->validate([
            'status' => 'required|in:open,served,paid,cancelled',
        ]);

        $order->update($data);

        if ($data['status'] === 'paid' && $order->dining_table_id) {
            DiningTable::where('id', $order->dining_table_id)->update(['status' => 'available']);
        }

        return back()->with('success', 'Order status updated.');
    }

    public function closeBill(Request $request, RestaurantOrder $order)
    {
        if ($order->status === 'paid') {
            return back()->with('error', 'This bill is already closed.');
        }

        if ($order->status === 'cancelled') {
            return back()->with('error', 'Cannot close a cancelled order.');
        }

        $data = $request->validate([
            'payment_method' => 'required|in:cash,card',
        ]);

        $order->update([
            'status' => 'paid',
            'payment_method' => $data['payment_method'],
            'paid_at' => now(),
        ]);

        if ($order->dining_table_id) {
            DiningTable::where('id', $order->dining_table_id)->update(['status' => 'available']);
        }

        return back()->with('success', 'Bill closed — payment recorded.');
    }
}
