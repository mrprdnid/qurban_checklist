<a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
    <i class="bi bi-house-fill"></i> Home
</a>

<div class="sidebar-header mt-2">Data</div>
<a href="{{ route('hewan.index') }}" class="nav-link {{ request()->routeIs('hewan.*') && !request()->routeIs('hewan.import*') ? 'active' : '' }}">
    <i class="bi bi-list-ul"></i> Data Hewan
</a>
<a href="{{ route('hewan.import') }}" class="nav-link {{ request()->routeIs('hewan.import*') ? 'active' : '' }}">
    <i class="bi bi-file-earmark-arrow-up"></i> Import Excel
</a>
<a href="{{ route('checklist.kehadiran') }}" class="nav-link {{ request()->routeIs('checklist.kehadiran*') ? 'active' : '' }}">
    <i class="bi bi-person-check"></i> Registrasi Kehadiran
</a>

<div class="sidebar-header mt-2">Domba</div>
<a href="{{ route('checklist.kandang') }}" class="nav-link {{ request()->routeIs('checklist.kandang') ? 'active' : '' }}">
    <i class="bi bi-house-door"></i> Kandang
</a>
<a href="{{ route('checklist.sembelih') }}" class="nav-link {{ request()->routeIs('checklist.sembelih') ? 'active' : '' }}">
    <i class="bi bi-scissors"></i> Sembelih Domba
</a>
<a href="{{ route('checklist.seset') }}" class="nav-link {{ request()->routeIs('checklist.seset') ? 'active' : '' }}">
    <i class="bi bi-tools"></i> Seset Domba
</a>

<div class="sidebar-header mt-2">Sapi</div>
<a href="{{ route('checklist.sapi') }}" class="nav-link {{ request()->routeIs('checklist.sapi') ? 'active' : '' }}">
    <i class="bi bi-scissors"></i> Checklist Sapi
</a>

<div class="sidebar-header mt-2">Pengambilan</div>
<a href="{{ route('checklist.pengambilan') }}" class="nav-link {{ request()->routeIs('checklist.pengambilan*') ? 'active' : '' }}">
    <i class="bi bi-bag-check"></i> Pengambilan Bagian
</a>

@if(auth()->check() && auth()->user()->isAdmin())
<div class="sidebar-header mt-2">Admin</div>
<a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
    <i class="bi bi-people"></i> Manajemen User
</a>
<a href="{{ route('logs.index') }}" class="nav-link {{ request()->routeIs('logs.*') ? 'active' : '' }}">
    <i class="bi bi-journal-text"></i> Log Aktivitas
</a>
@endif
