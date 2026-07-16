@extends('layouts.app')@section('title','New Order')@section('page_title','New Order')
@section('content')<div class="card p-4 border-0 shadow-sm"><form method="POST" action="{{ route('orders.store') }}">@csrf
<div class="row g-3 mb-4"><div class="col-md-4"><label class="form-label">Table</label><select name="dining_table_id" class="form-select"><option value="">Takeaway</option>@foreach($tables as $t)<option value="{{ $t->id }}">{{ $t->name }}</option>@endforeach</select></div><div class="col-md-4"><label class="form-label">Reservation</label><select name="reservation_id" class="form-select"><option value="">None</option>@foreach($reservations as $r)<option value="{{ $r->id }}">{{ $r->customer_name }} - {{ $r->reservation_date->format('M d') }}</option>@endforeach</select></div><div class="col-md-4"><label class="form-label">Tax</label><input type="number" step="0.01" name="tax" class="form-control" value="0"></div></div>
<h5 class="fw-bold">Items</h5><div id="itemsWrap"></div><button type="button" class="btn btn-outline-primary btn-sm mb-3" id="addItem">Add Item</button>
<button class="btn btn-success">Create Order</button></form></div>
@endsection
@section('scripts')<script>
const menu=@json($menuItems->map(fn($m)=>['id'=>$m->id,'name'=>$m->name,'price'=>$m->price]));let i=0;
function row(n){return `<div class="row g-2 mb-2 item-row"><div class="col-md-7"><select name="items[${n}][menu_item_id]" class="form-select" required>${menu.map(m=>`<option value="${m.id}">${m.name} ($${m.price})</option>`).join('')}</select></div><div class="col-md-3"><input type="number" name="items[${n}][quantity]" class="form-control" min="1" value="1" required></div><div class="col-md-2"><button type="button" class="btn btn-outline-danger w-100 remove-item">X</button></div></div>`}
document.getElementById('addItem').onclick=()=>document.getElementById('itemsWrap').insertAdjacentHTML('beforeend',row(i++));
document.getElementById('addItem').click();
document.getElementById('itemsWrap').onclick=e=>{if(e.target.classList.contains('remove-item'))e.target.closest('.item-row').remove();};
</script>@endsection
