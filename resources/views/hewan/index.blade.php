@extends('layouts.app')
@section('title', 'Data Hewan Kurban')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0"><i class="bi bi-list-ul me-2 text-success"></i>Data Hewan Kurban</h5>
    <a href="{{ route('hewan.create') }}" class="btn btn-success btn-sm">
        <i class="bi bi-plus-lg me-1"></i><span class="d-none d-sm-inline">Tambah </span>Hewan
    </a>
</div>

<form method="GET" class="mb-3">
    @if($sort !== 'nomor_urut' || $dir !== 'asc')
    <input type="hidden" name="sort" value="{{ $sort }}">
    <input type="hidden" name="direction" value="{{ $dir }}">
    @endif
    <div class="input-group">
        <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
        <input type="text" name="q" class="form-control" placeholder="Cari no. urut, nama hewan, atau pekurban..." value="{{ $q }}">
        @if($q)<a href="{{ route('hewan.index') }}" class="btn btn-outline-secondary">Hapus</a>@endif
    </div>
</form>

{{-- ── DESKTOP TABLE ── --}}
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
<div class="card border-0 shadow-sm d-none d-md-block">
    <div class="card-body p-0">
        <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>{!! $thSort('nomor_urut', 'No. Urut') !!}</th>
                    <th>{!! $thSort('jenis', 'Jenis') !!}</th>
                    <th>{!! $thSort('nama_hewan', 'Nama Hewan') !!}</th>
                    <th>{!! $thSort('nama_pekurban', 'Nama Pekurban') !!}</th>
                    <th>{!! $thSort('nomor_wa', 'No. WA') !!}</th>
                    <th>Keterangan</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($hewan as $h)
            @php
                $done = false;
                if ($h->jenis === 'domba' && $h->checklistSeset) $done = $h->checklistSeset->otw_pengambilan;
                if ($h->jenis === 'sapi' && $h->checklistSapi) $done = $h->checklistSapi->otw_pengambilan;
            @endphp
            <tr>
                <td class="fw-bold">{{ $h->nomor_urut }}</td>
                <td><span class="badge {{ $h->jenis==='domba' ? 'bg-primary' : 'bg-warning text-dark' }}">{{ ucfirst($h->jenis) }}</span></td>
                <td>{{ $h->nama_hewan ?: '-' }}</td>
                <td>{{ $h->nama_pekurban }}</td>
                <td>{{ $h->nomor_wa ?: '-' }}</td>
                <td>{{ $h->keterangan ?: '-' }}</td>
                <td>
                    @if($done)<span class="badge bg-success">OTW Pengambilan</span>
                    @else<span class="badge bg-secondary">Proses</span>@endif
                </td>
                <td class="text-center text-nowrap">
                    <a href="{{ route('hewan.journey', $h) }}" class="btn btn-outline-success btn-sm" title="Journey"><i class="bi bi-map"></i></a>
                    <a href="{{ route('hewan.edit', $h) }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-pencil"></i></a>
                    <form action="{{ route('hewan.destroy', $h) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data hewan ini?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center text-muted py-4">
                @if($q)Tidak ada hewan yang cocok dengan "<strong>{{ $q }}</strong>"@else Belum ada data hewan.@endif
            </td></tr>
            @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>

{{-- ── MOBILE CARDS ── --}}
<div class="d-md-none">
@forelse($hewan as $h)
@php
    $done = false;
    if ($h->jenis === 'domba' && $h->checklistSeset) $done = $h->checklistSeset->otw_pengambilan;
    if ($h->jenis === 'sapi' && $h->checklistSapi) $done = $h->checklistSapi->otw_pengambilan;
@endphp
<div class="animal-card">
    <div class="card-top">
        <div class="d-flex align-items-center gap-2">
            <span class="fw-bold">{{ $h->nomor_urut }}</span>
            <span class="badge {{ $h->jenis==='domba' ? 'bg-primary' : 'bg-warning text-dark' }}">{{ ucfirst($h->jenis) }}</span>
        </div>
        <span class="badge {{ $done ? 'bg-success' : 'bg-secondary' }}">{{ $done ? 'OTW Ambil' : 'Proses' }}</span>
    </div>
    <div class="card-body">
        @if($h->nama_hewan)<div class="fw-semibold">{{ $h->nama_hewan }}</div>@endif
        <div class="small {{ $h->nama_hewan ? 'text-muted' : 'fw-semibold' }}">{{ $h->nama_pekurban }}</div>
        @if($h->nomor_wa)<div class="small text-muted"><i class="bi bi-whatsapp text-success"></i> {{ $h->nomor_wa }}</div>@endif
        @if($h->keterangan)<div class="small text-muted mt-1">{{ $h->keterangan }}</div>@endif
        <div class="d-flex gap-2 mt-3">
            <a href="{{ route('hewan.journey', $h) }}" class="btn btn-outline-success btn-sm">
                <i class="bi bi-map me-1"></i>Journey
            </a>
            <a href="{{ route('hewan.edit', $h) }}" class="btn btn-outline-primary btn-sm flex-grow-1">
                <i class="bi bi-pencil me-1"></i>Edit
            </a>
            <form action="{{ route('hewan.destroy', $h) }}" method="POST" onsubmit="return confirm('Hapus data hewan ini?')">
                @csrf @method('DELETE')
                <button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
            </form>
        </div>
    </div>
</div>
@empty
<p class="text-muted text-center py-4">
    @if($q)Tidak ada hewan yang cocok dengan "<strong>{{ $q }}</strong>"@else Belum ada data hewan.@endif
</p>
@endforelse
</div>

<div class="mt-3">{{ $hewan->links() }}</div>
@endsection
