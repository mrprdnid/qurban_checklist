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
    <div class="input-group">
        <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
        <input type="text" name="q" class="form-control" placeholder="Cari no. urut, nama hewan, atau pekurban..." value="{{ $q }}">
        @if($q)<a href="{{ route('hewan.index') }}" class="btn btn-outline-secondary">Hapus</a>@endif
    </div>
</form>

{{-- ── DESKTOP TABLE ── --}}
<div class="card border-0 shadow-sm d-none d-md-block">
    <div class="card-body p-0">
        <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>No. Urut</th>
                    <th>Jenis</th>
                    <th>Nama Hewan</th>
                    <th>Nama Pekurban</th>
                    <th>No. WA</th>
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
                <td class="text-center">
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
