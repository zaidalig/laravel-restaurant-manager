<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Restaurant Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('styles')
</head>
<body>
<aside class="sidebar" id="sidebar">
    <div class="brand"><i class="fa-solid fa-utensils"></i><span>Restaurant</span></div>
    <ul class="nav-list">
        <li class="nav-item"><a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard')?'active':'' }}"><i class="fa-solid fa-chart-pie"></i><span>Dashboard</span></a></li>
        @can('manage-reservations')<li class="nav-item"><a href="{{ route('reservations.index') }}" class="nav-link {{ request()->routeIs('reservations.*')?'active':'' }}"><i class="fa-solid fa-calendar-check"></i><span>Reservations</span></a></li>@endcan
        @can('manage-orders')<li class="nav-item"><a href="{{ route('orders.index') }}" class="nav-link {{ request()->routeIs('orders.*')?'active':'' }}"><i class="fa-solid fa-receipt"></i><span>Orders</span></a></li>@endcan
        @can('manage-restaurant')
        <li class="nav-item"><a href="{{ route('tables.index') }}" class="nav-link {{ request()->routeIs('tables.*')?'active':'' }}"><i class="fa-solid fa-chair"></i><span>Tables</span></a></li>
        <li class="nav-item"><a href="{{ route('menu-items.index') }}" class="nav-link {{ request()->routeIs('menu-items.*')?'active':'' }}"><i class="fa-solid fa-burger"></i><span>Menu</span></a></li>
        <li class="nav-item"><a href="{{ route('menu-categories.index') }}" class="nav-link {{ request()->routeIs('menu-categories.*')?'active':'' }}"><i class="fa-solid fa-list"></i><span>Menu Categories</span></a></li>
        <li class="nav-item"><a href="{{ route('shifts.index') }}" class="nav-link {{ request()->routeIs('shifts.*')?'active':'' }}"><i class="fa-solid fa-user-clock"></i><span>Staff Shifts</span></a></li>
        @endcan
        @can('manage-users')<li class="nav-item"><a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*')?'active':'' }}"><i class="fa-solid fa-user-gear"></i><span>Users</span></a></li>@endcan
        <li class="nav-item"><a href="{{ route('activity.index') }}" class="nav-link {{ request()->routeIs('activity.*')?'active':'' }}"><i class="fa-solid fa-clock-rotate-left"></i><span>Activity</span></a></li>
    </ul>
</aside>
<div class="main-wrapper">
    <header class="topbar">
        <div class="d-flex align-items-center gap-3"><button class="btn btn-light d-lg-none" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button><h4 class="mb-0 fw-bold">@yield('page_title','Dashboard')</h4></div>
        <div class="d-flex align-items-center gap-3">
            <span class="badge bg-primary-subtle text-primary border px-3 py-2 rounded-pill">{{ auth()->user()->name }}</span>
            <form action="{{ route('logout') }}" method="POST">@csrf<button class="btn btn-outline-danger btn-sm rounded-pill">Logout</button></form>
        </div>
    </header>
    <main class="content-body">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
        @yield('content')
    </main>
</div>
<div class="modal fade" id="deleteModal" tabindex="-1"><div class="modal-dialog modal-dialog-centered"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Confirm Delete</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body">Delete <strong id="deleteItemName"></strong>?</div><div class="modal-footer"><button class="btn btn-light" data-bs-dismiss="modal">Cancel</button><form id="deleteForm" method="POST">@csrf @method('DELETE')<button class="btn btn-danger">Delete</button></form></div></div></div></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('sidebarToggle')?.addEventListener('click',()=>document.getElementById('sidebar').classList.toggle('active'));
const deleteModal=document.getElementById('deleteModal');deleteModal?.addEventListener('show.bs.modal',e=>{const b=e.relatedTarget;document.getElementById('deleteItemName').textContent=b.getAttribute('data-name');document.getElementById('deleteForm').action=b.getAttribute('data-url');});
</script>
@yield('scripts')
</body>
</html>
