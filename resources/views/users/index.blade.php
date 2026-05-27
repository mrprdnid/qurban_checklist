@extends('layouts.app')
@section('title', 'Manajemen User')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0"><i class="bi bi-people me-2 text-success"></i>Manajemen User</h5>
    <a href="{{ route('users.create') }}" class="btn btn-success btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Tambah User
    </a>
</div>

@php
$thSort = function(string $col, string $label) use ($sort, $dir): string {
    $newDir = ($sort === $col && $dir === 'asc') ? 'desc' : 'asc';
    $url    = request()->fullUrlWithQuery(['sort' => $col, 'direction' => $newDir]);
    $icon   = $sort === $col
        ? '<i class="bi bi-caret-' . ($dir === 'asc' ? 'up' : 'down') . '-fill" style="font-size:.6rem"></i>'
        : '<i class="bi bi-caret-up opacity-25" style="font-size:.6rem"></i>';
    return '<a href="' . htmlspecialchars($url, ENT_QUOTES) . '" class="text-decoration-none text-dark d-inline-flex align-items-center gap-1">'
        . htmlspecialchars($label, ENT_QUOTES) . ' ' . $icon . '</a>';
};
@endphp
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>{!! $thSort('name', 'Nama') !!}</th>
                    <th>{!! $thSort('email', 'Email') !!}</th>
                    <th>{!! $thSort('role', 'Role') !!}</th>
                    <th>{!! $thSort('created_at', 'Dibuat') !!}</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($users as $u)
            <tr>
                <td class="fw-semibold">
                    {{ $u->name }}
                    @if($u->id === auth()->id())
                    <span class="badge bg-secondary ms-1">Anda</span>
                    @endif
                </td>
                <td>{{ $u->email }}</td>
                <td>
                    @if($u->role === 'admin')
                    <span class="badge bg-danger">Admin</span>
                    @else
                    <span class="badge bg-primary">User</span>
                    @endif
                </td>
                <td class="small text-muted">{{ $u->created_at->format('d M Y') }}</td>
                <td class="text-center">
                    <a href="{{ route('users.edit', $u) }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-pencil"></i></a>
                    @if($u->id !== auth()->id())
                    <form action="{{ route('users.destroy', $u) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus user {{ $u->name }}?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
                    </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center text-muted py-4">Belum ada user.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
