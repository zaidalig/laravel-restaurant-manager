<?php

namespace App\Http\Controllers;

use App\Models\DiningTable;
use App\Models\MenuItem;
use App\Models\Reservation;
use App\Models\RestaurantOrder;
use App\Models\StaffShift;
use App\Models\ActivityLog;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'tables' => DiningTable::count(),
            'available_tables' => DiningTable::where('status', 'available')->count(),
            'reservations_today' => Reservation::whereDate('reservation_date', today())->count(),
            'open_orders' => RestaurantOrder::where('status', 'open')->count(),
            'menu_items' => MenuItem::where('status', 'active')->count(),
            'shifts_today' => StaffShift::whereDate('shift_date', today())->count(),
        ];

        $todayReservations = Reservation::with('table')->whereDate('reservation_date', today())->orderBy('reservation_time')->limit(6)->get();
        $openOrders = RestaurantOrder::with(['table', 'waiter'])->where('status', 'open')->latest()->limit(5)->get();
        $recentLogs = ActivityLog::with('user')->latest()->limit(8)->get();

        return view('dashboard', compact('stats', 'todayReservations', 'openOrders', 'recentLogs'));
    }
}
