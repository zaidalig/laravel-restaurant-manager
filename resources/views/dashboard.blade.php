@extends('layouts.app')
@section('title','Dashboard')@section('page_title','Restaurant Dashboard')
@section('content')
<div class="row g-4 mb-4">
@foreach([['Tables',$stats['tables'],'chair'],['Available',$stats['available_tables'],'circle-check'],['Reservations Today',$stats['reservations_today'],'calendar'],['Open Orders',$stats['open_orders'],'receipt'],['Menu Items',$stats['menu_items'],'burger'],['Shifts Today',$stats['shifts_today'],'user-clock']] as $s)
<div class="col-md-4 col-xl-2"><div class="card stat-card p-3"><div class="text-muted small">{{ $s[0] }}</div><h4 class="fw-bold mb-0">{{ $s[1] }}</h4></div></div>
@endforeach
</div>
<div class="row g-4">
<div class="col-lg-6"><div class="card card-table border-0"><div class="card-header bg-white fw-bold">Today's Reservations</div><div class="table-responsive"><table class="table mb-0"><thead class="table-light"><tr><th>Guest</th><th>Time</th><th>Table</th><th>Status</th></tr></thead><tbody>@forelse($todayReservations as $r)<tr><td>{{ $r->customer_name }}</td><td>{{ $r->reservation_time }}</td><td>{{ $r->table?->name ?? '-' }}</td><td>{{ $r->status }}</td></tr>@empty<tr><td colspan="4" class="text-center py-3 text-muted">No reservations today.</td></tr>@endforelse</tbody></table></div></div></div>
<div class="col-lg-6"><div class="card card-table border-0"><div class="card-header bg-white fw-bold">Open Orders</div><div class="table-responsive"><table class="table mb-0"><thead class="table-light"><tr><th>Order</th><th>Table</th><th>Total</th></tr></thead><tbody>@forelse($openOrders as $o)<tr><td><a href="{{ route('orders.show',$o) }}">{{ $o->order_number }}</a></td><td>{{ $o->table?->name ?? 'Takeaway' }}</td><td>${{ number_format($o->total,2) }}</td></tr>@empty<tr><td colspan="3" class="text-center py-3 text-muted">No open orders.</td></tr>@endforelse</tbody></table></div></div></div>
</div>
@endsection
