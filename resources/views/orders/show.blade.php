@extends('layouts.app')@section('title',$order->order_number)@section('page_title',$order->order_number)
@section('content')
<div class="card p-4 border-0 shadow-sm mb-4"><p>Table: {{ $order->table?->name ?? 'Takeaway' }} | Waiter: {{ $order->waiter?->name ?? '-' }}</p><p>Subtotal: ${{ number_format($order->subtotal,2) }} | Tax: ${{ number_format($order->tax,2) }} | <strong>Total: ${{ number_format($order->total,2) }}</strong></p>
@if($order->status==='paid')
<p class="mb-0"><span class="badge bg-success-subtle text-success">Paid</span> via {{ ucfirst($order->payment_method) }} @if($order->paid_at) on {{ $order->paid_at->format('M d, Y h:i A') }}@endif</p>
@else
<form method="POST" action="{{ route('orders.status',$order) }}" class="d-flex gap-2 mb-3">@csrf @method('PATCH')<select name="status" class="form-select w-auto">@foreach(['open','served','paid','cancelled'] as $s)<option value="{{ $s }}" @selected($order->status===$s)>{{ ucfirst($s) }}</option>@endforeach</select><button class="btn btn-primary">Update Status</button></form>
<div class="card bg-light border-0 p-3">
<h6 class="fw-bold mb-2">Close Bill</h6>
<form method="POST" action="{{ route('orders.pay',$order) }}" class="d-flex gap-2 align-items-end">@csrf @method('PATCH')
<div><label class="form-label small mb-1">Payment Method</label><select name="payment_method" class="form-select" required><option value="cash">Cash</option><option value="card">Card</option></select></div>
<button class="btn btn-success"><i class="fa-solid fa-receipt"></i> Close Bill &amp; Mark Paid</button>
</form>
</div>
@endif
</div>
<div class="card card-table border-0"><div class="table-responsive"><table class="table mb-0"><thead class="table-light"><tr><th>Item</th><th>Qty</th><th>Price</th><th>Subtotal</th></tr></thead><tbody>@foreach($order->items as $item)<tr><td>{{ $item->item_name }}</td><td>{{ $item->quantity }}</td><td>${{ number_format($item->unit_price,2) }}</td><td>${{ number_format($item->subtotal,2) }}</td></tr>@endforeach</tbody></table></div></div>
@endsection
