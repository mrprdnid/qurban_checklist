@extends('layouts.app')
@section('title', 'Checklist Seset Domba')

@section('content')
<h5 class="fw-bold mb-3"><i class="bi bi-tools me-2 text-success"></i>Checklist Seset Domba</h5>

<form method="GET" class="mb-2">
    @if($sort)<input type="hidden" name="sort" value="{{ $sort }}">
    <input type="hidden" name="direction" value="{{ $dir }}">@endif
    <div class="input-group">
        <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
        <input type="text" name="q" class="form-control" placeholder="Cari no. urut, nama hewan, atau pekurban..." value="{{ $q }}">
        @if($q || $status)<a href="{{ route('checklist.seset') }}" class="btn btn-outline-secondary">Hapus</a>@endif
    </div>
</form>
@include('layouts._sort_bar')
@include('layouts._status_filter', ['filterRoute' => 'checklist.seset'])

<div class="list-group shadow-sm">
@forelse($hewan as $h)
@php
    $cl = $h->checklistSeset;
    $done = ($cl?->bagian_pekurban?1:0) + ($cl?->kesesuaian_bagian?1:0) + ($cl?->otw_pengambilan?1:0);
@endphp
<a href="{{ route('checklist.seset.show', $h) }}" class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3">
    <div class="flex-grow-1 min-w-0">
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="fw-bold">{{ $h->nomor_urut }}</span>
            @if($h->nama_hewan)<span class="text-muted small">— {{ $h->nama_hewan }}</span>@endif
        </div>
        <div class="small text-muted">{{ $h->nama_pekurban }}</div>
    </div>
    <div class="text-end flex-shrink-0">
        @if($done === 3)<span class="badge bg-success">Selesai</span>
        @elseif($done > 0)<span class="badge bg-warning text-dark">{{ $done }}/3</span>
        @else<span class="badge bg-secondary">Belum</span>@endif
        <i class="bi bi-chevron-right text-muted ms-2"></i>
    </div>
</a>
@empty
<div class="list-group-item text-center text-muted py-5">
    @if($q)Tidak ada domba yang cocok dengan "<strong>{{ $q }}</strong>"@else Belum ada data domba.@endif
</div>
@endforelse
</div>
<div class="mt-3">{{ $hewan->links() }}</div>
@endsection
