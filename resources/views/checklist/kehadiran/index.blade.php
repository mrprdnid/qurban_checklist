@extends('layouts.app')
@section('title', 'Registrasi Kehadiran Pekurban')

@section('content')
<h5 class="fw-bold mb-3"><i class="bi bi-person-check me-2 text-success"></i>Registrasi Kehadiran Pekurban</h5>

<form method="GET" class="mb-2">
    <div class="input-group">
        <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
        <input type="text" name="q" class="form-control" placeholder="Cari no. urut, nama hewan, atau pekurban..." value="{{ $q }}">
        @if($q || $status)
        <a href="{{ route('checklist.kehadiran') }}" class="btn btn-outline-secondary">Hapus</a>
        @endif
    </div>
</form>

<div class="d-flex gap-2 mb-3 flex-wrap">
    @php
    $filters = [
        ''         => ['label' => 'Semua',      'class' => 'btn-outline-secondary'],
        'belum'    => ['label' => 'Belum',       'class' => 'btn-outline-danger'],
        'progress' => ['label' => 'On Progress', 'class' => 'btn-outline-warning'],
        'selesai'  => ['label' => 'Selesai',     'class' => 'btn-outline-success'],
    ];
    @endphp
    @foreach($filters as $val => $f)
    @php $active = ($status ?? '') === $val; @endphp
    <a href="{{ route('checklist.kehadiran', array_filter(['q' => $q, 'status' => $val ?: null])) }}"
       class="btn btn-sm {{ $active ? str_replace('outline-', '', $f['class']) . ' text-white' : $f['class'] }}">
        {{ $f['label'] }}
    </a>
    @endforeach
</div>

<div class="list-group shadow-sm">
@forelse($hewan as $h)
@php
    $cl = $h->checklistKehadiran;
    $done  = ($cl?->absensi ? 1 : 0) + ($cl?->penyerahan_tagging ? 1 : 0);
    $total = 2;
@endphp
<a href="{{ route('checklist.kehadiran.show', $h) }}" class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3">
    <div class="flex-grow-1 min-w-0">
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="fw-bold">{{ $h->nomor_urut }}</span>
            @if($h->nama_hewan)<span class="text-muted small">— {{ $h->nama_hewan }}</span>@endif
            <span class="badge {{ $h->jenis === 'domba' ? 'bg-primary' : 'bg-warning text-dark' }} ms-1" style="font-size:.65rem">{{ ucfirst($h->jenis) }}</span>
        </div>
        <div class="small text-muted">{{ $h->nama_pekurban }}</div>
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
@endsection
