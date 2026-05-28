@extends('layouts.app')
@section('title', 'Registrasi Kehadiran Pekurban')
@php use Illuminate\Support\Str; @endphp

@section('content')
<h5 class="fw-bold mb-3"><i class="bi bi-person-check me-2 text-success"></i>Registrasi Kehadiran Pekurban</h5>

<form method="GET" class="mb-2">
    @if($sort)<input type="hidden" name="sort" value="{{ $sort }}">
    <input type="hidden" name="direction" value="{{ $dir }}">@endif
    @if($jenis)<input type="hidden" name="jenis" value="{{ $jenis }}">@endif
    <div class="input-group">
        <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
        <input type="text" name="q" class="form-control" placeholder="Cari no. urut, nama hewan, atau pekurban..." value="{{ $q }}">
        @if($q || ($status ?? '') || $jenis)
        <a href="{{ route('checklist.kehadiran') }}" class="btn btn-outline-secondary">Hapus</a>
        @endif
    </div>
</form>

{{-- Jenis filter --}}
<div class="d-flex gap-2 mb-2 flex-wrap">
    @foreach([null => 'Semua', 'domba' => 'Domba', 'sapi' => 'Sapi'] as $val => $label)
    @php $active = $jenis === $val; @endphp
    <a href="{{ request()->fullUrlWithQuery(['jenis' => $val ?: null, 'page' => null]) }}"
       class="btn btn-sm {{ $active ? 'btn-secondary' : 'btn-outline-secondary' }}">
        {{ $label }}
    </a>
    @endforeach
</div>

@if($jenis !== 'sapi')
@include('layouts._sort_bar')
@endif
@include('layouts._status_filter', ['filterRoute' => 'checklist.kehadiran'])

@if($jenis === 'sapi' && $grouped !== null)

{{-- ── SAPI GROUPED VIEW ── --}}
@forelse($grouped as $prefix => $items)
@php
    $doneCount  = $items->filter(fn($h) => $h->checklistKehadiran?->absensi && $h->checklistKehadiran?->penyerahan_tagging)->count();
    $totalCount = $items->count();
    $allDone    = $doneCount === $totalCount;
    $collapseId = 'sapi-' . Str::slug($prefix);
@endphp
<div class="card border-0 shadow-sm mb-3">
    <div class="card-header d-flex align-items-center justify-content-between py-2 px-3 cursor-pointer"
         style="background:#f8f9fa; border-bottom:1px solid #dee2e6"
         data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}" role="button" aria-expanded="false">
        <span class="fw-bold">
            <i class="bi bi-chevron-right me-1 text-muted transition-icon" style="font-size:.75rem"></i>
            <i class="bi bi-grid-3x3-gap me-1 text-warning"></i>{{ $prefix }}
        </span>
        <span class="badge {{ $allDone ? 'bg-success' : ($doneCount > 0 ? 'bg-warning text-dark' : 'bg-secondary') }}">
            {{ $doneCount }}/{{ $totalCount }} selesai
        </span>
    </div>
    <div class="collapse" id="{{ $collapseId }}">
    <div class="list-group list-group-flush">
    @foreach($items as $h)
    @php
        $cl   = $h->checklistKehadiran;
        $done = ($cl?->absensi ? 1 : 0) + ($cl?->penyerahan_tagging ? 1 : 0);
    @endphp
    <a href="{{ route('checklist.kehadiran.show', $h) }}" class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-2">
        <div class="text-muted text-center flex-shrink-0" style="width:1.5rem;font-size:.75rem">{{ $loop->iteration }}</div>
        <div class="flex-grow-1 min-w-0">
            <div class="d-flex align-items-center gap-2">
                <span class="fw-semibold small">{{ $h->nomor_urut }}</span>
                @if($h->nama_hewan)<span class="text-muted small">— {{ $h->nama_hewan }}</span>@endif
            </div>
            <div class="small text-muted">{{ $h->nama_pekurban }}</div>
            @if($cl?->absensi_at)
            <div class="small text-success"><i class="bi bi-clock me-1"></i>Absen {{ $cl->absensi_at->format('H:i, d M') }}</div>
            @endif
            @if($h->kode_registrasi)
            <div class="mt-1">
                <span class="badge bg-success" style="font-size:.65rem; letter-spacing:.05em">
                    <i class="bi bi-qr-code me-1"></i>{{ $h->kode_registrasi }}
                </span>
            </div>
            @endif
        </div>
        <div class="text-end flex-shrink-0">
            @if($done === 2)<span class="badge bg-success">Selesai</span>
            @elseif($done > 0)<span class="badge bg-warning text-dark">{{ $done }}/2</span>
            @else<span class="badge bg-secondary">Belum</span>@endif
            <i class="bi bi-chevron-right text-muted ms-2"></i>
        </div>
    </a>
    @endforeach
    </div>
    </div>
</div>
@empty
<div class="card border-0 shadow-sm">
    <div class="list-group-item text-center text-muted py-5">
        @if($q)Tidak ada sapi yang cocok dengan "<strong>{{ $q }}</strong>"@else Belum ada data sapi.@endif
    </div>
</div>
@endforelse

@else

{{-- ── NORMAL LIST ── --}}
<div class="list-group shadow-sm">
@forelse($hewan as $h)
@php
    $cl = $h->checklistKehadiran;
    $done  = ($cl?->absensi ? 1 : 0) + ($cl?->penyerahan_tagging ? 1 : 0);
    $total = 2;
@endphp
<a href="{{ route('checklist.kehadiran.show', $h) }}" class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3">
    <div class="text-muted text-center flex-shrink-0" style="width:1.5rem;font-size:.75rem">{{ $hewan->firstItem() + $loop->index }}</div>
    <div class="flex-grow-1 min-w-0">
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="fw-bold">{{ $h->nomor_urut }}</span>
            @if($h->nama_hewan)<span class="text-muted small">— {{ $h->nama_hewan }}</span>@endif
            <span class="badge {{ $h->jenis === 'domba' ? 'bg-primary' : 'bg-warning text-dark' }} ms-1" style="font-size:.65rem">{{ ucfirst($h->jenis) }}</span>
        </div>
        <div class="small text-muted">{{ $h->nama_pekurban }}</div>
        @if($cl?->absensi_at)
        <div class="small text-success mt-1"><i class="bi bi-clock me-1"></i>Absen {{ $cl->absensi_at->format('H:i, d M') }}</div>
        @endif
        @if($h->kode_registrasi)
        <div class="mt-1">
            <span class="badge bg-success" style="font-size:.7rem; letter-spacing:.05em">
                <i class="bi bi-qr-code me-1"></i>{{ $h->kode_registrasi }}
            </span>
        </div>
        @endif
    </div>
    <div class="text-end flex-shrink-0">
        @if($done === $total)<span class="badge bg-success">Selesai</span>
        @elseif($done > 0)<span class="badge bg-warning text-dark">{{ $done }}/{{ $total }}</span>
        @else<span class="badge bg-secondary">Belum</span>@endif
        <i class="bi bi-chevron-right text-muted ms-2"></i>
    </div>
</a>
@empty
<div class="list-group-item text-center text-muted py-5">
    @if($q)Tidak ada hewan yang cocok dengan "<strong>{{ $q }}</strong>"@else Belum ada data hewan.@endif
</div>
@endforelse
</div>
<div class="mt-3">{{ $hewan->links() }}</div>

@endif

@push('scripts')
<style>
.transition-icon { transition: transform .2s ease; }
[data-bs-toggle="collapse"][aria-expanded="true"] .transition-icon { transform: rotate(90deg); }
</style>
@endpush
@endsection
