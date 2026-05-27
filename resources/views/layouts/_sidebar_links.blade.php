<div class="sidebar-header">Data</div>
<a href="{{ route('hewan.index') }}" class="nav-link {{ request()->routeIs('hewan.*') ? 'active' : '' }}">
    <i class="bi bi-list-ul"></i> Data Hewan
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
<a href="{{ route('checklist.pengambilan') }}" class="nav-link {{ request()->routeIs('checklist.pengambilan') ? 'active' : '' }}">
    <i class="bi bi-bag-check"></i> Pengambilan Bagian
</a>
