<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Checklist Hewan Kurban')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }

        /* ── Sidebar ── */
        .sidebar { background: #198754; }
        .sidebar .nav-link { color: rgba(255,255,255,.8); border-radius: 6px; margin-bottom: 2px; padding: .45rem .75rem; }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active { color: #fff; background: rgba(255,255,255,.15); }
        .sidebar .nav-link i { width: 20px; }
        .sidebar-header { color: #fff; padding: .75rem .75rem .3rem; font-size: .68rem; text-transform: uppercase; letter-spacing: .08em; opacity: .6; }
        .sidebar-brand { color: #fff; text-decoration: none; display: flex; align-items: center; gap: .5rem; padding: .75rem; margin-bottom: .25rem; }

        /* Desktop: static sidebar */
        @media (min-width: 768px) {
            .sidebar {
                width: 220px;
                min-width: 220px;
                min-height: 100vh;
                position: sticky;
                top: 0;
                height: 100vh;
                overflow-y: auto;
            }
            .topbar { display: none !important; }
        }

        /* Mobile: full-width offcanvas */
        @media (max-width: 767.98px) {
            .sidebar.offcanvas { width: 240px; }
            .main-content { padding: 1rem !important; }
        }

        /* ── Table styles ── */
        .table thead th { background: #198754; color: #fff; white-space: nowrap; }
        .checklist-row td { vertical-align: middle; }
        .check-cell { text-align: center; min-width: 70px; }
        .timestamp-text { font-size: .7rem; color: #6c757d; }
        .card-header-green { background: #198754; color: #fff; }
        .progress-mini { height: 6px; }

        /* ── Pagination ── */
        .pagination { flex-wrap: wrap; }
        .pagination svg, svg.w-5 { width: 14px !important; height: 14px !important; }
        .w-5 { width: 14px !important; }
        .h-5 { height: 14px !important; }

        /* ── Mobile cards ── */
        .animal-card { border-radius: 10px; border: 1px solid #dee2e6; background: #fff; margin-bottom: .75rem; }
        .animal-card .card-top { background: #198754; color: #fff; border-radius: 9px 9px 0 0; padding: .6rem .85rem; display: flex; justify-content: space-between; align-items: center; }
        .animal-card .card-body { padding: .75rem .85rem; }
        .check-item { display: flex; align-items: center; justify-content: space-between; padding: .4rem 0; border-bottom: 1px solid #f0f0f0; }
        .check-item:last-child { border-bottom: none; }
        .check-item label { margin: 0; font-size: .9rem; flex-grow: 1; cursor: pointer; }
        .check-item .form-check-input { width: 1.2em; height: 1.2em; cursor: pointer; flex-shrink: 0; }
        .check-item .ts { font-size: .7rem; color: #6c757d; margin-left: .5rem; white-space: nowrap; }
    </style>
</head>
<body>

{{-- Mobile top bar --}}
<nav class="topbar navbar navbar-dark bg-success d-md-none px-2 py-2">
    <button class="btn btn-link text-white p-1" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
        <i class="bi bi-list fs-4"></i>
    </button>
    <a href="{{ route('dashboard') }}" class="navbar-brand mb-0 fw-bold ms-1">
        <i class="bi bi-moon-stars-fill me-1"></i>Kurban
    </a>
    @auth
    <form method="POST" action="{{ route('logout') }}" class="mb-0">
        @csrf
        <button type="submit" class="btn btn-link text-white p-1" title="Logout">
            <i class="bi bi-box-arrow-right fs-5"></i>
        </button>
    </form>
    @endauth
</nav>

<div class="d-flex">

    {{-- Desktop sidebar (always visible ≥md) --}}
    <nav class="sidebar d-none d-md-flex flex-column p-2">
        <a href="{{ route('dashboard') }}" class="sidebar-brand fw-bold">
            <i class="bi bi-moon-stars-fill fs-5"></i> Kurban
        </a>
        @include('layouts._sidebar_links')
        @auth
        <div class="mt-auto pt-2 border-top border-white border-opacity-25">
            <div class="px-2 pb-1" style="color:rgba(255,255,255,.7);font-size:.78rem">
                <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
                @if(auth()->user()->isAdmin())
                <span class="badge bg-danger ms-1" style="font-size:.6rem">Admin</span>
                @endif
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mb-0">
                @csrf
                <button type="submit" class="nav-link w-100 text-start">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </div>
        @endauth
    </nav>

    {{-- Mobile offcanvas sidebar --}}
    <div class="offcanvas offcanvas-start sidebar d-md-none" tabindex="-1" id="mobileSidebar">
        <div class="offcanvas-header pb-1">
            <a href="{{ route('dashboard') }}" class="sidebar-brand fw-bold" onclick="document.getElementById('mobileSidebar').classList.remove('show')">
                <i class="bi bi-moon-stars-fill fs-5"></i> Kurban
            </a>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-2 pt-0 d-flex flex-column">
            @include('layouts._sidebar_links')
            @auth
            <div class="mt-auto pt-2 border-top border-white border-opacity-25">
                <div class="px-2 pb-1" style="color:rgba(255,255,255,.7);font-size:.78rem">
                    <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
                    @if(auth()->user()->isAdmin())
                    <span class="badge bg-danger ms-1" style="font-size:.6rem">Admin</span>
                    @endif
                </div>
            </div>
            @endauth
        </div>
    </div>

    {{-- Main content --}}
    <main class="flex-grow-1 p-4 main-content" style="min-width:0;">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle-fill me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
